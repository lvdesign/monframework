<?php

use App\Blog\BlogModule;

use function \DI\autowire;
use function \DI\get;

// DI\autowire() object() pas pour version 6
//use function DI\create as object;
// constructorParameter

return [
    'blog.prefix' => '/blog',
     BlogModule::class => autowire()->constructorParameter('prefix', get('blog.prefix'))
];
