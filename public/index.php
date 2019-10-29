<?php

use Middlewares\Whoops;
use App\Blog\BlogModule;
use App\Admin\AdminModule;
use GuzzleHttp\Psr7\ServerRequest;
// use Framework\Middleware\CsrfMiddleware;
use Framework\Middleware\MethodMiddleware;
use Framework\Middleware\RouterMiddleware;
use Framework\Middleware\NotFoundMiddleware;
use Framework\Middleware\DispatcherMiddleware;
use Framework\Middleware\TrailingSlashMiddleware;
use Framework\Middleware\RendererRequestMiddleware;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$modules =  [
    AdminModule::class,
    BlogModule::class,
];



$app = (new \Framework\App('config/config.php'))
        ->addModule(\App\Admin\AdminModule::class)
        ->addModule(\App\Blog\blogModule::class)
        ->pipe(Whoops::class)
        ->pipe(TrailingSlashMiddleware::class)
        ->pipe(MethodMiddleware::class)
          
        ->pipe(RendererRequestMiddleware::class)
        
        ->pipe(RouterMiddleware::class)
        ->pipe(DispatcherMiddleware::class)
        ->pipe(NotFoundMiddleware::class);

// lors de migration eviter de chercher des responses

if (php_sapi_name() !== "cli") {
    // throw new Execption();
    $response = $app->run(ServerRequest::fromGlobals());
    \Http\Response\send($response);
}
// ->pipe(CsrfMiddleware::class)
