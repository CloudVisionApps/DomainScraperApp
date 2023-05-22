<?php

namespace App;

class ScraperHelper
{
    public static function getDomainsFromContent($content) {

        preg_match_all('/[a-zA-Z0-9\-]+\.(com|net|org|biz|info|co|org|mobi|id|me|it|eu)(\.au|\.nz|\.uk|\.pe|\.ve|\.ru|\.so|\.ph|\.za)?/i', $content, $regexUrls);
        $regexUrlsCleaned = [];
        foreach ($regexUrls[0] as $regexUrl) {
            $regexUrl = trim($regexUrl);
            $regexUrl = strtolower($regexUrl);
            if (empty($regexUrl)) {
                continue;
            }
            if (!filter_var($regexUrl, FILTER_VALIDATE_DOMAIN)) {
                continue;
            }
            $regexUrlsCleaned[$regexUrl] = $regexUrl;
        }

        $regexUrlsCleaned = array_keys($regexUrlsCleaned);

        return $regexUrlsCleaned;
    }
}
