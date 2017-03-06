<?php

/**
 * Created by PhpStorm.
 * User: vmelnychuk
 * Date: 3/6/17
 * Time: 11:38 AM
 */
class HttpTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider httpParams_dataProvider
     * @param $params
     * @param $expectedUri
     */
    public function testInsert($params, $expectedUri)
    {
        $http = $this->getMockBuilder('\ClickHouse\Transport\Http')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $client = $this->getMockBuilder('\GuzzleHttp\Client')
            ->disableOriginalConstructor()
            ->setMethods(['request'])
            ->getMock();
        $client->expects($this->once())
            ->method('request')
            ->willReturn(new \GuzzleHttp\Psr7\Response());
        $client->expects($this->at(0))
            ->method('request')
            ->with('POST', $expectedUri);

        /**@var \ClickHouse\Transport\Http $http */
        /**@var \GuzzleHttp\Client $client */
        $http->setHttpClient($client);
        $http->insert('test', [], [], $params);

    }

    /**
     * @dataProvider httpParams_dataProvider
     * @param $params
     * @param $expectedUri
     */
    public function testSelect($params, $expectedUri)
    {
        $http = $this->getMockBuilder('\ClickHouse\Transport\Http')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $client = $this->getMockBuilder('\GuzzleHttp\Client')
            ->disableOriginalConstructor()
            ->setMethods(['request'])
            ->getMock();
        $client->expects($this->once())
            ->method('request')
            ->willReturn(new \GuzzleHttp\Psr7\Response());
        $client->expects($this->at(0))
            ->method('request')
            ->with('POST', $expectedUri);

        /**@var \ClickHouse\Transport\Http $http */
        /**@var \GuzzleHttp\Client $client */
        $http->setHttpClient($client);
        $http->select('test', [], $params);
    }

    /**
     * @dataProvider httpParams_dataProvider
     * @param $params
     * @param $expectedUri
     */
    public function testExecute($params, $expectedUri)
    {
        $http = $this->getMockBuilder('\ClickHouse\Transport\Http')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $client = $this->getMockBuilder('\GuzzleHttp\Client')
            ->disableOriginalConstructor()
            ->setMethods(['request'])
            ->getMock();
        $client->expects($this->once())
            ->method('request')
            ->willReturn(new \GuzzleHttp\Psr7\Response());
        $client->expects($this->at(0))
            ->method('request')
            ->with('POST', $expectedUri);

        /**@var \ClickHouse\Transport\Http $http */
        /**@var \GuzzleHttp\Client $client */
        $http->setHttpClient($client);
        $http->execute('test', [], $params);
    }

    public function httpParams_dataProvider()
    {
        return [
            [
                ['query_log' => 1], 'query_log=1'
            ],
            [
                ['query_log' => 1, 'test_param' => 2], 'query_log=1&test_param=2',
            ],
            [
                [], '',
            ]
        ];
    }

}
