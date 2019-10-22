<?php

use Framework\Router;
use Framework\Twig\TimeExtension;
use Framework\Renderer\RendererInterface;
use Framework\Router\RouterTwigExtension;
use Framework\Renderer\TwigRendererFactory;

// config generale
//use function \DI\{autowire,get,factory};

// use function DI\create as object;
// http://php-di.org/doc/migration/6.0.html#diobject
// DI\autowire()  ou create()

return [

    'database.host' => 'localhost:8889',
    'database.username' => 'root',
    'database.password' => 'root',
    'database.name' => 'monsupersite',

    'views.path' => dirname(__DIR__) . '/views',
    'twig.extensions' => [
        \DI\get(\Framework\Router\RouterTwigExtension::class),
        \DI\get(\Framework\Twig\PagerFantaExtension::class),
        \DI\get(\Framework\Twig\TextExtension::class),
        \DI\get(\Framework\Twig\TimeExtension::class),
        \DI\get(\Framework\Twig\FlashExtension::class),
        \DI\get(\Framework\Twig\FormExtension::class),
        
    ],
   \Framework\Session\SessionInterface::class => \DI\create(\Framework\Session\PHPSession::class), 
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
