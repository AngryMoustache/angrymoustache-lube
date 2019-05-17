<?php

namespace Lube\Database;

use PDO;

/**
 * Database class, for connecting to the database and performing queries
 *
 * @author Sander Van Damme <sander@codedor.be>
 * @since 22/3/2019
 */
class Database
{
    /**
     * Instance of the database
     *
     * @var array
     */
    protected static $_db = null;

    /**
     * Connect to the database and return the database instance
     *
     * @return void
     */
    static function connect()
    {
        if (self::$_db == null) {
            $dsn_properties = [
                'dbname'  => DB_NAME,
                'host'    => DB_HOST,
                'port'    => DB_PORT,
                'charset' => 'utf8',
            ];

            $dsn = 'mysql:';

            foreach ($dsn_properties as $property => $value)
            {
                $dsn .= "{$property}={$value};";
            }

            $dbconfig  = [
                'dsn'      => $dsn,
                'user'     => DB_USER,
                'password' => DB_PASS,
                'options'  => array(
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::NULL_TO_STRING => false
                ),
            ];

            try
            {
                $db = new PDO(
                    $dbconfig['dsn'],
                    $dbconfig['user'],
                    $dbconfig['password'],
                    $dbconfig['options']
                );
            }

            catch (PDOException $e)
            {
                die('Failed to connect to the database<pre>' . $e->getMessage() .'</pre>');
            }

            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            self::$_db = $db;
        }
    }

    /**
     * Perform a select on the database
     *
     * @param string $sql the sql to perform
     *
     * @return array or null
     */
    static function SQLselect($sql)
    {
        $db = self::$_db;
        if ($db->query($sql))
        {
            if ($_SERVER['REQUEST_METHOD'] != 'DELETE')
            {
                $rows = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
                if ($rows)
                {
                    return $rows;
                }
            }
        }

        return null;
    }

    /**
     * Do a raw SQL for INSERT and UPDATE
     *
     * @param string  $sql    the sql to perform
     * @param boolean $return wether to return the new ID or not
     *
     * @return boolean or id
     */
    static function SQL($sql, $return = true) {
        $db = self::$_db;
        $stmt = $db->prepare($sql);
        if ($stmt)
        {
            $stmt->execute();
            if ($return)
            {
                $id = $db->lastInsertId();
                return $id;
            }

            return true;
        }
    }

    /**
     * Get the amount of items in a table
     *
     * @param string $tablename tablename
     * @param string $where where clause
     * @param string $countDeleted count deleted items
     *
     * @return integer
     */
    static function getAmount($tablename, $where = '', $countDeleted = true)
    {
        if ($countDeleted != true) {
            if ($where == '') {
                $where .= ' WHERE '. $countDeleted . ' IS NULL';
            } else {
                $where .= ' AND '. $countDeleted . ' IS NULL';
            }
        }

        $sql = 'SELECT COUNT(*) FROM ' . $tablename . ' ' . $where;
        $count = Database::SQLselect($sql);
        return $count[0]['COUNT(*)'];
    }

    /**
     * Create an SQL where string from options
     *
     * @param array $options array of options
     *
     * @return string
     */
    static function createWhereString($options)
    {
        $sql = '';
        foreach ($options as $key => $value) {
            if ($sql == '') {
                $sql .= ' WHERE `' . $key . '` = "' . $value . '"';
            } else {
                $sql .= ' AND `' . $key . '` = "' . $value . '"';
            }
        }

        return $sql;
    }
}
