<?php

namespace Lube\Database;

use Lube\Debug;

/**
 * The Query trait for creating database calls, used on Model
 *
 * @author Sander Van Damme <sander@codedor.be>
 * @since 22/03/2019
 */
trait Queryable
{
    /**
     * The data saved in the query
     *
     * @var array
     */
    static protected $query = [
        'from' => '',
        'order' => [],
        'select' => [],
        'where' => [],
    ];

    /**
     * Set the tablename to get from
     *
     * @param string $tablename tablename
     *
     * @return self
     */
    static function table($tablename)
    {
        self::$query['from'] = $tablename;
        return new static;
    }

    /**
     * Add a select statement
     *
     * @param array $fields The columns to get
     *
     * @return self
     */
    static function select($fields)
    {
        foreach ($fields as $field) {
            if (array_search($field, self::$query['select']) === false) {
                self::$query['select'][] = $field;
            }
        }

        return new static;
    }

    /**
     * Set conditions for the query
     *
     * @param array $conditions The conditions
     *
     * @return self
     */
    static function where($conditions, $is = null)
    {
        if (is_array($conditions)) {
            foreach ($conditions as $key => $field) {
                if (array_search($field, self::$query['where']) === false) {
                    self::$query['where'][$key] = $field;
                }
            }
        } else {
            self::$query['where'][$conditions] = $is;
        }

        return new static;
    }

    /**
     * Set the order
     *
     * @param array $orders The orders
     *
     * @return self
     */
    static function order($orders)
    {
        foreach ($orders as $field) {
            if (array_search($field, self::$query['order']) === false) {
                self::$query['order'][] = $field;
            }
        }

        return new static;
    }

    /**
     * Perform a get statement
     *
     * @return array
     */
    static function get()
    {
        // Build the SQL
        self::beforeFind(new static);
        $query = self::$query;

        $sql = 'SELECT ';

        // Set the tablename if none is set yet
        if ($query['from'] === '') {
            $query['from'] = self::$tablename;
        }

        // SELECT
        if (!empty($query['select'])) {
            $sql .= join(', ', $query['select']);
        } else {
            $sql .= ' * ';
        }

        // EXLUDE
        if (!empty(self::$excluded)) {
            $sql .= ' , NULL AS ';
            $sql .= join(', NULL AS ', self::$excluded);
        }

        // FROM
        $sql .= ' FROM ' . $query['from'];

        // WHERE
        if (!empty($query['where'])) {
            $where = [];
            foreach ($query['where'] as $key => $value) {
                if (preg_match('/<(.*?)>/', $key)) {
                    $where[] = $key . ' "' . $value . '"';
                } else {
                    $where[] = $key . ' = "' . $value . '"';
                }
            }

            $sql .= ' WHERE ' . join(' AND ', $where);
        }

        // ORDER
        if (!empty($query['order'])) {
            $sql .= ' ORDER BY ' . join(', ', $query['order']);
        }

        return self::afterFind(Database::SQLselect($sql));
    }

    /**
     * Perform a save
     *
     * @param array $data The data to save
     *
     * @return array
     */
    static function save($data)
    {
        $sql = 'INSERT INTO ' . self::$tablename . ' (';
        $sql .= join(', ', array_keys($data));
        $sql .= ') VALUES (\'';
        $sql .= join('\', \'', array_values($data));
        $sql .= '\');';

        return Database::SQL($sql);
    }

    /**
     * Perform a get statement, but only get the first one
     *
     * @return array
     */
    static function first()
    {
        return self::get()[0];
    }

    /**
     * Perform a get statement and dump it
     *
     * @return array
     */
    static function dump()
    {
        return Debug::dump(self::get());
    }

    /**
     * Perform a get statement, dump it and die
     *
     * @return die
     */
    static function dumpDie()
    {
        self::dump();
        die;
    }
}
