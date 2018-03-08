<?php

namespace Manday;

/**
 * PSR-4 compliant class autoloader.
 *
 * @author Manda Yugana
 */
class Autoloader
{
    /**
     * Associative array which maps namespace prefix to base directory
     * suggestions.
     * 
     * Autoloader iterates through base directory suggestions to find required
     * class file.
     * 
     * Example:
     * <code>
     * $baseDirs = [
     *     'Manday' => [
     *         '/suggest/path/to/manday/libraries-v2/src',
     *         '/suggest/path/to/manday/libraries-v1/src',
     *     ],
     *     'OtherNamespace' => [
     *         '/path/to/other/framework'
     *     ],
     * ];
     * </code>
     * 
     * @var array 
     */
    protected $baseDirs = [];
    
    /**
     * Extension of the class file.
     * 
     * @var string
     */
    protected $fileExtension = 'php';
    
    /**
     * Registers current autoloader.
     * 
     * @return \Manday\Autoloader Current autoloader object.
     */
    public function register(): Autoloader
    {
        spl_autoload_register([$this, 'load']);
        return $this;
    }
    
    /**
     * Suggests a base directory for a namespace prefix.
     * 
     * @param string $namespacePrefix Namespace prefix.
     * @param string $baseDir Base directory of the namespace prefix.
     * @param bool $prepend Whether to prepend or to append suggestion to the
     * list. If this is set to true, base directory will have higher priority as
     * it is placed at the top of the list.
     * @return \Manday\Autoloader Current autoloader object.
     */
    public function add(
        string $namespacePrefix,
        string $baseDir,
        bool $prepend = false
    ): Autoloader {
        // normalize prefix and base directory
        $namespacePrefix = trim($namespacePrefix, '\\') . '\\';
        $baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        // initialize prefixes
        if (isset($this->baseDirs[$namespacePrefix]) === false) {
            $this->baseDirs[$namespacePrefix] = [];
        }
        
        // add namespace
        if ($prepend) {
            array_unshift($this->baseDirs[$namespacePrefix], $baseDir);
        } else {
            $this->baseDirs[$namespacePrefix][] = $baseDir;
        }
        
        return $this;
    }
    
    /**
     * Searches required file and loads the class file.
     * 
     * Iterates through possible namespace prefixes of the class, and for each
     * prefix, iterates through base directory suggestions to find required
     * file. It stops after finding and loading the file.
     * 
     * 
     * @param string $class Fully qualified class name.
     * @return void
     */
    public function load(string $class): void
    {
        // iterate through possible namespaces of the class
        foreach ($this->getPossibleNamespacePrefixes($class) as $prefix) {
            if (isset($this->baseDirs[$prefix]) === false) {
                continue;
            }
            // iterate through base directory suggestions
            foreach ($this->baseDirs[$prefix] as $baseDir) {
                $relativeClass = substr($class, strlen($prefix));
                // stop if file found and loaded
                if ($this->loadFile($baseDir, $relativeClass)) {
                    return;
                }
            }
        }
    }
    
    /**
     * Retrieves all possible namespace prefixes from a class.
     * 
     * Example:
     * Class <code>Foo\Bar\Baz\Foo2\Bar2\Baz2</code> will return array
     * <code>
     * [
     *     'Foo\Bar\Baz\Foo2\Bar2\',
     *     'Foo\Bar\Baz\Foo2\',
     *     'Foo\Bar\Baz\',
     *     'Foo\Bar\',
     *     'Foo\',
     * ]
     * </code>.
     * 
     * @param string $class Fully qualified class name.
     * @return array List of possible namespace prefixes.
     */
    protected function getPossibleNamespacePrefixes(string $class): array
    {
        $prefixes = [];
        $prefix = $class;
        while (($pos = strrpos($prefix, '\\')) !== false) {
            $prefix = substr($prefix, 0, $pos);
            $prefixes[] = $prefix . '\\';
        }
        return $prefixes;
    }
    
    /**
     * Loads required file
     * 
     * @param string $baseDir Base directory.
     * @param string $relativeClass Part of fully qualified class name which
     * represents directory structure of the required file relative to base
     * directory.
     * @return bool True if file loaded successfully. False otherwise.
     */
    protected function loadFile(string $baseDir, string $relativeClass): bool
    {
        $relativePath = strtr($relativeClass, '\\', DIRECTORY_SEPARATOR);
        $fullPath = $baseDir . $relativePath . '.' . $this->fileExtension;
        if (file_exists($fullPath)) {
            require $fullPath;
            return true;
        }
        return false;
    }
}
