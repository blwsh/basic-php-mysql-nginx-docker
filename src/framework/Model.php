<?php

namespace Framework;

/**
 * Class Model
 * This model acts as a Data Access Object. Static methods such as get, create
 * and delete may be called on classes which extend this one. Each model has a
 * table property which must be set as it determines what table should be used
 * for the particular data access object.
 *
 * Setting what table a model uses is usually done by extending and overriding
 * the protected $table property but can also be passed in to the class upon
 * construction.
 */
class Model {
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
     * @var
     */
    public  $attributes = [];

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
     * @param        $data
     * @param string $comparator
     *
     * @return QueryBuilder
     */
    public static function where($data,  $comparator = '=') {
        return self::query()->where($data, $comparator);
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
        return $this->builder->update($data);
    }

    /**
     * @return bool
     */
    public function delete() {
        if ($id = $this->attributes->{$this->primaryKey}) {
            return $this->builder->where(['id' => $id])->delete();
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
     * @param $id
     *
     * @return Model
     */
    public static function find($id) {
        $builder = self::query();
        return self::query()->where([$builder->model->primaryKey ?? 'id' => $id])->first();
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
}