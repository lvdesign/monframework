<?php
namespace Framework\Router;

use Framework\Router;

class RouterTwigExtension extends \Twig\Extension\AbstractExtension
{

    private $router;


    public function __construct(Router $router)
    {
        $this->router = $router;
    }

// \Twig_SimpleFunction
    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('path', [$this, 'pathFor'])
        ];
    }

    public function pathFor(string $path, array $params = []): string
    {
        return $this->router->generateUri($path, $params);
    }
}
