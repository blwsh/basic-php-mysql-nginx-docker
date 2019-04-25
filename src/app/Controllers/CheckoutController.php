<?php

namespace App\Controllers;

use App\Models\Basket;
use Framework\Controller;

/**
 * Class CheckoutController
 * @package App\Controllers
 */
class CheckoutController extends Controller
{
    /**
     * @return \Framework\View
     */
    public function overview() {
        return view('checkout.overview', ['items' => Basket::items(), 'subtotal' => Basket::subtotal()]);
    }

    /**
     * @return \Framework\View
     */
    public function complete() {
        return view('checkout.complete', ['items' => Basket::items(), 'subtotal' => Basket::subtotal()]);
    }
}