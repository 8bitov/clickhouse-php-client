<?php
namespace ClickHouse;

use ClickHouse\Format\AbstractFormat;
use ClickHouse\Format\JSON;
use ClickHouse\Transport\TransportInterface;

class Statement
{
    /**
     *
     */
    CONST FETCH_ONE = 'fetch_one';

    /**
     *
     */
    CONST FETCH_ALL = 'fetch_all';

    /**
     *
     */
    CONST FETCH_COLUMN = 'fetch_column';

   
    /**
     * @var TransportInterface
     */
    private $transport;

    /**
     * @var string
     */
    private $query;

    /**
     * @var AbstractFormat
     */
    private $format;


    public function __construct(TransportInterface $transport, $query)
    {
        $this->transport = $transport;
        $this->query = $query;
        $this->format = new JSON();
        $this->prepareQueryFormat();
    }

    /**
     * @return $this
     */
    public function executeSelectStatement()
    {
        $response = $this->transport->executeStatement($this);
        $this->format->output($response);

        return $this;
    }


    /**
     *
     */
    protected function prepareQueryFormat()
    {
        $this->query = $this->query . ' FORMAT '. $this->format->getName();
    }

    /**
     * @return string
     */
    public function toSql()
    {
        return $this->query;
    }

    public function __toString()
    {
        return $this->toSql();
    }

    /**
     * @return array
     */
    public function fetchAll()
    {
        return $this->format->getData();
    }
    /**
     * @return mixed
     */
    public function fetchOne()
    {
        return current($this->format->getData());
    }
    /**
     * @param $name
     * @return mixed
     */
    public function fetchColumn($name)
    {
        $current = current($this->format->getData());
        return $current->{$name};
    }

    public function rowCount()
    {
        return $this->format->getRows();
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return  $this->format->getMeta();
    }

    /**
     * @param string $column
     * @return \stdClass
     */
    public function getColumnMeta($column)
    {
        $meta = $this->getMeta();

       return array_reduce($meta, function($carry, $item) use ($column) {
           if ($item->name === $column) {
               $carry = $item;
           }

           return $carry;
       });
    }

}