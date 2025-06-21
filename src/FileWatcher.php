<?php

namespace Ifrias\DrupalFileWatcher;

use Spatie\Watcher\Watch;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class FileWatcher
{
    const WATCH_PATTERNS = [
        '*.html.twig',
        '*.inc',
        '*.yml',
        '*.php',
        '*.theme'
    ];

    const POSSIBLE_WEB_ROOTS = ['web', 'docroot'];

    public static function postInstall()
    {
        echo "Drupal File Watcher installed successfully.\n";
        echo "Run 'vendor/bin/drupal-watcher' to start watching for changes.\n";
    }

    public static function watch()
    {
        if (!class_exists(Watch::class)) {
            throw new \RuntimeException(
                'The spatie/file-system-watcher package is required. ' .
                'Install it with: composer require spatie/file-system-watcher'
            );
        }

        $paths = self::getWatchPaths();

        if (empty($paths)) {
            throw new \RuntimeException(
                'Could not find any Drupal custom modules or themes directories to watch. ' .
                'Checked in both web/ and docroot/ directories.'
            );
        }

        $watcher = Watch::paths($paths)
            ->onAnyChange(function (string $type, string $path) {
                self::handleChange($type, $path);
            });

        echo "Starting Drupal File Watcher...\n";
        echo "Watching for changes in:\n";
        foreach ($paths as $path) {
            echo "  - $path\n";
        }
        echo "\nPress Ctrl+C to stop\n";

        $watcher->start();
    }

    private static function getWatchPaths(): array
    {
        $projectRoot = getcwd();
        $paths = [];

        foreach (self::POSSIBLE_WEB_ROOTS as $webRoot) {
            $basePath = "$projectRoot/$webRoot";
            
            if (!is_dir($basePath)) {
                continue;
            }

            // Check for custom modules
            $modulesPath = "$basePath/modules/custom";
            if (is_dir($modulesPath)) {
                $paths[] = $modulesPath;
            }

            // Check for custom themes
            $themesPath = "$basePath/themes/custom";
            if (is_dir($themesPath)) {
                $paths[] = $themesPath;
            }

            // If we found at least one web root, stop checking others
            if (!empty($paths)) {
                break;
            }
        }

        return $paths;
    }

    private static function handleChange(string $type, string $path)
    {
        $fileType = is_dir($path) ? 'directory' : 'file';
        echo sprintf(
            "[%s] %s %s: %s\n",
            date('Y-m-d H:i:s'),
            ucfirst($type),
            $fileType,
            $path
        );

        if ($type === 'delete' || !file_exists($path)) {
            return;
        }

        foreach (self::WATCH_PATTERNS as $pattern) {
            if (fnmatch($pattern, basename($path))) {
                self::clearCache();
                break;
            }
        }
    }

    private static function clearCache()
    {
        echo "Detected relevant change - clearing Drupal cache...\n";

        $process = new Process(['./vendor/bin/drush', 'cr']);
        try {
            $process->mustRun();
            echo "Cache cleared successfully.\n";
            echo "Waiting for further changes...\n";
        } catch (ProcessFailedException $exception) {
            echo "Error clearing cache: " . $exception->getMessage() . "\n";
        }
    }
}