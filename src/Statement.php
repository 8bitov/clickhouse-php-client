<?php
namespace ClickHouse;

use ClickHouse\Transport\TransportInterface;
use ClickHouse\Query\Query;

/**
 * Class Statement
 * @package ClickHouse
 */
class Statement
{

    /**
     * @var TransportInterface
     */
    private $transport;

    /**
     * @var Query
     */
    private $query;

    /**
     * @var \stdClass
     */
    private $result;
    /**
     * @var
     */
    private $meta;
    /**
     * @var
     */
    private $data;
    /**
     * @var
     */
    private $totals;
    /**
     * @var array
     */
    private $extremes;
    /**
     * @var int
     */
    private $rows;
    /**
     * @var
     */
    private $rows_before_limit_at_least;
    /**
     * @var
     */
    private $rawResult;

    const JSON_RESPONSE_POSSIBLE_KEYS = [
        'meta',
        'data',
        'totals',
        'extremes',
        'rows',
        'rows_before_limit_at_least',
    ];


    /**
     * Statement constructor.
     * @param $data
     * @param Query|null $query
     * @param TransportInterface $transport
     */
    public function __construct($data, Query $query = null, TransportInterface $transport)
    {
        $this->transport = $transport;
        $this->query = $query;
        $this->prepareJsonResponse($data);

    }

    /**
     * @param $data
     *
     * @return Statement
     */
    protected function prepareJsonResponse($data)
    {
        $this->rawResult = $data;

        if (empty($data)) {
            return $this;
        }

        $this->result = json_decode($data);
        foreach (self::JSON_RESPONSE_POSSIBLE_KEYS as $possibleKey) {
            if (property_exists($this->result, $possibleKey)) {
                $this->{$possibleKey} = $this->result->{$possibleKey};
            }
        }

        return $this;

    }


    /**
     * @return array
     */
    public function fetchAll()
    {
        return $this->data;
    }

    /**
     * @return \stdClass
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
        $current = $this->fetchOne();

        return $current->{$name};
    }

    /**
     * @return int
     */
    public function rowsCount()
    {
        return $this->rows;
    }



    /**
     * @return mixed
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
     * @return mixed
     */
    public function getRowsBeforeLimitAtLeast()
    {
        return $this->rows_before_limit_at_least;
    }


    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @param string $column
     * @return \stdClass
     */
    public function getColumnMeta($column)
    {
        $meta = $this->getMeta();

        return array_reduce($meta, function ($carry, $item) use ($column) {
            if ($item->name === $column) {
                $carry = $item;
            }

            return $carry;
        });
    }

    /**
     * @return mixed
     */
    public function getExtremes()
    {
        return $this->extremes;
    }

    /**
     * @return mixed
     */
    public function getTotals()
    {
        return $this->totals;
    }

}