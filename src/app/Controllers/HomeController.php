<?php

namespace App\Controllers;

use App\Models\Film;

class HomeController
{
    public function index() {
        dd((new Film())->get());
    }
}