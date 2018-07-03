<?php
/**
 * Created by PhpStorm.
 * User: vmelnychuk
 * Date: 2/24/17
 * Time: 2:21 PM
 */

namespace ClickHouse\Sql;


class UnionTest extends \PHPUnit_Framework_TestCase
{

    public function testGetSql()
    {
        $union = new Union();
        $union->add('SELECT * from test');

        $this->assertEquals(' UNION ALL SELECT * from test',$union->getSql());
    }
}
