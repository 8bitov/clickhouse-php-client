<?php
/**
 * Created by PhpStorm.
 * User: vmelnychuk
 * Date: 2/17/17
 * Time: 5:45 PM
 */

namespace ClickHouse\Sql;


use PHPUnit\Framework\TestCase;

class ColumnsTest extends TestCase
{

    /**
     * @dataProvider dataProviderGetSql
     * @param $columns
     * @param $expected
     */
    public function testGetSql($columns, $expected)
    {
        $sqlColumns = new Columns();
        $sqlColumns->setColumns($columns);

        $this->assertEquals($expected, $sqlColumns->getSql());
    }


    public function dataProviderGetSql()
    {
        return [
            [[],'*'],
            [['test'],'test'],
            [['test','test2'],'test, test2'],
            [['test','test2'],'test, test2'],
            [['sum(applicationId)','test2'],'sum(applicationId), test2'],
            [['total' => 'sum(applicationId)'],'sum(applicationId) AS total'],
            [['total' => 'sum(applicationId)','test1'],'sum(applicationId) AS total, test1'],
        ];
    }
}
