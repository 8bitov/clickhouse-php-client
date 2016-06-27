<?php

namespace ClickHouse\Format;

use ClickHouse\Exception\NotImplementedException;

class Pretty extends AbstractFormat
{
    const NAME = 'Pretty';

    public function parseRawOutput($rawResult)
    {
        throw new NotImplementedException();

    }
}