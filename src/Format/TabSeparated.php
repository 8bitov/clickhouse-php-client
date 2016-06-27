<?php

namespace ClickHouse\Format;

use ClickHouse\Exception\NotImplementedException;

class TabSeparated extends AbstractFormat
{

    const NAME = 'TabSeparated';

    public function parseRawOutput($rawResult)
    {
        throw new NotImplementedException();

    }
}