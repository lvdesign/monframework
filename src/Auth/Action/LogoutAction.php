<?php
namespace App\Auth\Action;

use App\Auth\DatabaseAuth;
use Framework\Response\RedirectResponse;
use Framework\Renderer\RendererInterface;
use Framework\Session\FlashService;
use Psr\Http\Message\ServerRequestInterface;

class LogoutAction
{
    /**
     * @var RendererInterface
     */
    private $renderer;

     /**
     * @var FlashService
     */
    private $flashService;


    public function __construct(RendererInterface $renderer, DatabaseAuth $auth, FlashService $flashService)
    {
        $this->renderer= $renderer;
        $this->auth= $auth;
        $this->flashService=$flashService;
    }


    public function __invoke(ServerRequestInterface $request)
    {
        $this->auth->logout();
        $this->flashService->success('Vous êtes bien deconnecté.');
        return new RedirectResponse('/');
    }
}
