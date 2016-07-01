<?php

namespace ClickHouse\Query;

use ClickHouse\Format\JSON;
use ClickHouse\Transport\TransportInterface;

class SelectQuery extends Query
{
    protected static $format = 'JSON';
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