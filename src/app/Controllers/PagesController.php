<?php

namespace App\Controllers;

use App\Models\Shop;
use Framework\Http\Controller;

/**
 * Class PagesController
 * @package App\Controllers
 */
class PagesController extends Controller
{
    /**
     * @return \Framework\Http\View
     */
    public function about() {
        return view('pages.about');
    }

    /**
     * @return \Framework\Http\View
     */
    public function shops() {
        return view('pages.shop', ['shops' => Shop::get()]);
    }
}