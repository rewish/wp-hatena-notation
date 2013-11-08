<?php
/**
 * Class WP_Hatena_Notation_Options
 */
class WP_Hatena_Notation_Options extends WP_Hatena_Notation_Domain {
	/**
	 * Page title
	 * @constant string
	 */
	const PAGE_TITLE = 'はてな記法の設定';

	/**
	 * Menu title
	 * @constant string
	 */
	const MENU_TITLE = 'はてな記法';

	/**
	 * Options
	 * @var array
	 */
	protected $options;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->setUp();
		$this->registerHooks();
	}

	/**
	 * Setup options
	 */
	public function setUp() {
		$this->options = get_option($this->domain, array()) + array(
			'Renderer' => array(),
			'PostSetting' => array()
		);

		$this->options['Renderer'] += array(
			'cache' => false,
			'headerlevel' => 3,
			'linebreak_method' => 'wpautop',
			'link_target_blank' => true,
			'title_expires' => 90,
			'textbody_html' => "<div class=\"section\">\n%content%\n</div>",
			'footnote_html' => "<div class=\"footnote\">\n%content%\n</div>",
			'superpre_html' => "<pre class=\"%type%\">\n%content%\n</pre>",
			'superpre_method' => 'geshi'
		);

		$this->options['PostSetting'] += array(
			'per_user' => false,
			'per_user_default' => true,
			'per_post' => false,
			'per_post_default' => true
		);
	}

	/**
	 * Register hooks
	 */
	public function registerHooks() {
		add_action('admin_menu', array($this, 'addOptionsPage'));
	}

	/**
	 * Set option
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function set($key, $value) {
		$k = explode('.', $key);
		$o =& $this->options;

		switch (count($k)) {
			case 1: $o[$k[0]] = $value; break;
			case 2: $o[$k[0]][$k[1]] = $value; break;
		}

		update_option($this->domain, $this->options);
	}

	/**
	 * Get option
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get($key = null) {
		if (empty($key)) {
			return $this->options;
		}

		$keys = explode('.', $key);
		$option = $this->options;

		do {
			$key = array_shift($keys);
			if (!isset($option[$key])) {
				$option = null;
				break;
			}
			$option = $option[$key];
		} while ($keys);

		return $option;
	}

	/**
	 * Option to JSON
	 *
	 * @param string $key
	 * @return string
	 */
	public function toJSON($key) {
		return json_encode($this->get($key));
	}

	/**
	 * Add options page
	 */
	public function addOptionsPage() {
		add_options_page(self::PAGE_TITLE, self::MENU_TITLE, 'manage_options', $this->domain, array($this, 'renderOptionsPage'));
	}

	/**
	 * Render options page
	 */
	public function renderOptionsPage() {
		global $wp_hatena_notation;

		$options = (object)$this->get();

		foreach ($options as &$option) {
			$option = (object)$option;
		}

		$highlightCSS = $wp_hatena_notation->fileURL('css/highlight.css');
		$pageJS = $wp_hatena_notation->fileURL('js/options_page.js');

		require_once WP_HATENA_NOTATION_VIEW_DIR . DIRECTORY_SEPARATOR . 'options.php';
	}
}