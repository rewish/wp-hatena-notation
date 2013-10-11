<?php
/**
 * Class WP_Hatena_Notation
 */
require_once dirname(__FILE__) . '/Notation/Exception.php';
require_once dirname(__FILE__) . '/Notation/Domain.php';
require_once dirname(__FILE__) . '/Notation/Options.php';
require_once dirname(__FILE__) . '/Notation/PostSetting.php';
require_once dirname(__FILE__) . '/Notation/Renderer.php';
require_once dirname(__FILE__) . '/Notation/Cache.php';
require_once dirname(__FILE__) . '/Notation/LinkTitle.php';
require_once dirname(__FILE__) . '/Notation/Migration.php';

class WP_Hatena_Notation {
	/**
	 * Options instance
	 * @var WP_Hatena_Notation_Options
	 */
	protected $Options;

	/**
	 * PostSetting instance
	 * @var WP_Hatena_Notation_PostSetting
	 */
	protected $PostSetting;

	/**
	 * Renderer instance
	 * @var WP_Hatena_Notation_Renderer
	 */
	protected $Renderer;

	/**
	 * Cache instance
	 * @var WP_Hatena_Notation_Cache
	 */
	protected $Cache;

	/**
	 * Enable wpautop filter
	 * @var bool
	 */
	protected $wpautop = false;

	/**
	 * Constructor
	 *
	 * @param string $domain
	 */
	public function __construct($domain) {
		$this->Options = new WP_Hatena_Notation_Options($domain);
		$this->PostSetting = new WP_Hatena_Notation_PostSetting($domain, $this->option('PostSetting'));
		$this->Renderer = new WP_Hatena_Notation_Renderer($this->option('Renderer'));
		$this->Cache = new WP_Hatena_Notation_Cache($domain);

		$this->registerHooks();
	}

	/**
	 * Register hooks
	 */
	protected function registerHooks() {
		add_action('admin_init', array($this, 'onAdminInit'));
		add_action('the_post', array($this, 'onThePost'));
		add_filter('the_content', array($this, 'onTheContent'));

		// Remove wpautop
		if ($this->option('Renderer.linebreak_method') !== 'wordpress') {
			$this->wpautop = remove_filter('the_content', 'wpautop');
		}
	}

	/**
	 * Get option
	 *
	 * @param string $key
	 * @param mixed $value Optional
	 * @return mixed
	 */
	public function option($key, $value = null) {
		if (count(func_get_args()) === 2) {
			return $this->Options->set($key, $value);
		}
		return $this->Options->get($key);
	}

	/**
	 * Render
	 *
	 * @param string $content
	 * @return string
	 */
	public function render($content) {
		return $this->Renderer->render(HatenaSyntax::parse($content));
	}

	/**
	 * Cached content
	 *
	 * @param WP_Post $post
	 * @param string $content
	 * @return string
	 */
	public function cachedContent($post, $content) {
		$cachedContent = $this->Cache->get($post, 'content');

		if (!$cachedContent) {
			$cachedContent = $this->render($content);
			$this->Cache->set($post, 'content', $cachedContent);
		}

		return $cachedContent;
	}

	/**
	 * Enabled post?
	 *
	 * @param WP_Post $post
	 * @return bool
	 */
	public function enabled($post, $enabled = 1) {
		$post = get_post($post);

		if (count(func_get_args()) === 2) {
			return $this->PostSetting->saveEnabled($post->ID, $enabled);
		}

		return $this->PostSetting->isEnabled($post->ID);
	}

	/**
	 * File URL
	 *
	 * @param string $file
	 * @return string
	 */
	public function fileURL($file) {
		return plugin_dir_url(WP_HATENA_NOTATION_FILE) . $file;
	}

	/**
	 * Hook on admin_init
	 */
	public function onAdminInit() {
		WP_Hatena_Notation_Migration::migrate($this);
	}

	/**
	 * Hook on the_post
	 *
	 * @global int $page
	 * @global array $pages
	 * @param WP_Post $post
	 */
	public function onThePost($post) {
		global $page, $pages;

		if (!$this->enabled($post)) {
			return;
		}

		$content = preg_replace('/<!--more(.*?)?-->/', '====', $pages[$page - 1]);
		$pages[$page - 1] = $this->cachedContent($post, $content);
	}

	/**
	 * Hook on the_content
	 *
	 * @param string $content
	 * @return string
	 */
	public function onTheContent($content) {
		$post = get_post();

		if (!$this->wpautop || $this->enabled($post)) {
			return $content;
		}

		return wpautop($content);
	}
}