<?php

namespace Lube\Controller;

use Lube\LubeObject;

/**
 * Object for storing request data - GET, POST, etc.
 *
 * @author Sander Van Damme <sander@codedor.be>
 * @since 22/03/2019
 */
class Request extends LubeObject
{
    /**
     * Store data into the Request
     *
     * @param mixed $data The variables to save
     *
     * @return void
     */
    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Get a variable from the post requests
     *
     * @param string $key The key to get
     *
     * @return mixed
     */
    public function post($key = null)
    {
        if ($key) {
            return $this->post[$key];
        }

        return $this->post;
    }
}
