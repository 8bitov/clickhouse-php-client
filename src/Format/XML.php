<?php
namespace ClickHouse\Format;

use ClickHouse\Exception\NotImplementedException;

class XML extends AbstractFormat
{

    public function parseRawOutput($rawResult)
    {
        throw new NotImplementedException();

    }
}