<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit7b201fc9b3018e32bcb49778920a5860
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit7b201fc9b3018e32bcb49778920a5860', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit7b201fc9b3018e32bcb49778920a5860', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit7b201fc9b3018e32bcb49778920a5860::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
