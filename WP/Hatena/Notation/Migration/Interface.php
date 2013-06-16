<?php
interface WP_Hatena_Notation_Migration_Interface {
	/**
	 * Migrate
	 * @param WP_Hatena_Notation $context
	 */
	public static function migrate(WP_Hatena_Notation $context);
}
