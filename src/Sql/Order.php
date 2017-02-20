<?php
/**
 * Created by PhpStorm.
 * User: vmelnychuk
 * Date: 2/17/17
 * Time: 4:46 PM
 */

namespace ClickHouse\Sql;


class Order
{
    const TYPE_DESC = 'DESC';
    const TYPE_ASC = 'ASC';

    private $orderColumns = [];

    /**
     * @param $column
     * @param $type
     */
    public function setOrderColumns($column, $type)
    {
        $this->orderColumns[] = $column . " " . $type;
    }

    /**
     * @return string
     */
    public function getSql()
    {
        if (empty($this->orderColumns)) {
            return '';
        } else {
            return ' ORDER BY ' . implode($this->orderColumns, ', ');
        }
    }
}