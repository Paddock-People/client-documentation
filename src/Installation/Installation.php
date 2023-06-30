<?php

namespace Atlas\Documentation\Installation;

class Installation
{
    public function __construct()
    {
        register_activation_hook(ATLAS_DOCS_PLUGIN_FILE, [$this, 'addPage']);
    }

    public function addPage()
    {
        // Insert the post into the database
        wp_insert_post(
            [
            'post_title'    => 'Atlas Documentation',
            'post_content'  => '[atlas_documentation_header][atlas_documentation]',
            'post_status'   => 'draft',
            'post_author'   => 1,
            'post_type'     => 'page'
             ] 
        );

        flush_rewrite_rules();
    }
}
