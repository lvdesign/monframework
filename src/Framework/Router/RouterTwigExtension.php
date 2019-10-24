<?php
namespace Framework\Router;

use Framework\Router;
use Twig\Extensions\TextExtension;

class RouterTwigExtension extends \Twig\Extension\AbstractExtension
{
    /**
     * @var Router
     */
    private $router;


    public function __construct(Router $router)
    {
        $this->router = $router;
    }

// \Twig_SimpleFunction
// [$this, 'pathFor']
    
    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('path', [$this, 'pathFor']),
            new \Twig\TwigFunction('is_subpath', [$this, 'isSubPath']),
        ];
    }

    public function pathFor(string $path, array $params = []): string
    {
        return $this->router->generateUri($path, $params);
    }

    public function isSubPath(string $path): bool
    {
        $uri =$_SERVER['REQUEST_URI'] ?? '/';
        $expectedUri = $this->router->generateUri($path);

        return strpos($uri, $expectedUri) !== false;
    }
}
