<?php

namespace App;

class UrlHelper
{
    public static function getDomainFromUrl($url){

        $parse = parse_url($url);
        if (isset($parse['host'])) {
            return $parse['host'];
        }

        $url = str_replace('www.', '', $url);
        $url = str_replace('http://www.', '', $url);
        $url = str_replace('https://www.', '', $url);

        return $url;

    }
}
