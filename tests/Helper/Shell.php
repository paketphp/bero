<?php
declare(strict_types=1);

namespace Paket\Bero\Helper;

final class Shell
{
    public static function deleteCoverage()
    {
        self::rimraf(__DIR__ . '/../../coverage');
    }

    private static function rimraf($dir)
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