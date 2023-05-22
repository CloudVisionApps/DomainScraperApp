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
        $findWebsiteLink = WebsiteLink::where('website_link', 'https://www.bgtop.net/')->first();
        if ($findWebsiteLink == null) {
            $findWebsiteLink = new WebsiteLink();
            $findWebsiteLink->website_link = 'https://www.bgtop.net/';
            $findWebsiteLink->save();
        }

        $this->browse(function (Browser $browser) {

            while(true) {
                $getWebsiteLinks = WebsiteLink::limit(1)
                    ->where(function ($query) {
                        $query->whereNull('website_last_scrape_date')
                            ->orWhere('website_last_scrape_date', '<', Carbon::now()->subDays(1));
                    })
                    ->orderBy('id', 'desc')
                    ->get();

                foreach ($getWebsiteLinks as $websiteLink) {

                    if (strpos($websiteLink->website_link, 'facebook.com') !== false) {
                        continue;
                    }

                    try {
                        $browser->visit($websiteLink->website_link);

                        // Mark as scraped
                        $websiteLink->website_last_scrape_date = Carbon::now();
                        $websiteLink->save();

                        $websiteLinkDomain = UrlHelper::getDomainFromUrl($websiteLink->website_link);
                        if (empty(trim($websiteLinkDomain))) {
                            continue;
                        }
                        $websiteLinkDomain = 'https://' . $websiteLinkDomain;

                        $pageLinks = $browser->elements('a');
                        foreach ($pageLinks as $pageLink) {

                            if (strpos($pageLink->getAttribute('href'), 'http') !== false) {
                                $pageLinkReady = $pageLink->getAttribute('href');
                            } else {
                                $href = $pageLink->getAttribute('href');
                                if (strpos($href, '/') !== false) {
                                    $pageLinkReady = $websiteLinkDomain . $href;
                                } else {
                                    $pageLinkReady = $websiteLinkDomain . '/' . $href;
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
                            $findWebsiteLink->save();

///////////////////////////////////////////////////////////////////////////////////


                        }
                    } catch (\Exception $e) {
                        echo 'Error: ' . $e->getMessage() . "\n";
                    }

                }
            }

        });
    }
}
