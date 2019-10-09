<?php
namespace Framework\Router;

/**
 *  Implementation de la methode en dehors de la biblio router
 */

class Route
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string|callable
     */
    private $callback;

    /**
     * @var array
     */
    private $parameters;
    

    /**
     * __construct
     *
     * @param  string $name
     * @param  string|callable $callback
     * @param  array $parameters
     *
     * @return void
     */
    public function __construct(string $name, $callback, array $parameters)
    {
        $this->name= $name;
        $this->callable=$callback;
        $this->parameters = $parameters;
    }
    
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * getCallback
     *
     * @return string|callable
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * getParams retirev URL parametaer
     *
     * @return array string[]
     */
    public function getParams(): array
    {
        return $this->parameters;
    }
}
