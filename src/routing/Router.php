<?php

namespace Lube\Routing;

use Lube\LubeObject;
use Lube\Routing\Route;
use Lube\Debug;

/**
 * Collection of all routes, handles redirecting
 *
 * @author Sander Van Damme <sander@codedor.be>
 * @since 22/3/2019
 */
class Router extends LubeObject
{
    /**
     * Collection of registered routes
     *
     * @var array
     */
    static protected $routes = [];

    /**
     * The current route
     *
     * @var array
     */
    static protected $currentRoute;

    /**
     * Get the current route
     *
     * @return Route
     */
    static public function getCurrentRoute() : Route
    {
        $parameters = array();

        // The current URL
        $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
        $_url = substr($_SERVER['REQUEST_URI'], strlen($basepath));
        if (strpos($_url, '?') > -1) {
            $_url = explode('?', $_url)[0];
        }

        $currentUrl = explode('/', $_url);

        // Pop the final element of the route if it is empty
        // otherwise /users would work, but /users/ would not
        if (count($currentUrl) > 1 && end($currentUrl) == '') {
            array_pop($currentUrl);
        }

        // Loop and find the correct route
        foreach (self::$routes as $_sysRoute) {
            $sysRoute = explode('/', $_sysRoute->path);
            array_shift($sysRoute);

            // Check if the length matches
            if (count($sysRoute) == count($currentUrl)) {
                $parameters = array();
                for ($i = 0; $i < count($sysRoute); $i++) {
                    $parameter = (strpos($sysRoute[$i], ':') > -1);
                    if ($sysRoute[$i] == $currentUrl[$i]) {
                        // Nothing, continue
                    } else if ($parameter) {
                        $parameters[ltrim($sysRoute[$i], ':')] = $currentUrl[$i];
                    } else {
                        break;
                    }
                    if ($i + 1 == count($sysRoute)) {
                        $foundRoute = $_sysRoute;
                        $foundRoute->setParameters($parameters);
                        break 2;
                    }
                }
            }
        }

        self::$currentRoute = $foundRoute ?? array();
        return self::$currentRoute;
    }

    /**
     * Add a route to the collection
     *
     * @param string $path       Path that the route triggers on
     * @param string $controller The controller to load
     * @param string $action     The action to fire on the controller
     * @param string $namespace  The namespace to look in
     *
     * @return void
     */
    static public function connect($path, $controller, $action, $namespace = 'App')
    {
        self::$routes[$path] = new Route(
            $path,
            $controller,
            $action,
            [],
            $namespace
        );
    }
}
