<?php
namespace App\Blog;

use App\Blog\Actions\AdminBlogAction;
use Framework\Module;
use Framework\Router;
use App\Blog\Actions\BlogAction;
use Framework\Renderer\RendererInterface;
use Psr\Container\ContainerInterface;

/**
 *  configuration du module, ajout routes pas d'actions
 */

class BlogModule extends Module
{
    
    const DEFINITIONS = __DIR__ . '/config.php';

    const MIGRATIONS = __DIR__ . '/db/migrations';

    const SEEDS = __DIR__ . '/db/seeds';
    

    //string $prefix, Router $router, RendererInterface $renderer
    public function __construct(ContainerInterface $container)
    {
        // blog
        $container->get(RendererInterface::class)->addPath('blog', __DIR__ . '/views');
        $router = $container->get(Router::class);
        $router->get($container->get('blog.prefix'), BlogAction::class, 'blog.index');
        $router->get($container->get('blog.prefix') . '/{slug:[a-z\-0-9]+}-{id:[0-9]+}', BlogAction::class, 'blog.show');
    
        // admin
        if ($container->has('admin.prefix')) {
            $prefix = $container->get('admin.prefix');

            $router->crud("$prefix/posts", AdminBlogAction::class, 'blog.admin');
        }
    }
}
