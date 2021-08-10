<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Helper;

use Acme\PhpExtension\AbstractStaticClass;
use InvalidArgumentException;

final class FileSystemHelper extends AbstractStaticClass
{
    public static function createDirIfNotExists(string $path, int $mode = 0744, bool $recursive = true): void
    {
        if (!is_dir($path) && !is_file($path) && !is_link($path)) {
            mkdir($path, $mode, $recursive);
        }
    }

    public static function deleteDir(string $dirPath): void
    {
        $dirPath = rtrim($dirPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        if (!file_exists($dirPath)) {
            return;
        }

        if (!is_dir($dirPath)) {
            throw new InvalidArgumentException("The given path is not a directory: '$dirPath'");
        }

        $fileList = glob($dirPath . '*', GLOB_MARK);
        foreach ($fileList as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }

        rmdir($dirPath);
    }

    public static function sanitizeRelativePath(string $path): string
    {
        return DIRECTORY_SEPARATOR . trim($path, DIRECTORY_SEPARATOR);
    }

    public static function sanitizeRootPath(string $path): string
    {
        return rtrim($path, DIRECTORY_SEPARATOR);
    }
}
