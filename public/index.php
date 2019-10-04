<?php
require '../vendor/autoload.php';

use GuzzleHttp\Psr7\ServerRequest;

// fichier source -> Request Response
$app = new \Framework\App();

$response = $app->run(ServerRequest::fromGlobals());

\Http\Response\send($response);
