<?php

namespace Atlas\Documentation\PostType;

class ClientDocumentation
{
    public function __construct()
    {
        add_action('init', [$this, 'register']);
    }

    public function register()
    {
        register_post_type(
            'atlas-documentation',
            [
                'labels' => [
                    'name' => __('Atlas Documentation'),
                    'singular_name' => __('Atlas Documentation')
                ],
                'public' => true,
                'show_ui' => true,
                'show_in_rest' => true,
                'has_archive' => false,
                'rewrite' => ['slug' => 'atlas-documentation'],
                'exclude_from_search' => true,
                'publicly_queryable'  => true,
            ]
        );
    }
}
