<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf4d4c3fcbd66439a703c4e3083f1910a
{
    public static $prefixLengthsPsr4 = array (
        'V' => 
        array (
            'Views\\' => 6,
        ),
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'M' => 
        array (
            'Models\\' => 7,
        ),
        'C' => 
        array (
            'Controllers\\' => 12,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Views\\' => 
        array (
            0 => __DIR__ . '/../..' . '/App/Views',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'Models\\' => 
        array (
            0 => __DIR__ . '/../..' . '/App/Models',
        ),
        'Controllers\\' => 
        array (
            0 => __DIR__ . '/../..' . '/App/Controllers',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/App',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf4d4c3fcbd66439a703c4e3083f1910a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf4d4c3fcbd66439a703c4e3083f1910a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitf4d4c3fcbd66439a703c4e3083f1910a::$classMap;

        }, null, ClassLoader::class);
    }
}
