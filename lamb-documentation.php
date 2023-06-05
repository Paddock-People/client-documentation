<?php

/**
 * Atlas Documenation
 *
 * @package       ATLASDOCS
 * @author        Lamb Agency
 * @version       1.2.0
 *
 * @wordpress-plugin
 * Plugin Name:   Atlas Documenation
 * Plugin URI:    https://github.com/kyle-lambAgency/lamb-documentation
 * Description:   This plugin creates a local version of documentation on the client site.
 * Version:       1.2.0
 * Author:        Lamb Agency
 * Author URI:    https://www.lambagency.com.au/
 * Text Domain:   lamb-documentation
 * Domain Path:   /languages
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;


function add_lamb_docs_page()
{
  add_menu_page(
    'Lamb Docs', // Page title
    'Lamb Docs', // Menu title
    'manage_options', // Required capability to access the page
    'lamb-docs', // Page slug
    'lamb_documentation_page_contents', // Callback function to display the page content
    'dashicons-book-alt', // Menu icon
    100 // Menu position
  );
}
add_action('admin_menu', 'add_lamb_docs_page');

// add_action('admin_head', 'view_only');

// function view_only()
// {
//   echo '<style>
//     .post-type-lamb-documentation .row-actions span:not(.view) {
//       display:none;
//     } 
//   </style>';
// }

function lamb_documentation_page_contents()
{
?>
  <h1>
    Lamb Documentation.
  </h1>

  <?php

  // General check for user permissions.
  if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient privileges to access this page.'));
  }

  // Start building the page

  echo '<div class="wrap">';
  echo '<form action="admin.php?page=lamb-docs" method="post">';

  // this is a WordPress security feature - see: https://codex.wordpress.org/WordPress_Nonces
  wp_nonce_field('rest_documentation_data');
  echo '<input type="hidden" value="true" name="import_documentation" />';
  echo 'Enter Site ID:';
  echo '<br/><br/>';
  echo '<input type="number" value="true" name="documentation_cat_id" />';
  submit_button('Import');
  echo '</form>';

  // Check whether the button has been pressed AND also check the nonce
  if (isset($_POST['import_documentation']) && check_admin_referer('rest_documentation_data')) {
    // the button has been pressed AND we've passed the security check
    //echo $_POST['documentation_cat_id'];
    import_docs($_POST['documentation_cat_id']);
  }

  echo '</div>';

  ?>

<?php
}

function import_docs($cat_it)
{

  if (!$cat_it) {
    echo '<h4> Please enter your Site ID </h4>';
  } else {

    // Delete all existing posts
    $args = array(
      'post_type'      => 'lamb-documentation',
      'posts_per_page' => -1,
      'post_status'    => 'any',
    );
    $existing_posts = get_posts($args);

    foreach ($existing_posts as $post) {
      wp_delete_post($post->ID, true);
    }

    $json_url = 'https://atlas.paddockpeople.com.au/wp-json/wp/v2/client-documentation?client=' . $cat_it . '&per_page=100';

    $json = file_get_contents($json_url);
    $lambDocumantation = json_decode($json);

    if ($lambDocumantation) {
      echo '<h4 style="margin-bottom:10;">Articles Imported:</h4>';
      foreach ($lambDocumantation as $item) {
        // $full_image_urls  = str_replace("/wp-content/uploads/", "https://www.apollopatios.com.au/wp-content/uploads/", $item->content->rendered);

        $my_post = array(
          'post_title'    => wp_strip_all_tags($item->title->rendered),
          'post_content'  => $item->content->rendered,
          'post_status'   => 'publish',
          'post_type'     => 'lamb-documentation',
        );

        // Insert the post into the database
        wp_insert_post($my_post);

        echo $item->title->rendered . ' imported';
        echo '<br/>';
      }
    } else {
      echo '<h4> Site ID does not exist. </h4>';
    }
  }
}

// Register the rewrite rule
function my_plugin_register_rewrite_rule($rules)
{
  $new_rules = array(
    'atlas-docs/?$' => 'index.php?lamb_plugin_page=1'
  );

  // Flush rewrite rules
  flush_rewrite_rules();

  return $new_rules + $rules;
}
add_filter('rewrite_rules_array', 'my_plugin_register_rewrite_rule');


// Register the query variable
function my_plugin_register_query_var($vars)
{
  $vars[] = 'lamb_plugin_page';
  return $vars;
}
add_filter('query_vars', 'my_plugin_register_query_var');

// Hook into the template_include filter to handle the rendering of your custom template
function my_plugin_template_include($template)
{
  // Check if the custom query variable is set
  if (get_query_var('lamb_plugin_page')) {
    // Set the path to your custom template file
    $template = plugin_dir_path(__FILE__) . 'client-docs.php';
  }

  return $template;
}
add_filter('template_include', 'my_plugin_template_include');

require 'plugin-update-checker/plugin-update-checker.php';

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
  'https://github.com/kyle-lambAgency/lamb-documentation/',
  __FILE__,
  'lamb-documentation'
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');

//Optional: If you're using a private repository, specify the access token like this:
// $myUpdateChecker->setAuthentication('your-token-here');

function create_posttype()
{
  register_post_type(
    'lamb-documentation',
    // CPT Options
    array(
      'labels' => array(
        'name' => __('Lamb Documentation'),
        'singular_name' => __('Lamb Documentation')
      ),
      'public' => true,
      'show_ui' => true,
      'show_in_rest' => true,
      'has_archive' => false,
      'rewrite' => array('slug' => 'lamb-documentation'),
      'exclude_from_search' => true, // Exclude from search results
      'publicly_queryable'  => true, // Make not publicly queryable
    )
  );
}
// Hooking up our function to theme setup
add_action('init', 'create_posttype');
