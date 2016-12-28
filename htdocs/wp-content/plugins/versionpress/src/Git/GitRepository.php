<?php

namespace VersionPress\Git;

use Nette\Utils\Strings;
use VersionPress\Utils\FileSystem;
use VersionPress\Utils\Process;
use VersionPress\Utils\ProcessUtils;

/**
 * Manipulates the Git repository.
 */
class GitRepository
{

    private $workingDirectoryRoot;
    private $tempDirectory;
    private $commitMessagePrefix;
    private $gitBinary;
    private $gitProcessTimeout = null;

    /**
     * @param string $workingDirectoryRoot Filesystem path to working directory root (where the .git folder resides)
     * @param string $tempDirectory Directory used for commit message temp file. See commit().
     * @param string $commitMessagePrefix Standard prefix applied to all commit messages
     * @param string $gitBinary Git binary to use
     */
    public function __construct(
        $workingDirectoryRoot,
        $tempDirectory = null,
        $commitMessagePrefix = "[VP] ",
        $gitBinary = "git"
    ) {
        $this->workingDirectoryRoot = $workingDirectoryRoot;
        $this->tempDirectory = $tempDirectory ?: __DIR__;
        $this->commitMessagePrefix = $commitMessagePrefix;
        $this->gitBinary = $gitBinary;
    }

    /**
     * Stages all files under the given path. No path = stage all files in whole working directory.
     *
     * @param string|null $path Null (the default) means the whole working directory
     */
    public function stageAll($path = null)
    {
        $path = $path ? $path : $this->workingDirectoryRoot;
        $this->runShellCommand("git add -A %s", $path);
    }

    /**
     * Creates a commit.
     *
     * Uses a temporary file to construct the commit message because on Windows, multi-line
     * commit message cannot be created on CLI and it's generally a more flexible solution
     * (very long commit messages, etc.).
     *
     * @param CommitMessage|string $message
     * @param string $authorName
     * @param string $authorEmail
     */
    public function commit($message, $authorName, $authorEmail)
    {

        if (is_string($message)) {
            $commitMessage = $message;
            $body = null;
        } else {
            $subject = $message->getSubject();
            $body = $message->getBody();
            $commitMessage = $this->commitMessagePrefix . $subject;
        }

        if ($body != null) {
            $commitMessage .= "\n\n" . $body;
        }

        $tempCommitMessageFilename = md5(rand());
        $tempCommitMessagePath = $this->tempDirectory . '/' . $tempCommitMessageFilename;
        file_put_contents($tempCommitMessagePath, $commitMessage);

        // Unfortunatelly, `git commit --author=...` is not enough.
        // It doesn't work with empty both local and global config.
        $localConfigUserName = $this->runShellCommandWithStandardOutput('git config --local user.name');
        $localConfigUserEmail = $this->runShellCommandWithStandardOutput('git config --local user.email');

        $this->runShellCommand('git config --local user.name %s', $authorName);
        $this->runShellCommand('git config --local user.email %s', $authorEmail);

        $this->runShellCommand("git commit --file=%s", $tempCommitMessagePath);
        FileSystem::remove($tempCommitMessagePath);

        if ($localConfigUserName === null) {
            $this->runShellCommand('git config --local --unset user.name');
        } else {
            $this->runShellCommand('git config --local user.name %s', $localConfigUserName);
        }

        if ($localConfigUserEmail === null) {
            $this->runShellCommand('git config --local --unset user.email');
        } else {
            $this->runShellCommand('git config --local user.email %s', $localConfigUserEmail);
        }

        $gitConfigPath = $this->workingDirectoryRoot . '/.git/config';
        GitConfig::removeEmptySections($gitConfigPath);
    }

    /**
     * True if the working directory is versioned
     *
     * @return bool
     */
    public function isVersioned()
    {
        return file_exists($this->workingDirectoryRoot . "/.git");
    }

    /**
     * Initializes the repository
     */
    public function init()
    {
        $this->runShellCommand("git init");
    }

    /**
     * Gets last (most recent) commit hash in the repository, or an empty string is there are no commits.
     *
     * @param string $options Options passed to git log
     * @return string Empty string or SHA1
     */
    public function getLastCommitHash($options = "")
    {
        if (!empty($options)) {
            $command = 'git log --pretty=format:"%%H" --max-count=1';
            $command .= " " . $options;
        } else {
            $command = 'git rev-parse HEAD';
        }

        $result = $this->runShellCommand($command);

        if ($result["stderr"]) {
            return "";
        } else {
            return $result["stdout"];
        }
    }

    /**
     * Returns the initial (oldest) commit in the repo
     *
     * @return Commit
     */
    public function getInitialCommit()
    {
        $initialCommitHash = $this->runShellCommandWithStandardOutput("git rev-list --max-parents=0 HEAD");
        return $this->getCommit($initialCommitHash);
    }

    /**
     * Returns an array of Commits based on {@link http://git-scm.com/docs/gitrevisions gitrevisions}
     *
     * @param string $options Options passed to git log
     * @param string $gitrevisions Empty by default, i.e., calling full 'git log'
     * @return Commit[]
     */
    public function log($options = "", $gitrevisions = "")
    {

        $commitDelimiter = chr(29);
        $dataDelimiter = chr(30);
        $statusDelimiter = chr(31);

        $logCommand = "git log --pretty=format:\"|begin|%%H|delimiter|%%aD|delimiter|%%ar|delimiter|%%an|delimiter" .
            "|%%ae|delimiter|%%P|delimiter|%%s|delimiter|%%b|end|\" --name-status";

        $logCommand .= " " . $options;
        if (!empty($gitrevisions)) {
            $logCommand .= " " . ProcessUtils::escapeshellarg($gitrevisions);
        }

        $logCommand = str_replace("|begin|", $commitDelimiter, $logCommand);
        $logCommand = str_replace("|delimiter|", $dataDelimiter, $logCommand);
        $logCommand = str_replace("|end|", $statusDelimiter, $logCommand);
        $log = trim($this->runShellCommandWithStandardOutput($logCommand), $commitDelimiter);

        if ($log == "") {
            $commits = [];
        } else {
            $commits = explode($commitDelimiter, $log);
        }

        return array_map(function ($rawCommitAndStatus) use ($statusDelimiter) {
            list($rawCommit, $rawStatus) = explode($statusDelimiter, $rawCommitAndStatus);
            return Commit::buildFromString(trim($rawCommit), trim($rawStatus));
        }, $commits);
    }

    /**
     * Returns list of files that were modified in given {@link http://git-scm.com/docs/gitrevisions gitrevisions}
     *
     * @param string $gitrevisions
     * @return string[]
     */
    public function getModifiedFiles($gitrevisions)
    {
        $result = $this->runShellCommandWithStandardOutput("git diff --name-only %s", $gitrevisions);
        $files = explode("\n", $result);
        return $files;
    }

    /**
     * Like getModifiedFiles() but also returns the status of each file ("A" for added,
     * "M" for modified, "D" for deleted and "R" for renamed).
     *
     * @param string $gitrevisions See gitrevisions
     * @return array Array of things like `array("status" => "M", "path" => "wp-content/vpdb/something.ini" )`
     */
    public function getModifiedFilesWithStatus($gitrevisions)
    {
        $command = 'git diff --name-status %s';
        $output = $this->runShellCommandWithStandardOutput($command, $gitrevisions);
        $result = [];

        foreach (explode("\n", $output) as $line) {
            list($status, $path) = explode("\t", $line);
            $result[] = ["status" => $status, "path" => $path];
        }

        return $result;
    }

    /**
     * Reverts all changes up to a given commit - performs a "rollback"
     *
     * @param $commitHash
     */
    public function revertAll($commitHash)
    {
        $this->runShellCommand("git reset --hard %s", $commitHash);
        $this->runShellCommand("git reset --soft HEAD@{1}");
    }

    /**
     * Reverts a single commit. If there is a conflict, aborts the revert and returns false.
     *
     * @param $commitHash
     * @return bool True if it succeeded, false if there was a conflict
     */
    public function revert($commitHash)
    {
        $output = $this->runShellCommandWithErrorOutput("git revert -n %s", $commitHash);

        if ($output !== null) { // revert conflict
            $this->abortRevert();
            return false;
        }

        return true;
    }

    /**
     * Aborts a revert, e.g., if there was a conflict
     */
    public function abortRevert()
    {
        $this->runShellCommand("git revert --abort");
    }

    /**
     * Returns true if $commitHash was created after the $afterWhichCommitHash commit ("after" meaning
     * that $commitHash is more recent commit, a child of $afterWhat). Same two commits return false.
     *
     * @param $commitHash
     * @param $afterWhichCommitHash
     * @return bool
     */
    public function wasCreatedAfter($commitHash, $afterWhichCommitHash)
    {
        $range = sprintf("%s..%s", $afterWhichCommitHash, $commitHash);
        // One commit is enough, only empty/not empty matters in this case
        $cmd = "git log %s --oneline --max-count=1";
        return $this->runShellCommandWithStandardOutput($cmd, $range) != null;
    }

    /**
     * Returns child (newer) commit. Assumes there is only a single child commit.
     *
     * @param $commitHash
     * @return mixed
     */
    public function getChildCommit($commitHash)
    {
        $range = "$commitHash..";
        $cmd = "git log --reverse --ancestry-path --format=%%H %s";
        $result = $this->runShellCommandWithStandardOutput($cmd, $range);
        list($childHash) = explode("\n", $result);
        return $childHash;
    }

    /**
     * Counts number of commits
     *
     * @param string $options Options passed to git log
     * @param string $gitrevisions Empty by default, i.e., calling full 'git log'
     * @return int
     */
    public function getNumberOfCommits($options = "", $gitrevisions = "")
    {
        $logCommand = 'git log --pretty=format:"%%h"';

        $logCommand .= " " . $options;
        $result = $this->runShellCommandWithStandardOutput($logCommand);
        $count = substr_count($result, "\n") + 1;
        return $count;
    }

    /**
     * Returns true if there is something to commit
     *
     * @return bool
     */
    public function willCommit()
    {
        $status = $this->runShellCommandWithStandardOutput("git status -s");
        return Strings::match($status, "~^[AMD].*~") !== null;
    }

    /**
     * Gets commit object based on its hash
     *
     * @param $commitHash
     * @return Commit
     */
    public function getCommit($commitHash)
    {
        $logWithInitialCommit = $this->log($commitHash);
        return $logWithInitialCommit[0];
    }

    /**
     * Returns git status in short format, something like:
     *
     *     A path1.txt
     *     M path2.txt
     *
     * Clean working directory returns an empty string.
     *
     * @param $array bool Return result as array
     * @return string|array[string]
     */
    public function getStatus($array = false)
    {
        $gitCmd = "git status --porcelain -uall";
        $output = $this->runShellCommandWithStandardOutput($gitCmd);
        if ($array) {
            if ($output === null) {
                return [];
            }

            $output = explode("\n", $output); // Consider using -z and explode by NUL
            foreach ($output as $k => $line) {
                $output[$k] = explode(" ", trim($line), 2);
            }
        }
        return $output;
    }

    /**
     * Returns true if there are no changes to commit.
     *
     * @return bool
     */
    public function isCleanWorkingDirectory()
    {
        $status = $this->getStatus();
        return empty($status);
    }

    /**
     * Returns diff for given commit.
     * If null, returns diff of working directory and HEAD
     *
     * @param string $hash
     * @return string
     */
    public function getDiff($hash = null)
    {
        if ($hash === null) {
            $status = $this->getStatus(true);
            $this->runShellCommand("git add -AN");
            $diff = $this->runShellCommandWithStandardOutput("git diff HEAD");
            $filesToReset = array_map(function ($file) {
                return $file[1];
            }, array_filter($status, function ($file) {
                return $file[0] === '??'; // unstaged
            }));

            if (count($filesToReset) > 0) {
                $this->runShellCommand(
                    sprintf("git reset HEAD %s", join(" ", array_map(['VersionPress\Utils\ProcessUtils', 'escapeshellarg'], $filesToReset)))
                );
            }

            return $diff;
        }

        if ($this->getInitialCommit()->getHash() === $hash) {
            // Inspired by: http://stackoverflow.com/a/25064285
            $emptyTreeHash = "4b825dc642cb6eb9a060e54bf8d69288fbee4904";
            $gitCmd = "git diff-tree -p $emptyTreeHash $hash";
        } else {
            $escapedHash = ProcessUtils::escapeshellarg($hash);
            $gitCmd = "git diff $escapedHash~1 $escapedHash";
        }

        $output = $this->runShellCommandWithStandardOutput($gitCmd);
        return $output;
    }

    /**
     * Discards all modifications made to files in working directory.
     * Also deletes untracked files.
     *
     * @return boolean
     */
    public function clearWorkingDirectory()
    {
        $this->runShellCommand("git clean -f");
        $this->runShellCommand("git reset --hard");

        $status = $this->getStatus(true);
        foreach ($status as $file) {
            if ($file[0] === '??') {
                unlink($this->workingDirectoryRoot . '/' . $file[1]);
            }
        }

        return $this->isCleanWorkingDirectory();
    }

    /**
     * Invokes {@see runShellCommand()} and returns its stdout output. The params are the same,
     * only the return type is string instead of an array.
     *
     * @see runShellCommand()
     * @see runShellCommandWithErrorOutput()
     *
     * @param string $command
     * @param string $args
     * @return string
     */
    private function runShellCommandWithStandardOutput($command, $args = '')
    {
        $result = call_user_func_array([$this, 'runShellCommand'], func_get_args());
        return $result['stdout'];
    }

    /**
     * Invokes {@see runShellCommand()} and returns its stderr output. The params are the same,
     * only the return type is string instead of an array.
     *
     * @see runShellCommand()
     * @see runShellCommandWithStandardOutput()
     *
     * @param string $command
     * @param string $args
     * @return string
     */
    private function runShellCommandWithErrorOutput($command, $args = '')
    {
        $result = call_user_func_array([$this, 'runShellCommand'], func_get_args());
        return $result['stderr'];
    }

    /**
     * Run a Git command, either fully specified (e.g., 'git log') or just by the name (e.g., 'log').
     * The comamnd can contain `sprintf()` markers such as '%s' which are replaced by shell-escaped $args.
     *
     * Note: shell-escaping is actually pretty important even for things that are not paths, like revisions.
     * For example, `git log HEAD^` will not work on Windows, only `git log "HEAD^"` will. So the right
     * approach is to provide `git log %s` as the $command and rev range as $args.
     *
     * @param string $command E.g., 'git log' or 'git add %s' (path will be shell-escaped) or just 'log'
     *   (the "git " part is optional).
     * @param string[] $args Will be shell-escaped and replace sprintf markers in $command
     * @return array array('stdout' => , 'stderr' => )
     */
    private function runShellCommand($command, ...$args)
    {

        // replace (optional) "git " with the configured git binary
        $command = Strings::startsWith($command, "git ") ? substr($command, 4) : $command;
        $command = ProcessUtils::escapeshellarg($this->gitBinary) . " " . $command;

        $escapedArgs = @array_map(['VersionPress\Utils\ProcessUtils', 'escapeshellarg'], $args);
        $commandWithArguments = vsprintf($command, $escapedArgs);

        $result = $this->runProcess($commandWithArguments);
        return $result;
    }

    /**
     * Low-level helper, generally use runShellCommand()
     *
     * @param $cmd
     * @return array
     */
    private function runProcess($cmd)
    {
        /*
         * MAMP / XAMPP issue on Mac OS X, see #106.
         *
         * http://stackoverflow.com/a/16903162/1243495
         */
        $dyldLibraryPath = getenv("DYLD_LIBRARY_PATH");
        if ($dyldLibraryPath != "") {
            putenv("DYLD_LIBRARY_PATH=");
        }

        $process = new Process($cmd, $this->workingDirectoryRoot);
        if ($this->gitProcessTimeout !== null) {
            $process->setTimeout($this->gitProcessTimeout);
        }
        $process->run();

        $result = [
            'stdout' => $process->getOutput(),
            'stderr' => $process->getErrorOutput()
        ];

        putenv("DYLD_LIBRARY_PATH=$dyldLibraryPath");

        if ($result['stdout'] !== null) {
            $result['stdout'] = trim($result['stdout']);
        }
        if ($result['stderr'] !== null) {
            $result['stderr'] = trim($result['stderr']);
        }

        return $result;
    }

    /**
     * Changes the timeout of {@link \Symfony\Component\Process\Process}
     * @param int $gitProcessTimeout
     */
    public function setGitProcessTimeout($gitProcessTimeout)
    {
        $this->gitProcessTimeout = $gitProcessTimeout;
    }

    public function getFileModifications($file)
    {
        $cmd = "git log --format=format:%%H --name-status -- %s";
        $log = $this->runShellCommandWithStandardOutput($cmd, $file);

        if (!$log) {
            return [];
        }

        $commits = explode("\n\n", $log);

        $modificationsGroupedByCommit = array_map(function ($commit) {
            $lines = explode("\n", $commit);

            $hash = $lines[0];
            $modifications = array_slice($lines, 1);

            return array_map(function ($statusAndPath) use ($hash) {
                list($status, $path) = explode("\t", $statusAndPath, 2);
                return [
                    'status' => $status,
                    'path' => $path,
                    'commit' => $hash
                ];
            }, $modifications);
        }, $commits);

        return call_user_func_array('array_merge', $modificationsGroupedByCommit);
    }

    public function getFileInRevision($path, $commitHash)
    {
        return $this->runShellCommandWithStandardOutput('git show %s:%s', $commitHash, $path);
    }
}
