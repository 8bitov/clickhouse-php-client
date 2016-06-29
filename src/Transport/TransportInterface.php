<?php

namespace ClickHouse\Transport;

use ClickHouse\Format\JSON;
use ClickHouse\Format\TabSeparated;
use ClickHouse\Statement;

/**
 * Interface TransportInterface
 * @package ClickHouse\Transport
 */
interface TransportInterface
{

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




}