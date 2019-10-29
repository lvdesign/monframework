<?php
namespace Tests\Framework\Middleware;

use Exception;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\ServerRequest;
use Framework\Middleware\CsrfMiddleware;
use Psr\Http\Server\RequestHandlerInterface;
use Framework\Exception\CsrfInvalidException;

class CsrfMiddlewareTest extends TestCase
{

    /**
     * @var CsrfMiddleware
     */
    private $middleware;
    private $session;

    public function setUp()
    {
        $this->session = [];
        $this->middleware = new CsrfMiddleware($this->session);
    }

    // test
    public function testLetGetRequestPass()
    {
        $handler = $this->getMockBuilder(RequestHandlerInterface::class)
            ->setMethods(['handle'])
            ->getMock();

            $handler ->expects($this->once())
            ->method('handle')
            ->willReturn(new Response());

        $request = (new ServerRequest('GET', '/demo'));
        $this->middleware->process($request, $handler );
    }


// sans csrf
    public function testBlockPostRequestWithoutCsrf()
    {
        $delegate = $this->getMockBuilder(RequestHandlerInterface::class)
            ->setMethods(['handle'])
            ->getMock();

        $delegate->expects($this->never())->method('handle');

        $request = (new ServerRequest('POST', '/demo'));
        $this->expectException(CsrfInvalidException::class);
        $this->middleware->process($request, $delegate);
    }

    //  csrf invalid
    public function testBlockPostRequestWithInvalidCsrf()
    {
        $delegate = $this->getMockBuilder(RequestHandlerInterface::class)
            ->setMethods(['handle'])
            ->getMock();

        $delegate->expects($this->never())->method('handle');
        $this->middleware->generateToken();
        $request = (new ServerRequest('POST', '/demo'));
        $request = $request->withParsedBody(['_csrf' => 'azeaz']);
        $this->expectException(CsrfInvalidException::class);
        $this->middleware->process($request, $delegate);
    }

    //  csrf pass avec token
    public function testLetPostWithTokenPass()
    {
        $delegate = $this->getMockBuilder(RequestHandlerInterface::class)
            ->setMethods(['handle'])
            ->getMock();

        $delegate->expects($this->once())->method('handle')->willReturn(new Response());

        $request = (new ServerRequest('POST', '/demo'));
        $token = $this->middleware->generateToken();
        $request = $request->withParsedBody(['_csrf' => $token]);
        $this->middleware->process($request, $delegate);
    }

    //  csrf  et token une seule fois
    public function testLetPostWithTokenPassOnce()
    {
        $delegate = $this->getMockBuilder(RequestHandlerInterface::class)
            ->setMethods(['handle'])
            ->getMock();

        $delegate->expects($this->once())->method('handle')->willReturn(new Response());

        $request = (new ServerRequest('POST', '/demo'));
        $token = $this->middleware->generateToken();
        $request = $request->withParsedBody(['_csrf' => $token]);
        $this->middleware->process($request, $delegate);
        $this->expectException(\Exception::class);
        $this->middleware->process($request, $delegate);
    }

    public function testLimitTheTokenNumber()
    {
        for ($i = 0; $i < 100; ++$i) {
            $token = $this->middleware->generateToken();
        }
        $this->assertCount(50, $this->session['csrf']);
        $this->assertEquals($token, $this->session['csrf'][49]);
    }
}

// ./vendor/bin/phpunit tests/Framework/Middleware/CsrfMiddlewareTest.php  --colors