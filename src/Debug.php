<?php

namespace Lube;

/**
 * Some basic debug functions, are disabled when ENV is production
 *
 * @author Sander Van Damme <sander@codedor.be>
 * @since 22/03/2019
 */
class Debug
{
    /**
     * Prettier var_dump
     *
     * @param mixed   $variable Variable to dump
     *
     * @return void
     */
    static public function dump($variable)
    {
        echo '<pre>';
        var_dump($variable);
        echo '</pre>';
    }

    /**
     * Prettier var_dump that will die
     *
     * @param mixed   $variable Variable to dump
     *
     * @return void
     */
    static public function dumpDie($variable)
    {
        static::dump($variable);
        die;
    }
}
