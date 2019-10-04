<?php
namespace Tests\Framework;

// lancer test:  ./vendor/bin/phpunit tests/Framework/AppTest.php

use Framework\App;
use App\Blog\BlogModule;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Tests\Framework\Modules\StringModule;
use Tests\Framework\Modules\ErroredModule;

class AppTest extends TestCase{

    public function testRedirectTrailingSlash(){
        $app = new App();

        $request = new ServerRequest('GET', '/demoslash/');

        $response = $app->run($request);

        $this->assertContains('/demoslash', $response->getHeader('Location'));
        $this->assertEquals(301, $response->getStatusCode());
    }

    // <h1>Bienvenue sur l\'article' . $request->getAttribute('slug'). '</h1>
    public function testBlog(){
        $app = new App([
            BlogModule::class
        ]);

        $request = new ServerRequest('GET', '/blog');
        $response = $app->run($request);
        $this->assertContains('<h1>Bienvenue sur le blog</h1>', (string)$response->getbody());
        $this->assertEquals(200, $response->getStatusCode());

        $requestSingle = new ServerRequest('GET', '/blog/article-de-test');
        $responseSingle = $app->run($requestSingle);
        $this->assertContains('<h1>Bienvenue sur l\'article article-de-test</h1>', (string)$responseSingle->getbody());

    }

    // 
    public function testThrowExecptionIfNoResponseSent(){
        
        $app = new App(
           [ ErroredModule::class]
        );

        $request = new ServerRequest('GET', '/demo');
        $this->expectException(\Exception::class);
        $app->run($request);
    }

    // 
    public function testConvertStringToResponse(){        
        $app = new App(
           [ StringModule::class]
        );
        $request = new ServerRequest('GET', '/demo');
        $response = $app->run($request);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('DEMO', (string)$response->getbody());
    }

    //
    public function testError404(){
        $app = new App();

        $request = new ServerRequest('GET', '/toto');

        $response = $app->run($request);

        $this->assertContains('<h1>Error 404</h1>', (string)$response->getBody());
        $this->assertEquals(404, $response->getStatusCode());
    }


}