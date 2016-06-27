<?php

namespace ClickHouse\Exception;

use BadMethodCallException;

class NotImplementedException extends BadMethodCallException
{
    protected $message = 'method not implemented';
}