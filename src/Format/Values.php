<?php

namespace ClickHouse\Format;

use ClickHouse\Exception\NotImplementedException;

class Values extends  AbstractFormat
{
    const NAME = 'Values';

    protected function parseRawResult()
    {
        $this->result = $this->getRawResult();
    }
}