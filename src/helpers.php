<?php

if (!function_exists('session')) {
    function session($session, $name)
    {
        return $_SESSION[$session][$name] ?? null;
    }
}
