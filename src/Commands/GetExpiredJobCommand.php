<?php

namespace Moox\Expiry\Commands;

use Illuminate\Console\Command;
use Moox\Expiry\Jobs\GetExpiredWikiDocsJob;
use Moox\Expiry\Jobs\GetExpiredWikiPostsJob;

class GetExpiredJobCommand extends Command
{
    protected $signature = 'moox:getexpired';

    protected $description = 'Start the Moox Expiry Jobs';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Starting Moox Expiry Jobs');

        GetExpiredWikiDocsJob::dispatch();
        GetExpiredWikiPostsJob::dispatch();

        $this->info('Moox Expiry Jobs started successfully!');
    }
}
