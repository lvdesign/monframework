<?php

use App\Admin\AdminModule;
use App\Blog\BlogModule;
use Framework\Middleware\DispatcherMiddleware;
use Framework\Middleware\MethodMiddleware;
use Framework\Middleware\RouterMiddleware;
use Framework\Middleware\RendererRequestMiddleware;
use Framework\Middleware\TrailingSlashMiddleware;
use Framework\Middleware\NotFoundMiddleware;
use GuzzleHttp\Psr7\ServerRequest;
use Middlewares\Whoops;

require dirname(__DIR__) . '/vendor/autoload.php';

$modules =  [
    AdminModule::class,
    BlogModule::class,
];



$app = (new \Framework\App(dirname(__DIR__) .'/config/config.php'))
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
