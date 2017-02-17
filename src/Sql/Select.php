<?php
namespace ClickHouse\Sql;

use ClickHouse\Query\Grammar;

/**
 * Created by PhpStorm.
 * User: vmelnychuk
 * Date: 2/17/17
 * Time: 4:28 PM
 */
class Select
{

    private $grammar;

    private $table;

    private $columns;

    private $where;

    /**
     * Select constructor.
     * @param null $table
     */
    public function __construct($table = null)
    {
        $this->table = $table;
        $this->grammar = new Grammar();
        $this->where = new Where();
        $this->columns = new Columns();
    }

    /**
     * @param $table
     * @param array $columns
     * @return $this
     */
    public function from($table, $columns = [])
    {
        $this->table = $table;
        $this->columns->setColumns($columns);

        return $this;
    }


    public function columns($columns)
    {
        $this->columns->setColumns($columns);

        return $this;
    }

    public function where($predicate, $bind)
    {
        $this->where->addPredicate(sprintf($predicate, $this->grammar->quote($bind)), Where::COMBINATION_AND);

        return $this;
    }

    public function orWhere($predicate, $bind)
    {
        $this->where->addPredicate(sprintf($predicate, $this->grammar->quote($bind)), Where::COMBINATION_OR);

        return $this;
    }

    /**
     * @param null $table
     */
    public function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * @param Columns $columns
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;
    }

    /**
     * @param Where $where
     */
    public function setWhere($where)
    {
        $this->where = $where;
    }

    public function getSql()
    {
        if (!$this->table) {
            return '';
        } else {
            return "SELECT " . $this->columns->getSql() . " FROM " . $this->table . $this->where->getSql();
        }
    }

}