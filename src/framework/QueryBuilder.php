<?php

namespace Framework;

/**
 * Class QueryBuilder
 */
class QueryBuilder
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var
     */
    protected $table;

    /**
     * @var string
     */
    protected $action = 'SELECT';

    /**
     * @var array
     */
    protected $select = [];

    /**
     * @var array
     */
    protected $from = [];

    /**
     * @var array
     */
    protected $where = [];

    /**
     * @var array
     */
    protected $joins = [];

    /**
     * @var array
     */
    protected $orderBy = [];

    /**
     * @var array
     */
    protected $groupBy = [];

    /**
     * QueryBuilder constructor.
     *
     * @param Connection $connection
     * @param            $table
     */
    public function __construct(Connection $connection, $table) {
        $this->connection = $connection;
        $this->table = $table;
    }

    /**
     * @return string
     */
    public function toString() {
        $actionString = $this->action;

        if ($this->action === 'SELECT') {
            $selectString = $this->action === 'SELECT' ? empty($this->select) ? '*' : implode(', ', $this->select) : null;
        } else {
            $selectString = null;
        }

        $fromString = 'FROM ' . $this->connection->getName() . '.' . $this->table;

        $joinString = implode(' ', $this->joins);

        $whereString = implode(' ', $this->where);

        $groupString = 'GROUP BY ' . implode(', ', $this->groupBy);

        $orderString = 'ORDER BY ' . implode(', ', $this->orderBy);

        return implode(' ', [
            $actionString,
            $selectString,
            $fromString,
            $joinString,
            $whereString,
            $groupString,
            $orderString
        ]);
    }

    /**
     * @param null $select
     *
     * @return string
     */
    public function get($select = null) {
        if ($select) {
            $this->select($select);
        }

        return $this->connection->getInstance()->exec($this->toString());
    }

    /**
     * @param string|array $select
     *
     * @return QueryBuilder
     */
    public function select($select) {
        if (is_string($select)) {
            $select = explode(',', str_replace(' ', '', $select));
        }

        foreach ($select as $item) {
            if (!in_array("`$item`", $this->select)) $this->select[] = "$item";
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function delete() {
        $this->action = 'DELETE';
        return $this;
    }

    public function join($table, $key, $operator, $value, $type = null) {
        $this->joins[] = ltrim("$type JOIN $table ON `$key` $operator `$value`");

        return $this;
    }

    public function innerJoin($table, $key, $operator, $value) {
        return $this->join($table, $key, $operator, $value, 'INNER');
    }

    public function leftJoin($table, $key, $operator, $value) {
        return $this->join($table, $key, $operator, $value,'LEFT');
    }

    public function rightJoin($table, $key, $operator, $value) {
        return $this->join($table, $key, $operator, $value,'RIGHT');
    }

    public function fullJoin($table, $key, $operator, $value) {
        return $this->join($table, $key, $operator, $value,'FULL OUTER JOIN');
    }

    /**
     * @param array  $where
     * @param string $comparator
     * @param bool   $isOr
     * @param bool   $excludeValue
     *
     * @return QueryBuilder
     */
    public function where($where, $comparator = '=', $isOr = false, $excludeValue = false) {
        $isFirst = $isOr ? true : empty($this->where);

        foreach ($where as $key => $value) {
            array_push($this->where, (!$isFirst ? 'AND ' : ($isOr ? 'OR' : 'WHERE') . ' ') . "`$key` $comparator" . ($excludeValue ? null : " `$value`"));
            $isFirst = false;
        }

        return $this;
    }

    /**
     * @param $where
     *
     * @return QueryBuilder
     */
    public function whereNotNull($where) {
        return $this->where(array_flip($where), 'IS NOT NULL', false, true);
    }

    /**
     * @param        $where
     * @param string $comparator
     *
     * @return QueryBuilder
     */
    public function orWhere($where, $comparator = '=') {
        return $this->where($where, $comparator, true);
    }

    /**
     * @param $where
     *
     * @return QueryBuilder
     */
    public function orWhereNotNull($where) {
        return $this->where(array_flip($where), 'IS NOT NULL', true, true);
    }

    /**
     * @param $columns
     *
     * @return QueryBuilder
     */
    public function groupBy($columns) {
        foreach ($columns as $column) {
            if (!in_array($column, $this->groupBy)) $this->groupBy[] = "$column";
        }

        return $this;
    }

    /**
     * @param array  $columns
     * @param string $order
     *
     * @return QueryBuilder
     */
    public function orderBy($columns, $order = 'DESC') {
        foreach ($columns as $column) {
            if (!in_array($column, $this->orderBy)) $this->orderBy[] = "$column $order";
        }

        return $this;
    }

    /**
     * @return QueryBuilder
     */
    public function min() {
        return $this;
    }

    /**
     * @return QueryBuilder
     */
    public function max() {
        return $this;
    }

    /**
     * @return QueryBuilder
     */
    public function count() {
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}