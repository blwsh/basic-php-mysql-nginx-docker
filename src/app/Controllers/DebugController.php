<?php

namespace App\Controllers;

use Framework\Controller;

class DebugController extends Controller
{
    public function toggle() {
        $_SESSION['debug'] = isset($_SESSION['debug']) && $_SESSION['debug'] ? false : true;
        return ['debug' => ['enabled' => $_SESSION['debug']]];
    }
}