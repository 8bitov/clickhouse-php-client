<?php
/**
 * Created by PhpStorm.
 * User: vmelnychuk
 * Date: 2/20/17
 * Time: 12:26 PM
 */

namespace ClickHouse\Sql;


class GroupByTest extends \PHPUnit_Framework_TestCase
{

    public function testGetSql_addGroup()
    {
        $sqlGroupBy = new GroupBy();
        $sqlGroupBy->addGroup('application');

        $this->assertEquals(' GROUP BY application', $sqlGroupBy->getSql());
    }

    public function testGetSql_addTwoGroups()
    {
        $sqlGroupBy = new GroupBy();
        $sqlGroupBy->addGroup('application');
        $sqlGroupBy->addGroup('userId');

        $this->assertEquals(' GROUP BY application, userId', $sqlGroupBy->getSql());
    }
}
