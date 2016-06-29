<?php

/**
 * Class HttpTransportTest
 */
class ClientTest extends \PHPUnit_Framework_TestCase
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
        $result = $this->client->select('SELECT number FROM system.numbers LIMIT 10');
        $data = $result->fetchAll();

        $this->assertCount(10, $data);

    }

    public function testFetchWithBindings()
    {
        $result = $this->client->select('SELECT number FROM system.numbers WHERE number = :number LIMIT 1', ['number' => 100]);
        $first = $result->fetchOne();

        $this->assertEquals(100, $first->number);

    }

    public function testFetchOne()
    {
        $result = $this->client->select('SELECT number FROM system.numbers LIMIT 1');
        $first = $result->fetchOne();

        $this->assertInstanceOf(\stdClass::class, $first);
        $this->assertEquals(0, $first->number);
    }

    public function testFetchColumn()
    {
        $result = $this->client->select('SELECT number FROM system.numbers LIMIT 1');
        $number = $result->fetchColumn('number');

        $this->assertTrue(is_numeric($number));
        $this->assertEquals(0, $number);
    }


    public function testCreateTable()
    {

        $tablename = 'test_create';
       $this->client->execute(
            'CREATE TABLE '.$tablename.' (abc UInt8) ENGINE = Memory;'
        );

        $st = $this->client->system()->columns($tablename);
        $column = $st->fetchOne();

        $this->assertEquals('abc', $column->name);
        $this->assertEquals('uint8', strtolower($column->type));


        $this->client->execute(
            'DROP TABLE '.$tablename.';'
        );
    }

    public function insertOneFormatValuesTest()
    {
        $columns = ['test_name', 'test_count'];
        $data = [['testName', 15]];
        $statement = $this->client->insert('test', $columns, $data);

        $this->assertEquals(1, $statement->rowCount());
    }

    public function insertManyFormatValuesTest()
    {
        $columns = ['test_name', 'test_count'];
        $data = [
            ['testName', 15],
            ['test2Name', 30]
        ];
        $statement = $this->client->insert('test', $columns, $data);

        $this->assertEquals(2, $statement->rowCount());
    }


    public function insertBatch()
    {
        // $this->client->insert();
    }


}