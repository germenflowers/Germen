<?php

namespace SKS;

/**
 * Class CClassLoader
 * Provides autoloading for other classes any package
 * Singleton.
 *
 * To correctly autoload place classes into /local/php_interface/include/classes/ folder
 * by this path template: ./package_name/class_name.php
 * @category  Init
 */
class CClassLoader
{
    /**
     * @access protected
     * @static object CClassLoader
     */
    private static $instance;

    /**
     * Get Instance function
     * @access public
     * @static
     */
    public static function getInstance(): CClassLoader
    {
        if (!isset(self::$instance)) {
            self::$instance = new CClassLoader;
        }

        return self::$instance;
    }

    /**
     * Constructor function
     * @access private
     */
    private function __construct()
    {
        $this->setRootDir();
        spl_autoload_register(
            array(
                $this,
                'includeClass',
            )
        );
    }

    /**
     * @access private
     */
    private function __clone()
    {
        /* this is Singleton class */
    }

    /**
     * @access private
     */
    private function __wakeup()
    {
        /* this is Singleton class */
    }

    /**
     * Include file with required class
     * @access private
     *
     * @param  $class string
     */
    private function includeClass(string $class): void
    {
        $name = strtolower(
            str_replace(
                '\\',
                '/',
                $class
            )
        );
        $classesDir = '/local/php_interface/include/classes/';
        $classFile = $_SERVER['DOCUMENT_ROOT'].$classesDir.$name.'.php';
        if (file_exists($classFile)) {
            require_once($classFile);
        }/* else {
            throw new \Exception('Unable to load '.$class);
        }*/
    }

    /**
     * Function check server variable DOCUMENT_ROOT and set it if unset to provide console calls
     * @access private
     */
    private function setRootDir(): void
    {
        if (empty($_SERVER['DOCUMENT_ROOT'])) {
            $_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__, 3).'/';
        }
    }
}

CClassLoader::getInstance();
