<?php
namespace ClickHouse\Query;

use ClickHouse\Transport\TransportInterface;

/**
 * Class InsertQuery
 * @package ClickHouse\Query
 */
class InsertQuery extends Query
{

    /**
     * InsertQuery constructor.
     * @param TransportInterface $transport
     * @param string $table
     * @param array $columns
     * @param array $values
     */
    public function __construct(TransportInterface $transport, $table, array $columns = [], array $values)
    {
        parent::__construct();
        $sql = $this->prepareSql($table, $columns, $values);
        $this->init($transport, $sql);
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

}