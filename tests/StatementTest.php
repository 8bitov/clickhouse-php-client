<?php
/**
 * Created by PhpStorm.
 * User: vmelnychuk
 * Date: 4/11/17
 * Time: 10:21 AM
 */

namespace ClickHouse;

use PHPUnit\Framework\TestCase;

class StatementTest extends TestCase
{

    public function testFetchOne_emptySet()
    {
        $transport = $this->getMockBuilder('ClickHouse\Transport\Http')
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();
        $statement = new Statement(json_encode(['data' => []]), null, $transport);

        $this->assertFalse($statement->fetchOne());

        $statement = new Statement(json_encode(['data' => [[]]]), null, $transport);

        $this->assertEmpty($statement->fetchOne());
    }

    public function testFetchColumn()
    {
        $transport = $this->getMockBuilder('ClickHouse\Transport\Http')
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();
        $statement = new Statement(json_encode(['data' => [
            [
                'test' => 1
            ]
        ]]), null, $transport);

        $this->assertNull($statement->fetchColumn('lalal'));
        $this->assertEquals(1, $statement->fetchColumn('test'));
    }

}
