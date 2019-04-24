<?php

namespace App\Models;

use Framework\Model;

/**
 * Class BasketItem
 * @package App\Models
 */
class BasketItem extends Model {
    /**
     * @var
     */
    public $quantity;

    /**
     * @var Model
     */
    public $item;

    /**
     * BasketItem constructor.
     *
     * @param $item
     */
    public function __construct(Model $item) {
        parent::__construct();
        $this->item = $item;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity) {
        if ($this->validateQuantity($quantity)) {
            $this->quantity = $quantity;
        }
    }

    /**
     * @param $quantity
     */
    public function increaseQuantity($quantity) {
        if ($this->validateQuantity($quantity)) {
            $this->quantity++;
        }
    }

    /**
     * @param $quantity
     */
    public function decreaseQuantity($quantity) {
        if ($this->validateQuantity($quantity)) {
            $this->quantity++;
        }
    }

    /**
     * @param int $quantity
     *
     * @return bool
     */
    protected function validateQuantity(int $quantity) { return $quantity > 0 && $quantity < 1000; }
}