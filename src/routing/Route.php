<?php

namespace Lube\Routing;

use Lube\LubeObject;

/**
 * A route in the collection of the Router
 *
 * @author Sander Van Damme <sander@codedor.be>
 * @since 22/3/2019
 */
class Route extends LubeObject
{
    /** @var string */
    protected $path;

    /** @var string */
    protected $controller;

    /** @var string */
    protected $action;

    /** @var array */
    protected $parameters;

    /** @var string */
    protected $namespace;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(
        $path,
        $controller,
        $action,
        $parameters = [],
        $namespace = 'App'
    ) {
        $this->path = $path;
        $this->controller = $controller;
        $this->action = $action;
        $this->parameters = $parameters;
        $this->namespace = $namespace;
    }

    /**
     * Set the parameters for a route
     * 
     * @param array $parameters The parameters to set
     * 
     * @return void
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }
}
