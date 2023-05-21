<?php

namespace App\Console\Commands;

use App\Models\Domain;
use App\Models\WebsiteLink;
use App\UrlHelper;
use GuzzleHttp\Client;
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

        while (true) {
            $links = [];
            $getDomains = Domain::all();
            foreach ($getDomains as $domain) {
                $links[] = 'https://' . $domain->domain;
            }
            $getWebsiteLinks = WebsiteLink::all();
            foreach ($getWebsiteLinks as $websiteLink) {
                $links[] = $websiteLink->website_link;
            }
            foreach ($links as $link) {
                \App\ScrapDomainAndLinks::scrap($link);
                sleep(rand(1,4));
            }
        }

        return Command::SUCCESS;
    }
}
