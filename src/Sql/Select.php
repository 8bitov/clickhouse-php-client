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
    private $limit;
    private $union;

    const PART_COLUMNS = 'columns';
    const PART_WHERE = 'where';
    const PART_GROUP_BY = 'group_by';
    const PART_ORDER_BY = 'order_by';

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
        $this->limit = new Limit();
        $this->union = new Union();
    }

    function __toString()
    {
        return '(' . $this->getSql() . ')';
    }

    function __clone()
    {
        $this->grammar = clone $this->grammar;
        $this->where = clone $this->where;
        $this->columns = clone $this->columns;
        $this->groupBy = clone $this->groupBy;
        $this->order = clone $this->order;
        $this->limit = clone $this->limit;
        $this->union = clone $this->union;
    }

    /**
     * @param $table
     * @param array $columns
     * @return $this
     */
    public function from($table, $columns = [])
    {
        $this->table = (string)$table;
        $this->columns->setColumns($columns);

        return $this;
    }


    /**
     * @param $columns
     * @return $this
     */
    public function columns($columns)
    {
        $this->columns->setColumns($columns);

        return $this;
    }

    /**
     * @param $predicate
     * @return $this
     */
    public function where($predicate)
    {

        $this->where->addPredicate($predicate, Where::COMBINATION_AND);

        return $this;
    }

    /**
     * @param $predicate
     * @return $this
     */
    public function orWhere($predicate)
    {
        $this->where->addPredicate($predicate, Where::COMBINATION_OR);

        return $this;
    }

    /**
     * @param $column
     * @return $this
     */
    public function groupBy($column)
    {
        $this->groupBy->addGroup($column);

        return $this;
    }

    /**
     * @param $column
     * @param $type
     * @return $this
     */
    public function order($column, $type)
    {
        $this->order->setOrderColumns($column, $type);

        return $this;
    }

    /**
     * @param $limit
     * @return $this
     */
    public function limit($limit)
    {
        $this->limit->setLimit($limit);

        return $this;
    }

    /**
     * @param $offset
     * @return $this
     */
    public function offset($offset)
    {
        $this->limit->setOffset($offset);

        return $this;
    }

    public function union($select)
    {
        if ($select instanceof Select) {
            $select = $select->getSql();
        }
        $this->union->add($select);

        return $this;
    }

    /**
     * @param array $types
     * @return $this
     */
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
                case (self::PART_ORDER_BY):
                    $this->order = new Order();
                    break;
                case (self::PART_GROUP_BY):
                    $this->groupBy = new GroupBy();
                    break;
                default:
                    new \InvalidArgumentException(sprintf('type \'%s\' is undefined for reset', $types[$i]));
            }
        }

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
     * @param Where $where
     */
    public function setWhere($where)
    {
        $this->where = $where;
    }

    /**
     * @return Grammar
     */
    public function getGrammar()
    {
        return $this->grammar;
    }

    /**
     * @return string
     */
    public function getSql()
    {
        if (!$this->table) {
            return '';
        } else {
            return "SELECT " . $this->columns->getSql() . " FROM " . $this->table . $this->where->getSql() .
                $this->groupBy->getSql() . $this->order->getSql() . $this->limit->getSql() . $this->union->getSql();
        }
    }

}