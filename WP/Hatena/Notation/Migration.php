<?php
class WP_Hatena_Notation_Migration {
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
		self::legacyOptions($context);
		self::linkTitle();
	}

	/**
	 * Legacy options
	 *
	 * @param WP_Hatena_Notation $context
	 */
	public static function legacyOptions(WP_Hatena_Notation $context) {
		$options = get_option(self::LEGACY_OPTION_NAME);

		if (!$options) {
			return;
		}

		delete_option(self::LEGACY_OPTION_NAME);

		$context->option('Renderer.headerlevel', $options['headerlevel']);
		$context->option('Renderer.link_target_blank', $options['target_blank']);
		$context->option('Renderer.title_expires', $options['title_term']);
		$context->option('Renderer.superpre_method', 'html');
		$context->option('Renderer.superpre_html', $options['spremarkup']);
		$context->option('Renderer.linebreak_method', $options['wp_paragraph'] ? 'wordpress' : 'wpautop');
		$context->option('Renderer.footnote_html', sprintf('<div class="%s">%s%s%s</div>', $options['footnoteclass'], PHP_EOL, '%content%', PHP_EOL));

		if ($options['wrap_section']) {
			$context->option('Renderer.textbody_html', sprintf('<div class="%s">%s%s%s</div>', $options['sectionclass'], PHP_EOL, '%content%', PHP_EOL));
		} else {
			$context->option('Renderer.textbody_html', '%content%');
		}

		self::legacyEnabler($context, $options['after_enable_date']);
	}

	/**
	 * Legacy enabler
	 *
	 * after_enable_date in legacy option
	 *
	 * @param WP_Hatena_Notation $context
	 * @param string $date
	 */
	public static function legacyEnabler(WP_Hatena_Notation $context, $date) {
		if (!$date) {
			return;
		}

		$filter = create_function('$where', 'return "$where AND post_date <= ' . $date . '";');

		add_filter('posts_where', $filter);
		$posts = get_posts();
		remove_filter('posts_where', $filter);

		foreach ($posts as $post) {
			$context->enabled($post->ID, 0);
		}

		$context->option('Config.per_post_default', true);
	}

	/**
	 * Migrate LinkTitle
	 *
	 * @global wpdb $wpdb
	 */
	public static function linkTitle() {
		self::legacyLinkTitle();
		WP_Hatena_Notation_LinkTitle::getInstance()->create();
	}

	/**
	 * Legacy LinkTitle
	 */
	public static function legacyLinkTitle() {
		global $wpdb;
		$tableName = $wpdb->prefix . self::LEGACY_LINK_TITLE_TABLE_NAME;
		$wpdb->query("DROP TABLE IF EXISTS `$tableName`;");
	}
}
