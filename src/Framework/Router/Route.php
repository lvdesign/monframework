<?php
namespace Framework\Router;

/**
 *  Implementation de la methode en dehors de la biblio router
 */

class Route
{

    private $name;
    private $callable;
    private $parameters;
    

    public function __construct(string $name, callable $callable, array $parameters)
    {
        $this->name= $name;
        $this->callable=$callable;
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
     * @return callable
     */
    public function getCallback(): callable
    {
        return $this->callable;
    }

    /**
     * getParams
     *
     * @return array string[]
     */
    public function getParams(): array
    {
        return $this->parameters;
    }
}
