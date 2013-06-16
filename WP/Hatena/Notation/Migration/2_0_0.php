<?php
class WP_Hatena_Notation_Migration_2_0_0 implements WP_Hatena_Notation_Migration_Interface {
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
		self::options($context);
		self::linkTitle();
	}

	/**
	 * Migrate options
	 *
	 * @param WP_Hatena_Notation $context
	 */
	protected static function options(WP_Hatena_Notation $context) {
		$options = get_option(self::LEGACY_OPTION_NAME);

		if (!$options) {
			return;
		}

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

		self::enabler($context, $options['after_enable_date']);
	}

	/**
	 * Migrate enabler
	 *
	 * after_enable_date in legacy option
	 *
	 * @param WP_Hatena_Notation $context
	 * @param string $date
	 */
	protected static function enabler(WP_Hatena_Notation $context, $date) {
		if (!$date) {
			return;
		}

		$filter = create_function('$where', 'return "$where AND post_date <= \'' . $date . '\'";');

		add_filter('posts_where', $filter);
		$posts = get_posts(array('suppress_filters' => false));
		remove_filter('posts_where', $filter);

		foreach ($posts as $post) {
			$context->enabled($post->ID, 0);
		}

		$context->option('Config.per_post', true);
	}

	/**
	 * Migrate LinkTitle
	 *
	 * @global wpdb $wpdb
	 */
	protected static function linkTitle() {
		global $wpdb;
		$tableName = $wpdb->prefix . self::LEGACY_LINK_TITLE_TABLE_NAME;
		$wpdb->query("DROP TABLE IF EXISTS `$tableName`;");

		WP_Hatena_Notation_LinkTitle::getInstance()->create();
	}
}
