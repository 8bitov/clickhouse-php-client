<?php
namespace ClickHouse;

use ClickHouse\Format\AbstractFormat;
use ClickHouse\Transport\TransportInterface;
use ClickHouse\Query\Query;

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
     * @var AbstractFormat
     */
    private $result;


    public function __construct($data, Query $query = null, TransportInterface $transport)
    {
        $this->transport = $transport;
        $this->query = $query;
        $this->result = $this->query->getFormat()->output($data);

    }

    /**
     * @return array
     */
    public function fetchAll()
    {
        return $this->result->getData();
    }

    /**
     * @return \stdClass
     */
    public function fetchOne()
    {
        return current($this->result->getData());
    }

    /**
     * @param $name
     * @return mixed
     */
    public function fetchColumn($name)
    {
        $current = current($this->result->getData());

        return $current->{$name};
    }

    /**
     * @return int
     */
    public function rowCount()
    {
        return $this->result->getRows();
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->result->getMeta();
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

    public function getRawResult()
    {
        return $this->result->getRawResult();
    }

}