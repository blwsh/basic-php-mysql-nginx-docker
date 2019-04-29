<?php

namespace App\Commands;

use Exception;
use App\Models\Film;
use Framework\Command;

/**
 * Class GetImages
 *
 * Gets images from themoviedb.org.
 *
 * Uses the column filmtitle from the table fss_Films table to search the API
 * for an image. If no results are found, a placeholder image is used instead.
 *
 * Usage: php command GetImages
 *
 * @package App\Commands
 */
class GetImages extends Command
{
    /**
     * @return mixed|void
     */
    public function handle()
    {
        // Setup
        $apiVersion = 3;
        $apiKey     = config('themoviedb_key');

        // Get films
        foreach (array_map(function($model) { return $model->filmtitle; }, Film::get()) as $filmName) {
            $this->info("Getting $filmName");

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