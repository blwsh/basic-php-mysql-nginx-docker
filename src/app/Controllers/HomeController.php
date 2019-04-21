<?php

namespace App\Controllers;

use App\Models\Film;

class HomeController
{
    public function index() {
        $films = Film::get();

        Film::

        dd([
            gettype($films),
            get_class($films[0]),
            $films[0]->Name
        ]);
    }
}