<?php
class WP_Hatena_Notation_Migration_2_1_0 implements WP_Hatena_Notation_Migration_Interface {
	/**
	 * Migrate
	 *
	 * @param WP_Hatena_Notation $context
	 */
	public static function migrate(WP_Hatena_Notation $context) {
		$option = $context->option('Config');

		if (!empty($option)) {
			$context->option('PostSetting', $option);
		}
	}
}
