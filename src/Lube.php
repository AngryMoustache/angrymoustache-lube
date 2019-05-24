<?php

namespace Lube;

use Lube\LubeObject;
use Lube\Controller\Request;
use Lube\Database\Database;
use Lube\Routing\Router;

/**
 * Main app file that handles init and stores global variables
 *
 * @author Sander Van Damme <sander@codedor.be>
 * @since 22/03/2019
 */
class Lube extends LubeObject
{
    /**
     * Construct the Lube instance
     *
     * @return void
     */
    public function __construct()
    {
        session_start();

        // Include files that must always be included
        $this->include('database.Database');
        $this->include('Debug');
        $this->include('controller.Controller');
        $this->include('routing.Router');
        $this->include('config.bootstrap');
        $this->include('helpers');

        // Connect to the database
        Database::connect();
    }

    /**
     * Render the page, called in webroot/index.php
     *
     * @return void
     */
    public function render()
    {
        $route = Router::getCurrentRoute();
        $request = $_SERVER['REQUEST_METHOD'];
        $parameters = $route->parameters;

        if ($request === 'POST') {
            foreach ($_POST as $key => $value) {
                $parameters['post'][$key] = $value;
            }
        }

        // Load the controller and call the action
        $controllerClass = $route->{'namespace'} . '\\Controllers\\' . $route->{'controller'};
        $controller = new $controllerClass();
        if (count($parameters) || $request === 'POST') {
            $view = $controller->{$route->{'action'}}(new Request($parameters));
        } else {
            $view = $controller->{$route->{'action'}}();
        }

        // Render the View object
        $view->render();
    }
}
