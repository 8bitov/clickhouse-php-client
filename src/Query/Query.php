<?php

namespace ClickHouse\Query;

use ClickHouse\Format\AbstractFormat;
use ClickHouse\Format\JSON;
use ClickHouse\Transport\TransportInterface;

/**
 * Class Query
 * @package ClickHouse
 */
abstract class Query
{


    /**
     * @var
     */
    protected $sql;
    /**
     * @var TransportInterface
     */
    protected $transport;
    /**
     * @var array
     */
    protected $bindings = [];
    /**
     * @var AbstractFormat
     */
    protected $format;


    /**
     * @param TransportInterface $transport
     * @param string $sql
     * @param string $formatName
     */
    protected function init(TransportInterface $transport, $sql, $formatName = null)
    {
        $this->sql = $sql;
        $this->transport = $transport;


        if (null === $formatName) {
            $formatName = static::DEFAULT_FORMAT;
        }
        $this->format = new $formatName();
    }

    /**
     * @return AbstractFormat
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param array $bindings
     */
    public function bindParams(array $bindings)
    {
        foreach ($bindings as $column => $value) {
            $this->bindParam($column, $value);
        }
    }

    /**
     * @param string $column
     * @param mixed $value
     */
    public function bindParam($column, $value)
    {
        $this->bindings[$column] = $value;
    }

    /**
     * Устанавливает формат вывода для SELECT/INSERT запроса
     *
     * @return string
     */
    protected function prepareQueryFormat()
    {
        return $this->sql = $this->sql . ' FORMAT ' . $this->format->getName();
    }

    /**
     * Биндит параметры в sql
     * @return string
     */
    protected function prepareQueryBindings()
    {
        $keys = array();
        $values = $this->bindings;

        # build a regular expression for each parameter
        foreach ($this->bindings as $key => $value) {
            if (is_string($key)) {
                $keys[] = '/:' . $key . '/';
            } else {
                $keys[] = '/[?]/';
            }

            if (is_string($value))
                $values[$key] = "'" . $value . "'";

            if (is_array($value))
                $values[$key] = "'" . implode("','", $value) . "'";

            if (null === $value)
                $values[$key] = 'NULL';
        }
        $this->sql = preg_replace($keys, $values, $this->sql, 1, $count);

        return $this->sql;
    }


    /**
     * @return string
     */
    public function toSql()
    {
        $this->prepareQueryBindings();
        $this->prepareQueryFormat();
        return $this->sql;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toSql();
    }
}