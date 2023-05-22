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

        $link = 'https://bgtop.net/category/0/720/';
        \App\ScrapDomainAndLinks::scrap($link);

        return;


        while (true) {
            $getWebsiteLinks = WebsiteLink::all();
            foreach ($getWebsiteLinks as $websiteLink) {

                \App\ScrapDomainAndLinks::scrap($websiteLink->website_link);

                sleep(rand(1,4));

            }
        }

        return Command::SUCCESS;
    }
}
