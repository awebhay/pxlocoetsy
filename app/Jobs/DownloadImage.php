<?php

namespace App\Jobs;

use App\LinkUrl;
use App\PageUrl;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Util;

class DownloadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $img_num;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($img_num)
    {
        $this->img_num = $img_num;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $urls = PageUrl::where('is_crawled', 0)->get();
        foreach ($urls as $url) {
            $html = $this->getPageDetails($url->page_url);
            $name = trim($html->find('[data-buy-box-listing-title="true"]')[0]->innertext());
            $a = $html->find("[data-carousel-pane-list] li");
            foreach ($a as $k => $v) {
                if ($k < (int)$this->img_num) {

                    if(strlen($name > 230)) {
                        $name = $str = substr($name, 0, 230);
                    }

                    if(!isset($v->find('img')[0])) {
                        // Xử lý khi gặp phải video mà không phải hình
                        continue;
                    }

                    if ($v->find('img')[0]->getAttribute('src')) {
                        $src = $v->find('img')[0]->getAttribute('src');
                    } else {
                        $src = $v->find('img')[0]->getAttribute('data-src-zoom-image');
                    }

                    try {
                        $contents = file_get_contents($src);
                        $path = str_replace("|","-", Util::normalizePath($this->formatFileName($name) . '.jpg'));
                        Storage::put($path, $contents);
                    } catch (\Exception $exception) {
                        continue;
                    }
                } else {
                    break;
                }
                $link = new LinkUrl();
                $link->page_url = $src;
                $link->path = $path;
                $link->is_crawled = 0;
                $link->save();
            }
            $url->is_crawled = 1;
            $url->save();
        }
    }

    public function formatFileName($name)
    {
        $i = 1;
        $name = trim($name);

        while ($this->isFileNameExits($name)) {
            $val = 1;
            $end_number = '0001';
            while($this->isFileNameExits($name . '_' . $end_number)) {
                $val++;
                $end_number = str_pad($val,4,"0",STR_PAD_LEFT); // 0001
            }
            $actual_name = (string)$name . '_' . (string)$end_number;
            $name = trim($actual_name);
            $i++;
        }

        return str_replace('/','',$name);
    }

    public function isFileNameExits($name) {
        if(Storage::disk('local')->has($name . '.jpg')) {
           return true;
        }
        return false;
    }

    public function nextPage($element)
    {
        $next_page_url = '[data-appears-component-name="search_pagination"] div > div:first-child > .search-pagination:last-child li:last-child a';
    }

    public function lastPageNum($element)
    {
        return $element->find('[data-appears-component-name="search_pagination"] div > div:first-child > .search-pagination:last-child li:last-child a span:last-child');
    }

    public function getPageDetails($element)
    {
        $context = stream_context_create(array(
            'http' => array(
                'header' => array('User-Agent: Mozilla/4.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201'),
            ),
        ));
        return file_get_html($element, false, $context);
    }
}
