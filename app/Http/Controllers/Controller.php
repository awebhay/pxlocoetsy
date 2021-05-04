<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function demo() {
        $html = file_get_html("https://www.teepublic.com/");
        $html = str_get_html("<html><head><title>Cool HTML Parser</title></head><body><h2>PHP Simple HTML DOM Parser</h2><p>PHP Simple HTML DOM Parser is the best HTML DOM parser in any programming language.</p></body></html>");
        $data = file_get_contents("http://nimishprabhu.com"); //or you can use curl too, like me <img src="http://nimishprabhu.com/wp-content/plugins/lazy-load/images/1x1.trans.gif" data-lazy-src="http://nimishprabhu.com/wp-includes/images/smilies/simple-smile.png" alt=":)" class="wp-smiley" style="height: 1em; max-height: 1em;"><noscript><img src="http://nimishprabhu.com/wp-includes/images/smilies/simple-smile.png" alt=":)" class="wp-smiley" style="height: 1em; max-height: 1em;" /></noscript>
        $data = str_replace("Nimish", "NIMISH", $data);
        $html = str_get_html($data);
    }
}
