<?php

namespace Framework;

/**
 * Class Model
 */
class Model {
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var \PDO
     */
    protected $instance;

    /**
     * @var QueryBuilder
     */
    protected $builder;

    /**
     * @var string|null
     */
    protected $table;

    /**
     * @var
     */

    protected $fields;
    /**
     * @var
     */
    public $attributes;

    /**
     * Model constructor.
     *
     * @param null $table
     */
    public function __construct($table = null) {
        // Create connection
        $this->connection = new Connection('magento', 'db', 'root', 'root');

        // Set the instance to point to the connection instance.
        $this->instance = &$this->connection->getInstance();

        // Instantiate builder for the model.
        $this->builder = new QueryBuilder($this->connection, $this->table);

        //  Set table for model
        $this->table = $table ? $table : $table = strtolower(get_called_class());
    }

    /**
     * @return string
     */
    private function getConnectionTable() {
        return $this->connection->getName() . '.' . $this->table;
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
        if ($id = $this->attributes->id) {
            return $this->builder->where(['id' => $id])->delete();
        }
    }

    /**
     * @return Model|Model[]
     */
    public function get() {
        return $this->builder->get();
    }

    /**
     *
     */
    public function find($id) {
        $this->builder->where(['id', $id])->first();
    }

    /**
     * @param array $data
     */
    public function create(array $data) {
        $this->builder->insert($data);
    }
}