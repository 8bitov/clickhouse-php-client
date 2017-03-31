<?php
/**
 * Created by PhpStorm.
 * User: vmelnychuk
 * Date: 2/17/17
 * Time: 6:26 PM
 */

namespace ClickHouse\Sql;


class SelectTest extends \PHPUnit_Framework_TestCase
{
    public function testFrom()
    {
        $select = new Select();
        $this->assertInstanceOf('ClickHouse\Sql\Select', $select->from('table1', []));
        $this->assertEquals('SELECT * FROM table1', $select->getSql());
    }

    public function testWhere()
    {
        $select = new Select();
        $select->from('table1', []);
        $this->assertInstanceOf('ClickHouse\Sql\Select', $select->where($select->getGrammar()->bind("test = %s", 123)));

        $this->assertEquals('SELECT * FROM table1 WHERE (test = 123)', $select->getSql());
    }

    public function testOrWhere()
    {
        $select = new Select();
        $select->from('table1', []);
        $select->where($select->getGrammar()->bind("test = %s", 123));
        $this->assertInstanceOf('ClickHouse\Sql\Select', $select->orWhere(
            $select->getGrammar()->bind("test = %s", 3)));

        $this->assertEquals('SELECT * FROM table1 WHERE (test = 123 OR test = 3)', $select->getSql());
    }

    public function testColumns()
    {
        $select = new Select();
        $select->from('table1', []);
        $this->assertInstanceOf('ClickHouse\Sql\Select', $select->columns([
            'test' => 'sum(seconds)'
        ]));

        $this->assertEquals('SELECT sum(seconds) AS test FROM table1', $select->getSql());
    }

    public function testReset_Columns()
    {
        $select = new Select();
        $select->setTable('table1');
        $select->columns([
            'test' => 'sum(seconds)'
        ]);
        $this->assertInstanceOf('ClickHouse\Sql\Select', $select->reset([
            Select::PART_COLUMNS
        ]));
        $this->assertEquals('SELECT * FROM table1', $select->getSql());
    }

    public function testReset_Where()
    {
        $select = new Select();
        $select->setTable('table1');
        $select->where($select->getGrammar()->bind('test = %s', 1));
        $select->reset([
            Select::PART_WHERE
        ]);
        $this->assertEquals('SELECT * FROM table1', $select->getSql());
    }

    public function testReset_GroupBy()
    {
        $select = new Select();
        $select->setTable('table1');
        $select->groupBy('application');
        $select->reset([
            Select::PART_GROUP_BY
        ]);
        $this->assertEquals('SELECT * FROM table1', $select->getSql());
    }

    public function testReset_Order()
    {
        $select = new Select();
        $select->setTable('table1');
        $select->order('application', Order::TYPE_ASC);
        $select->reset([
            Select::PART_ORDER_BY
        ]);
        $this->assertEquals('SELECT * FROM table1', $select->getSql());
    }

    public function testOrder()
    {
        $select = new Select();
        $select->setTable('table1');
        $this->assertInstanceOf('ClickHouse\Sql\Select', $select->order('application', Order::TYPE_ASC));
        $this->assertEquals('SELECT * FROM table1 ORDER BY application ASC', $select->getSql());
    }

    public function testLimit()
    {
        $select = new Select();
        $select->setTable('table1');
        $this->assertInstanceOf('ClickHouse\Sql\Select', $select->limit(10));
        $this->assertEquals('SELECT * FROM table1 LIMIT 10', $select->getSql());
    }

    public function testOffset()
    {
        $select = new Select();
        $select->setTable('table1');
        $select->limit(10);
        $this->assertInstanceOf('ClickHouse\Sql\Select', $select->offset(10));
        $this->assertEquals('SELECT * FROM table1 LIMIT 10,10', $select->getSql());
    }

    public function testSubSelect()
    {
        $select = new Select();
        $subSelect = new Select();
        $subSelect->from('table2');
        $select->from($subSelect);
        $this->assertEquals('SELECT * FROM (SELECT * FROM table2)', $select->getSql());
    }

    public function testClone()
    {
        $select = new Select();
        $select->from('test');
        $select->where('eq = 1');

        $select2 = clone $select;
        $select2->where('eq2 = 2');

        $this->assertNotSame($select, $select2);
        $this->assertNotEquals($select->getSql(), $select2->getSql());
    }

    public function testUnion()
    {
        $select = new Select();
        $select->from('test');
        $select->union('SELECT * FROM test2');

        $this->assertEquals('SELECT * FROM test UNION ALL SELECT * FROM test2',$select->getSql());
    }

    public function testUnionWithObject()
    {
        $select = new Select();
        $select->from('test');
        $select2 = new Select();
        $select2->from('test2');

        $this->assertInstanceOf('ClickHouse\Sql\Select', $select->union($select2));
        $this->assertEquals('SELECT * FROM test UNION ALL SELECT * FROM test2',$select->getSql());
    }
}
