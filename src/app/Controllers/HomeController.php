<?php

namespace App\Controllers;

use App\Models\Film;
use Framework\Cache\FilesystemCache;
use Framework\Http\Controller;

/**
 * Class HomeController
 * @package App\Controllers
 */
class HomeController extends Controller
{
    /**
     * @return \Framework\Http\View
     */
    public function index() {
        return view('home.index', [
            'films' => FilesystemCache::get('home.films') ?? FilesystemCache::put('home.films', Film::limit(12)->get()),
            'classics' => FilesystemCache::get('home.classics') ?? FilesystemCache::put('home.classics', Film::limit(4)->get())
        ]);
    }
}