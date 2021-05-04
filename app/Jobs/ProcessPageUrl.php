<?php

namespace App\Jobs;

use App\PageUrl;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class ProcessPageUrl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
    protected $from_page;
    protected $to_page;
    protected $is_shop;

    /**
     * ProcessPageUrl constructor.
     * @param $url
     * @param $from_page
     * @param $to_page
     * @param $is_shop
     */
    public function __construct($url, $from_page, $to_page, $is_shop)
    {
        $this->url = $url;
        $this->from_page = $from_page;
        $this->to_page = $to_page;
        $this->is_shop = $is_shop;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(is_null($this->from_page)) {
           $this->from_page = 1;
        }

        if(is_null($this->to_page)) {
            $this->to_page = 1;
        }

        $from = (int)$this->from_page;
        $to = (int)$this->to_page;
        while ($from <= $to) {
            if ($this->is_shop) {
                // Redirect to shop page
                $url = $this->url . "?page=" . $from;
                $selector_path = 'div[data-listings-container] > div > div a';

            } else {
                // Search
                $url = $this->url . "&page=" . $from;
                $selector_path = 'div[data-search-results-region] li a';
            }

            $client = new Client();
            $crawler = $client->request('GET', $url);
            $links = $crawler->filter($selector_path)->each(function ($node) use ($from) {
                $data['page_url'] = $node->attr('href');
                $data['is_crawled'] = 0;
                $data['page'] = $from;
                return $data;
            });
            PageUrl::insert($links);
            $from ++;
        }
    }
}
