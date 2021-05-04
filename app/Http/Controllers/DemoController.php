<?php

namespace App\Http\Controllers;

include( 'simple_html_dom.php');

use App\Jobs\DownloadImage;
use App\Jobs\ProcessPageUrl;
use App\LinkUrl;
use App\PageUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DemoController extends Controller
{
    public function getLinks(Request $request) {
        set_time_limit(0);
        $is_shop_page = true;
        $url = $request->url;
        if(strpos($url, 'shop') == false) {
            $is_shop_page = false;
        }

        $from_page = $request->from_page;
        if(!$request->from_page) {
            $from_page = 1;
        }

        $to_page = $request->to_page;
        if(!$request->to_page) {
            $to_page = 1;
        }

        $img_num = $request->img_num;
        if(!$request->img_num) {
            $img_num = 1;
        }

        //Drop DB
        $this->truncateDB();

        // Lấy hết link của từng bài post
        ProcessPageUrl::dispatch($url, $from_page, $to_page, $is_shop_page);

        //Duyệt qua link từng bài lấy hình theo số lượng img_num
        DownloadImage::dispatch($img_num)->delay(now()->addMinutes(1));

        return redirect("/")->with('status', 'Download thành công!!!!');
    }

    public function truncateDB() {
        PageUrl::truncate();
        LinkUrl::truncate();
        return redirect("/")->with('status', 'Đã xóa hết DB <3 !!!!');
    }
}
