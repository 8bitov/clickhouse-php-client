<?php
/**
 * Created by PhpStorm.
 * User: vmelnychuk
 * Date: 2/20/17
 * Time: 1:09 PM
 */

namespace ClickHouse\Sql;


class LimitTest extends \PHPUnit_Framework_TestCase
{

    public function testOffset()
    {
        $sqlLimit = new Limit();
        $sqlLimit->setOffset(1);

        $this->assertEmpty($sqlLimit->getSql());

        $sqlLimit->setLimit(1);

        $this->assertEquals(' LIMIT 1,1', $sqlLimit->getSql());

    }
}
