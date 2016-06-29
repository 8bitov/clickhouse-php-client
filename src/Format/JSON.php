<?php

namespace ClickHouse\Format;

class JSON extends AbstractFormat
{
    const NAME = 'JSON';

    protected function parseRawResult()
    {
        $rawResult = $this->rawResult;

        $this->result = json_decode($rawResult);

        if (property_exists($this->result, 'meta'))
            $this->meta = $this->result->meta;

        if (property_exists($this->result, 'data'))
            $this->data = $this->result->data;

        if (property_exists($this->result, 'totals'))
            $this->totals = $this->result->totals;

        if (property_exists($this->result, 'extremes'))
            $this->extremes = $this->result->extremes;

        if (property_exists($this->result, 'rows'))
            $this->rows = $this->result->rows;

        if (property_exists($this->result, 'rows_before_limit_at_least'))
            $this->rows_before_limit_at_least = $this->result->rows_before_limit_at_least;

        return $this;
    }
}