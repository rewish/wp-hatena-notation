<?php
require_once dirname(__FILE__) . '/Migration/Interface.php';

class WP_Hatena_Notation_Migration {
	/**
	 * Migration version name
	 */
	const VERSION_NAME = 'wp-hatena-notation-migration-version';

	/**
	 * Migrate
	 *
	 * @param WP_Hatena_Notation $context
	 */
	public static function migrate(WP_Hatena_Notation $context) {
		$version = get_option(self::VERSION_NAME, '1.4');
		$versions = self::getVersions();
		$latestVersion = 0;

		foreach ($versions as $info) {
			if ($version > $info['version']) {
				continue;
			}

			require_once $info['path'];
			call_user_func(array($info['class'], 'migrate'), $context);

			if ($latestVersion < $info['version']) {
				$latestVersion = $info['version'];
			}
		}

		if ($latestVersion > $version) {
			update_option(self::VERSION_NAME, $latestVersion);
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
