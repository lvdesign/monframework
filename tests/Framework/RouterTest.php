<?php
namespace Tests\Framework;

use Framework\Router;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\ServerRequest;

class RouterTest extends TestCase{

    /**
     * @var Router
     */
    private $router;

    public function setUp():void
    { 
        $this->router = new Router();
    }

    public function testGetMethod()
    {
        $request = new ServerRequest('GET','/blog');
        $this->router->get('/blog', function(){ return 'hellooo'; }, 'blog');
        $route = $this->router->match($request);
        $this->assertEquals('blog', $route->getName());
        $this->assertEquals('hellooo', call_user_func_array($route->getCallback(), [$request]) );  
    }

    public function testGetMethodIfURLDoesNotExists()
    {
        $request = new ServerRequest('GET','/blog');
        $this->router->get('/blogaze', function(){ return 'hellooo'; }, 'blog');
        $route = $this->router->match($request);
        $this->assertEquals(null, $route);
    }

    public function testGetMethodWithParameters()
    {
        $request = new ServerRequest('GET','/blog/mon-slug-8');
        $this->router->get('/blog', function(){ return 'hello'; }, 'posts');
        $this->router->get('/blog/{slug:[a-z0-9\-]+}-{id:\d+}', function(){ return 'hello'; }, 'post.show');
        $route = $this->router->match($request);
        $this->assertEquals('post.show', $route->getName());
        $this->assertEquals('hello', call_user_func_array($route->getCallback(), [$request]) ); 
        $this->assertEquals(['slug' => 'mon-slug', 'id' => '8'], $route->getParams() );  
        // test invalid URI
        $route = $this->router->match(new ServerRequest('GET', '/blog/mon_slug-8'));
        $this->assertEquals(null, $route ); 
    }

    public function testGenerateUri()
    {
        $this->router->get('/blog', function(){ return 'hello'; }, 'posts');
        $this->router->get('/blog/{slug:[a-z0-9\-]+}-{id:\d+}', function(){ return 'hello'; }, 'post.show');
        $uri= $this->router->generateUri('post.show', ['slug' => 'mon-article', 'id' => 10]);
        $this->assertEquals('/blog/mon-article-10', $uri); 
    }

    public function testGenerateUriWithQueryParams()
    {
        $this->router->get('/blog', function(){ return 'hello'; }, 'posts');
        $this->router->get('/blog/{slug:[a-z0-9\-]+}-{id:\d+}', function(){ return 'hello'; }, 'post.show');
        $uri= $this->router->generateUri(
            'post.show', 
            ['slug' => 'mon-article', 'id' => 10],
        ['p' => 2]
    );
        $this->assertEquals('/blog/mon-article-10?p=2', $uri); 
    }

}