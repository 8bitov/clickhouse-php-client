<?php

namespace ClickHouse;

use ClickHouse\Transport\Http;
use ClickHouse\Transport\TransportInterface;

/**
 * Class Client
 * @package ClickHouse
 */
class Client
{

    /**
     * @var Http
     */
    private $transport;

    /**
     * @var System
     */
    private $system;

    /**
     * @var Query\Grammar
     */
    private $queryGrammar;


    /**
     * Client constructor.
     * @param $host
     * @param $port
     * @param null $username
     * @param null $password
     * @param string $transport
     * @param array $settings
     */
    public function __construct($host = null, $port = null, $username = null, $password = null, $settings = [], $transport = null)
    {
        if (null === $transport) {
            $transport = Http::class;
        }
        $this->transport = new $transport($host, $port, $username, $password);

        $this->system = new System($this, $settings);

        $this->queryGrammar = new Query\Grammar();
    }


    /**
     * Begin a fluent query against a database table.
     *
     * @param  string $table
     * @return \ClickHouse\Query\Builder
     */
    public function table($table)
    {
        return $this->query()->from($table);
    }

    /**
     * Run a select statement against the database.
     * @param  string $sql
     * @param  array $bindings
     * @return Statement
     * @throws \RuntimeException
     */
    public function select($sql, $bindings = [])
    {
        return $this->transport->select($sql, $bindings);
    }

    /**
     * @param string $table
     * @param array $values
     * @param array $columns
     *
     * @return mixed|void
     */
    public function insert($table, $columns = [], $values)
    {
        return $this->transport->insert($table, $columns, $values);
    }

    /**
     * @param string $sql
     * @param $data
     * @param null $formatName
     * @return mixed
     */
    public function insertBatch($table, $data, $formatName = null)
    {
        return $this->transport->insertBatch($table, $data, $formatName);
    }

    /**
     * @param $sql
     * @param array $bindings
     * @return Statement
     */
    public function execute($sql, $bindings = [])
    {
        return $this->transport->execute($sql, $bindings);
    }

    /**
     * @return bool
     */
    public function ping()
    {
        $sql = 'SELECT 1 as ping';
        $stm = $this->select($sql);
        $ping = $stm->fetchColumn('ping');

        return $ping === 1;
    }

    /**
     * @return System
     */
    public function system()
    {
        return $this->system;
    }
    
}