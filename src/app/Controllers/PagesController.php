<?php

namespace App\Controllers;

use Framework\Controller;
use function view;

class PagesController extends Controller
{
    public function about() {
        return view('pages.about');
    }
}