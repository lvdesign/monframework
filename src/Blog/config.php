<?php

use App\Blog\BlogModule;
use function DI\get;
use function DI\add;
use function DI\create;
use function DI\autowire;

// DI\autowire() object() pas pour version 6
//use function DI\create as object;

return [
    'blog.prefix' => '/blog',
     BlogModule::class => autowire()->constructorParameter('prefix', get('blog.prefix'))
];
