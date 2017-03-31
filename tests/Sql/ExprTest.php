<?php

namespace ClickHouse\Sql;

use ClickHouse\Query\InsertQuery;
use ClickHouse\Transport\TransportInterface;
use PHPUnit\Framework\TestCase;

class ExprTest extends TestCase
{
    public function testExpr()
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
}
