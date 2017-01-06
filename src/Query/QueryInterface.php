<?php

namespace ClickHouse\Query;

/**
 * Interface QueryInterface
 *
 * @package ClickHouse\Query
 */
interface QueryInterface
{
    public function table();
    public function select();
}
