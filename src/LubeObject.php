<?php

namespace Lube;

/**
 * Global variables, most classes overwrite this one
 *
 * @author Sander Van Damme <sander@codedor.be>
 * @since 22/03/2019
 */
class LubeObject
{
    /**
     * Magic __get function
     *
     * @var string $key the key of the value to get
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->$key;
    }

    /**
     * Include a file
     *
     * @param string $path      Path to the file to load
     * @param array  $variables Variables to be available in the included file
     *
     * @return void
     */
    public function include($path, $variables = [])
    {
        foreach($variables as $key => $value) {
            ${$key} = $value;
        }

        $path = str_replace('.', DS, $path);
        include $path . '.php';
    }

    /**
     * Convert a string with dot notation to a path
     *
     * @param string $path   The path to convert
     * @param bool   $folder Default to assets.views or not
     *
     * @return string
     */
    public function path($path, $folder = true)
    {
        return str_replace('.', DS, ($folder ? 'assets.views.' : '') . $path);
    }
}
