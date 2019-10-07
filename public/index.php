<?php
require '../vendor/autoload.php';

use GuzzleHttp\Psr7\ServerRequest;

$renderer = new \Framework\Renderer();
$renderer->addPath(dirname(__DIR__) . '/views');

// fichier source -> Request Response
$app = new \Framework\App(
    [
    \App\Blog\BlogModule::class
    ],
    [ 'renderer' => $renderer
    ]
);

$response = $app->run(ServerRequest::fromGlobals());

\Http\Response\send($response);
