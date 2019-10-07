<?php
require '../vendor/autoload.php';

use GuzzleHttp\Psr7\ServerRequest;

//PHP code : $renderer = new \Framework\Renderer\PHPRenderer(dirname(__DIR__) . '/views');
//$renderer->addPath(dirname(__DIR__) . '/views');

//$twig = new Twig_Environment($loader, []);
$renderer = new \Framework\Renderer\TwigRenderer(dirname(__DIR__) . '/views');
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
