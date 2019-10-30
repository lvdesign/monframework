<?php
namespace Framework\Actions;

use Framework\Database\Table;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Framework\Session\FlashService;
use Framework\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class CrudAction
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
     * @var Table
     */
    protected $table;
    /**
     * @var FlashService
     */
    private $flash;

    /**
     * @var string
     */
    protected $viewPath;
    /**
     * @var string
     */
    protected $routePrefix;

    /**
     * @var string
     */
    protected $messages=[
        'create' => "l'élément a bien été crée",
        'edit' => "l'élément a bien été modifié",
    ];


    use RouterAwareAction;



    public function __construct(
        RendererInterface $renderer,
        Router $router,
        Table $table,
        FlashService $flash
    ) {
                $this->renderer = $renderer;
                $this->router = $router;
                $this->table = $table;
                $this->flash = $flash;
    }


    
    public function __invoke(Request $request)
    {
        $this->renderer->addGlobal('viewPath', $this->viewPath);
        $this->renderer->addGlobal('routePrefix', $this->routePrefix);

        
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
     * index  INDEX  affiche la liste des elements
     *      reglage nb page visible ds BLOG $perpage 12  !!!  posts devient items
     *
     * @return string
     */
    public function index(Request $request): string
    {
        $params = $request->getQueryParams();
        $items = $this->table->findPaginated(12, $params['p'] ?? 1);

        return $this->renderer->render($this->viewPath .'/index', compact('items'));
    }



    /**
     * edit un article EDIT
     *
     * @param Request $request
     *
     * @return ResponseInterface|string
     */
    public function edit(Request $request)
    {
        $item = $this->table->find($request->getAttribute('id'));

        if ($request->getMethod() === 'POST') {
            // recupere tous les parametre
             // ds Validator $params = array_merge($request->getParsedBody(), $request->getUploadedFiles());
            //$this->getParams($request);

            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                //$this->table->update($item->id, $params);
                $this->table->update($item->id, $this->getParams($request, $item));
                $this->flash->success($this->messages['edit']);
                return $this->redirect($this->routePrefix . '.index');
            }
           // var_dump($params);
            $errors = $validator->getErrors();
            $params = $request->getParsedBody();
            $params['id'] = $item->id;
            $item =$params;
        }



        return $this->renderer->render(
            $this->viewPath .'/edit',
            $this->formParams(compact('item', 'errors'))
        ); // ajout de la liste
    }


    /**
     * Crée un nouvel element  CREATE
     * @param Request $request
     * @return ResponseInterface|string
     */
    public function create(Request $request)
    {
        $item = $this->getNewEntity();
        
        if ($request->getMethod() === 'POST') {
            // $params = $this->getParams($request); lors ajout de image
            
            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->table->insert($this->getParams($request, $item));
                $this->flash->success($this->messages['create']);
                return $this->redirect($this->routePrefix . '.index');
            }

            $item = $request->getParsedBody();  //$params;
            $errors = $validator->getErrors();
           // var_dump($params);
        }
        //return $this->renderer->render($this->viewPath .'/create', compact('item', 'errors'));

        return $this->renderer->render(
            $this->viewPath .'/create',
            $this->formParams(compact('item', 'errors'))
        ); // ajout de la liste
    }



    /**
     * delete DELETE
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function delete(Request $request)
    {
        $this->table->delete($request->getAttribute('id'));
        return $this->redirect($this->routePrefix . '.index');
    }



    protected function getParams(Request $request, $item): array
    {
        return array_filter($request->getParsedBody(), function ($key) {
            return in_array($key, []);
        }, ARRAY_FILTER_USE_KEY);
    }


    protected function getValidator(Request $request)
    {
        //return new Validator($request->getParsedBody());
        return new Validator(array_merge($request->getParsedBody(), $request->getUploadedFiles()));
    }

    /**
     * getNewEntity
     *
     * @return void
     */
    protected function getNewEntity()
    {
        return [];
    }


    /**
     * formParams traite parametre a envoye a la vue
     *
     * @param  array $params
     *
     * @return array
     */
    protected function formParams(array $params): array
    {
        return $params;
    }
}
