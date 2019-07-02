<?php

namespace App\Controllers;

use Framework\Http\Controller;

/**
 * Class DebugController
 * @package App\Controllers
 */
class DebugController extends Controller
{
    /**
     * @return array
     */
    public function toggle() {
        $_SESSION['debug'] = isset($_SESSION['debug']) && $_SESSION['debug'] ? false : true;
        return ['debug' => ['enabled' => $_SESSION['debug']]];
    }
}