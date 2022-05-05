<?php

namespace BulletDigitalSolutions\Gunshot\Builders\Engines;

use BulletDigitalSolutions\Gunshot\Contracts\QueryEngineContract;

class ElasticsearchQueryEngine implements QueryEngineContract
{
    /**
     * @return string
     */
    public function toString(array $filters)
    {
        if (count($filters) === 0) {
            return '*';
        }
        $i = 0;

        $filterStrings = [];
        foreach ($filters as $filter) {
            if ($filter['type'] == 'where') {
                $filterStrings[] = $this->toWhereString($filter, (bool)$i);
            } elseif ($filter['type'] == 'or_where') {
                $filterStrings[] = $this->toOrWhereString($filter);
            } elseif ($filter['type'] == 'where_in') {
                $filterStrings[] = $this->toWhereInString($filter, (bool)$i);
            } elseif ($filter['type'] == 'sub') {
                $filterStrings[] = $this->toSubQueryString($filter['filters'], (bool)$i);
            }

            $i++;
        }
        return implode(' ', $filterStrings);
    }

    /**
     * @param $filter
     * @return string
     */
    public function toWhereString($filter, bool $and = false)
    {
        $string = '(' . $filter['field'] . ':' . $filter['value'] . ')';

        if ($and) {
            $string = 'AND ' . $string;
        }

        if ($filter['operator'] == '!=' || $filter['operator'] == '<>') {
            $string = 'NOT ' . $string;
        }
        return $string;
    }


    /**
     * @param $filter
     * @return string
     */
    public function toOrWhereString($filter)
    {
        return 'OR ' . $this->toWhereString($filter, false);
    }

    /**
     * @param $filter
     * @return string
     */
    public function toWhereInString($filter)
    {
        $string = '';
        $i = 0;

        foreach($filter['value'] as $value) {
            $filter = [
                'field' => $filter['field'],
                'value' => $value,
                'operator' => $filter['operator'],
            ];

            if ($i > 0) {
                $string .= ' OR ';
            }

            $string .=  $this->toWhereString($filter);
            $i++;
        }

        return '(' . $string . ')';
    }

    public function toSubQueryString($filters, $and = false)
    {
        $string = '(' . $this->toString($filters) . ')';

        if ($and) {
            $string = 'AND ' . $string;
        }

        return $string;
    }

}