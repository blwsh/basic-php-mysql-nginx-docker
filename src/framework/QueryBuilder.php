<?php

namespace Framework;

use function array_filter;
use Framework\Framework\OrBeforeWhereException;
use PDO;

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
     * @var \PDO
     */
    protected $instance;

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
    protected $inserts = [];

    /**
     * @var array
     */
    protected $where = [];

    /**
     * $var values
     */
    protected $values = [];

    /**
     * @var array
     */
    protected $joins = [];

    /**
     * @var array
     */
    protected $groupBy = [];

    /**
     * @var array
     */
    protected $orderBy = [];

    /**
     * @var
     */
    protected $limit;

    /**
     * @var array
     */
    protected $safeOperators = ["=", "<=>", "<>", "!=", ">", ">=", "<", "<=", "like"];

    /**
     * QueryBuilder constructor.
     *
     * @param Connection $connection
     * @param            $table
     */
    public function __construct(Connection $connection, $table) {
        $this->connection = $connection;
        $this->instance = &$this->connection->getInstance();
        $this->table = $table;
    }

    /**
     * @return string
     */
    public function toString() {
        // Construct the action string. Can be SELECT, INSERT, UPDATE or DELETE
        $actionString = $this->action;

        // Construct the select string.
        $selectString = $actionString === 'SELECT' ? empty($this->select) ? '*' : implode(', ', $this->select) : null;

        // Construct from string. Only does if the action is select or delete.
        $fromString = ($actionString === 'SELECT' || $actionString === 'DELETE' ? 'FROM ' : null) . $this->connection->getName() . '.' . $this->table;

        // Construct join string.
        $joinString = implode(' ', $this->joins);

        // Construct where string and append values for prepared statement.
        $whereString = implode(' ', array_map(
            function($where) {
                if (!is_null($where['value'])) {
                    $this->values[] = $where['value'];
                }

                return implode(' ', [$where['statement'], $where['key'], $where['comparator'], is_null($where['value'])  ? null : '?']);
            },
        $this->where));

        // Construct the group by string.
        $groupString = $this->groupBy ? 'GROUP BY ' . implode(', ', $this->groupBy) : null;

        // Construct the order by string.
        $orderString = $this->orderBy ? 'ORDER BY ' . implode(', ', $this->orderBy) : null;

        // Construct the limit string.
        $limitString = $this->limit ? 'LIMIT ' . $this->limit : null;

        // Construct the entire query string.
        return implode(' ', array_filter([
            $actionString,
            $selectString,
            $fromString,
            $joinString,
            $whereString,
            $groupString,
            $orderString,
            $limitString
        ]));
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
     * @param null $select
     *
     * @return array
     */
    public function get($select = null) {
        if ($select) {
            $this->select($select);
        }

        $statement = $this->instance->prepare($this->toString());
        $statement->execute($this->values);

        return $statement->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function insert(array $data) {
        $this->action = 'INSERT INTO';

        foreach ($data as $key => $value) {
            $this->inserts[] = [
                'key'   => $key,
                'value' => $value
            ];
        }

        dd($this->toString());

        $prepared = $this->instance->prepare($this->toString());
        $prepared->execute([]);

        return $prepared;
    }

    /**
     * @return bool
     */
    public function delete() {
        $this->action = 'DELETE';

        return $this->connection->exec($this->toString());
    }

    /**
     * @param      $table
     * @param      $key
     * @param      $operator
     * @param      $value
     * @param null $type
     *
     * @return $this
     */
    public function join($table, $key, $operator, $value, $type = null) {
        $this->joins[] = ltrim("$type JOIN $table ON `$key` $operator `$value`");

        return $this;
    }

    /**
     * @param $table
     * @param $key
     * @param $operator
     * @param $value
     *
     * @return QueryBuilder
     */
    public function innerJoin($table, $key, $operator, $value) {
        return $this->join($table, $key, $operator, $value, 'INNER');
    }

    /**
     * @param $table
     * @param $key
     * @param $operator
     * @param $value
     *
     * @return QueryBuilder
     */
    public function leftJoin($table, $key, $operator, $value) {
        return $this->join($table, $key, $operator, $value,'LEFT');
    }

    /**
     * @param $table
     * @param $key
     * @param $operator
     * @param $value
     *
     * @return QueryBuilder
     */
    public function rightJoin($table, $key, $operator, $value) {
        return $this->join($table, $key, $operator, $value,'RIGHT');
    }

    /**
     * @param $table
     * @param $key
     * @param $operator
     * @param $value
     *
     * @return QueryBuilder
     */
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
        if (!in_array($comparator, $this->safeOperators)) $this;

        $isFirst = $isOr ? true : empty($this->where);

        foreach ($where as $key => $value) {
            $this->where[] = [
                'statement'  => ($isFirst ? ($isOr ? 'OR' : 'WHERE') : 'AND'),
                'key'        => $key,
                'comparator' => $comparator,
                'value'      => $excludeValue ? null : $value,
            ];

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
        if (is_string($where)) $where = [$where];

        return $this->where(array_flip($where), 'IS NOT NULL', false, true);
    }

    /**
     * @param        $where
     * @param string $comparator
     *
     * @return QueryBuilder
     */
    public function orWhere($where, $comparator = '=') {
        if (!empty($this->where)) {
            return $this->where($where, $comparator, true);
        }

        return $this;
    }

    /**
     * @param $where
     *
     * @return QueryBuilder
     */
    public function orWhereNotNull($where) {
        if (is_string($where)) $where = [$where];

        if (!empty($this->where)) {
            return $this->where(array_flip($where), 'IS NOT NULL', true, true);
        }

        return $this;
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
     * @param int $limit
     */
    public function limit(int $limit) {
        $this->limit = $limit;
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