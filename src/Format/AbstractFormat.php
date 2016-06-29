<?php

namespace ClickHouse\Format;

/**
 * Class AbstractFormat
 * @package ClickHouse\Format
 */
abstract class AbstractFormat
{


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

    public function __construct()
    {

    }

    /**
     * @param $response
     * 
     * @return AbstractFormat
     */
    public function output($response)
    {
        $this->rawResult = $response;
        $this->parseRawResult();

        return $this;
    }

    abstract protected function parseRawResult();

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

    public function getName()
    {
        return static::NAME;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }


}