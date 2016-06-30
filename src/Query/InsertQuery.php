<?php
namespace ClickHouse\Query;

use ClickHouse\Format\Values;
use ClickHouse\Format\TabSeparated;
use ClickHouse\Transport\TransportInterface;

/**
 * Class InsertQuery
 * @package ClickHouse\Query
 */
class InsertQuery extends Query
{

    /**
     *
     */
    const DEFAULT_FORMAT = Values::class;

    /**
     * InsertQuery constructor.
     * @param TransportInterface $transport
     * @param string $table
     * @param array $columns
     * @param array $values
     * @param string $formatName
     */
    public function __construct(TransportInterface $transport, $table, array $columns = [], array $values, $formatName = null)
    {
        parent::__construct();
        $sql = $this->prepareSql($table, $columns, $values);
        $this->init($transport, $sql, $formatName);
    }


    /**
     * @param string $table
     * @param array $columns
     * @param array $values
     * @return string
     */
    protected function prepareSql($table, array $columns = [], array $values)
    {
        $sql = 'INSERT INTO ' . $table;

        if (0 !== count($columns)) {
            $sql .= ' (' . implode(',', $columns) . ') ';
        }

        $sql .= 'VALUES ';

        foreach ($values as $row) {
            $sql .= ' (' . implode(',', $this->quote($row)) . '), ';
        }

        $sql = trim($sql, ', ');

        return $sql;
    }

    /**
     * @param array $row
     * @return array
     */
    protected function quote(array $row)
    {
        $grammar = $this->grammar;
        $quote = function ($value) use ($grammar) {
            return $grammar->quote($value);
        };
        return array_map($quote, $row);
    }

    /**
     * @return string
     */
    public function toSql()
    {
        $this->prepareQueryBindings();
        return $this->sql;
    }
}