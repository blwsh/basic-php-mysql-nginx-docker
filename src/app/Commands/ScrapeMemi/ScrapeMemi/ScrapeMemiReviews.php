<?php

namespace App\Commands\ScrapeMemi\ScrapeMemi;

use Goutte\Client;
use Framework\Traits\LogsToConsole;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeMemiReviews
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
     * ScrapeMemiReviews constructor.
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

    protected function handle()
    {
        $this->crawler->each(function(Crawler $node) {
            $this->result[] = [
                'name' => preg_split('/[A-Z]{2}/', $node->text())[0],
                'text' => preg_split('/[A-Z]{2}/', $node->text())[1]
            ];
        });
    }
}