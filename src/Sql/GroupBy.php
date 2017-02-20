<?php
/**
 * Created by PhpStorm.
 * User: vmelnychuk
 * Date: 2/17/17
 * Time: 4:46 PM
 */

namespace ClickHouse\Sql;


class GroupBy
{

    private $groupByColumns = [];

    /**
     * @param $column
     */
    public function addGroup($column)
    {
        $this->groupByColumns[] = $column;
    }

    /**
     * @param array $columns
     */
    public function setGroup(array $columns)
    {
        $this->groupByColumns[] = $columns;
    }

    /**
     * @return string
     */
    public function getSql()
    {
        if (empty($this->groupByColumns)) {
            return '';
        } else {
            return ' GROUP BY ' . implode($this->groupByColumns, ', ');
        }
    }
}