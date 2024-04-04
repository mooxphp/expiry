<?php

return [
    'navigation_sort' => 2001,
    'navigation_label' => 'Moox Expiry',

    // currently not used
    'monitors' => [
        'press-posts-acf' => [
            'label' => 'Press Posts ACF',
            'class' => \Moox\Expiry\Jobs\GetExpiredJob::class,
        ],
    ],

    // currently not used
    'executes' => [
        'press-posts-acf' => [
            'label' => 'Press Posts ACF',
            'class' => \Moox\Expiry\Jobs\GetExpiredJob::class,
        ],
    ],

    // currently not used
    'notifies' => [
        'press-posts-acf' => [
            'label' => 'Press Posts ACF',
            'class' => \Moox\Expiry\Jobs\GetExpiredJob::class,
        ],
    ],

    // currently not used
    'escalates' => [
        'press-posts-acf' => [
            'label' => 'Press Posts ACF',
            'class' => \Moox\Expiry\Jobs\GetExpiredJob::class,
        ],
    ],
];
