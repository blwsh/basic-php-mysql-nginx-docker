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
     * @var PDO
     */
    protected $instance;

    /**
     * @var string|null
     */
    protected $table;

    /**
     * @var
     */
    protected $fields;

    /**
     * Model constructor.
     *
     * @param null $table
     */
    public function __construct($table = null) {
        // Create connection
        $this->connection = new Connection('magento', 'db', 'root', 'root');

        $this->instance = $this->connection->getInstance();

        //  Set table for model
        $this->table = $table ? $table : $table = strtolower(get_called_class());
    }

    private function getConnectionTable() {
        return $this->connection->getName() . '.' . $this->table;
    }

    /**
     *
     */
    public function insert() {

    }

    /**
     *
     */
    public function update() {

    }

    /**
     *
     */
    public function delete() {

    }

    /**
     *
     */
    public function get() {
        return $this->instance->query("SELECT * FROM " . $this->getConnectionTable());
    }

    /**
     *
     */
    public function create() {

    }
}