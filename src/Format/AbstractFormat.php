<?php

namespace ClickHouse\Format;

/**
 * Class AbstractFormat
 * @package ClickHouse\Format
 */
abstract class AbstractFormat
{

    /**
     *
     */
    CONST FETCH_ONE = 'fetch_one';

    /**
     *
     */
    CONST FETCH_ALL = 'fetch_all';

    /**
     *
     */
    CONST FETCH_COLUMN = 'fetch_column';

    /**
     * @var mixed|string
     */
    protected $rawResult;

    /**
     * @var \stdClass
     */
    protected $result;

    /**
     * @var array
     */
    protected $meta;
    /**
     * @var array
     */
    protected $data;
    /**
     * @var \stdClass
     */
    protected $totals;
    /**
     * @var \stdClass
     */
    protected $extremes;
    /**
     * @var int
     */
    protected $rows;

    /**
     * @var int
     */
    protected $rows_before_limit_at_least;


    /**
     * @return mixed
     */
    public function getName()
    {
        return static::NAME;
    }

    /**
     *
     */
    public function input()
    {

    }

    /**
     * @param $rawResult
     * @return $this
     */
    public function output($rawResult)
    {
        $this->rawResult = $rawResult;
        $this->parseRawOutput($rawResult);

        return $this;
    }

    /**
     * @param $rawResult
     * @return mixed
     */
    abstract public function parseRawOutput($rawResult);

    /**
     * @return mixed|string
     */
    public function getRawResult()
    {
        return $this->rawResult;
    }

    /**
     * @return \stdClass
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }


    /**
     * @return array
     */
    public function fetchAll()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function fetchOne()
    {
        return current($this->data);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function fetchColumn($name)
    {
        $current = current($this->data);

        return $current->{$name};
    }

    /**
     * @return \stdClass
     */
    public function getTotals()
    {
        return $this->totals;
    }

    /**
     * @return \stdClass
     */
    public function getExtremes()
    {
        return $this->extremes;
    }

    /**
     * @return int
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @return int
     */
    public function getRowsBeforeLimitAtLeast()
    {
        return $this->rows_before_limit_at_least;
    }
}