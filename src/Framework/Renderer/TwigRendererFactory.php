<?php
namespace Framework\Renderer;

use Psr\Container\ContainerInterface;
use Twig\Extension\DebugExtension;

class TwigRendererFactory
{


    public function __invoke(ContainerInterface $container): TwigRenderer
    {
        $debug  = $container->get('env') !== 'production';
        //var_dump($env);
        //die();
        $viewPath = $container->get('views.path');
        $loader = new \Twig\Loader\FilesystemLoader($viewPath);
        $twig = new \Twig\Environment($loader, [
            'debug' => $debug,
            'cache' => $debug ? false : 'tmp/views',
            'auto_reload' => $debug
            ]);
        $twig->addExtension(new DebugExtension());

        if ($container->has('twig.extensions')) {
            foreach ($container->get('twig.extensions') as $extension) {
                $twig->addExtension($extension);
            }
        }
        //return new TwigRenderer($loader, $twig);
        return new TwigRenderer($twig);
    }
}
