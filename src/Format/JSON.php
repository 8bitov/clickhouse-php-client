<?php

namespace ClickHouse\Format;

class JSON extends AbstractFormat
{
    const NAME = 'JSON';

    public function parseRawOutput($rawResult)
    {
        $this->rawResult = $rawResult;

        $this->result = json_decode($rawResult);

        $this->meta = $this->result->meta;
        $this->data = $this->result->data;
//        $this->totals = $this->result->totals;
        //   $this->extremes = $this->result->extremes;
        $this->rows = $this->result->rows;
        //     $this->rows_before_limit_at_least = $this->result->rows_before_limit_at_least;

        return $this;
    }
}