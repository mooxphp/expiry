<?php

namespace Moox\Expiry\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Moox\Jobs\Traits\JobProgress;

class DemoExpiries implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, JobProgress, Queueable, SerializesModels;

    public $tries;

    public $timeout;

    public $maxExceptions;

    public $backoff;

    public function __construct()
    {
        $this->tries = 3;
        $this->timeout = 300;
        $this->maxExceptions = 1;
        $this->backoff = 350;
    }

    public function handle()
    {

        $this->setProgress(1);

        // This job creates a bunch of demo expiries for testing purposes

        $demoData = [
            ['user_id' => 1, 'licence_title' => 'Demo-License 1', 'license_key' => 'FWv%ion34o', 'expiry_date' => '2020-01-01'],
        ];
    }
}
