<?php

use Framework\Router;
use Framework\Renderer\RendererInterface;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router\RouterTwigExtension;

// config generale
use function \DI\{autowire,get,factory};

// use function DI\create as object;
// http://php-di.org/doc/migration/6.0.html#diobject
// DI\autowire()  ou create

return [
    
    'database.host' => 'localhost:8889',
    'database.username' => 'root',
    'database.password' => 'root',
    'database.name' => 'monsupersite',

    'views.path' => dirname(__DIR__) . '/views',
    'twig.extensions' => [
        get(RouterTwigExtension::class)
    ],
    Router::class => autowire(),
    RendererInterface::class => factory(TwigRendererFactory::class)
    
];
