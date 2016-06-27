<?php

namespace ClickHouse\Transport;

/**
 * Interface TransportInterface
 * @package ClickHouse\Transport
 */
interface TransportInterface
{

    /**
     * @param $sql
     * @param null $format
     * @return mixed
     */
    public function query($sql, $format);

    /**
     * @param $sql
     * @param null $format
     * @return mixed
     */
    public function execute($sql, $format);


}