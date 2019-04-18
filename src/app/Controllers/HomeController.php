<?php

namespace App\Controllers;

class HomeController
{
    public function index() {
        dd(app()->getRouter());
    }
}