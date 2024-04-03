<?php

namespace Moox\Expiry\Commands;

use Illuminate\Console\Command;
use Moox\Expiry\Jobs\GetExpiredJob;

class GetExpiredJobCommand extends Command
{
    protected $signature = 'moox:getexpired';

    protected $description = 'Start the Moox Get Expired Job';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Starting Moox Get Expired Job');

        GetExpiredJob::dispatch();

        $this->info('Moox Demo Get Expired finished');
    }
}
