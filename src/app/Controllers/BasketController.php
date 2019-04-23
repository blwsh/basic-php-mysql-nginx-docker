<?php

namespace App\Controllers;

use Framework\Controller;
use Framework\Request;

class BasketController extends Controller
{
    public function get(Request $request) {
        $basketItems = $request->session('basket.items');
        $counts = array_count_values($basketItems);

        return $counts;
    }

    public function add(Request $request) {
        $_SESSION['basket']['items'][] = $request->get('filmid');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}