<?php

namespace App\Commands;

use Goutte\Client;
use Framework\Command;
use Symfony\Component\DomCrawler\Crawler;
use App\Commands\ScrapeMemi\ScrapeMemiProduct;

class ScrapeMemi extends Command
{
    /**
     * @return mixed
     */
    public function handle()
    {
        $client = new Client();

        $crawler = $client->request('get', 'https://www.memimakeup.com');

        $crawler
            ->filter('body > div.wrapper > div > div.header-container.header-color.color > header > div > div > div.header_menu.p-2.order-2.hidden-xs.hidden-sm > div.magicmenu.clearfix > ul > li.level0.cat.hasChild > div > div a')
            ->each(function(Crawler $node) use ($client) {
                $this->info("Handling {$node->text()}");
                $crawler = $client->click($node->link());

                $crawler->filter('ul.product-grid .item')->each(function(Crawler $node) use ($client) {
                    $node = $client->click($node->filter('.product-image')->link());
                    $this->info((new ScrapeMemiProduct($client, $node))->result);
                });
            });
    }
}