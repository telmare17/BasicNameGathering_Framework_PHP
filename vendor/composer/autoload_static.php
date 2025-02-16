<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit052cf36c8036cc98f48e629ab436a86b
{
    public static $files = array (
        'ff05b2fc531ffef7bf6195ce20dc3dc7' => __DIR__ . '/../..' . '/app/config.php',
        '9403696b7e048f01b3e05e13818a4bf4' => __DIR__ . '/../..' . '/app/helpers/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'b' => 
        array (
            'bng\\System\\' => 11,
            'bng\\Models\\' => 11,
            'bng\\Controllers\\' => 16,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'bng\\System\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/system',
        ),
        'bng\\Models\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/models',
        ),
        'bng\\Controllers\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/controllers',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/src',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit052cf36c8036cc98f48e629ab436a86b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit052cf36c8036cc98f48e629ab436a86b::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit052cf36c8036cc98f48e629ab436a86b::$classMap;

        }, null, ClassLoader::class);
    }
}
