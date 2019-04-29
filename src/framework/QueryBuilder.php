<?php

namespace Framework;

use function dd;
use function dump;
use function is_null;
use function max;
use PDO;
use PDOStatement;

/**
 * Class QueryBuilder
 *
 * The query builder, as the name suggests, builds queries. Concatenating strings
 * can become overwhelming and remembering exact syntax can sometimes be difficult.
 * A query builder helpers counter this issues by building SQL queries based on
 * the methods you call.
 *
 * There are intermediate methods calls such as where and join and terminal
 * methods such as get and first which return responses.
 *
 * An example usage of a query builder may look something like this:
 * Model::where(...)->orderBy(...)->join(..., ..., ..., ...)->whereIn(...)->get()
 *
 * Notice orderBy is called before join and whereIn. If you did this when building
 * a query with string concatenation you would recieve an error however, not only
 * does a query builder construct a query, it does it in the right order regardless
 * of which methods you call first.
 *
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
     * @var PDOStatement
     */
    protected $prepared;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var Model
     */
    public $model;

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
    protected $updates = [];

    /**
     * @var array
     */
    protected $deletes = [];

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
    protected $safeOperators = ["=", "<=>", "<>", "!=", ">", ">=", "<", "<=", "LIKE", 'IN'];

    /**
     * QueryBuilder constructor.
     *
     * @param Connection $connection
     * @param            $table
     * @param Model|null $model
     */
    public function __construct(Connection $connection, $table, Model &$model = null) {
        $this->connection = $connection;
        $this->instance = $this->connection->getInstance();
        $this->table = $table;

        if ($model) {
            $this->model = $model;
        }
    }

    /**
     * @return string
     */
    public function build() {
        // Construct the action string. Can be SELECT, INSERT, UPDATE or DELETE
        $actionString = $this->action;

        // Construct the select string.
        $selectString = $actionString === 'SELECT' ? empty($this->select) ? '*' : implode(', ', $this->select) : null;

        // Construct from string. Only does if the action is select or delete.
        $fromString = ($actionString === 'SELECT' || $actionString === 'DELETE' ? 'FROM ' : null) . $this->connection->getName() . '.' . $this->table;

        // Construct join string.
        $joinString = implode(' ', $this->joins);

        // Construct the update string.
        $updateString = $this->updates ?  'SET ' . implode(', ', array_map(function($update) {
            $this->values[] = $update['value'];
            return $update['key'] . ' = ?';
        }, $this->updates)) : null;

        // Construct where string and append values for prepared statement.
        $whereString = implode(' ', array_map(
            function($where) {
                if (!$where['raw']) {
                    $this->values[] = $where['value'];
                }

                return implode(' ', [$where['statement'], $where['key'], $where['comparator'], is_null($where['value'])  ? null : $where['raw'] ? ($where['value']) : '?']);
            },
        $this->where));

        // Construct the inserts string.
        $insertString = $this->inserts ?  '(' . implode(', ', array_map(function($insert) {
            if ($insert['value']) {
                $this->values[] = $insert['value'];

                return $insert['key'];
            }

            return null;
        }, $this->inserts)) . ')' : null;

        // Construct the group by string.
        $groupString = $this->groupBy ? 'GROUP BY ' . implode(', ', $this->groupBy) : null;

        // Construct the order by string.
        $orderString = $this->orderBy ? 'ORDER BY ' . implode(', ', $this->orderBy) : null;

        // Construct the limit string.
        $limitString = $this->limit ? 'LIMIT ' . $this->limit : null;

        // Construct the values string
        $valuesString = $actionString === 'INSERT INTO' ? 'VALUES (' . implode(', ', array_map(function() { return "?"; }, $this->values)) . ')' : null;

        // Construct the entire query string.
        return implode(' ', array_filter([
            $actionString,
            $selectString,
            $fromString,
            $joinString,
            $updateString,
            $whereString,
            $insertString,
            $valuesString,
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

        $prepared = $this->instance->prepare($this->build());

        $this->prepared = $prepared->execute($this->values);

        $results = $prepared->fetchAll(PDO::FETCH_CLASS);

        if ($this->model) {
            return $this->model::fillArray($results);
        }

        return $results;
    }

    /**
     * @return Model|Object|bool
     */
    public function first() {
        $this->limit(1);

        $prepared = $this->instance->prepare($this->build());

        $this->prepared = $prepared->execute($this->values);

        $result = $prepared->fetch(PDO::FETCH_ASSOC);

        if ($result && $this->model) {
            $this->model->fill($result);
            return $this->model;
        }

        return false;

    }

    /**
     * @param $id
     *
     * @return bool|Model|Object
     */
    public function find($id) {
        return $this->where([$builder->model->primaryKey ?? 'id' => $id])->first();
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
                'value' => $value ?? 'null'
            ];
        }

        $this->prepared = $prepared = $this->instance->prepare($this->build());

        return $prepared->execute($this->values);
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function update($data) {
        $this->action = 'UPDATE';

        foreach ($data as $key => $value) {
            $this->updates[] = [
                'key'   => $key,
                'value' => $value
            ];
        }

        $this->prepared = $prepared = $this->instance->prepare($this->build());

        return $prepared->execute($this->values);
    }

    /**
     * @return bool
     */
    public function delete() {
        $this->action = 'DELETE';
        return $this->instance->exec($this->build());
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
        $this->joins[] = ltrim("$type JOIN $table ON $key $operator $value");

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
     * @param bool   $isRawValue
     *
     * @return QueryBuilder
     */
    public function where($where, $comparator = '=', $isOr = false, $excludeValue = false, $isRawValue = false) {
        if (!in_array($comparator, $this->safeOperators)) return $this;

        $isFirst = $isOr ? true : empty($this->where);

        foreach ($where as $key => $value) {
            $this->where[] = [
                'statement'  => ($isFirst ? ($isOr ? 'OR' : 'WHERE') : 'AND'),
                'key'        => $key,
                'comparator' => $comparator,
                'value'      => $excludeValue ? null : $value,
                'raw'        => $isRawValue
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
     * @param string $key
     * @param array  $values
     *
     * @return QueryBuilder
     */
    public function whereIn(string $key, array $values) {
        $this->where([$key => '(' . implode(', ', $values) . ')'], 'IN', false, false, true);
        return $this;
    }

    /**
     * @param array $columns
     *
     * @return QueryBuilder
     */
    public function groupBy(array $columns) {
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
    public function orderBy(array $columns, string $order = 'DESC') {
        foreach ($columns as $column) {
            if (!in_array($column, $this->orderBy)) $this->orderBy[] = "$column $order";
        }

        return $this;
    }

    /**
     * @param int      $perPage
     * @param int|null $page
     *
     * @return QueryBuilder
     */
    public function limit(int $perPage, int $page = null) {
        $this->limit = !is_null($page) ? (max($page - 1, 0) * $perPage) . ",$perPage" : $perPage;
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
     * @param string $column
     *
     * @return int
     */
    public function count(string $column = '*') {
        $this->action = "SELECT COUNT($column) FROM";

        $prepared = $this->instance->prepare($this->build());

        $this->prepared = $prepared->execute($this->values);

        $result = $prepared->fetch(PDO::FETCH_COLUMN);

        return $result;
    }

    /**
     * @return array
     */
    public function getValues() {
        return $this->values;
    }

    /**
     * @return void
     */
    public function clearValues() {
        $this->values = [];
    }

    /**
     * @return PDOStatement
     */
    public function toString()
    {
        return $this->prepared;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}