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
        return 'Y-m-d';
    }

    /**
     * Get the format for database stored datetimes.
     *
     * @return string
     */
    public function getDateTimeFormat()
    {
        return 'Y-m-d H:i:s';
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function quote($value)
    {
        if (is_string($value)) {
            return "'" . addslashes($value) . "'";
        }

        if (is_array($value))
            return json_encode($value);

        if (null === $value)
            return '';

        return $value;
    }
}