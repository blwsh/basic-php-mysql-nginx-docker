<?php

namespace Framework\Database;

use Framework\App;
use Framework\Database\Connection;
use Framework\Database\QueryBuilder;
use JsonSerializable;

/**
 * Class Model (DAO)
 *
 * This model acts as a Data Access Object (DAO). Static methods such as get, create
 * and delete may be called on classes which extend this one. Each model has a
 * table property which must be set as it determines what table should be used
 * for the particular data access object.
 *
 * Setting what table a model uses is usually done by extending and overriding
 * the protected $table property but can also be passed in to the class upon
 * construction.
 *
 * @package Framework
 */
class Model implements JsonSerializable {
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var QueryBuilder
     */
    protected $builder;

    /**
     * @var string|null
     */
    protected $table;

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    public $attributes = [];

    /**
     * @var array
     */
    protected $hidden = [];

    /**
     * @var array
     */
    public $guard = ['password', 'custpassword'];

    /**
     * Model constructor.
     *
     * @param null $table
     */
    public function __construct($table = null) {
        //  Set table for model
        $this->table = $table ? $table : $this->table ? $this->table : $table = strtolower(get_called_class());

        // Create connection
        $this->connection = App::getInstance()->getConnection();

        // Instantiate builder for the model.
        $this->builder = new QueryBuilder($this->connection, $this->table, $this);
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function __get($name) {
        return $this->attributes[$name] ?? null;
    }

    /**
     * @param $data
     */
    public function fill($data) {
        foreach ($data as $key => $value) {
            $this->attributes[$key] = $value;
        }
    }

    /**
     * @return string
     */
    public function getPrimaryKey(): string {
        return $this->primaryKey ?? 'id';
    }

    /**
     * @param array $data
     *
     * @return Model[]
     */
    public static function fillArray(array $data) {
        return array_map(function($data) {
            $model = new static;
            $model->fill($data);
            return $model;
        }, $data);
    }

    /**
     * @param array|string $select
     *
     * @return QueryBuilder
     */
    public static function select($select) {
        return self::query()->select($select);
    }

    /**
     * @param        $data
     * @param string $comparator
     *
     * @return QueryBuilder
     */
    public static function where($data,  $comparator = '=') {
        return self::query()->where($data, $comparator);
    }

    /**
     * @param      $table
     * @param      $key
     * @param      $operator
     * @param      $value
     * @param null $type
     *
     * @return QueryBuilder
     */
    public static function join($table, $key, $operator, $value, $type = null) {
        return self::query()->join($table, $key, $operator, $value, $type = null);
    }

    /**
     * @param       $key
     * @param array $values
     *
     * @return QueryBuilder
     */
    public static function whereIn($key,  array $values) {
        return self::query()->whereIn($key, $values);
    }

    /**
     * @return QueryBuilder
     */
    public static function query() {
        return (new static)->builder;
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function update($data) {
        $this->builder->clearValues();
        return $this->builder->update($data);
    }

    /**
     * @return bool
     */
    public function delete() {
        if ($id = $this->attributes[$this->primaryKey]) {
            return self::query()->where([$this->primaryKey => $id])->delete();
        }
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public static function insert($data) {
        return self::query()->insert($data);
    }

    /**
     * @return Model|Model[]
     */
    public static function get() {
        return self::query()->get();
    }

    /**
     * @return Model
     */
    public static function first() {
        return self::query()->first();
    }

    /**
     * @param int $id
     *
     * @return Model
     */
    public static function find(int $id) {
        return self::query()->find($id);
    }

    /**
     * @param string $column
     *
     * @return int
     */
    public static function count(string $column = '*') {
        return self::query()->count($column);
    }

    /**
     * @param array $data
     *
     * @return Model
     */
    public static function create(array $data) {
        $model = new static;

        if (self::query()->insert($data)) {
            $model->fill($data);
        }

        return $model;
    }

    /**
     * @param int      $perPage
     * @param int|null $page
     *
     * @return QueryBuilder
     */
    public static function limit(int $perPage, int $page =  null) {
        return self::query()->limit($perPage, $page);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link  https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return array_diff_key($this->attributes, array_flip($this->hidden) ?? []);
    }

    public function __debugInfo()
    {
        $array = (array) $this;

        $array['attributes'] = array_diff_key(
            (array) $this->attributes,
            array_flip($this->guard)
        );

        return $array;
    }
}