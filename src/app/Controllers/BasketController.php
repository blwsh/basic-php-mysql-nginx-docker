<?php

namespace App\Controllers;

use App\Models\Basket;
use App\Models\BasketItem;
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
        return Basket::get();
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function add(Request $request) {
        back(302, Basket::add($request->get('filmid')) ? null : ['errors' => ['Out of stock.']]);
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

    /**
     * @return void
     */
    public function clear() {
        Basket::clear();
        back();
    }
}