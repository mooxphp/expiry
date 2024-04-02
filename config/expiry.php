<?php

return [
    'navigation_sort' => 2001,
    'navigation_label' => 'Moox Expiry',

    'monitors' => [
        'press-posts-acf' => [
            'label' => 'Press Posts ACF',
            'class' => \Moox\Expiry\Monitors\PressPostsAcf::class,
        ],
    ],

    'executes' => [
        'press-posts-acf' => [
            'label' => 'Press Posts ACF',
            'class' => \Moox\Expiry\Monitors\PressPostsAcf::class,
        ],
    ],

    'notifies' => [
        'press-posts-acf' => [
            'label' => 'Press Posts ACF',
            'class' => \Moox\Expiry\Monitors\PressPostsAcf::class,
        ],
    ],

    'escalates' => [
        'press-posts-acf' => [
            'label' => 'Press Posts ACF',
            'class' => \Moox\Expiry\Monitors\PressPostsAcf::class,
        ],
    ],
];
