<?php

namespace App\Models;

use function config;
use Framework\Traits\Singleton;

/**
 * Class Basket
 * This class uses the singleton design pattern. This means there is only one basket
 * that can be accessed and modified through the app.
 */
class Basket {
    use Singleton;

    /**
     * @var BasketItem[]
     */
    protected static $items;

    /**
     * Returns a list of basket items.
     * Basket items are objects which have a quantity property and items property.
     *
     * @return BasketItem[]|bool
     */
    public static function get() {
        $items = Basket::items();

        return [
            'count' => self::count(),
            'items' => $items,
        ];
    }

    /**
     * Returns a count of how many items are in the basket.
     *
     * @return int
     */
    public static function count() {
        return array_sum(array_map(function($item) { return $item->quantity; }, self::$items ?? self::items()));
    }

    /**
     * Works out the total cost of all items in the basket.
     *
     * @return float|int
     */
    public static function subtotal() {
        return array_sum(array_map(function($item) { return $item->quantity * config('base_price', 9.99); }, self::items()));
    }


    /**
     * Returns a list of all BasketItem's in this basket.
     *
     * @return BasketItem[]
     */
    public static function items() {
        $basketItems = array_keys($_SESSION['basket']);
        $itemCounts = $_SESSION['basket'];

        if ($basketItems) {
            return self::$items = array_map(function($film) use ($itemCounts) {
                $item = new BasketItem($film);
                $item->setQuantity($itemCounts[$film->filmid]);
                return $item;
            }, Film::whereIn('filmid', $basketItems)->get());
        } else {
            return [];
        }
    }

    /**
     * Adds an item to the basket.
     * Currently only adds films to the basket.
     *
     * @param int $filmId
     *
     * @return bool
     */
    public static function add(int $filmId) {
        if ($_SESSION['basket']) {
            if (self::validateAdd($_SESSION['basket'][$filmId]  + 1 ?? 0, $filmId)) {
                $_SESSION['basket'][$filmId]++;
                return true;
            }
        } else if (is_int((int) $filmId) && $filmId) {
            $_SESSION['basket'][$filmId] = 1;
            return true;
        }

        return false;
    }

    /**
     * Remove an item from the basket.
     * Currently only removes films to the basket.
     *
     * @param int $filmId
     *
     * @return bool
     */
    public static function remove(int $filmId ) {
        if ($_SESSION['basket']) {
            if (self::validateRemove($_SESSION['basket'][$filmId])) {
                $_SESSION['basket'][$filmId]--;
                return true;
            }

            if ($_SESSION['basket'][$filmId] == 0) {
                unset($_SESSION['basket'][$filmId]);
                return true;
            }
        }

        return false;
    }

    /**
     * Clears the basket.
     *
     * @return void
     */
    public static function clear() {
        unset($_SESSION['basket']);
    }

    /**
     * Called by the basket att method.
     *
     * @param int $int
     * @param int $filmid
     *
     * @return bool
     */
    public static function validateAdd(int $int, int $filmid) {
        return $int <= DVDStock::where(['filmid' => $filmid, 'shopid' => 1])->first()->stocklevel;

    }

    /**
     * Called by the Basket remove method.
     *
     * @param $int
     *
     * @return bool
     */
    public static function validateRemove($int) {
        return $int >= 0;
    }
}