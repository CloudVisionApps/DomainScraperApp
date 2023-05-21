<?php

namespace App;

class UrlHelper
{
    public static function getDomainFromUrl($url){

        $parse = parse_url($url);
        if (isset($parse['host'])) {
            $host = $parse['host'];
            $host = str_replace('www.', '', $host);
            return $host;
        }

        $url = str_replace('www.', '', $url);
        $url = str_replace('http://www.', '', $url);
        $url = str_replace('https://www.', '', $url);
        $url = str_replace('http://', '', $url);
        $url = str_replace('https://', '', $url);

        $expUrl = explode('/', $url);
        if (isset($expUrl[0])) {
            $url = $expUrl[0];
        }

        return $url;

    }
}
