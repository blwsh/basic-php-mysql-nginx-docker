<?php

namespace Framework;

use ReflectionClass;
use ReflectionException;

/**
 * Class Model
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
        // Create connection
        $this->connection = new Connection('magento', 'db', 'root', 'root');

        // Instantiate builder for the model.
        $this->builder = new QueryBuilder($this->connection, $this->table);

        //  Set table for model
        $this->table = $table ? $table : $this->table ? $this->table : $table = strtolower(get_called_class());
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
     * @return string
     */
    private function getConnectionTable() {
        return $this->connection->getName() . '.' . $this->table;
    }

    /**
     * @param $data
     */
    public function fill($data) {

        foreach ($data as $key => $value) {
            $this->attributes[$key] = $value;
        }
    }

    public function where($data,  $comparator = '=') {
        return $this->builder->where($data, $comparator);
    }

    /**
     * @return Object|Model
     */
    public static function query() {
        try {
            return (new ReflectionClass(get_called_class()))->newInstance();
        } catch (ReflectionException $e) {
            // Do nothing.
        }
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function insert($data) {
        return $this->builder->insert($data);
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
     * @return Model|Model[]
     */
    public function get() {
        $results = array_map(function($data) {
            $model = self::query();
            $model->fill($data);
            return $model;
        }, self::query()->builder->get());

        return $results;
    }

    /**
     * @return Model
     */
    public function first() {
        $data = $this->builder->first();

        $this->fill($data);

        return $this;
    }

    /**
     * @param $id
     *
     * @return Model
     */
    public function find($id) {
        $data = $this->builder->where(['id', $id])->first();

        $this->fill($data);

        return $this;
    }

    /**
     * @param array $data
     *
     * @return Model
     */
    public function create(array $data) {
        $model = self::query();

        if ($model->insert($data)) {
            $model->fill($data);
        }

        return $model;
    }
}