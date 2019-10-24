<?php
namespace Framework;

use Framework\Router\Route;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\Route as ZendRoute;
use Psr\Http\Server\MiddlewareInterface;

/**
 * Register and match routes
 */
class Router
{

    /**
     * @var FastRouteRouter
     */
    private $router;

    public function __construct()
    {
        $this->router = new FastRouteRouter();
        // implemetation de ce router
    }

  
    /**
     * get
     *
     * @param  string $path
     * @param  string|callable $callable
     * @param  string $name
     *
     * @return void
     */
    public function get(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(new ZendRoute($path, $callable, ['GET'], $name));
    }

    /**
     * post
     *
     * @param  string $path
     * @param  string|callable $callable
     * @param  string $name
     *
     * @return void
     */
    public function post(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(new ZendRoute($path, $callable, ['POST'], $name));
    }


       /**
     * post
     *
     * @param  string $path
     * @param  string|callable $callable
     * @param  string $name
     *
     * @return void
     */
    public function delete(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(new ZendRoute($path, $callable, ['DELETE'], $name));
    }


    /**
     * crud genere les pages du CRUD
     * for example:
     * link : admin/posts -> $prefixPath;
     * page:  admin.posts.index: $prefixName.index
     *
     * @param  string $prefixPath  for example: admin/posts
     * @param  $callable
     * @param  string $prefixName
     *
     * @return void
     */
    public function crud(string $prefixPath, $callable, ?string $prefixName)
    {
        $this->get("$prefixPath", $callable, "$prefixName.index");

        $this->get("$prefixPath/new", $callable, "$prefixName.create");
        $this->post("$prefixPath/new", $callable);

        $this->get("$prefixPath/{id:\d+}", $callable, "$prefixName.edit");
        $this->post("$prefixPath/{id:\d+}", $callable);

        $this->delete("$prefixPath/{id:\d+}", $callable, "$prefixName.delete");
    }

    /**
     * match
     *
     * @param  ServerRequestInterface $request
     *
     * @return Route|null
     */
    public function match(ServerRequestInterface $request): ?Route
    {
        $result = $this->router->match($request);
        if ($result->isSuccess()) {
            return new Route(
                $result->getMatchedRouteName(),
                $result->getMatchedMiddleware(),
                $result->getMatchedParams()
            );
        }
        return null;
    }


    
    public function generateUri(string $name, array $params = [], array $queryParams = []): ?string
    {
        $uri =  $this->router->generateUri($name, $params);
        if (!empty($queryParams)) {
            return $uri . '?' . http_build_query($queryParams);
        }
        return $uri;
    }
}
