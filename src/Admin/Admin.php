<?php

namespace Atlas\Documentation\Admin;
class Admin
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'adminPage']);
        add_action('atlas_import_docs', [$this, 'importDocs']);
    }

    public function adminPage()
    {
        add_menu_page(
            'Atlas Documentation',
            'Atlas Documentation',
            'manage_options',
            'atlas-documentation',
            function () {
                include ATLAS_DOCS_PLUGIN_DIR . 'src/templates/admin/options.php';
            },
            'dashicons-book-alt',
            100
        );
    }

    public function importDocs($client_id)
    {
        (new ImportDocumentation($client_id))->import();
    }
}
