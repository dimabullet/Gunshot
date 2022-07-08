<?php

namespace BulletDigitalSolutions\Gunshot\Builders\Engines;

use BulletDigitalSolutions\Gunshot\Contracts\FilterEngineContract;

class ElasticsearchFilterEngine implements FilterEngineContract
{
    /**
     * This created a query string based on the following documentation:
     * https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-query-string-query.html
     */

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
            $type = $filter['type'];

            switch ($type) {
                case 'where':
                    $filterStrings[] = $this->toWhereString($filter, (bool) $i);
                    break;
                case 'or_where':
                    $filterStrings[] = $this->toOrWhereString($filter);
                    break;
                case 'where_in':
                    $filterStrings[] = $this->toWhereInString($filter, (bool) $i);
                    break;
                case 'sub':
                    $filterStrings[] = $this->toSubQueryString($filter, (bool) $i);
                    break;
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
        $string = '('.$filter['field'].':'.$filter['value'].')';

        if ($and) {
            $string = 'AND '.$string;
        }

        if ($filter['operator'] == '!=' || $filter['operator'] == '<>') {
            $string = 'NOT '.$string;
        }

        return $string;
    }

    /**
     * @param $filter
     * @return string
     */
    public function toOrWhereString($filter)
    {
        return 'OR '.$this->toWhereString($filter, false);
    }

    /**
     * @param $filter
     * @return string
     */
    public function toWhereInString($filter, bool $and = false)
    {
        $string = '';
        $i = 0;

        foreach ($filter['value'] as $value) {
            $filter = [
                'field' => $filter['field'],
                'value' => $value,
                'operator' => $filter['operator'],
            ];

            if ($i > 0) {
                $string .= ' OR ';
            }

            $string .= $this->toWhereString($filter);
            $i++;
        }

        $string = '('.$string.')';

        if ($and) {
            $string = 'AND '.$string;
        }

        return $string;
    }

    /**
     * @param $filters
     * @param $and
     * @return string
     */
    public function toSubQueryString($filters, $and = false)
    {
        $string = '('.$this->toString($filters['filters']).')';

        if ($and) {
            $string = 'AND '.$string;
        }

        return $string;
    }
}
