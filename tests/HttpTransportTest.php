<?php

/**
 * Class HttpTransportTest
 */
class HttpTransportTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \ClickHouse\Client
     */
    private $client;

    /**
     *
     */
    public function setUp()
    {
        $this->client = new \ClickHouse\Client('http://127.0.0.1', 8123);
    }

    /**
     *
     */
    public function testPing()
    {
        $result = $this->client->ping();

        $this->assertTrue($result);

    }

    public function testFetchAll()
    {
        $result = $this->client->query('SELECT number FROM system.numbers LIMIT 10');
        $data = $result->fetchAll();

        $this->assertCount(10, $data);

    }

    public function testFetchOne()
    {
        $result = $this->client->query('SELECT number FROM system.numbers LIMIT 1');
        $first = $result->fetchOne();

        $this->assertInstanceOf(\stdClass::class, $first);
        $this->assertEquals(0, $first->number);
    }

    public function testFetcColumn()
    {
        $result = $this->client->query('SELECT number FROM system.numbers LIMIT 1');
        $number = $result->fetchColumn('number');

        $this->assertTrue(is_numeric($number));
        $this->assertEquals(0, $number);
    }

    public function testCreateTable()
    {
        
    }


}