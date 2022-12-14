<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit86aea60ff5b033a34394959bf1539af1
{
    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'Parsedown' => 
            array (
                0 => __DIR__ . '/..' . '/erusev/parsedown',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit86aea60ff5b033a34394959bf1539af1::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit86aea60ff5b033a34394959bf1539af1::$classMap;

        }, null, ClassLoader::class);
    }
}
