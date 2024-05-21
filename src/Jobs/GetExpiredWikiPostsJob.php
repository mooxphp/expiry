<?php

namespace Moox\Expiry\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Moox\Expiry\Models\Expiry;
use Moox\Jobs\Traits\JobProgress;
use Moox\Press\Models\WpPost;

class GetExpiredWikiPostsJob implements ShouldQueue
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

        $oneYearAgo = Carbon::now()->subYear();

        $this->setProgress(10);

        $posts = WpPost::where('post_type', 'wiki')
            ->where('post_status', 'publish')
            ->whereDate('post_modified', '<', $oneYearAgo)
            ->where('post_parent', 0)
            ->get();

        $this->setProgress(40);

        foreach ($posts as $post) {

            $this->processExpiryPost($post);

        }

        $this->setProgress(100);
    }

    private function processExpiryPost($post)
    {

        $baseHref = config('app.url').config('press.wordpress_slug').'/?p=';

        if ($post->gultig_bis) {
            $expiryDate = Carbon::parse($post->gultig_bis);
            $status = 'OK';
        } else {
            $expiryDate = Carbon::parse($post->post_modified)->addYear();
            $status = 'Kein Ablaufdatum';
        }

        if ($post->verantwortlicher) {
            $notifiedTo = $post->verantwortlicher;
            $status = 'OK';
        } else {
            $notifiedTo = config('expiry.default_notified_to');
            $status = 'Niemand verantwortlich';
        }

        if ($post->turnus) {
            // manipulate expiry date based on turnus
        }

        if ($post->fruhwarnung) {
            // manipulate expiry date based on fruhwarnung
        }

        $themaTaxonomy = $post->taxonomies()->where('taxonomy', 'thema')->first();

        if ($themaTaxonomy) {
            $thema = $themaTaxonomy->term->name;
        } else {
            $thema = 'Unbekannt';
        }

        $expiryData = [
            'item_id' => $post->ID,
            'meta_id' => 0,
            'expired_at' => $post->post_modified,
            'title' => $post->post_title,
            'slug' => $post->post_name,
            'link' => $baseHref.$post->ID,
            'notified_to' => $notifiedTo,
            'expiry_job' => 'Wiki Posts',
            'category' => $thema,
            'status' => $status,
        ];

        Expiry::updateOrCreate([
            'meta_id' => 0,
            'item_id' => $post->ID,
        ], $expiryData);

        $this->setProgress(75);

    }
}
