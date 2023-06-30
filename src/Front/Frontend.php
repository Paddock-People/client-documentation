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
        $this->loadTemplate('documentation');
    }

    private function loadTemplate($template)
    {
        if(file_exists(get_stylesheet_directory() . "/client-documentation/{$template}.php")) {
            include get_stylesheet_directory() . "/client-documentation/{$template}.php";
            return;
        }

        if(file_exists(get_stylesheet_directory() . "/resources/views/client-documentation/{$template}.blade.php") && function_exists('view')) {
            echo view("client-documentation.{$template}");
            return;
        }

        include ATLAS_DOCS_PLUGIN_DIR . "src/templates/frontend/{$template}.php";
    }

    public function documentationHeader()
    {
        $this->loadTemplate('header');
    }
}
