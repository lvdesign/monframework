<?php
namespace Tests\Framework\Middelware;

use Framework\Middleware\MethodMiddleware;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;



class MethodMiddlewareTest extends TestCase
{

    /**
     * @var MethodMiddleware
     */
    private $middleware;

    public function setUp()
    {
        $this->middleware = new MethodMiddleware();
    }
    
    
    
    /* public function testAddMethod(){
        $request= (new ServerRequest( 'POST', '/demo'))
             ->withParseBody( ['_method' => 'DELETE']);
        call_user_func_array($this->middleware, [request, function(ServerRequestInterface $request){
            $this->assertEquals('DELETE', $request->getMethod());
        }]);
        

    } */
    public function testAddMethod()
    {
        $delegate = $this->getMockBuilder(RequestHandlerInterface::class)
            ->setMethods(['handle'])
            ->getMock();

        $delegate->expects($this->once())
            ->method('handle')
            ->with($this->callback(function ($request) {
                return $request->getMethod() === 'DELETE';
            }));

        $request = (new ServerRequest('POST', '/demo'))
            ->withParsedBody(['_method' => 'DELETE']);
        $this->middleware->process($request, $delegate);
    }
}