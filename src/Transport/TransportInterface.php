<?php

namespace ClickHouse\Transport;

use ClickHouse\Statement;

/**
 * Interface TransportInterface
 * @package ClickHouse\Transport
 */
interface TransportInterface
{
    /**
     * @param  string $sql
     *
     * @param array $bindings
     * @return Statement
     * @throws \RuntimeException
     */
    public function select($sql, array $bindings = []);

    /**
     * @param string $table
     * @param array $values
     * @param array $columns
     *
     * @return Statement
     * @throws \RuntimeException
     *
     */
    public function insert($table, array $columns = [], array $values);

    /**
     * @param $sql
     * @param array $bindings
     *
     * @return Statement
     * @throws \RuntimeException
     */
    public function execute($sql, $bindings = []);
}