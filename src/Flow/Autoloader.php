<?php

namespace Flow;

class Autoloader
{
    /**
     * Directory path
     *
     * @var string
     */
    private $dir;

    /**
     * Constructor
     *
     * @param string|null $dir
     */
    public function __construct(string $dir = null)
    {
        if (is_null($dir)) {
            $dir = __DIR__ . '/..';
        }

        $this->dir = $dir;
    }

    /**
     * Return directory path
     *
     * @return string
     */
    public function getDir(): string
    {
        return $this->dir;
    }

    /**
     * Register
     *
     * @codeCoverageIgnore
     * @param string|null $dir
     */
    public static function register(string $dir = null)
    {
        ini_set('unserialize_callback_func', 'spl_autoload_call');
        spl_autoload_register(array(new self($dir), 'autoload'));
    }

    /**
     * Handles autoloading of classes
     *
     * @param string $class A class name
     *
     * @return boolean Returns true if the class has been loaded, and false for else.
     */
    public function autoload(string $class): bool
    {
        //If the class name doesn't contain 'Flow'
        if (0 !== strpos($class, 'Flow')) {
            return false;
        }

        if (file_exists($file = $this->dir . '/' . str_replace('\\', '/', $class) . '.php')) {
            require $file;

            return true;
        }

        return false;
    }
}
