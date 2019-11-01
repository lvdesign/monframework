<?php

use App\Auth\DatabaseAuth;
use App\Auth\ForbiddenMiddleware;
use App\Auth\AuthTwigExtension;
use Framework\Auth;

return [
    'auth.login' => '/login',
    'twig.extensions' => \DI\add([
        \DI\get(AuthTwigExtension::class),
        
    ]),
    Auth::class => \DI\get(DatabaseAuth::class),
    ForbiddenMiddleware::class => \DI\autowire()->constructorParameter('loginPath', \DI\get('auth.login'))
];
