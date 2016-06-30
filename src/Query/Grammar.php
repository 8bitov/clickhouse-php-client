<?php

namespace ClickHouse\Query;

class Grammar
{
    /**
     * Get the format for database stored dates.
     *
     * @return string
     */
    public function getDateFormat()
    {
        return 'Y-m-d H:i:s';
    }

    /**
     * @param $value
     * @return string
     */
    public function quote($value)
    {
        if (is_string($value))
            return "'" . $value . "'";

        if (is_array($value))
            return "'" . implode("','", $value) . "'";

        if (null === $value)
            return '';

        return $value;
    }
}