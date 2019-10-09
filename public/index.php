<?php
require '../vendor/autoload.php';

use GuzzleHttp\Psr7\ServerRequest;

$modules =  [
    \App\Blog\BlogModule::class
];


// injec Dependences
$builder = new DI\ContainerBuilder();
$builder->addDefinitions(dirname(__DIR__) .'/config/config.php');


foreach ($modules as $module) {
    if ($module::DEFINITIONS) {
        $builder->addDefinitions($module::DEFINITIONS);
    }
}
$builder->addDefinitions(dirname(__DIR__) .'/config.php');

$container = $builder->build();
// Permet de overwrite le module


$app = new \Framework\App($container, $modules);

$response = $app->run(ServerRequest::fromGlobals());

\Http\Response\send($response);
