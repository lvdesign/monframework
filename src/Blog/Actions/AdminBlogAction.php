<?php
namespace App\Blog\Actions;

use App\Blog\Table\PostTable;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Framework\Session\FlashService;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Framework\Session\SessionInterface;
use Framework\Validator;

class AdminBlogAction
{

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var Router
     */
    private $router;
    /**
     * @var PostTable
     */
    private $postTable;
  


    use RouterAwareAction;

    public function __construct(
        RendererInterface $renderer,
        Router $router,
        PostTable $postTable,
        FlashService $flash
    ) {
                $this->renderer = $renderer;
                $this->router = $router;
                $this->postTable = $postTable;
                $this->flash = $flash;
    }

    public function __invoke(Request $request)
    {
        if ($request->getMethod() === 'DELETE') {
            return $this->delete($request);
        }
        
        if (substr((string)$request->getUri(), -3) === 'new') {
            return $this->create($request);
        }
        
        if ($request->getAttribute('id')) {
            return $this->edit($request);
        }
        return $this->index($request);
    }

    
    /**
     * index reglage nb page visible ds BLOG $perpage 12  !!!  posts devient items
     *
     * @return string
     */
    public function index(Request $request): string
    {
        $params = $request->getQueryParams();
        $items = $this->postTable->findPaginated(12, $params['p'] ?? 1);

        return $this->renderer->render('@blog/admin/index', compact('items'));
    }



    /**
     * edit un article
     *
     * @param Request $request
     *
     * @return ResponseInterface|string
     */
    public function edit(Request $request)
    {
        $item = $this->postTable->find($request->getAttribute('id'));

        if ($request->getMethod() === 'POST') {
            $params = $this->getParams($request);

            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->postTable->update($item->id, $params);
                $this->flash->success('Article modifie');
                return $this->redirect('blog.admin.index');
            }
           // var_dump($params);
            $errors = $validator->getErrors();
            $params['id'] = $item->id;
            $item =$params;
        }
        return $this->renderer->render('@blog/admin/edit', compact('item', 'errors'));
    }


      /**
     * Crée un nouvel article
     * @param Request $request
     * @return ResponseInterface|string
     */
    public function create(Request $request)
    {
        if ($request->getMethod() === 'POST') {
            $params = $this->getParams($request);
            
            
            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->postTable->insert($params);
                $this->flash->success('Article cree');
                return $this->redirect('blog.admin.index');
            }

            $item= $params;
            $errors = $validator->getErrors();
            

           // var_dump($params);
        }
        return $this->renderer->render('@blog/admin/create', compact('item', 'errors'));
    }



    public function delete(Request $request)
    {
        $this->postTable->delete($request->getAttribute('id'));
        return $this->redirect('blog.admin.index');
    }


    private function getParams(Request $request)
    {
        $params = array_filter($request->getParsedBody(), function ($key) {
            return in_array($key, ['name', 'slug','content', 'created_at']);
        }, ARRAY_FILTER_USE_KEY);

        return array_merge($params, [
            'updated_at' => date('Y-m-d H:i:s')
         ]);
    }


    private function getValidator(Request $request)
    {
        return ( new Validator($request->getParsedBody()) )
                ->required('content', 'name', 'slug')
                ->length('content', 10)
                ->length('name', 2, 250)
                ->length('slug', 2, 50)
                ->slug('slug');
    }
}
