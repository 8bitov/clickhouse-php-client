<?php
/**
 * Created by PhpStorm.
 * User: vmelnychuk
 * Date: 2/20/17
 * Time: 12:55 PM
 */

namespace ClickHouse\Sql;


class OrderTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSql_ASC()
    {
        $sqlOrder = new Order();
        $sqlOrder->setOrderColumns('test',Order::TYPE_ASC);

        $this->assertEquals(' ORDER BY test ASC', $sqlOrder->getSql());
    }

    public function testGetSql_DESC_and_ASC()
    {
        $sqlOrder = new Order();
        $sqlOrder->setOrderColumns('groupId',Order::TYPE_ASC);
        $sqlOrder->setOrderColumns('groupName',Order::TYPE_DESC);

        $this->assertEquals(' ORDER BY groupId ASC, groupName DESC', $sqlOrder->getSql());
    }
}
