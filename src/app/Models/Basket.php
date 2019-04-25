<?php

namespace App\Models;

/**
 * Class Basket
 */
class Basket {
    /**
     * @return array|bool
     */
    public static function items()
    {
        $basketItems = array_keys($_SESSION['basket']);
        $itemCounts = $_SESSION['basket'];

        if ($basketItems) {
            return array_map(function($film) use ($itemCounts) {
                $item = new BasketItem($film);
                $item->setQuantity($itemCounts[$film->filmid]);
                return $item;
            }, Film::whereIn('filmid', $basketItems)->get());
        } else {
            return false;
        }
    }

    /**
     * @param int $filmId
     */
    public static function add(int $filmId)
    {
        if ($_SESSION['basket']) {
            if (self::validate($_SESSION['basket'][$filmId] + 1)) {
                $_SESSION['basket'][$filmId]++;
            }
        } else if (is_int((int) $filmId) && $filmId) {
            $_SESSION['basket'][$filmId] = 1;
        }
    }

    /**
     * @param int $filmId
     */
    public static function remove(int $filmId )
    {
        if ($_SESSION['basket']) {
            if (self::validate($_SESSION['basket'][$filmId] - 1)) {
                $_SESSION['basket'][$filmId]--;
            }

            if ($_SESSION['basket'][$filmId] == 0) {
                unset($_SESSION['basket'][$filmId]);
            }
        }
    }

    public static function clear() {
        unset($_SESSION['basket']);
    }

    /**
     * @param int $int
     *
     * @return bool
     */
    public static function validate(int $int) {
        return $int >= 0 && $int <= DVDStock::where(['filmid' => $int, 'shopid' => 1])->first()->stocklevel;
    }

    public static function subtotal() {
        return array_sum(array_map(function($item) { return $item->quantity * 9.99; }, self::items()));
    }

}