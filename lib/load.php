<?php
define('WP_HATENA_NOTATION_LIB_DIR',  dirname(__FILE__));

// Load HatenaSyntax
function wp_hatena_notation_autoloader($class) {
	foreach (array('PEG', 'HatenaSyntax') as $name) {
		if ($class === $name || strpos($class, "{$name}_") === 0) {
			$path = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
			require_once WP_HATENA_NOTATION_LIB_DIR . "/$name/src/$path";
		}
	}
}
spl_autoload_register('wp_hatena_notation_autoloader');

// Load GeSHi
require_once dirname(__FILE__) . '/geshi/geshi.php';
