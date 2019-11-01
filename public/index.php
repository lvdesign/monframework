<?php

use Middlewares\Whoops;
use App\Auth\AuthModule;
use App\Blog\BlogModule;
use App\Admin\AdminModule;
use App\Auth\ForbiddenMiddleware;
use Framework\Auth\LoggedInMiddleware;

// use Framework\Middleware\CsrfMiddleware;
use GuzzleHttp\Psr7\ServerRequest;
use Framework\Middleware\MethodMiddleware;
use Framework\Middleware\RouterMiddleware;
use Framework\Middleware\NotFoundMiddleware;
use Framework\Middleware\DispatcherMiddleware;
use Framework\Middleware\TrailingSlashMiddleware;
use Framework\Middleware\RendererRequestMiddleware;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/* $modules =  [
    AdminModule::class,
    BlogModule::class,
]; */



$app = (new \Framework\App('config/config.php'))
        ->addModule(AdminModule::class)
        ->addModule(BlogModule::class)
        ->addModule(AuthModule::class);


$container = $app->getContainer();

$app->pipe(Whoops::class)
    ->pipe(TrailingSlashMiddleware::class)
    ->pipe(ForbiddenMiddleware::class)
    ->pipe($container->get('admin.prefix'), LoggedInMiddleware::class)
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
