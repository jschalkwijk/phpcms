<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6a7b91ece681d4a9388290eac1d825c8
{
    public static $files = array (
        '5255c38a0faeba867671b61dfda6d864' => __DIR__ . '/..' . '/paragonie/random_compat/lib/random.php',
        '2c102faa651ef8ea5874edb585946bce' => __DIR__ . '/..' . '/swiftmailer/swiftmailer/lib/swift_required.php',
    );

    public static $prefixLengthsPsr4 = array (
        'J' => 
        array (
            'Jorn\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Jorn\\' => 
        array (
            0 => __DIR__ . '/../..' . '/admin',
        ),
    );

    public static $classMap = array (
        'Defuse\\Crypto\\Core' => __DIR__ . '/..' . '/defuse/php-encryption/src/Core.php',
        'Defuse\\Crypto\\Crypto' => __DIR__ . '/..' . '/defuse/php-encryption/src/Crypto.php',
        'Defuse\\Crypto\\DerivedKeys' => __DIR__ . '/..' . '/defuse/php-encryption/src/DerivedKeys.php',
        'Defuse\\Crypto\\Encoding' => __DIR__ . '/..' . '/defuse/php-encryption/src/Encoding.php',
        'Defuse\\Crypto\\Exception\\BadFormatException' => __DIR__ . '/..' . '/defuse/php-encryption/src/Exception/BadFormatException.php',
        'Defuse\\Crypto\\Exception\\CryptoException' => __DIR__ . '/..' . '/defuse/php-encryption/src/Exception/CryptoException.php',
        'Defuse\\Crypto\\Exception\\EnvironmentIsBrokenException' => __DIR__ . '/..' . '/defuse/php-encryption/src/Exception/EnvironmentIsBrokenException.php',
        'Defuse\\Crypto\\Exception\\IOException' => __DIR__ . '/..' . '/defuse/php-encryption/src/Exception/IOException.php',
        'Defuse\\Crypto\\Exception\\WrongKeyOrModifiedCiphertextException' => __DIR__ . '/..' . '/defuse/php-encryption/src/Exception/WrongKeyOrModifiedCiphertextException.php',
        'Defuse\\Crypto\\File' => __DIR__ . '/..' . '/defuse/php-encryption/src/File.php',
        'Defuse\\Crypto\\Key' => __DIR__ . '/..' . '/defuse/php-encryption/src/Key.php',
        'Defuse\\Crypto\\KeyOrPassword' => __DIR__ . '/..' . '/defuse/php-encryption/src/KeyOrPassword.php',
        'Defuse\\Crypto\\KeyProtectedByPassword' => __DIR__ . '/..' . '/defuse/php-encryption/src/KeyProtectedByPassword.php',
        'Defuse\\Crypto\\RuntimeTests' => __DIR__ . '/..' . '/defuse/php-encryption/src/RuntimeTests.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6a7b91ece681d4a9388290eac1d825c8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6a7b91ece681d4a9388290eac1d825c8::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit6a7b91ece681d4a9388290eac1d825c8::$classMap;

        }, null, ClassLoader::class);
    }
}