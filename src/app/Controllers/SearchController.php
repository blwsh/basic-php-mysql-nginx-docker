<?php

namespace App\Controllers;

use App\Models\Film;
use Framework\Database\Model;
use Framework\Http\Request;

/**
 * Class SearchController
 * @package App\Controllers
 */
class SearchController extends Model
{
    /**
     * @param Request $request
     *
     * @return array
     */
    public function search(Request $request) {
        $query = trim($request->get('q', ' '));

        if (strlen($query) > 3) {
            return Film::where(['filmtitle' => "%{$query}%"], 'LIKE')->limit(5)->get();
        }
    }
}