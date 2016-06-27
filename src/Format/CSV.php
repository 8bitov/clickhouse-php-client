<?php

namespace ClickHouse\Format;

use ClickHouse\Exception\NotImplementedException;

class CSV extends AbstractFormat
{

    const NAME = 'CSV';

    public function parseRawOutput($rawResult)
    {
        throw new NotImplementedException();
    }
}