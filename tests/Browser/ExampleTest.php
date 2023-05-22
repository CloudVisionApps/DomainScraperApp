<?php

namespace Tests\Browser;

use App\Models\Domain;
use App\Models\WebsiteLink;
use App\UrlHelper;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */
    public function testBasicExample(): void
    {
        $this->browse(function (Browser $browser) {

            $links = [];
            $getDomains = Domain::all();
            foreach ($getDomains as $domain) {
                $links[] = 'https://' . $domain->domain;
            }
            $getWebsiteLinks = WebsiteLink::limit(500)->orderBy('id', 'desc')->get();
            foreach ($getWebsiteLinks as $websiteLink) {
                $links[] = $websiteLink->website_link;
            }

            foreach ($links as $link) {

                try {
                    $browser->visit($link);
                    $browser->pause(1000);
                    $pageLinks = $browser->elements('a');
                    foreach ($pageLinks as $pageLink) {

                        if (strpos($pageLink->getAttribute('href'), 'http') !== false) {
                            $pageLinkReady = $pageLink->getAttribute('href');
                        } else {
                            $href = $pageLink->getAttribute('href');
                            if (strpos($href, '/') !== false) {
                                $pageLinkReady = $link . $href;
                            } else {
                                $pageLinkReady = $link . '/' . $href;
                            }
                        }

///////////////////////////////////////////////////////////////////////////////////

                        $domain = UrlHelper::getDomainFromUrl($pageLinkReady);
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
                            echo 'Save new domain: ' . $domain . "\n";
                            $findDomain = new Domain();
                            $findDomain->domain = $domain;
                        }

                        $findDomain->save();

                        $findWebsiteLink = WebsiteLink::where('website_link', $pageLinkReady)->first();
                        if ($findWebsiteLink == null) {
                            $findWebsiteLink = new WebsiteLink();
                            echo 'Save new website link: ' . $pageLinkReady . "\n";
                            $findWebsiteLink->website_link = $pageLinkReady;
                        }

                        $findWebsiteLink->website_last_scrape_date = Carbon::now();
                        $findWebsiteLink->save();

///////////////////////////////////////////////////////////////////////////////////


                    }
                } catch (\Exception $e) {
                    echo 'Error: ' . $e->getMessage() . "\n";
                }

            }

        });
    }
}
