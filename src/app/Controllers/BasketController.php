<?php

namespace App\Controllers;

use App\Models\BasketItem;
use App\Models\Film;
use Framework\Request;
use Framework\Controller;


/**
 * Class BasketController
 * @package App\Controllers
 */
class BasketController extends Controller
{
    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function get(Request $request) {
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
     * @param Request $request
     */
    public function add(Request $request) {
        $filmId = $request->get('filmid');

        if ($_SESSION['basket']) {
            if ($this->validate($_SESSION['basket'][$filmId] + 1)) {
                $_SESSION['basket'][$filmId]++;
            }
        } else if (is_int((int) $filmId) && $filmId) {
            $_SESSION['basket'][$filmId] = 1;
        }

        back();
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request) {
        $filmId = $request->get('filmid');

        if ($_SESSION['basket']) {
            if ($this->validate($_SESSION['basket'][$filmId] - 1)) {
                $_SESSION['basket'][$filmId]--;
            }

            if ($_SESSION['basket'][$filmId] == 0) {
                unset($_SESSION['basket'][$filmId]);
            }
        }

        back();
    }

    /**
     * @param int $int
     *
     * @return bool
     */
    public function validate(int $int) {
        return $int >= 0 && $int <= 100;
    }
}