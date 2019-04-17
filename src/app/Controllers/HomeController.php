<?php

namespace App\Controllers;

use Framework\App;

class HomeController
{
    public function index() {
        dd(App::getInstance()->getRouter());
    }
}