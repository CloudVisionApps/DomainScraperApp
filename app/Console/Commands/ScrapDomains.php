<?php

namespace App\Console\Commands;

use App\Models\Domain;
use App\Models\WebsiteLink;
use App\UrlHelper;
use Illuminate\Console\Command;

class ScrapDomains extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrap-domains:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $getDomains = Domain::all();
        foreach ($getDomains as $domain) {

            try {
                $domain = 'http://' . $domain->domain;
                $content = file_get_contents($domain);

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
                continue;
            }
        }

        return Command::SUCCESS;
    }
}
