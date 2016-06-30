<?php

namespace ClickHouse\Query;

use ClickHouse\Format\JSON;
use ClickHouse\Transport\TransportInterface;

class SelectQuery extends Query
{

    const DEFAULT_FORMAT = JSON::class;

    /**
     * Query constructor.
     * @param TransportInterface $transport
     * @param string $sql
     * @param array $bindings
     * @param null $formatName
     */
    public function __construct(TransportInterface $transport, $sql, $bindings = [], $formatName = null)
    {
        parent::__construct();
        $this->init($transport, $sql, $formatName);
        $this->bindParams($bindings);
    }
}