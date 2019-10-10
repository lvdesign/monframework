<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$modules =  [
    \App\Blog\BlogModule::class
];


// injec Dependences
$builder = new \DI\ContainerBuilder();
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


// lors de migration eviter de chercher des responses

if (php_sapi_name() !== "cli") {
    // throw new Execption();
    $response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());
    \Http\Response\send($response);
}
