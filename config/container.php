<?php

namespace App\Controllers;

use Slim\Container;
use Slim\Http\Environment;

/** @var \Slim\App $app */
$container = $app->getContainer();

// Activating routes in a subfolder
$container['environment'] = function () {
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $_SERVER['SCRIPT_NAME'] = dirname(dirname($scriptName)) . '/' . basename($scriptName);
    return new Environment($_SERVER);
};

$container['App\Controller\Controller'] = function ($c)
{
    return new App\Controller\Controller($c);
};

// Register Twig View helper
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig('views', [
        'cache' => false,//lub lokalizacja
		'debug'=>true,
    ]);
	$view->addExtension(new \Twig\Extension\DebugExtension());
	$view['baseUrl'] = $c['request']->getUri()->getBaseUrl();

    // Instantiate and add Slim specific extension
    $router = $c->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    return $view;
};
