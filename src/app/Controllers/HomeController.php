<?php

namespace App\Controllers;

use App\Models\Film;
use Framework\Cache;
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
            'films' => Cache::get('home.films') ?? Cache::put('home.films', Film::limit(12)->get()),
            'classics' => Cache::get('home.classics') ?? Cache::put('home.classics', Film::limit(4)->get())
        ]);
    }
}