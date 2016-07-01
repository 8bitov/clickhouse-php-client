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

    private $tablename = 'all_types_test_table';

    /**
     *
     */
    public function setUp()
    {
        $this->client = new \ClickHouse\Client('http://127.0.0.1', 8123);


        $this->client->execute(
        /** @lang SQL */
            "CREATE TABLE IF NOT EXISTS " . $this->tablename . " (
            RowId UInt32,
            RowDate Date,
            RowUInt8 UInt8,
            RowInt16 Int16,
            RowFloat32 Float32,
            RowString String,
            RowFixedString FixedString(20),
            RowDateTime DateTime,
            RowEnum8 Enum8('hello' = 1, 'world' = 2),
            RowStringArray Array(String)     
            ) ENGINE = MergeTree(RowDate, (RowId, RowDate), 8124);"
        );

    }

    public function tearDown()
    {
        $this->client->execute('DROP TABLE IF EXISTS ' . $this->tablename);
    }

    /**
     * Заполняет тестовую таблицу, фейковыми данными
     * @param int $count
     */
    public function fixtures($count = 1000)
    {

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


    public function testCreateDropTable()
    {

        $tablename = 'test_create';
        $this->client->execute(
            'CREATE TABLE ' . $tablename . ' (abc UInt8) ENGINE = Memory;'
        );

        $st = $this->client->system()->columns($tablename);
        $column = $st->fetchOne();

        $this->assertEquals('abc', $column->name);
        $this->assertEquals('uint8', strtolower($column->type));


        $this->client->execute(
            'DROP TABLE ' . $tablename . ';'
        );
    }

    /**
     *
     */
    public function testInsertFormatValues()
    {
        $faker = Faker\Factory::create();
        $columns = ['RowId', 'RowDate', 'RowString'];
        $data = [
            [$id1 = $faker->randomDigitNotNull, $date1 = $faker->date, $string1 = $faker->word],
            [$id2 = $faker->randomDigitNotNull, $date2 = $faker->date, $string2 = $faker->word],
        ];
        $this->client->insert($this->tablename, $columns, $data);

        $statement = $this->client->select('SELECT * FROM '.$this->tablename);

        $this->assertEquals(2, $statement->rowsCount());

        $all  = $statement->fetchAll();
        $first = current($all);
        $this->assertEquals($id1, $first->RowId);

        $last = end($all);
        $this->assertEquals($id2, $last->RowId);


    }


    public function insertBatch()
    {
        // $this->client->insert();
    }


}