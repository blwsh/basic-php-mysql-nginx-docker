<?php

namespace App\Controllers;

use function abort;
use App\Models\Film;
use Framework\Controller;
use Framework\Request;

class FilmsController extends Controller
{
    public function index() {
        return view('films.index', ['films' => Film::get()]);
    }

    public function view(Request $request) {
        if ($film = Film::find($request->get('filmid'))) {
            return view('films.view', ['film' => $film, 'rating' => rand(1, 5)]);
        }

        abort();
    }

    public function create() {
        return view('films.create');
    }
}