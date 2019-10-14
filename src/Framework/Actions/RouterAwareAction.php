<?php
namespace Framework\Actions;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

/**
 *
 * Rajoute methode liée au Router
 * Trait RouterAwareAction
 * @package Framework\Actions;
 */
trait RouterAwareAction
{
    /**
     * redirect a response
     *
     * @param  mixed $path
     * @param  mixed $params
     *
     * @return ResponseInterface
     */
    public function redirect(string $path, array $params = []): ResponseInterface
    {
        $redirectUri = $this->router->generateUri($path, $params);
        return (new Response())
            ->withStatus(301)
            ->withHeader('location', $redirectUri);
    }
}
