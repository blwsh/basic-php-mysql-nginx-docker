<?php

namespace App\Controllers;

use App\Models\Basket;
use App\Models\BasketItem;
use function back;
use Framework\Request;
use Framework\Controller;


/**
 * Class BasketController
 * @package App\Controllers
 */
class BasketController extends Controller
{
    /**
     * @return BasketItem[]
     */
    public function get() {
        return Basket::items();
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function add(Request $request) {
        Basket::add($request->get('filmid'));
        back();
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function remove(Request $request) {
        Basket::remove($request->get('filmid'));
        back();
    }

    public function clear() {
        Basket::clear();
        back();
    }
}