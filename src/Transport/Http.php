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

        if (null !== $username)
            $this->username = $username;

        if (null !== $password)
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
            'timeout'  => $this->timeout,
            'handler'  => $stack,
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
     * @param array $queryParams
     * @return Statement
     * @throws \Exception
     */
    public function select($sql, array $bindings = [], $queryParams = [])
    {
        $query = new SelectQuery($this, $sql, $bindings);

        try {
            $response = $this->httpClient->request('POST', $this->_formatQueryParams($queryParams), [
                'body' => $query->toSql(),
            ]);
        } catch (\Exception $e) {
            throw $this->_prepareQueryException($e, [
                'sqlTemplate'   => $query->toSql(),
                'sql'           => $sql,
                'bindings'      => $bindings,
                'queryParams'   => $queryParams
            ]);
        }

        $data = $response->getBody()->getContents();

        return new Statement($data, $query, $this);
    }


    /**
     * @param string $table
     * @param array $columns
     *
     * @param array $values
     * @param array $queryParams
     * @return Statement
     * @throws \Exception
     */
    public function insert($table, array $columns = [], array $values, array $queryParams = [])
    {
        $query = new InsertQuery($this, $table, $columns, $values);

        try {
            $response = $this->httpClient->request('POST', $this->_formatQueryParams($queryParams), [
                'body' => $query->toSql(),
            ]);
        } catch (\Exception $e) {
            throw $this->_prepareQueryException($e, [
                'sql'           => $query->toSql(),
                'table'         => $table,
                'columns'       => $columns,
                'values'        => $values,
                'queryParams'   => $queryParams,
            ]);
        }

        $data = $response->getBody()->getContents();

        return new Statement($data, $query, $this);
    }

    /**
     * @param $sql
     * @param array $bindings
     *
     * @param array $queryParams
     * @return Statement
     * @throws \Exception
     */
    public function execute($sql, $bindings = [], $queryParams = [])
    {
        $query = new ExecuteQuery($this, $sql, $bindings);

        try {
            $response = $this->httpClient->request('POST', $this->_formatQueryParams($queryParams), [
                'body' => $query->toSql(),
            ]);
        } catch (\Exception $e) {
            throw $this->_prepareQueryException($e, [
                'sqlTemplate'   => $query->toSql(),
                'sql'           => $sql,
                'bindings'      => $bindings,
                'queryParams'   => $queryParams
            ]);
        }

        $data = $response->getBody()->getContents();

        return new Statement($data, $query, $this);
    }

    protected function _prepareQueryException(\Exception $prev, $data)
    {
        $message = 'Error while ClickHouse request.' . PHP_EOL
            . 'Message: ' . PHP_EOL . $prev->getMessage()
            . PHP_EOL . 'Params: ' . print_r($data, true);
        return new \Exception($message, 0, $prev);
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

    protected function _formatQueryParams($queryParams)
    {
        $url = '';
        foreach ($queryParams as $param => $value) {
            if (!empty($url)) {
                $url .= '&';
            } else {
                $url .= '?';
            }
            $url .= $param . '=' . $value;
        }

        return $url;
    }

    /**
     * @return Client
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @param Client $httpClient
     */
    public function setHttpClient($httpClient)
    {
        $this->httpClient = $httpClient;
    }
}
