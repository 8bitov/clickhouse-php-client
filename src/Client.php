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
     * Формат дефолтный для селектов
     */
    const SELECT_FORMAT = JSON::class;
    /**
     * Формат дефолтный для Инсертов
     */
    const INSERT_FORMAT = TabSeparated::class;
    /**
     * Формат дефолтный для массовых Инсертов
     */
    const BATCH_INSERT_FORMAT = TabSeparated::class;

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
        $this->settings = new Settings($this, $settings);
    }

    /**
     * @param  string $sql
     * @param string|null $formatName
     *
     * @return AbstractFormat
     * @throws \RuntimeException
     */
    public function query($sql, $formatName = null)
    {

        $format = $this->formatFactory($formatName);
        return $this->transport->query($sql, $format);
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
        $result = $this->query($sql);
        $ping = $result->fetchColumn('ping');

        return $ping === 1;
    }

    /**
     * Содержит информацию о кусках таблиц семейства MergeTree.
     * @param int $limit
     * @return array
     */
    public function numbers($limit = 10)
    {
        $sql = 'SELECT number FROM system.numbers LIMIT ' . $limit;
        $result = $this->query($sql);

        return $result->fetchAll();
    }

    /**
     * Таблица содержит столбцы database, name, engine типа String.
     *
     * @return array
     */
    public function tables()
    {
        $sql = 'SHOW TABLES';
        $result = $this->query($sql);

        return $result->fetchAll();
    }

    /**
     * Таблица содержит один столбец name типа String - имя базы данных.
     * Для каждой базы данных, о которой знает сервер, будет присутствовать соответствующая запись в таблице.
     *
     * @return array
     */
    public function databases()
    {
        $sql = 'SHOW DATABASES';
        $result = $this->query($sql);

        return $result->fetchAll();
    }

    /**
     * @return array
     */
    public function processes()
    {
        $sql = 'SHOW PROCESSLIST';
        $result = $this->query($sql);

        return $result->fetchAll();
    }

    /**
     * Содержит информацию о количестве произошедших в системе событий, для профилирования и мониторинга.
     * Пример: количество обработанных запросов типа SELECT.
     * Столбцы: event String - имя события, value UInt64 - количество.
     *
     * @return array
     */
    public function events()
    {
        $sql = 'SELECT * FROM system.events';
        $result = $this->query($sql);

        return $result->fetchAll();
    }

    /**
     * информация о доступных в конфигурационном файле кластерах и серверах, которые в них входят.
     *
     * @return array
     */
    public function clusters()
    {
        $sql = 'SELECT * FROM system.clusters';
        $result = $this->query($sql);

        return $result->fetchAll();
    }

    /**
     * Содержит информацию о столбцах всех таблиц.
     *
     * @return array
     */
    public function columns()
    {
        $sql = 'SELECT * FROM system.columns';
        $result = $this->query($sql);

        return $result->fetchAll();
    }

    /**
     *  информация о внешних словарях.
     * @return array
     */
    public function dictionaries()
    {
        $sql = 'SELECT * FROM system.dictionaries';
        $result = $this->query($sql);

        return $result->fetchAll();
    }

    /**
     * Содержит информацию об обычных и агрегатных функциях.
     * @return array
     */
    public function functions()
    {
        $sql = 'SELECT * FROM system.functions';
        $result = $this->query($sql);

        return $result->fetchAll();
    }

    /**
     * Содержит информацию о производящихся прямо сейчас слияниях для таблиц семейства MergeTree.
     * @return array
     */
    public function merges()
    {
        $sql = 'SELECT * FROM system.merges';
        $result = $this->query($sql);

        return $result->fetchAll();
    }

    /**
     * Содержит информацию о кусках таблиц семейства MergeTree.
     * @return array
     */
    public function parts()
    {
        $sql = 'SELECT * FROM system.parts';
        $result = $this->query($sql);

        return $result->fetchAll();
    }

    /**
     * Содержит информацию и статус для реплицируемых таблиц, расположенных на локальном сервере.
     * @return array
     */
    public function replicas($table)
    {
        $sql = 'SELECT * FROM system.parts WHERE table=' . $table;
        $result = $this->query($sql);

        return $result->fetchAll();
    }

    /**
     * @return Settings
     */
    public function settings()
    {
        return $this->settings;
    }

    /**
     * @return array
     */
    public function zookeeper($path)
    {
        $sql = 'SELECT * FROM system.zookeeper WHERE $path = ' . $path;
        $result = $this->query($sql);

        return $result->fetchAll();
    }


}