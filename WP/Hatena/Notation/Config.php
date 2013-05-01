<?php
/**
 * Class WP_Hatena_Notation_Config
 */
class WP_Hatena_Notation_Config extends WP_Hatena_Notation_Domain {
	/**
	 * Meta box title
	 * @constant string
	 */
	const META_BOX_TITLE = 'はてな記法';

	/**
	 * Config data
	 * @var array
	 */
	protected $config;

	/**
	 * Constructor
	 *
	 * @param array $config
	 */
	public function __construct($name, Array $config) {
		parent::__construct($name);
		$this->config = $config;
		$this->registerHooks();
	}

	/**
	 * Register hooks
	 */
	public function registerHooks() {
		if ($this->config['per_post']) {
			add_action('add_meta_boxes', array($this, 'addMetaBox'));
			add_action('save_post', array($this, 'onSavePost'));
		}
	}

	/**
	 * Add meta box
	 */
	public function addMetaBox() {
		add_meta_box($this->name, self::META_BOX_TITLE, array($this, 'renderMetaBox'), 'post', 'side', 'high');
		add_meta_box($this->name, self::META_BOX_TITLE, array($this, 'renderMetaBox'), 'page', 'side', 'high');
	}

	/**
	 * Render meta box
	 */
	public function renderMetaBox() {
		global $post_id;
		$enabled = $this->isEnabled($post_id);
		require_once WP_HATENA_NOTATION_VIEW_DIR . DIRECTORY_SEPARATOR . 'meta_box.php';
	}

	/**
	 * Get nonce key
	 *
	 * @return string
	 */
	public function nonceKey() {
		return $this->name . '_nonce';
	}

	/**
	 * Get meta name
	 *
	 * @return string
	 */
	public function metaName($name) {
		return "_{$this->name}_{$name}";
	}

	/**
	 * Is enabled
	 *
	 * @param int $post_id
	 * @return bool
	 */
	public function isEnabled($post_id) {
		if (!$this->config['per_post']) {
			return true;
		}

		$enabled = get_post_meta($post_id, $this->metaName('enabled'), true);
		if ($enabled === false) {
			$enabled = $this->config['per_post_default'];
		}
		return !!$enabled;
	}

	/**
	 * Save enabled
	 *
	 * @param int $post_id
	 * @param int $enabled
	 */
	public function saveEnabled($post_id, $enabled) {
		update_post_meta($post_id, $this->metaName('enabled'), $enabled);
	}

	/**
	 * Hook on save_post
	 */
	public function onSavePost($post_id) {
		if (!isset($_POST['post_type'])) {
			return;
		}

		$key = $this->nonceKey();
		$type = $_POST['post_type'] === 'page' ? $_POST['post_type'] : 'post';

		if (empty($_POST[$key])
			|| !wp_verify_nonce($_POST[$key], $this->name)
			|| (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			|| !current_user_can("edit_$type", $post_id)
			|| !isset($_POST[$this->name]['Post']['enabled'])) {
			return;
		}

		$this->saveEnabled($post_id, $_POST[$this->name]['Post']['enabled']);
	}
}