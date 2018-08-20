<?php

namespace ClickHouse\Query;

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
     * @var Grammar
     */
    protected $grammar;

    protected static $format = null;

    public function __construct()
    {
        $this->grammar = new Grammar();
    }
    
    /**
     * @param TransportInterface $transport
     * @param string $sql
     */
    protected function init(TransportInterface $transport, $sql)
    {
        $this->sql = $sql;
        $this->transport = $transport;

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

        if (null !== static::$format) {
            $this->sql = $this->sql . ' FORMAT ' . static::$format;
        }

        return $this->sql;
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
                $values[$key] = '';
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