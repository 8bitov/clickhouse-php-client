<?php

namespace ClickHouse\Sql;

use ClickHouse\Query\InsertQuery;
use ClickHouse\Transport\TransportInterface;
use PHPUnit\Framework\TestCase;

class ExprTest extends TestCase
{
    public function testExprWithInsert()
    {
        /** @var TransportInterface $transportMock */
        $transportMock = $this->getMockBuilder(TransportInterface::class)
            ->setMethods(['select', 'insert', 'execute'])
            ->disableOriginalConstructor()
            ->getMock();

        $insert = new InsertQuery($transportMock, 'test', ['id', 'string'], [
            [19, 'func("escaped string")'],
            [27, new Expr('func("not escaped string")')],
        ]);

        $expectedSql = 'INSERT INTO test (id,string) VALUES  '
            . '(19,\'func("escaped string")\'),  '
            . '(27,func("not escaped string"))';
        $this->assertEquals($expectedSql, $insert->toSql());
    }

    public function testExprWithWhere()
    {
        $select = new Select();
        $select
            ->from('test')
            ->where('1 = 1');

        $expectedSql = 'SELECT * FROM test WHERE (1 = 1)';
        $this->assertEquals($expectedSql, $select->getSql());
    }

    public function testExprWithOrder()
    {
        $select = new Select();
        $select
            ->from('test')
            ->order(new Expr('min < max'), 'DESC');

        $expectedSql = 'SELECT * FROM test ORDER BY min < max DESC';
        $this->assertEquals($expectedSql, $select->getSql());
    }

    public function testExprWithGroupBy()
    {
        $select = new Select();
        $select
            ->from('test')
            ->groupBy(new Expr('date(time_start)'));

        $expectedSql = 'SELECT * FROM test GROUP BY date(time_start)';
        $this->assertEquals($expectedSql, $select->getSql());
    }
}
