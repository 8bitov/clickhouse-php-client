<?php
/**
 * Created by PhpStorm.
 * User: vmelnychuk
 * Date: 2/17/17
 * Time: 5:12 PM
 */

namespace ClickHouse\Sql;

use PHPUnit\Framework\TestCase;

class WhereTest extends TestCase
{

    /**
     * @dataProvider dataProviderGetSql
     * @param $predicate
     * @param $combination
     * @param $expected
     */
    public function testGetSql($predicate, $combination, $expected)
    {
        $where = new Where();
        $where->addPredicate($predicate, $combination);
        $this->assertEquals($expected, $where->getSql());
    }

    public function testGetSql_ANDCombination()
    {
        $where = new Where();
        $where->addPredicate("test='2'", Where::COMBINATION_AND);
        $where->addPredicate("test='3'", Where::COMBINATION_AND);
        $this->assertEquals(" WHERE (test='2' AND test='3')", $where->getSql());
    }

    public function testGetSql_ORCombination()
    {
        $where = new Where();
        $where->addPredicate("test='2'", Where::COMBINATION_OR);
        $where->addPredicate("test='3'", Where::COMBINATION_OR);
        $this->assertEquals(" WHERE (test='2' OR test='3')", $where->getSql());
    }
    public function testGetSql_ANDWithORCombination()
    {
        $where = new Where();
        $where->addPredicate("test='2'", Where::COMBINATION_AND);
        $where->addPredicate("test='3'", Where::COMBINATION_OR);
        $this->assertEquals(" WHERE (test='2' OR test='3')", $where->getSql());
    }


    public function dataProviderGetSql()
    {
        return [
            ["test='1'", Where::COMBINATION_OR, " WHERE (test='1')"],
            ["test='1'", Where::COMBINATION_AND, " WHERE (test='1')"],
            ["test = '!@#%$%'", Where::COMBINATION_AND, " WHERE (test = '!@#%$%')"]
        ];
    }
}
