<?php

namespace Framework;

abstract class Validator
{
    /**
     * @var
     */
    protected $errors;

    /**
     * @var
     */
    protected $data;

    /**
     * @var array
     */
    protected $fields;

    /**
     * ValidatePurchaseRequest constructor.
     *
     * @param $data
     */
    public function __construct($data) {
        $this->data = $data;
        $this->handle();
    }

    /**
     * The method called when checking data.
     *
     * @return void
     */
    public abstract function handle();

    /**
     * @return mixed
     */
    public function errors() {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function hasErrors() : bool {
        return count($this->errors) > 0;
    }

    /**
     * @param $message
     */
    protected function error($message) {
        $this->errors[] = $message;
    }
}