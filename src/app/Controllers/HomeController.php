<?php

namespace App\Controllers;

use App\Models\Film;
use function get_class;
use function gettype;

class HomeController
{
    public function index() {
        $films = Film::get();

        dd([
            gettype($films),
            get_class($films[0]),
            $films[0]->Name
        ]);
    }
}