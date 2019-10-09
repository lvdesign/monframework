<?php

use Framework\Router;
use Framework\Renderer\RendererInterface;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router\RouterTwigExtension;


//use function \DI\{autowire,get,factory};

// use function DI\create as object;
// http://php-di.org/doc/migration/6.0.html#diobject
// DI\autowire()  ou create

return [    

    'views.path' => dirname(__DIR__) . '/views',
    'twig.extensions' => [
        \DI\get(RouterTwigExtension::class)
    ],
    Router::class => \DI\autowire(),
    RendererInterface::class => \DI\factory( TwigRendererFactory::class)
    
];
