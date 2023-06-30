<?php

namespace Atlas\Documentation\Admin;

class ImportDocumentation
{
    private int $client_id;

    public function __construct(int $client_id)
    {
        $this->client_id = $client_id;
    }

    public function import()
    {
        if(!$this->client_id) {
            echo '<div class="notice notice-error is-dismissible">
                        <p>Invalid client ID provided.</p>
                    </div>';
            return;
        }


        // Delete all existing posts
        $args = [
            'post_type'      => 'lamb-documentation',
            'posts_per_page' => -1,
            'post_status'    => 'any',
        ];

        $existing_posts = get_posts($args);

        foreach ($existing_posts as $post) {
            wp_delete_post($post->ID, true);
        }

        $json_url = 'https://atlas.paddockpeople.com.au/wp-json/wp/v2/client-documentation?client=' . $this->client_id . '&per_page=100';

        $json = file_get_contents($json_url);
        $documentation = json_decode($json);

        if(!$documentation) {
            echo '<div class="notice notice-error is-dismissible">
                        <p>Site ID does not exist.</p>
                    </div>';
            return;
        }

        foreach ($documentation as $item) {
            $my_post = array(
                'post_title'    => wp_strip_all_tags($item->title->rendered),
                'post_content'  => $item->content->rendered,
                'post_status'   => 'publish',
                'post_type'     => 'atlas-documentation',
            );

            // Insert the post into the database
            wp_insert_post($my_post);
        }

        echo '<div class="notice notice-success is-dismissible">
                    <p>' . count($documentation) . ' documentation pages imported</p>
                </div>';

    }
}
