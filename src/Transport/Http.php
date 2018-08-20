<?php

namespace ClickHouse\Transport;

use ClickHouse\Query\ExecuteQuery;
use ClickHouse\Query\Query;
use ClickHouse\Query\InsertQuery;
use ClickHouse\Query\SelectQuery;
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
    private $host = 'http://127.0.0.1';

    /**
     * @var
     */
    private $port = 8123;
    /**
     * @var null
     */
    private $username = 'default';
    /**
     * @var null
     */
    private $password = null;

    /**
     * Float describing the timeout of the request in seconds. Use 0 to wait indefinitely (the default behavior).
     * @var int
     */
    private $timeout = 0;

    /**
     * Http constructor.
     * @param $host
     * @param $port
     * @param null $username
     * @param null $password
     * @param array $requestOptions
     */
    public function __construct($host = null, $port = null, $username = null, $password = null, array $requestOptions = [])
    {
        if (null !== $host)
            $this->host = $host;

        if (null !== $port)
            $this->port = $port;

        if(null !== $username)
            $this->username = $username;

        if(null !== $password)
            $this->password = $password;


        if (array_key_exists('timeout', $requestOptions)) {
            $this->timeout = $requestOptions['timeout'];
        }

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
            'handler' => $stack,
        ];

        if (null !== $this->username) {
            $httpClientSettings['auth'] = [$this->username, $this->password];
        }

        $this->httpClient = new Client($httpClientSettings);
    }


    /**
     * @param  string $sql
     *
     * @param array $bindings
     * @return Statement
     * @throws \RuntimeException
     */
    public function select($sql, array $bindings = [])
    {
        $query = new SelectQuery($this, $sql, $bindings);

        $response = $this->httpClient->request('GET', null, [
            'query' => $this->prepareQueryGetRequest($query),
        ]);

        $data = $response->getBody()->getContents();

        return new Statement($data, $query, $this);
    }


    /**
     * @param string $table
     * @param array $values
     * @param array $columns
     *
     * @return Statement
     * @throws \RuntimeException
     *
     */
    public function insert($table, array $columns = [], array $values)
    {
        $query = new InsertQuery($this, $table, $columns, $values);

        $response = $this->httpClient->request('POST', null, [
            'body' => $query->toSql(),
        ]);

        $data = $response->getBody()->getContents();

        return new Statement($data, $query, $this);
    }

    /**
     * @param $sql
     * @param array $bindings
     *
     * @return Statement
     * @throws \RuntimeException
     */
    public function execute($sql, $bindings = [])
    {
        $query = new ExecuteQuery($this, $sql, $bindings);

        $response = $this->httpClient->request('POST', null, [
            'body' => $query->toSql(),
        ]);

        $data = $response->getBody()->getContents();

        return new Statement($data, $query, $this);
    }


    /**
     * @param Query $query
     *
     * @return array
     *
     */
    protected function prepareQueryGetRequest(Query $query)
    {
        return ['query' => $query->toSql()];
    }

}
