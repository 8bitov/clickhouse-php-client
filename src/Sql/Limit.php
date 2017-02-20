<?php
/**
 * Created by PhpStorm.
 * User: vmelnychuk
 * Date: 2/17/17
 * Time: 4:46 PM
 */

namespace ClickHouse\Sql;


class Limit
{
    const ALL_COLUMNS = '*';

    private $limit;

    private $offset;

    /**
     * @param mixed $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @param mixed $offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

    /**
     * @return string
     */
    public function getSql()
    {
        if ($this->limit) {
            $limitString = ($this->offset) ? $this->offset . ',' . $this->limit : $this->limit;

            return ' LIMIT ' . $limitString;
        } else {
            return '';
        }
    }
}