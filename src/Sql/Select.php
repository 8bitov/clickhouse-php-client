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

    private $groupBy;

    private $order;

    const PART_COLUMNS = 'columns';
    const PART_WHERE = 'where';
    const PART_GROUP_BY = 'group_by';

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
        $this->groupBy = new GroupBy();
        $this->order = new Order();
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

    public function groupBy($column)
    {
        $this->groupBy->addGroup($column);
    }

    public function order($column, $type)
    {
        $this->order->setOrderColumns($column, $type);
    }

    public function reset(array $types)
    {
        for ($i = 0; $i < count($types); $i++) {
            switch ($types[$i]) {
                case (self::PART_COLUMNS):
                    $this->columns = new Columns();
                    break;
                case (self::PART_WHERE):
                    $this->where = new Where();
                    break;
                case (self::PART_GROUP_BY):
                    $this->groupBy = new GroupBy();
                    break;
                default:
                    new \InvalidArgumentException(sprintf('type \'%s\' is undefined for reset', $types[$i]));
            }
        }
    }

    /**
     * @param null $table
     */
    public function setTable($table)
    {
        $this->table = $table;
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
            return "SELECT " . $this->columns->getSql() . " FROM " . $this->table . $this->where->getSql() .
                $this->groupBy->getSql() . $this->order->getSql();
        }
    }

}