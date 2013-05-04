<?php
/*
Plugin Name: WP Hatena Notation
Plugin URI: https://github.com/rewish/wp-hatena-notation
Description: あなたのWordPressに「はてな記法」を導入します。
Version: 2.0.1
Author: rewish
Author URI: https://github.com/rewish
*/
define('WP_HATENA_NOTATION_DIR',      WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . basename(dirname(__FILE__)));
define('WP_HATENA_NOTATION_FILE',     WP_HATENA_NOTATION_DIR . DIRECTORY_SEPARATOR . basename(__FILE__));
define('WP_HATENA_NOTATION_VIEW_DIR', WP_HATENA_NOTATION_DIR . DIRECTORY_SEPARATOR . 'views');

require_once WP_HATENA_NOTATION_DIR . '/lib/load.php';
require_once WP_HATENA_NOTATION_DIR . '/WP/Hatena/Notation.php';

// Global instance
$wp_hatena_notation = new WP_Hatena_Notation('wp-hatena-notation');
