<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CrawlEventsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:crawl-events {eventSourceId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the Node.js crawler to fetch events from registered sources.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $eventSourceId = $this->argument('eventSourceId');

        $this->info('Dispatching StartCrawling event...');

        event(new \App\Events\StartCrawling($eventSourceId));

        $this->info('StartCrawling event dispatched.');
    }
}
