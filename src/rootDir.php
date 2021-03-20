<?php

namespace Keven;

use ReflectionClass;
use ReflectionException;
use RuntimeException;

/**
 * Find current project root directory
 *
 * @return string
 * @throws ReflectionException
 */
function rootDir(int $maxParentLevel = 5): string
{
    static $path;

    if ($path) {
        return $path;
    }

    foreach(get_declared_classes() as $class) {
        if (0 === strpos($class, 'ComposerAutoloaderInit')) {
            $dir = dirname((new ReflectionClass($class))->getFileName());

            while (!file_exists($dir.'/composer.json')) {
                $dir = dirname($dir);
                $maxParentLevel--;
                if (0 === $maxParentLevel) {
                    throw new RuntimeException('Project root not found.');
                }
            }

            return $dir;
        }
    }

    throw new RuntimeException('Project root not found.');
}
