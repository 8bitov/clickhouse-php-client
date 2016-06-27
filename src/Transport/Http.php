<?php

namespace ClickHouse\Transport;


use ClickHouse\Driver\Connection;
use ClickHouse\Format\AbstractFormat;
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
     * @param  string $sql
     * @param   $format
     *
     * @return AbstractFormat
     *
     * @throws \RuntimeException
     */
    public function query($sql, $format)
    {
        $response = $this->httpClient->request('GET', null, [
            'query' => $this->prepareQuery($sql, $format),
        ]);

        return $format->output($response->getBody()->getContents());
    }

    /**
     * @param $sql
     * @param $format
     */
    public function execute($sql, $format)
    {
        $response = $this->httpClient->request('POST', null, [
            'query' => $this->prepareQuery($sql, $format),
        ]);
    }


    /**
     * @param string $sql
     * @param $format
     *
     * @return array
     */
    protected function prepareQuery($sql, AbstractFormat $format)
    {
        $sql = $this->prepareQueryFormat($sql, $format);

        return ['query' => $sql];
    }

    /**
     * @param string $sql
     * @param AbstractFormat $format
     * @return string
     * @internal param string $formatName
     */
    protected function prepareQueryFormat($sql, AbstractFormat $format)
    {
        return $sql . ' FORMAT ' . $format->getName();
    }
}
