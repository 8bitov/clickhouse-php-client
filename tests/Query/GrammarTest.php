<?php
/**
 * Created by PhpStorm.
 * User: vmelnychuk
 * Date: 2/21/17
 * Time: 4:12 PM
 */

namespace ClickHouse\Query;


class GrammarTest extends \PHPUnit_Framework_TestCase
{

    public function testIntQuote()
    {
        $grammar = new Grammar();
        $this->assertEquals('1', $grammar->intQuote(1));
        $this->assertEquals('', $grammar->intQuote(null));
        $this->assertEquals('1', $grammar->intQuote('1'));
        $this->assertEquals('1,2,3', $grammar->intQuote([1, 2, 3]));
        $this->assertEquals('1,2,3', $grammar->intQuote(['1', '2', '3']));
    }
}
