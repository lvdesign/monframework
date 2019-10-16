<?php
namespace Framework\Twig;

use Framework\Router;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap4View;

class PagerFantaExtension extends \Twig\Extension\AbstractExtension
{
    /**
     * @var Router
     */
    private $router;

    /**
     * __construct grac a injection de dependance
     *
     * @param  $router
     *
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }
   
   
    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('paginate', [$this, 'paginate'], ['is_safe' => ['html']]),
        ];
    }

    public function paginate(Pagerfanta $paginatedResults, string $route, array $queryArgs = []): string
    {

        $view = new TwitterBootstrap4View();
        //$options = array('proximity' => 3);
            return $html = $view->render($paginatedResults, function (int $page) use ($route, $queryArgs) {
                if ($page > 1) {
                    $queryArgs['p']=  $page; // permet de faire des filtres  tel que "page 1 verte"
                }
                return $this->router->generateUri($route, [], $queryArgs);
            });
    }
}
