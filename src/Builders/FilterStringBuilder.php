<?php

namespace BulletDigitalSolutions\Gunshot\Builders;

use BulletDigitalSolutions\Gunshot\Contracts\QueryEngineContract;

class FilterStringBuilder
{
    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var
     */
    private $queryEngine;

    /**
     *
     */
    public function __construct($queryEngine)
    {
        $this->queryEngine = $queryEngine;
        $this->filters = [];
    }

    /**
     * @return $this
     */
    public function where($field, $value = null, $operator = '=')
    {
        if ($field instanceof \Closure) {
            return $this->group($field);
        }

        $this->filters[] = $this->getFilter('where', $field, $value, $operator);
        return $this;
    }

    /**
     * @return $this
     */
    public function andWhere($field, $value, $operator = '=')
    {
        $this->filters[] = $this->getFilter('where', $field, $value, $operator);
        return $this;
    }

    /**
     * @return $this
     */
    public function orWhere($field, $value, $operator = '=')
    {
        $this->filters[] =  $this->getFilter('or_where', $field, $value, $operator);
        return $this;
    }

    /**
     * @param $field
     * @param array $value
     * @return $this
     */
    public function whereIn($field, array $value)
    {
        $this->filters[] = $this->getFilter('where_in', $field, $value, '=');
        return $this;
    }

    /**
     * @param $field
     * @param array $value
     * @return $this
     */
    public function whereNotIn($field, array $value)
    {
        $this->filters[] = $this->getFilter('where_in', $field, $value, '!=');
        return $this;
    }

    public function group($closure)
    {
        $subQuery = new FilterStringBuilder($this->queryEngine);
        call_user_func($closure, $query = $subQuery);

        $this->filters[] = $this->getGroup($subQuery->getFilters());
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getQueryEngine()->toString($this->filters);
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @return mixed
     */
    public function getQueryEngine()
    {
        return $this->queryEngine;
    }

    /**
     * @param mixed $queryEngine
     */
    public function setQueryEngine($queryEngine): void
    {
        $this->queryEngine = $queryEngine;
    }

    /**
     * @param $type
     * @param $field
     * @param $value
     * @param $operator
     * @return array
     */private function getFilter($type, $field, $value, $operator)
    {
        return [
            'type' => $type,
            'field' => $field,
            'value' => $value,
            'operator' => $operator,
        ];
    }

    /**
     * @param $filters
     * @return array
     */
    private function getGroup($filters)
    {
        return [
            'type' => 'sub',
            'filters' => $filters,
        ];
    }
}