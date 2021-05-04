<?php

namespace App\Console\Commands;

use App\LinkUrl;
use App\PageUrl;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Util;

class DownloadImg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:img';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download Image';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $urls = PageUrl::where('is_crawled', 0)->get();
        foreach ($urls as $url) {
            $html = $this->getPageDetails($url->page_url);
            $a = $html->find("[data-carousel-pane-list] li img");
            foreach ($a as $k => $v) {
                if ($k > $this->img_num) break;
                $name = $v->getAttribute('alt');
                if ($v->getAttribute('src')) {
                    $src = $v->getAttribute('src');
                } else {
                    $src = $v->getAttribute('data-src-zoom-image');
                }
                $contents = file_get_contents($src);
                $fileType = substr($src, strrpos($src, '/') + 1);
                $path = Util::normalizePath($name . '.' . $fileType);
                try {
                    Storage::put($path, $contents);
                } catch (\Exception $exception) {
                    continue;
                }

                $link = new LinkUrl();
                $link->page_url = $url;
                $link->is_crawled = 1;
                $link->save();

            }
            $url->is_crawled = 1;
            $url->save();
        }
        return 0;
    }

    public function nextPage($element) {
        $next_page_url = '[data-appears-component-name="search_pagination"] div > div:first-child > .search-pagination:last-child li:last-child a';
    }

    public function lastPageNum($element) {
        return $element->find('[data-appears-component-name="search_pagination"] div > div:first-child > .search-pagination:last-child li:last-child a span:last-child');
    }

    public function getPageDetails($element) {
        $context = stream_context_create(array(
            'http' => array(
                'header' => array('User-Agent: Mozilla/4.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201'),
            ),
        ));
        return file_get_html($element, false, $context);
    }
}
