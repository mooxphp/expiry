<?php

namespace Moox\Expiry\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Moox\Expiry\Models\Expiry;
use Moox\Jobs\Traits\JobProgress;
use Moox\Press\Models\WpPost;

class GetExpiredWikiDocsJob implements ShouldQueue
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

        $postmetaTable = config('press.wordpress_prefix').'postmeta';
        $expiries = DB::table($postmetaTable)
            ->where('meta_key', 'LIKE', '%_gultig_bis')
            ->where('meta_value', 'REGEXP', '^[0-9]{8}$')
            ->get();

        $this->setProgress(10);

        $today = Carbon::today();

        $relevantMetaIds = [];
        $expiredMeta = $expiries->filter(function ($meta) use ($today, &$relevantMetaIds) {
            $expiryDate = Carbon::createFromFormat('Ymd', $meta->meta_value);
            $isExpired = $expiryDate->lessThan($today);
            if ($isExpired) {
                $relevantMetaIds[] = $meta->meta_id;
            }

            return $isExpired;
        });

        $this->setProgress(20);

        $expired = $expiredMeta
            ->pluck('post_id')
            ->unique()
            ->all();

        $this->setProgress(30);

        // @phpstan-ignore-next-line
        $posts = WpPost::whereIn('ID', $expired)
            ->where('post_type', 'wiki')
            ->where('post_status', 'publish')
            ->with('meta')
            ->get();

        $this->setProgress(40);

        foreach ($posts as $post) {
            foreach ($post->meta as $meta) {
                if (Str::endsWith($meta->meta_key, '_gultig_bis') && preg_match("/^\d{8}$/", $meta->meta_value)) {
                    $this->processExpiryMeta($meta, $post, $relevantMetaIds);
                }
            }
        }

        Expiry::whereNotIn('meta_id', $relevantMetaIds)->delete();

        $this->setProgress(100);
    }

    private function processExpiryMeta($meta, $post, &$relevantMetaIds)
    {
        $dueDate = Carbon::createFromFormat('Ymd', $meta->meta_value)->startOfDay();
        if ($dueDate->isPast()) {
            $newKey = str_replace('_gultig_bis', '_download-titel', $meta->meta_key);
            $metaTitle = $post->meta->firstWhere('meta_key', $newKey);
            $postTitle = $post->post_title;

            $title = $metaTitle ? ($postTitle.' '.$metaTitle->meta_value) : $postTitle;
            $baseHref = config('app.url').config('press.wordpress_slug').'/?p=';
            $formattedDate = $dueDate->toDateTimeString();

            $expiryData = [
                'item_id' => $post->ID,
                'meta_id' => $meta->meta_id,
                'expired_at' => $formattedDate,
                'title' => $title,
                'slug' => $post->post_name,
                'link' => $baseHref.$post->ID,
                'notified_to' => $post->post_author,
                'expiry_job' => 'Wiki Dokumente',
                'status' => 'OK',
                'category' => 'Downloads',
            ];

            Expiry::updateOrCreate(['meta_id' => $meta->meta_id], $expiryData);
            $this->setProgress(75);
        }
    }
}
