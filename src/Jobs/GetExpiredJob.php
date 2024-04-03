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

class GetExpiredJob implements ShouldQueue
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

        $expiries = DB::table('tyar9_postmeta')
            ->where('meta_key', 'LIKE', '%_gultig_bis')
            ->where('meta_value', 'REGEXP', '^[0-9]{8}$')
            ->get();

        $today = Carbon::today();

        $expiredMeta = $expiries->filter(function ($meta) use ($today) {
            $expiryDate = Carbon::createFromFormat('Ymd', $meta->meta_value);

            return $expiryDate->lessThan($today);
        });

        $expired = $expiredMeta
            ->pluck('post_id')
            ->unique()
            ->all();

        // @phpstan-ignore-next-line
        $posts = WpPost::whereIn('ID', $expired)
            ->where('post_type', 'wiki')
            ->where('post_status', 'publish')
            ->with('meta')
            ->get();

        foreach ($posts as $post) {
            $post->meta->each(function ($meta) use ($post) {
                if (Str::endsWith($meta->meta_key, '_gultig_bis')) {
                    if (preg_match("/^\d{8}$/", $meta->meta_value)) {
                        $dueDate = Carbon::createFromFormat(
                            'Ymd',
                            $meta->meta_value
                        )->startOfDay();
                        if ($dueDate->isPast()) {
                            $newkey = str_replace(
                                '_gultig_bis',
                                '_download-titel',
                                $meta->meta_key
                            );

                            $metaTitle = $post->meta->firstWhere('meta_key', $newkey);
                            $postTitle = $post->post_title;

                            if ($metaTitle && $postTitle) {
                                $title = $metaTitle->meta_value.' - '.$postTitle;
                            } elseif ($metaTitle) {
                                $title = $metaTitle->meta_value;
                            } else {
                                $title = $postTitle;
                            }

                            $baseHref = config('app.url').config('press.wordpress_slug').'/?p=';

                            $metaDate = Carbon::createFromFormat('Ymd', $meta->meta_value);

                            $formattedDate = $metaDate->startOfDay()->toDateTimeString();

                            $postArray[$post->ID] = [
                                'item_id' => $post->ID,
                                'meta_id' => $meta->meta_id,
                                'expired_at' => $formattedDate,
                                'title' => $title,
                                'slug' => $post->post_name,
                                'link' => $baseHref.$post->ID,
                                'notified_to' => $post->post_author,
                                'expiry_monitor_id' => 1,
                            ];

                            Expiry::updateOrCreate(
                                ['meta_id' => $meta->meta_id],
                                end($postArray)
                            );

                            // Notification::send($post->author, new ExpiryNotification($post));
                        }
                    }
                }
            });
        }
    }
}
