<?php

namespace Moox\Expiry\Monitors;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Moox\Press\Models\WpPost;

class PressPostsAcf
{
    public function __construct()
    {
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

                            if ($metaTitle) {
                                $title = $metaTitle->meta_value;
                            } else {
                                $title = $post->post_title;
                            }

                            $postArray[$post->ID] = [
                                'id' => $post->ID,
                                'date' => $meta->meta_value,
                                'title' => $title,
                                'slug' => $post->post_name,
                                'user' => $post->post_author,
                            ];

                            var_dump($postArray);
                        }
                    }
                }
            });
        }

    }
}
