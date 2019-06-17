<?php

namespace App\Models;

use Exception;
use Framework\Model;

/**
 * Class BasketItem
 * @package App\Models
 */
class BasketItem extends Model {
    /**
     * @varx
     */
    public $quantity;

    /**
     * @var Model|Film
     */
    public $item;

    /**
     * BasketItem constructor.
     *
     * @param $item
     */
    public function __construct(Model $item = null) {
        parent::__construct();
        $this->item = $item;

        $this->fill(['item' => $item]);
    }

    /**
     * @return Model|Model[]|void
     * @throws Exception
     */
    public static function get()
    {
        throw new Exception('This class does not support the get method.');
    }

    /**
     * Sets the quantity of this BasketItem
     *
     * @param int $quantity
     */
    public function setQuantity(int $quantity) {
        if ($this->validateQuantity($quantity)) {
            $this->quantity = $quantity;
            $this->fill(['quantity' => $this->quantity]);
        }
    }

    /**
     * Increases the quantity of this basket by the amount specified in the method
     * parameter.
     *
     * @param int $quantity
     */
    public function increaseQuantity(int $quantity) {
        if ($this->validateQuantity($quantity)) {
            $this->quantity++;

            $this->fill(['quantity' => $this->quantity]);
        }
    }

    /**
     * Decreases the quantity of this basket by the amount specified in the method
     * parameter.
     *
     * @param $quantity
     */
    public function decreaseQuantity($quantity) {
        if ($this->validateQuantity($quantity)) {
            $this->quantity++;

            $this->fill(['quantity' => $this->quantity]);
        }
    }

    /**
     * Checks to see if the quantity being set is within the range 0 to 100.
     *
     * @param int $quantity
     *
     * @return bool
     */
    protected function validateQuantity(int $quantity) { return $quantity > 0 && $quantity < 1000; }
}