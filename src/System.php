<?php

namespace ClickHouse;

class System
{
    private $client;

    public function __construct($client, $settings)
    {

        $this->client = $client;
        $this->settings = new Settings($client, $settings);
    }

    /**
     * Содержит информацию о кусках таблиц семейства MergeTree.
     * @param int $limit
     * @return array
     */
    public function numbers($limit = 10)
    {
        $sql = 'SELECT number FROM system.numbers LIMIT ' . $limit;
        $result = $this->client->select($sql);

        return $result->fetchAll();
    }

    /**
     * Таблица содержит столбцы database, name, engine типа String.
     *
     * @return array
     */
    public function tables($table = null)
    {
        $sql = 'SELECT * FROM system.tables';

        if (null !== $table) {
            $sql .= ' WHERE name=:name';
        }

        return $this->client->select($sql, ['name'=>$table]);

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
        $result = $this->client->select($sql);

        return $result->fetchAll();
    }

    /**
     * @return array
     */
    public function processes()
    {
        $sql = 'SHOW PROCESSLIST';
        $result = $this->client->select($sql);

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
        $result = $this->client->select($sql);

        return $result->fetchAll();
    }

    /**
     * информация о доступных в конфигурационном файле кластерах и серверах, которые в них входят.
     *
     * @return Statement
     */
    public function clusters()
    {
        $sql = 'SELECT * FROM system.clusters';
        $result = $this->client->select($sql);

        return $result;
    }

    /**
     * Содержит информацию о столбцах всех таблиц.
     *
     * @param null|string $table
     * @return Statement
     */
    public function columns($table = null)
    {
        $sql = 'SELECT * FROM system.columns';

        if (null !== $table) {
            $sql .= ' WHERE table=:table';
        }

        $result = $this->client->select($sql, ['table'=>$table]);

        return $result;
    }

    /**
     *  информация о внешних словарях.
     * @return array
     */
    public function dictionaries()
    {
        $sql = 'SELECT * FROM system.dictionaries';
        $result = $this->client->select($sql);

        return $result->fetchAll();
    }

    /**
     * Содержит информацию об обычных и агрегатных функциях.
     * @return array
     */
    public function functions()
    {
        $sql = 'SELECT * FROM system.functions';
        $result = $this->client->select($sql);

        return $result->fetchAll();
    }

    /**
     * Содержит информацию о производящихся прямо сейчас слияниях для таблиц семейства MergeTree.
     * @return array
     */
    public function merges()
    {
        $sql = 'SELECT * FROM system.merges';
        $result = $this->client->select($sql);

        return $result->fetchAll();
    }

    /**
     * Содержит информацию о кусках таблиц семейства MergeTree.
     * @return array
     */
    public function parts()
    {
        $sql = 'SELECT * FROM system.parts';
        $result = $this->client->select($sql);

        return $result->fetchAll();
    }

    /**
     * Содержит информацию и статус для реплицируемых таблиц, расположенных на локальном сервере.
     * @return array
     */
    public function replicas($table)
    {
        $sql = 'SELECT * FROM system.replicas WHERE table=' . $table;
        $result = $this->client->select($sql);

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
        $result = $this->client->select($sql);

        return $result->fetchAll();
    }



}