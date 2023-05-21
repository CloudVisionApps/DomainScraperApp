<?php

namespace App;

use App\Models\Domain;
use App\Models\WebsiteLink;
use GuzzleHttp\Client;

class ScrapDomainAndLinks
{
    public static function scrap($link)
    {
        try {

            $client = new Client(['base_uri' => $link]);
            $getRequest = $client->request('GET');
            $content = $getRequest->getBody()->getContents();

            libxml_use_internal_errors(true);

            $dom = new \DOMDocument();
            $dom->loadHTML('<!DOCTYPE html><meta charset="UTF-8">' . $content);

            foreach ($dom->getElementsByTagName('a') as $node) {
                $href = $node->getAttribute('href');
                if (strpos($href, 'http') !== false) {

                    $domain = UrlHelper::getDomainFromUrl($href);
                    if (empty(trim($domain))) {
                        continue;
                    }

                    if (!UrlHelper::isValidDomainName($domain)) {
                        continue;
                    }

                    if (UrlHelper::isSubDomainName($domain)) {
                        continue;
                    }

                    $findDomain = Domain::where('domain', $domain)->first();
                    if ($findDomain == null) {
                        $findDomain = new Domain();
                        $findDomain->domain = $domain;
                    }
                    $findDomain->save();

                    $findWebsiteLink = WebsiteLink::where('website_link', $href)->first();
                    if ($findWebsiteLink == null) {
                        $findWebsiteLink = new WebsiteLink();
                        $findWebsiteLink->website_link = $href;
                    }

                    $findWebsiteLink->save();

                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
        }
    }
}
