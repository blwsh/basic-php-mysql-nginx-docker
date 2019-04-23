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
        return view('films.view', ['film' => Film::find($request->get('filmid')), 'rating' => rand(1, 5)]);
    }

    public function create() {
        return view('films.create');
    }
}