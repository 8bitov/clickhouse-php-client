<?php
/**
 * Created by PhpStorm.
 * User: vmelnychuk
 * Date: 2/17/17
 * Time: 4:46 PM
 */

namespace ClickHouse\Sql;


class Columns
{
    const ALL_COLUMNS = '*';

    private $columns = [];

    /**
     * @param array $columns
     */
    public function setColumns($columns = [])
    {
        $this->columns = $columns;
    }

    /**
     * @return string
     */
    public function getSql()
    {
        if (empty($this->columns)) {
            return self::ALL_COLUMNS;
        } else {
            $columns = [];
            foreach ($this->columns as $alias => $column) {
                if (is_string($alias)) {
                    $columns[] = "$column AS $alias";
                } else {
                    $columns[] = $column;
                }
            }

            return implode($columns, ', ');
        }
    }
}