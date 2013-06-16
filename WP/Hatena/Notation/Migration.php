<?php
require_once dirname(__FILE__) . '/Migration/Interface.php';

class WP_Hatena_Notation_Migration {
	/**
	 * Migration version number
	 */
	const VERSION = '2.0.0';

	/**
	 * Migration version name
	 */
	const VERSION_NAME = 'hatena-notation-migrations';

	/**
	 * Legacy option name
	 * @constant string
	 */
	const LEGACY_OPTION_NAME = 'hatena_notation';

	/**
	 * Legacy link title table name
	 * @constant string
	 */
	const LEGACY_LINK_TITLE_TABLE_NAME = 'hatena_notation';

	/**
	 * Migrate
	 *
	 * @param WP_Hatena_Notation $context
	 */
	public static function migrate(WP_Hatena_Notation $context) {
		$version = get_option(self::VERSION_NAME, '1.4');
		$versions = self::getVersions();
		$migrated = false;

		foreach ($versions as $info) {
			if ($version <= $info['version']) {
				require_once $info['path'];
				call_user_func(array($info['class'], 'migrate'), $context);
				$migrated = true;
			}
		}

		if ($migrated) {
			update_option(self::VERSION_NAME, self::VERSION);
		}
	}

	public static function getVersions() {
		$files = glob(dirname(__FILE__) . '/Migration/*.php');
		$versions = array();

		foreach ($files as $file) {
			$version = basename($file, '.php');
			if ($version !== 'Interface') {
				$versions[] = array(
					'version' => str_replace('_', '.', $version),
					'path'    => $file,
					'class'   => __CLASS__ . '_' . $version
				);
			}
		}

		return $versions;
	}
}
