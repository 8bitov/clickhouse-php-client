<?php
namespace ClickHouse\Query;

use ClickHouse\Transport\TransportInterface;

/**
 * Class ExecuteQuery
 * @package ClickHouse\Query
 */
class ExecuteQuery extends Query
{

    /**
     * Query constructor.
     * @param TransportInterface $transport
     * @param string $sql
     * @param array $bindings
     */
    public function __construct(TransportInterface $transport, $sql, $bindings = [])
    {
        parent::__construct();
        $this->init($transport, $sql);
        $this->bindParams($bindings);
    }

}