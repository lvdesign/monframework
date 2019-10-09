<?php

namespace Framework;

use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class App
{
    /**
     * list modules
     * @var array
     */
    private $modules= [];

     /**
     * ContainerInterface
     * @var ContainerInterface
     */
    private $container;

   

    /**
     * __construct
     *
     * @param  mixed $container
     * @param  mixed $modules
     *
     * @return void
     */
    public function __construct(ContainerInterface $container, array $modules = [])
    {
        $this->container = $container;
        foreach ($modules as $module) {
            $this->modules[] = $container->get($module);
        }
    }


    public function run(ServerRequestInterface $request): ResponseInterface
    {
        $uri= $request->getUri()->getPath();
        if (!empty($uri) && $uri[-1]==="/") {
            return (new Response())
                ->withStatus(301)
                ->withHeader('Location', substr($uri, 0, -1));
        }

        $router = $this->container->get(Router::class);
        $route = $router->match($request);
        if (is_null($route)) {
            return new Response(404, [], '<h1>Error 404</h1>');
        }
        //
        $params = $route->getParams();
        $request = array_reduce(array_keys($params), function ($request, $key) use ($params) {
            return  $request->withAttribute($key, $params[$key]);
        }, $request);

        // Peut etre un callable ou une chaine de caractere
        $callback = $route->getCallback();
        if (is_string($callback)) {
            $callback = $this->container->get($callback);
        }
        $response = call_user_func_array($callback, [$request]);

        if (is_string($response)) {
            return new Response(200, [], $response);
        } elseif ($response instanceof ResponseInterface) {
            return $response;
        } else {
            throw new \Exception('The response not a string-TOTO');
        }
    }
}
