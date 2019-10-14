<?php

namespace Tests\App\Blog\Actions;

use PDO;
use Framework\Router;
use App\Blog\Table\PostTable;
use PHPUnit\Framework\TestCase;
use App\Blog\Actions\BlogAction;
use GuzzleHttp\Psr7\ServerRequest;
use Framework\Renderer\RendererInterface;

class BlogActionsTest extends TestCase
{
    /**
     * @var BlogAction
     */
    private $action;

    private $renderer;
    private $postTable;
    private $router;


        public function setUp()
        {
        // simulation avec prophesize
            $this->renderer = $this->prophesize(RendererInterface::class);
            $this->postTable = $this->prophesize(PostTable::class);

            $this->router = $this->prophesize(Router::class);            

            $this->action= new BlogAction(
                $this->renderer->reveal(),               
                $this->router->reveal(),
                $this->postTable->reveal()
            );
        }

        public function makePost(int $id, string $slug): \stdClass
        {
            $post = new \stdClass();
            $post->id = $id;
            $post->slug = $slug;
            return $post;
        }



        public function testshowRedirect() {            

            $post = $this->makePost( 9, 'toto-toto');

           
            $request = (new ServerRequest('GET', '/'))
                ->withAttribute('id', $post->id)
                ->withAttribute('slug','pas-bon-slug');

            $this->router->generateUri('blog.show', ['id' => $post->id, 'slug'=> $post->slug])->willReturn('/demo2');
            $this->postTable->find($post->id)->willReturn($post);
    

            $response = call_user_func_array($this->action, [$request] );
            $this->assertEquals(301, $response->getStatusCode() );
            $this->assertEquals(['/demo2'], $response->getHeader('location') );
        }


        public function testshowRender() {            

            $post = $this->makePost( 9, 'toto-toto');

           
            $request = (new ServerRequest('GET', '/'))
                ->withAttribute('id', $post->id)
                ->withAttribute('slug',$post->slug);

           
            $this->postTable->find($post->id)->willReturn($post);
            $this->renderer->render('@blog/show', ['post' => $post])->willReturn('');

            $response = call_user_func_array($this->action, [$request] );
            $this->assertEquals(true, true);
           
        }
    
}