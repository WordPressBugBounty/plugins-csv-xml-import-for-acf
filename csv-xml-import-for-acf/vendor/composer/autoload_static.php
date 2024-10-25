<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7b201fc9b3018e32bcb49778920a5860
{
    public static $prefixLengthsPsr4 = array (
        'p' => 
        array (
            'pmai_acf_add_on\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'pmai_acf_add_on\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7b201fc9b3018e32bcb49778920a5860::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7b201fc9b3018e32bcb49778920a5860::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7b201fc9b3018e32bcb49778920a5860::$classMap;

        }, null, ClassLoader::class);
    }
}
