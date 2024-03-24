<?php

use DI\ContainerBuilder;
use DI\Bridge\Slim\Bridge;

// Require composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// Create PHP-DI container builder
$containerBuilder = new ContainerBuilder();

// Enable PHP-DI autowiring
$containerBuilder->useAutowiring(true);

// Build PHP-DI container
$container = $containerBuilder->build();

// Define factory function for 'config' entry in the container
$container->set('config', function () {
    // Read configuration data from file
    $config = require_once __DIR__ . '/../src/config.php';
    return $config;
});

// Create Slim app with PHP-DI integration
$app = Bridge::create($container);

// Set base path to run the app in a subdirectory.    
$app->setBasePath('/www/');

// Add body parsing middleware
$app->addBodyParsingMiddleware();

// Add error middleware
$app->addErrorMiddleware(true, true, true); // (displayErrorDetails, logErrors, logErrorDetails)

// Add routes
require __DIR__ . '/../src/routes.php';

$app->run();
