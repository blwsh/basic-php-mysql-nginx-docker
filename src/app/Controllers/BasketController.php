<?php

namespace App\Controllers;

use App\Models\Film;
use Framework\Controller;

class BasketController extends Controller
{
    public function get() {
        $query = Film::query();
        return $query->get();
    }
}