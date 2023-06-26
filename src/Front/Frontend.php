<?php

namespace Atlas\Documentation\Front;

class Frontend
{
    public function __construct()
    {
        add_shortcode('atlas_documentation', [$this, 'documentation']);
        add_shortcode('atlas_documentation_header', [$this, 'documentationHeader']);
    }

    public function documentation()
    {
        include ATLAS_DOCS_PLUGIN_DIR . 'src/templates/frontend/documentation.php';
    }

    public function documentationHeader()
    {
        include ATLAS_DOCS_PLUGIN_DIR . 'src/templates/frontend/header.php';
    }
}
