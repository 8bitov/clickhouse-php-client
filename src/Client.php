<?php

namespace ClickHouse;

use ClickHouse\Format\AbstractFormat;
use ClickHouse\Format\JSON;
use ClickHouse\Format\TabSeparated;
use ClickHouse\Transport\Http;

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
     * Client constructor.
     * @param $host
     * @param $port
     * @param null $username
     * @param null $password
     * @param array $settings
     */
    public function __construct($host, $port, $username = null, $password = null, $settings = [])
    {
        $this->transport = new Http($host, $port, $username, $password);
        $this->system = new System($this, $settings);
    }

    /**
     * @param  string $sql
     *
     * @return Statement
     * @throws \RuntimeException
     */
    public function query($sql)
    {
        return $this->transport->query($sql);
    }

    /**
     * @param $formatName
     * @param $type
     * @return mixed
     */
    public function formatFactory($formatName, $type = self::SELECT_FORMAT)
    {
        if (null !== $formatName) {
            $class = "\\ClickHouse\\Format\\" . $formatName;
            $format = new $class();

        } else {
            $class = $type;
            $format = new $class();
        }

        return $format;
    }

    /**
     * @param string $sql
     * @param string|null $formatName
     *
     * @return mixed
     */
    public function execute($sql, $formatName = null)
    {
        $format = $this->formatFactory($formatName, self::INSERT_FORMAT);
        return $this->transport->execute($sql, $format);
    }

    /**
     * @param string $sql
     * @param $data
     * @param null $formatName
     * @return mixed
     */
    public function executeBatch($sql, $data, $formatName = null)
    {
        $format = $this->formatFactory($formatName, self::BATCH_INSERT_FORMAT);
        return $this->transport->executeBatch($sql, $data, $format);
    }

    /**
     * @return bool
     */
    public function ping()
    {
        $sql = 'SELECT 1 as ping';
        $stm = $this->query($sql);
        $ping = $stm->fetchColumn('ping');

        return $ping === 1;
    }

    public function createTable($dbName, $tableName, $engine, $columns = [], $ifNotExists = false, $temporary = false)
    {

        $sql = 'CREATE ' . $dbName . '.' . $tableName . '(';

        foreach ($columns as $column) {
            $sql .= $column['name'] . ' ' . $column['type'];
        }

        $sql .= ') ';

        $sql .= 'ENGINE = ' . $engine;

        $result = $this->execute($sql);
    }
}