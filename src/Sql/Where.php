<?php
/**
 * Created by PhpStorm.
 * User: vmelnychuk
 * Date: 2/17/17
 * Time: 4:46 PM
 */

namespace ClickHouse\Sql;


class Where
{

    const COMBINATION_OR = 'OR';

    const COMBINATION_AND = 'AND';

    private $predicates = [];

    /**
     * @param $predicate
     * @param $combination
     */
    public function addPredicate($predicate, $combination)
    {
        $this->predicates[][$combination] = $predicate;
    }

    /**
     * @return string
     */
    public function getSql()
    {
        if (empty($this->predicates)) {
            return '';
        } else {
            $where = '';
            for ($i = 0; $i < count($this->predicates); $i++) {
                foreach ($this->predicates[$i] as $combination => $predicate) {
                    if ($i == 0) {
                        $where .= $predicate;
                    } else {
                        $where .= " $combination $predicate";
                    }
                }
            }

            return ' WHERE (' . $where . ')';
        }
    }

}