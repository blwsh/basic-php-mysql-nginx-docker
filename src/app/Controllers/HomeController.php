<?php

namespace App\Controllers;

use function view;

class HomeController
{
    public function index() {
        return view('home.index', ['hello' => 'test']);
    }
}