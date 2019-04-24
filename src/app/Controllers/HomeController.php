<?php

namespace App\Controllers;

use App\Models\Film;

class HomeController
{
    public function index() {
        return view('home.index', [
            'films' => Film::limit(12)->get(),
            'classics' => Film::limit(4)->get()
        ]);
    }
}