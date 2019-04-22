<?php

namespace App\Commands;

use Exception;
use Framework\Command;

class GetImages extends Command
{
    public function handle()
    {
        // Setup
        $apiVersion = 3;
        $apiKey     = config('themoviedb_key');

        // Get films
        foreach (array_map(function($model) { return $model->filmtitle; }, \App\Models\Film::get()) as $filmName) {
            $this->info("Getting $filmName");

            dd("https://api.themoviedb.org/$apiVersion/search/movie?query=$filmName&api_key=$apiKey");

            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://api.themoviedb.org/$apiVersion/search/movie?query=$filmName&api_key=$apiKey");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);

                $response = json_decode(curl_exec($ch));

                file_put_contents(
                    'public/assets/img/films/' . slug($filmName) . '.jpg',
                    file_get_contents($response && $response->results[0]->poster_path ? 'http://image.tmdb.org/t/p/w300/' . $response->results[0]->poster_path : 'http://placehold.jp/300x450.png')
                );
            } catch (Exception $exception) {
                $this->error($exception->getMessage());
            }
        }

        curl_close($ch);


    }
}