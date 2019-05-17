<?php

namespace Lube\Models;

use Lube\LubeObject;
// use Lube\Database\Query;

/**
 * Model that all other models extend
 *
 * @author Sander Van Damme <sander@codedor.be>
 * @since 22/03/2019
 */
class Model extends LubeObject
{
    // use Query;

    /**
     * Fields to be excluded while selecting
     *
     * @var array
     */
    static $excluded;
}
