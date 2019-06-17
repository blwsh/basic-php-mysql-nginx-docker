<?php

namespace App\Controllers;

use App\Models\Shop;
use Framework\Controller;

/**
 * Class PagesController
 * @package App\Controllers
 */
class PagesController extends Controller
{
    /**
     * @return \Framework\View
     */
    public function about() {
        return view('pages.about');
    }

    /**
     * @return \Framework\View
     */
    public function shops() {
        return view('pages.shop', ['shops' => Shop::get()]);
    }
}