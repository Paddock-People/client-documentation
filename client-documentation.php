<?php
/**
 * Client Documentation
 *
 * @package       ATLASDOCS
 * @author        Paddock People
 * @version       1.4.6
 *
 * @wordpress-plugin
 * Plugin Name:   Client Documentation
 * Plugin URI:    https://github.com/Paddock-People/client-documentation
 * Description:   This plugin creates a local version of documentation on the client site.
 * Version:       1.4.6
 * Author:        Paddock People
 * Author URI:    https://www.paddockpeople.com.au/
 * Text Domain:   lamb-documentation
 * Domain Path:   /languages
 */

// Exit if accessed directly.
use Atlas\Documentation\AtlasDocumentation;

if (!defined('ABSPATH')) exit;

define('ATLAS_DOCS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ATLAS_DOCS_PLUGIN_FILE', __FILE__);
define('ATLAS_DOCS_PLUGIN_URL', plugins_url('/', __FILE__));

if (is_file(__DIR__ . '/vendor/autoload_packages.php')) {
  require_once __DIR__ . '/vendor/autoload_packages.php';
}

new AtlasDocumentation();
