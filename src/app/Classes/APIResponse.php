<?php

namespace App\Classes;

/**
 * Class APIResponse
 * @package App\Classes
 */
class APIResponse
{
    /**
     * APIResponse constructor.
     *
     * @param $data
     * @param $errors
     * @param $currentPage
     * @param $lastPage
     *
     * @return array
     */
    public static function send($data, $errors = null, int $currentPage = 1, int $lastPage = null)
    {
        $lastPage = $lastPage ?? 1;

        return [
            'links' => [
                'self' => $currentPage,
                'next' => min($currentPage + 1, $lastPage),
                'last' => $lastPage
            ],
            'data' => $data,
            'errors' => $errors
        ];
    }
}