<?php

namespace App\Controllers;

use App\Models\Shop;
use Framework\Controller;
use function view;

class PagesController extends Controller
{
    public function about() {
        return view('pages.about');
    }
    public function shops() {
        return view('pages.shop', ['shops' => Shop::get()]);
    }
}