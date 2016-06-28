<?php

namespace ClickHouse\Transport;


use ClickHouse\Driver\Connection;
use ClickHouse\Format\AbstractFormat;
use ClickHouse\Statement;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;

/**
 * Class Http
 * @package ClickHouse\Transport
 */
class Http implements TransportInterface
{

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var
     */
    private $host;

    /**
     * @var array
     */
    private $allowRedirects = [
        'max' => 5,
        'strict' => false,
        'referer' => true,
        'protocols' => ['http', 'https'],
        'track_redirects' => false,
    ];
    /**
     * @var
     */
    private $port;
    /**
     * @var null
     */
    private $username = null;
    /**
     * @var null
     */
    private $password = null;
    /**
     * @var int
     */
    private $timeout = 0;

    /**
     * Http constructor.
     * @param $host
     * @param $port
     * @param null $username
     * @param null $password
     */
    public function __construct($host, $port, $username = null, $password = null)
    {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->connect();
    }

    /**
     *
     */
    protected function connect()
    {
        $handler = new CurlHandler();
        $stack = HandlerStack::create($handler);

        $httpClientSettings = [
            'base_uri' => $this->host . ':' . $this->port,
            'timeout' => $this->timeout,
            'allow_redirects' => $this->allowRedirects,
            'handler' => $stack,
        ];

        if (null !== $this->username) {
            $httpClientSettings['auth'] = [$this->username, $this->password];
        }

        $this->httpClient = new Client($httpClientSettings);
    }


    /**
     * Prepares a statement for execution and returns a Statement object.
     *
     * @param string $prepareString
     *
     * @return Statement
     */
    public function prepare($prepareString)
    {
        return new Statement($this, $prepareString);
    }

    /**
     * @param  string $sql
     *
     * @return Statement
     *
     * @throws \RuntimeException
     */
    public function query($sql)
    {
        $stmt = $this->prepare($sql);
        $stmt->executeSelectStatement();

        return $stmt;
    }

    /**
     * @param Statement $statement
     * @return string
     */
    public function executeStatement(Statement $statement)
    {
        $sql = $statement->toSql();
        $response = $this->httpClient->request('GET', null, [
            'query' => $this->prepareQuery($sql),
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * @param $sql
     * @param $format
     *
     * @return mixed|void
     */
    public function execute($sql, $format)
    {
        $response = $this->httpClient->request('POST', null, [
            'body' => $this->prepareQueryFormat($sql, $format),
        ]);

        return $format->output($response->getBody()->getContents());
    }


    /**
     * @param string $sql
     * @param $format
     *
     * @return array
     */
    protected function prepareQuery($sql)
    {
        return ['query' => $sql];
    }

}
