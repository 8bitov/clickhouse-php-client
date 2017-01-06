<?php

namespace ClickHouse\Exception;

use BadMethodCallException;

/**
 * Class NotImplementedException
 *
 * @package ClickHouse\Exception
 */
class NotImplementedException extends BadMethodCallException
{
    protected $message = 'method not implemented';
}
