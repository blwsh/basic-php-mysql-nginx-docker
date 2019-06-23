<?php

namespace framework\Util;

use function array_push;

/**
 * Class Collection
 * @package framework\Util
 */
class Collection
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * Collection constructor.
     *
     * @param $data
     */
    public function __construct(array $data) {
        $this->data = $data;
    }

    /**
     * @param null $key
     * @param null $default
     *
     * @return mixed
     */
    public function get($key = null, $default = null) {
        return Arr::get($this->data, $key, $default);
    }

    public function push(...$data) {

    }

    /**
     * @return self $this
     */
    public function clear() {
        $this->data = [];

        return $this;
    }
}