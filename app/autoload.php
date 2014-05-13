<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var $loader ClassLoader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

//$loader->registerPrefixes(array('Sailthru_Client' => __DIR__.'/../vendor/sailthru/sailthru-php5-client/sailthru'));

//echo __DIR__.'/../vendor/AuthorizeDotNet/';
 //$loader->registerPrefixes(array('AuthorizeNet' => __DIR__.'/../vendor/AuthorizeDotNetBundle/AuthorizeDotNetBundle/lib'));

return $loader;
