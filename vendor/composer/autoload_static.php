<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7de8ca7b2ef4b667d3a9ea974a4be144
{
    public static $prefixLengthsPsr4 = array (
        'l' => 
        array (
            'leifermendez\\police\\' => 20,
        ),
        'a' => 
        array (
            'anlutro\\cURL\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'leifermendez\\police\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'anlutro\\cURL\\' => 
        array (
            0 => __DIR__ . '/..' . '/anlutro/curl/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7de8ca7b2ef4b667d3a9ea974a4be144::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7de8ca7b2ef4b667d3a9ea974a4be144::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}