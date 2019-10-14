<?php

use Framework\Router;
use Framework\Renderer\RendererInterface;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router\RouterTwigExtension;

// config generale
//use function \DI\{autowire,get,factory};

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
        \DI\get(\Framework\Router\RouterTwigExtension::class)
    ],
    \Framework\Router::class => \DI\autowire(),
    \Framework\Renderer\RendererInterface::class => \DI\factory(\Framework\Renderer\TwigRendererFactory::class),

    \PDO::class => function (\Psr\Container\ContainerInterface $c) {
        return new PDO(
            'mysql:host=' . $c->get('database.host') . ';dbname=' . $c->get('database.name'),
            $c->get('database.username'),
            $c->get('database.password'),
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }

];
