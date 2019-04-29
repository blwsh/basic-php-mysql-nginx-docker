<?php

namespace App\Controllers;

use App\Models\Film;
use Framework\Controller;
use Framework\Request;

/**
 * Class FilmsController
 * @package App\Controllers
 */
class FilmsController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Framework\View
     */
    public function index(Request $request) {
        $page = (int) $request->get('page');
        $perPage = 15;

        return view('films.index', [
            'films' => Film::limit($perPage, $page)->get(),
            'count' => Film::count(),
            'perPage' => $perPage,
            'page' => $page
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Framework\View
     */
    public function view(Request $request) {
        if ($film = Film
            ::join('fss_Rating', 'fss_Film.ratid', '=', 'fss_Rating.ratid')
            ->join('fss_DVDStock', 'fss_Film.filmid', '=', 'fss_DVDStock.filmid')
            ->where(['fss_Film.filmid' => $request->get('filmid')])
            ->first()
        ) {
            $related = Film::limit(3)->orderBy(['RAND()'])->get();

            return view('films.view', ['film' => $film, 'related' => $related, 'rating' => rand(1, 5)]);
        }

        abort();
    }

    /**
     * @return \Framework\View
     */
    public function create() {
        return view('films.create');
    }
}