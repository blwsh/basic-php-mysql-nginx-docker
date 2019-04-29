<?php

namespace App\Controllers;

use App\Models\Film;

/**
 * Class HomeController
 * @package App\Controllers
 */
class HomeController
{
    /**
     * @return \Framework\View
     */
    public function index() {
        return view('home.index', [
            'films' => Film::limit(12)->get(),
            'classics' => Film::limit(4)->get()
        ]);
    }
}