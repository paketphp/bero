<?php
declare(strict_types=1);

namespace Paket\Bero\Helper;

use RuntimeException;

final class Shell
{
    public static function deleteCoverage(): void
    {
        $dir = __DIR__ . '/../../coverage';
        if (is_dir($dir)) {
            self::rimraf($dir);
        }
    }

    public static function downloadPhpUnit(): void
    {
        $path = __DIR__ . '/../../phpunit.phar';
        if (is_file($path)) {
            return;
        }

        if (version_compare(PHP_VERSION, '8.0.0') >= 0) {
            $url = 'https://phar.phpunit.de/phpunit-9.phar';
        } else {
            $url = 'https://phar.phpunit.de/phpunit-8.phar';
        }

        if (file_put_contents($path,  fopen($url, 'r')) === false) {
            throw new RuntimeException("Failed downloading phpunit from {$url}");
        }
    }

    private static function rimraf(string $dir): void
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            if (is_dir("{$dir}/{$file}") && !is_link($dir)) {
                self::rimraf("{$dir}/{$file}");
            } else {
                unlink("{$dir}/{$file}");
            }
        }
        rmdir($dir);
    }
}