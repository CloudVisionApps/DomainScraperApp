<?php

namespace App;

class UrlHelper
{
    public static function isValidDomainName($domainName)
    {
        return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domainName) //valid chars check
            && preg_match("/^.{1,253}$/", $domainName) //overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domainName)   ); //length of each label
    }

    public static function isSubDomainName($domainName)
    {
        $exp = explode('.', $domainName);
        if (count($exp) > 2) {
            return true;
        }

        return false;
    }

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
