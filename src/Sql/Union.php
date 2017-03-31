<?php
/**
 * Created by PhpStorm.
 * User: vmelnychuk
 * Date: 2/17/17
 * Time: 4:46 PM
 */

namespace ClickHouse\Sql;


class Union
{

    private $unions = [];

    public function add($select)
    {
        $this->unions[] = $select;
    }

    /**
     * @return string
     */
    public function getSql()
    {
        $result = '';

        foreach ($this->unions as $union) {
            $result = ' UNION ALL ' . $union;
        }

        return $result;
    }
}