<?php
namespace ClickHouse\Query;

use ClickHouse\Format\JSON;
use ClickHouse\Format\Values;
use ClickHouse\Format\TabSeparated;
use ClickHouse\Transport\TransportInterface;

/**
 * Class ExecuteQuery
 * @package ClickHouse\Query
 */
class ExecuteQuery extends Query
{

    /**
     *
     */
    const DEFAULT_FORMAT = TabSeparated::class;

    /**
     * Query constructor.
     * @param TransportInterface $transport
     * @param string $sql
     * @param array $bindings
     * @param null $formatName
     */
    public function __construct(TransportInterface $transport, $sql, $bindings = [], $formatName = null)
    {
        $this->init($transport, $sql, $formatName);
        $this->bindParams($bindings);
    }

    /**
     * @return string
     */
    public function toSql()
    {
        $this->prepareQueryBindings();
      //  $this->prepareQueryFormat();
        return $this->sql;
    }

}