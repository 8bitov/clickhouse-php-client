<?php

namespace ClickHouse\Format;

use ClickHouse\Exception\NotImplementedException;

class Values
{
    const NAME = 'Values';

    protected function parseRawResult()
    {
        throw new NotImplementedException();
    }
}