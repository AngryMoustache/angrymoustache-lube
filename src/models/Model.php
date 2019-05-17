<?php

namespace Lube\Models;

use Lube\LubeObject;

/**
 * Model that all other models extend
 *
 * @author Sander Van Damme <sander@codedor.be>
 * @since 22/03/2019
 */
class Model extends LubeObject
{
    /**
     * Do something before getting information from the database
     *
     * @param array $data The data to parse
     *
     * @return object
     */
    static function beforeFind($query): object
    {
        return $query;
    }

    /**
     * Do something after getting information from the database
     *
     * @param array $data The data to parse
     *
     * @return array
     */
    static function afterFind($data): array
    {
        return $data ?? [];
    }
}
