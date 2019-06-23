<?php

namespace App\Controllers;

use App\Models\Film;
use Framework\Controller;

/**
 * Class HomeController
 * @package App\Controllers
 */
class HomeController extends Controller
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