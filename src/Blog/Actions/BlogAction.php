<?php
namespace App\Blog\Actions;

use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class BlogAction
{

    /**
     * @var RendererInterface
     */
    private $renderer;

    

    /**
     * __constructor
     *
     * @param  mixed $renderer
     *
     * @return void
     */
    
    public function __constructor(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    
    /**
     * __invoke
     *
     * @param  mixed $request
     *
     * @return void
     */
    
    public function __invoke(Request $request)
    {
        $slug = $request->getAttribute('slug');

        if ($slug) {
            return $this->show($slug);
        }
        return $this->index();
    }


    /**
     * index
     *
     * @return string
     */
    public function index(): string
    {
        return $this->renderer->render('@blog/index');
    }

    /**
     * show
     *
     * @param  mixed $slug
     *
     * @return string
     */
    public function show(string $slug): string
    {
        return $this->renderer->render(
            '@blog/show',
            ['slug' => $slug ]
        );
    }
}
