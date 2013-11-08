<?php
/*
Plugin Name: WP Hatena Notation
Plugin URI: https://github.com/rewish/wp-hatena-notation
Description: WordPressに「はてな記法」を導入します。
Version: 2.1.0
Author: rewish
Author URI: https://github.com/rewish
*/
define('WP_HATENA_NOTATION_DOMAIN', 'wp-hatena-notation');

define('WP_HATENA_NOTATION_DIR',      WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . basename(dirname(__FILE__)));
define('WP_HATENA_NOTATION_FILE',     WP_HATENA_NOTATION_DIR . DIRECTORY_SEPARATOR . basename(__FILE__));
define('WP_HATENA_NOTATION_VIEW_DIR', WP_HATENA_NOTATION_DIR . DIRECTORY_SEPARATOR . 'views');

require_once WP_HATENA_NOTATION_DIR . '/lib/load.php';
require_once WP_HATENA_NOTATION_DIR . '/WP/Hatena/Notation.php';

// Global instance
$wp_hatena_notation = new WP_Hatena_Notation();

// Function to maintain compatibility with 1.x
function wphn_render($content) {
	global $wp_hatena_notation;
	return $wp_hatena_notation->render($content);
}
