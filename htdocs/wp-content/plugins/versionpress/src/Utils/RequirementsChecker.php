<?php

namespace VersionPress\Utils;

use Exception;
use Symfony\Component\Filesystem\Exception\IOException;
use VersionPress\Database\Database;
use VersionPress\Database\DbSchemaInfo;
use wpdb;

class RequirementsChecker
{
    private $requirements = [];
    /**
     * @var Database
     */
    private $database;
    /**
     * @var DbSchemaInfo
     */
    private $schema;

    const SITE = 'site';
    const ENVIRONMENT = 'environment';

    /** @var string[] */
    public static $compatiblePlugins = [
        'akismet' => 'akismet/akismet.php',
        'advanced-custom-fields' => 'advanced-custom-fields/acf.php',
        'hello-dolly' => 'hello-dolly/hello.php',
        '_hello-dolly' => 'hello.php',
        'versionpress' => 'versionpress/versionpress.php',
    ];

    public static $incompatiblePlugins = [
        'wp-super-cache' => 'wp-super-cache/wp-cache.php'
    ];

    /** @var bool */
    private $isWithoutCriticalErrors;
    /** @var bool */
    private $isEverythingFulfilled;

    /**
     * RequirementsChecker constructor.
     * @param Database $database
     * @param DbSchemaInfo $schema
     * @param string $checkScope determines if all VersionPress requirements need to be fullfilled or just some of them.
     * Possible values are RequirementsChecker::SITE or RequirementsChecker::ENVIRONMENT
     * Default value is RequirementsChecker::SITE which means that all requirements need to be matched.
     * RequirementsChecker::ENVIRONMENT checks only requirements related to "runtime" environment.
     */
    public function __construct($database, $schema, $checkScope = RequirementsChecker::SITE)
    {

        $this->database = $database;
        $this->schema = $schema;

        // Markdown can be used in the 'help' field

        $this->requirements[] = [
            'name' => 'PHP 5.6+',
            'level' => 'critical',
            'fulfilled' => version_compare(PHP_VERSION, '5.6.0', '>='),
            'help' => 'PHP 5.6+ is required.'
        ];

        $this->requirements[] = [
            'name' => "'mbstring' extension",
            'level' => 'critical',
            'fulfilled' => extension_loaded('mbstring'),
            'help' => 'Extension `mbstring` is required.'
        ];

        $this->requirements[] = [
            'name' => 'Execute external commands',
            'level' => 'critical',
            'fulfilled' => $this->tryRunProcess(),
            'help' => 'PHP function `proc_open()` must be enabled as VersionPress uses it to execute Git commands. ' .
                'Please update your php.ini.'
        ];

        $gitCheckResult = $this->tryGit();

        switch ($gitCheckResult) {
            case "no-git":
                // @codingStandardsIgnoreLine
                $gitHelpMessage = '[Git](http://git-scm.com/) must be installed on the server. If you think it is then it\'s probably not visible to the web server user – please update its PATH. Alternatively, [configure VersionPress](http://docs.versionpress.net/en/getting-started/configuration#git-binary) to use specific Git binary. [Learn more](http://docs.versionpress.net/en/getting-started/installation-uninstallation#git).';
                break;

            case "wrong-version":
                // @codingStandardsIgnoreLine
                $gitHelpMessage = 'Git version ' . SystemInfo::getGitVersion() . ' detected with which there are known issues. Please install at least version ' . self::GIT_MINIMUM_REQUIRED_VERSION . ' (this can be done side-by-side and VersionPress can be [configured](http://docs.versionpress.net/en/getting-started/configuration#git-binary) to use that specific Git version). [Learn more](http://docs.versionpress.net/en/getting-started/installation-uninstallation#git).';
                break;

            default:
                $gitHelpMessage = "";
        }

        $this->requirements[] = [
            'name' => 'Git ' . self::GIT_MINIMUM_REQUIRED_VERSION . '+ installed',
            'level' => 'critical',
            'fulfilled' => $gitCheckResult == "ok",
            'help' => $gitHelpMessage
        ];

        $this->requirements[] = [
            'name' => 'Write access on the filesystem',
            'level' => 'critical',
            'fulfilled' => $this->tryWrite(),
            // @codingStandardsIgnoreLine
            'help' => 'VersionPress needs write access in the site root, its nested directories and the <abbr title="' . sys_get_temp_dir() . '" style="border-bottom: 1px dotted; border-color: inherit;">system temp directory</abbr>. Please update the permissions.'
        ];

        $this->requirements[] = [
            'name' => 'Access rules can be installed',
            'level' => 'warning',
            'fulfilled' => $this->tryAccessControlFiles(),
            // @codingStandardsIgnoreLine
            'help' => 'VersionPress automatically tries to secure certain locations, like `wp-content/vpdb`. You either don\'t have a supported web server or rules cannot be enforced. [Learn more](http://docs.versionpress.net/en/getting-started/installation-uninstallation#supported-web-servers).'
        ];
        if ($checkScope === RequirementsChecker::SITE) {
            if (file_exists(VP_PROJECT_ROOT . '/composer.json')) {
                $this->requirements[] = [
                    'name' => 'Composer scripts',
                    'level' => 'warning',
                    'fulfilled' => $this->testComposerJson(),
                    // @codingStandardsIgnoreLine
                    'help' => 'Your project uses Composer but `pre-update-cmd` or `post-update-cmd` scripts in `composer.json` are already defined. VersionPress needs to run a WP-CLI commands in these scripts to automatically commit changes of Composer packages. Please update your scripts to run [the WP-CLI commands](http://docs.versionpress.net/en/feature-focus/composer).'
                ];
            }

            $this->requirements[] = [
                'name' => 'wpdb hook',
                'level' => 'critical',
                'fulfilled' => is_writable(ABSPATH . WPINC . '/wp-db.php'),
                // @codingStandardsIgnoreLine
                'help' => 'For VersionPress to do its magic, it needs to change the `wpdb` class and put some code there. ' .
                    'To do so it needs write access to the `wp-includes/wp-db.php` file. Please update the permissions.'
            ];

            $this->requirements[] = [
                'name' => 'Not multisite',
                'level' => 'critical',
                'fulfilled' => !is_multisite(),
                'help' => 'Currently VersionPress does not support multisites. Stay tuned!'
            ];

            $this->requirements[] = [
                'name' => 'Standard directory layout',
                'level' => 'warning',
                'fulfilled' => $this->testDirectoryLayout(),
                // @codingStandardsIgnoreLine
                'help' => 'It seems like you use customized project structure. VersionPress supports only some scenarios. [Learn more](http://docs.versionpress.net/en/feature-focus/custom-project-structure).'
            ];


            $setTimeLimitEnabled = (false === strpos(ini_get("disable_functions"), "set_time_limit"));
            $countOfEntities = $this->countEntities();

            if ($setTimeLimitEnabled) {
                // @codingStandardsIgnoreLine
                $help = "The initialization will take a little longer. This website contains $countOfEntities entities.";
            } else {
                $help = "The initialization may not finish. This website contains $countOfEntities entities.";
            }

            $this->requirements[] = [
                'name' => 'Web size',
                'level' => 'warning',
                'fulfilled' => $countOfEntities < 500,
                'help' => $help
            ];

            $unsupportedPluginsCount = $this->testExternalPlugins();
            $externalPluginsHelp = "You run $unsupportedPluginsCount external " .
                ($unsupportedPluginsCount == 1 ? "plugin" : "plugins") .
                ' we have not tested yet. <a href="http://docs.versionpress.net/en/feature-focus/external-plugins">' .
                'Read more about 3rd party plugins support.</a>';

            $this->requirements[] = [
                'name' => 'External plugins',
                'level' => 'warning',
                'fulfilled' => $unsupportedPluginsCount == 0,
                'help' => $externalPluginsHelp
            ];
        }

        $this->isWithoutCriticalErrors = array_reduce($this->requirements, function ($carry, $requirement) {
            return $carry && ($requirement['fulfilled'] || $requirement['level'] === 'warning');
        }, true);

        $this->isEverythingFulfilled = array_reduce($this->requirements, function ($carry, $requirement) {
            return $carry && $requirement['fulfilled'];
        }, true);
    }

    /**
     * Returns list of requirements and their fulfillment
     *
     * @return array
     */
    public function getRequirements()
    {
        return $this->requirements;
    }

    public function isWithoutCriticalErrors()
    {
        return $this->isWithoutCriticalErrors;
    }

    public function isEverythingFulfilled()
    {
        return $this->isEverythingFulfilled;
    }

    private function tryRunProcess()
    {
        try {
            $process = new Process("echo test");
            $process->run();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @return string "ok", "no-git" or "wrong-version"
     */
    private function tryGit()
    {
        try {
            $gitVersion = SystemInfo::getGitVersion();
            return self::gitMatchesMinimumRequiredVersion($gitVersion) ? "ok" : "wrong-version";
        } catch (Exception $e) {
            return "no-git";
        }
    }

    private function tryWrite()
    {
        $filename = ".vp-try-write";
        $testPaths = [
            ABSPATH,
            WP_CONTENT_DIR,
            sys_get_temp_dir()
        ];

        $writable = true;

        foreach ($testPaths as $directory) {
            $filePath = $directory . '/' . $filename;
            /** @noinspection PhpUsageOfSilenceOperatorInspection */
            @file_put_contents($filePath, "");
            $writable &= is_file($filePath);
            FileSystem::remove($filePath);

            // Trying to create file from process (issue #522)
            $process = new Process(sprintf("echo test > %s", ProcessUtils::escapeshellarg($filePath)));
            $process->run();
            $writable &= is_file($filePath);

            try {
                FileSystem::remove($filePath);
            } catch (IOException $ex) {
                $writable = false; // the file could not be deleted - the permissions are wrong
            }
        }

        return $writable;
    }

    private function tryAccessControlFiles()
    {
        $securedUrl = site_url() . '/wp-content/plugins/versionpress/temp/security-check.txt';
        /** @noinspection PhpUsageOfSilenceOperatorInspection */
        return @file_get_contents($securedUrl) === false; // intentionally @
    }

    private function testDirectoryLayout()
    {
        $uploadDirInfo = wp_upload_dir();
        $isStandardLayout = true;
        $isStandardLayout &= ABSPATH . 'wp-content' === WP_CONTENT_DIR;
        $isStandardLayout &= WP_CONTENT_DIR . '/plugins' === WP_PLUGIN_DIR;
        $isStandardLayout &= WP_CONTENT_DIR . '/themes' === get_theme_root();
        $isStandardLayout &= WP_CONTENT_DIR . '/uploads' === $uploadDirInfo['basedir'];
        $isStandardLayout &= is_file(ABSPATH . 'wp-config.php');

        return $isStandardLayout;
    }

    /**
     * Minimum required Git version
     */
    const GIT_MINIMUM_REQUIRED_VERSION = "1.9";

    /**
     * Returns true if git version matches the minimum required version. If minimum required version
     * is not given, RequirementsChecker::GIT_MINIMUM_REQUIRED_VERSION is used by default.
     *
     * @param string $gitVersion
     * @param string $minimumRequiredVersion
     * @return bool
     */
    public static function gitMatchesMinimumRequiredVersion($gitVersion, $minimumRequiredVersion = null)
    {
        $minimumRequiredVersion = $minimumRequiredVersion ?: self::GIT_MINIMUM_REQUIRED_VERSION;
        return version_compare($gitVersion, $minimumRequiredVersion, ">=");
    }

    private function countEntities()
    {
        $entities = $this->schema->getAllEntityNames();
        $totalEntitiesCount = 0;

        foreach ($entities as $entity) {
            $table = $this->schema->getPrefixedTableName($entity);
            $totalEntitiesCount += $this->database->get_var("SELECT COUNT(*) FROM $table");
        }

        return $totalEntitiesCount;
    }

    /**
     * @return int Number of unsupported plugins.
     */
    private function testExternalPlugins()
    {
        $plugins = get_option('active_plugins');
        $unsupportedPluginsCount = 0;
        foreach ($plugins as $plugin) {
            if (!in_array($plugin, self::$compatiblePlugins)) {
                $unsupportedPluginsCount++;
            }
        }
        return $unsupportedPluginsCount;
    }

    private function testComposerJson()
    {
        $composerJsonPath = VP_PROJECT_ROOT . '/composer.json';
        $composerJson = json_decode(file_get_contents($composerJsonPath));
        if (!isset($composerJson->scripts)) {
            return true;
        }

        if (isset($composerJson->scripts->{'pre-update-cmd'}) || isset($composerJson->scripts->{'post-update-cmd'})) {
            return false;
        }

        return true;
    }
}
