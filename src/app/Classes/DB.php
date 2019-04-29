<?php

namespace App\Classes;

/**
 * A simple helper class that provides static methods for calling transaction
 * methods on the app database connection.
 *
 * Class DB
 * @package App\Classes
 */
class DB
{
    /**
     * @param string|null $name
     *
     * @return string
     */
    public static function lastInsertId(string $name = null) {
        return app()->getConnection()->getInstance()->lastInsertId($name);
    }

    /**
     * Begins a database transaction.s
     */
    public static function beginTransaction() {
        app()->getConnection()->getInstance()->beginTransaction();
    }

    /**
     * Rollback a database transaction.
     */
    public static function rollback() {
        return app()->getConnection()->getInstance()->rollBack();
    }

    /**
     * Commit a database transaction.
     */
    public static function commit() {
        return app()->getConnection()->getInstance()->commit();

    }
}