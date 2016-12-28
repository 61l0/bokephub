<?php

namespace VersionPress\Utils;

class RequestDetector
{

    private $isWpCli;
    private $wpCliArguments;

    public function __construct()
    {
        $this->isWpCli = defined('WP_CLI') && WP_CLI;
        if ($this->isWpCli) {
            $runner = \WP_CLI::get_runner();
            $this->wpCliArguments = $runner->arguments;
        }
    }

    public function isThemeDeleteRequest()
    {
        if ($this->isWpCli) {
            return $this->isWpCliCommand(['theme', 'delete']);
        }

        return basename($_SERVER['PHP_SELF']) === 'themes.php' && $this->queryVariableEquals('action', 'delete');
    }

    public function isPluginDeleteRequest()
    {
        if ($this->isWpCli) {
            return $this->isWpCliCommand(['plugin', 'delete']) || $this->isWpCliCommand([
                'plugin',
                'uninstall'
            ]);
        }

        return basename($_SERVER['PHP_SELF']) === 'plugins.php'
        && ($this->queryVariableEquals('action', 'delete-selected')
            || $this->postVariableEquals('action', 'delete-selected'))
        && isset($_REQUEST['verify-delete']);
    }

    public function isCoreLanguageUninstallRequest()
    {
        return $this->isWpCliCommand(['core', 'language', 'uninstall']);
    }

    /**
     * @param $command
     * @return bool
     */
    private function isWpCliCommand($command)
    {
        foreach ($command as $n => $subcommand) {
            if (!isset($this->wpCliArguments[$n]) || $this->wpCliArguments[$n] !== $subcommand) {
                return false;
            }
        }
        return true;
    }

    private function queryVariableEquals($name, $value)
    {
        return isset($_GET[$name]) && $_GET[$name] === $value;
    }

    private function postVariableEquals($name, $value)
    {
        return isset($_POST[$name]) && $_POST[$name] === $value;
    }

    public function getPluginNames()
    {
        if (!$this->isWpCli) {
            return $_REQUEST['checked'];
        }

        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $arguments = $this->wpCliArguments;
        array_shift($arguments); // command (plugin)
        array_shift($arguments); // subcommand (delete)

        $plugins = [];

        foreach ($arguments as $name) {
            // code from WP_CLI\Fetchers\Plugin\get()
            foreach (get_plugins() as $file => $_) {
                if ($file === "$name.php" ||
                    ($name && $file === $name) ||
                    (dirname($file) === $name && $name !== '.')
                ) {
                    $plugins[] = $file;
                }
            }
        }

        return $plugins;
    }

    public function getLanguageCode()
    {
        if (!$this->isWpCli) {
            return null; // Translation cannot be uninstalled using administration
        }

        return $this->wpCliArguments[3]; // core language uninstall <language>
    }

    public function getThemeStylesheets()
    {
        if (!$this->isWpCli) {
            return [$_GET['stylesheet']];
        }

        return array_slice($this->wpCliArguments, 2); // theme delete <stylesheet>
    }
}
