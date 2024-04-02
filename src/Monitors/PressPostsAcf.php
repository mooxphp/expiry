<?php

namespace Moox\Expiry\Monitors;

class PressPostsAcf
{
    public function __construct()
    {
        // Construct the Press Posts ACF
    }

    public function __invoke()
    {
        // Invoke the Press Posts ACF
    }

    public function boot()
    {
        // Boot the Press Posts ACF
    }

    public function monitors()
    {
        $model = \Moox\Press\Models\WpPost::class;
        $query = $model::query();

        $query->where('post_type', 'post');

        return $query->get();
    }

    public function executes()
    {
        // Execute the Press Posts ACF
    }

    public function notifies()
    {
        // Notify the Press Posts ACF
    }

    public function escalates()
    {
        // Escalate the Press Posts ACF
    }
}
