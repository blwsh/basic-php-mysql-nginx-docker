<?php

namespace App\Controllers;

use App\Models\Film;
use Framework\Controller;
use Framework\Request;

class FilmsController extends Controller
{
    public function index() {
        return view('films.index', ['films' => Film::get()]);
    }

    public function view(Request $request) {
        if ($film = Film
            ::join('fss_Rating', 'fss_Film.ratid', '=', 'fss_Rating.ratid')
            ->where(['filmid' => $request->get('filmid')])
            ->first()
        ) {
            $related = Film::limit(3)->orderBy(['RAND()'])->get();

            return view('films.view', ['film' => $film, 'related' => $related, 'rating' => rand(1, 5)]);
        }

        abort();
    }

    public function create() {
        return view('films.create');
    }
}