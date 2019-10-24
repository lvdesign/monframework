<?php

use App\Blog\BlogModule;
use function \DI\add;
use function \DI\get;

// DI\autowire() object() pas pour version 6
//use function DI\create as object;
// constructorParameter

return [
    'blog.prefix' => '/blog',
    'admin.widgets' => add([
        get(\App\Blog\BlogWidget::class)

    ])
];
