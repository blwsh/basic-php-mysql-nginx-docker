<?php

namespace App\Controllers;

use App\Models\Film;
use Framework\Cache\FilesystemCache;
use Framework\Http\Controller;
use Framework\Http\Request;

/**
 * Class FilmsController
 * @package App\Controllers
 */
class FilmsController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Framework\Http\View
     * @throws \Exception
     */
    public function index(Request $request) {
        $page = (int) $request->get('page');
        $perPage = 15;

        return view('films.index', [
            'films' => FilesystemCache::get('films.index') ?? FilesystemCache::put('films.index', Film::limit($perPage, $page)->get()),
            'count' => FilesystemCache::get('films.index.count') ?? FilesystemCache::put('films.index.count', Film::count()),
            'perPage' => $perPage,
            'page' => $page
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Framework\Http\View
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
     * @return \Framework\Http\View
     */
    public function create() {
        return view('films.create');
    }
}