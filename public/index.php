<?php
/**
 * Define application environment
 */
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

/**
 * Setup autoloading
 */
if (file_exists('vendor/autoload.php')) {
    $loader = include 'vendor/autoload.php';
}

if (is_dir('vendor/ZF2/library') && isset($loader)) {
    $loader->add('Zend', 'vendor/ZF2/library');
    $loader->add('ZendXml', 'vendor/ZF2/library');
}

if (!class_exists('Zend\Loader\AutoloaderFactory')) {
    throw new RuntimeException('Unable to load ZF2. Run `php composer.phar install` or define a ZF2_PATH environment variable.');
}

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
