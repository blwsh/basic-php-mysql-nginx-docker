<?php

namespace App\Commands\ScrapeMemi;

use Goutte\Client;
use Framework\Traits\LogsToConsole;
use function is_string;
use Symfony\Component\DomCrawler\Crawler;
use App\Commands\ScrapeMemi\ScrapeMemi\ScrapeMemiReviews;

/**
 * Class ScrapeMemiProduct
 * @package App\Commands\ScrapeMemi
 */
class ScrapeMemiProduct
{
    use LogsToConsole;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Crawler
     */
    protected $crawler;

    /**
     * @var array
     */
    public $result = [];

    /**
     * ScrapeMemiProduct constructor.
     *
     * @param Client  $client
     * @param Crawler $crawler
     */
    public function __construct(Client $client, Crawler $crawler)
    {
        $this->client = $client;
        $this->crawler = $crawler;
        $this->handle();
    }

    protected function handle() {
        foreach ([
            'title' => '.product_title',
            'type' => '.product_type',
            'price' => '.price-box .old-price',
            'special_price' => '.price-box .special-price',
            'description' => ['selector' => '.product_content .std' , 'raw' => true],
            'rating_count' => '.ratings text'
        ] as $key => $selector) {
            $raw = false;
            if (is_array($selector)) {
                $selector = $selector['selector'];
                $raw = isset($selector['raw']) ? $selector['raw'] : false;
            }

            if ($this->crawler->filter($selector)->count()) {
                $this->result[$key] = $result = preg_replace(
                    '/[\x00-\x1F\x80-\xFF]/',
                    '',
                    $this->crawler->filter($selector)->{$raw ? "html" : "text"}()
                );
            }
        }

        $this->result['reviews'] = (
            new ScrapeMemiReviews($this->client, $this->crawler->filter('.box-reviews li dd'))
        )->result ?? [];

        // Trim the text
        array_map(function($item) { return is_string($item) ? trim($item, " ") : $item; }, $this->result);

        // Extract numeric values from strings
        foreach (['price', 'special_price', 'rating'] as $key) {
            preg_match('/\d+(\.\d{1,2})*/', $this->result[$key], $match);

            if ($match) {
                $this->result[$key] = $match[0];
            }
        }
    }
}