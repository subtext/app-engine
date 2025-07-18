<?php

namespace Subtext\AppEngine\Namespaces;

use DirectoryIterator;
use InvalidArgumentException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Resolves PSR-4 namespaces to filesystem paths and enumerates PHP classes,
 * interfaces, enums, and traits within them.
 *
 * Example:
 * If your PSR-4 namespace is 'App\\Service\\' and it maps to the  directory
 * '/var/www/src/Service', this class can find all PHP  classes in
 * 'App\\Service\\Submodule' and convert the file paths to fully-qualified class
 * names.
 *
 * @example
 * $ns = new NamespaceResolver('App\\', '/var/www/src');
 * $ns->getClassesFrom('App\\Service\\Submodule\\', true);
 * Output:
 * [
 *   "App\Service\Submodule\UserService",
 *   "App\Service\Submodule\Billing\Invoice",
 *   "App\Service\Submodule\Contracts\UserInterface",
 *   "App\Service\Submodule\Traits\Loggable",
 * ]
 */
class Resolver
{
    private string $psr;
    private string $dir;

    /**
     * @param string $psr The PSR-4 project namespace, must end with backslash
     * @param string $dir The corresponding directory
     */
    public function __construct(string $psr, string $dir)
    {
        $this->validateParams($psr, $dir);
        $this->psr = $psr;
        $this->dir = rtrim($dir, '/');
    }

    /**
     * @param string $namespace The namespace to resolve, must end with backslash
     * @param bool $recursive   Whether to recursively search directories
     * @return array
     */
    public function getClassesFrom(string $namespace, bool $recursive = false): array
    {
        if (!str_starts_with($namespace, $this->psr)) {
            throw new InvalidArgumentException(sprintf(
                'The namespace: "%s" does not belong to the root: "%s"',
                $namespace,
                $this->psr
            ));
        }
        $directory = $this->dir . '/' . str_replace(
            '\\',
            '/',
            substr($namespace, strlen($this->psr))
        );
        if (!is_dir($directory)) {
            throw new InvalidArgumentException(sprintf(
                'The directory %s does not exist',
                $directory
            ));
        }
        $classes = [];
        foreach ($this->loopDirectory($directory, $recursive) as $file) {
            if ($file->getExtension() === 'php') {
                array_push($classes, str_replace(
                    '/',
                    '\\',
                    str_replace(
                        $this->dir,
                        rtrim($this->psr, '\\'),
                        $file->getPath() . '/' . $file->getBasename('.php')
                    )
                ));
            }
        }
        return $classes;
    }

    /**
     * @param string $path
     * @return DirectoryIterator
     */
    private function getLoop(string $path): DirectoryIterator
    {
        return new DirectoryIterator($path);
    }

    /**
     * @param string $path
     * @return RecursiveIteratorIterator
     */
    private function getRecursiveLoop(string $path): RecursiveIteratorIterator
    {
        return new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path)
        );
    }

    /**
     * @param string $path    A valid filesystem directory
     * @param bool $recursive Whether to recursively search the directory
     * @return iterable       A generator containing resolved values
     */
    private function loopDirectory(string $path, bool $recursive = false): iterable
    {
        if ($recursive) {
            $loop = $this->getRecursiveLoop($path);
        } else {
            $loop = $this->getLoop($path);
        }
        while ($loop->valid()) {
            $fileInfo = $loop->current();
            if ($fileInfo->isFile() && !$this->isDot($fileInfo)) {
                yield $fileInfo;
            }
            $loop->next();
        }
    }

    /**
     * @param SplFileInfo $info
     * @return bool
     */
    private function isDot(SplFileInfo $info): bool
    {
        return in_array(
            strtolower($info->getFilename()),
            ['.','..','.ds_store','thumbs.db','desktop.ini']
        );
    }

    /**
     * @param string $psr
     * @param string $dir
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateParams(string $psr, string $dir): void
    {
        $valid = true;
        if (!preg_match("/^[\w\\\\]+\\\\$/", $psr)) {
            $valid = false;
            $msg   = sprintf('The namespace: %s is not valid', $psr);
        }
        if (!is_dir($dir)) {
            $valid = false;
            $msg   = sprintf('The directory: %s is not valid in this filesystem', $dir);
        }
        if (!$valid) {
            throw new InvalidArgumentException($msg);
        }
    }

}
