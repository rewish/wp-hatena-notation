<?php
/**
 * Class WP_Hatena_Notation_PostSetting
 */
class WP_Hatena_Notation_PostSetting extends WP_Hatena_Notation_Domain {
	/**
	 * Meta box title
	 * @constant string
	 */
	const META_BOX_TITLE = 'はてな記法';

	/**
	 * Option data of PostSetting
	 * @var array
	 */
	protected $options;

	/**
	 * Constructor
	 *
	 * @param array $options
	 */
	public function __construct(Array $options) {
		$this->options = $options;
		$this->registerHooks();
	}

	/**
	 * Register hooks
	 */
	public function registerHooks() {
		if ($this->options['per_post']) {
			add_action('add_meta_boxes', array($this, 'addMetaBox'));
			add_action('save_post', array($this, 'onSavePost'));
		}
	}

	/**
	 * Add meta box
	 */
	public function addMetaBox() {
		add_meta_box($this->domain, self::META_BOX_TITLE, array($this, 'renderMetaBox'), 'post', 'side', 'high');
		add_meta_box($this->domain, self::META_BOX_TITLE, array($this, 'renderMetaBox'), 'page', 'side', 'high');
	}

	/**
	 * Render meta box
	 */
	public function renderMetaBox() {
		$enabled = $this->isEnabled();
		require_once WP_HATENA_NOTATION_VIEW_DIR . DIRECTORY_SEPARATOR . 'meta_box.php';
	}

	/**
	 * Is enabled
	 *
	 * @param WP_Post $post
	 * @return bool
	 */
	public function isEnabled($post = null) {
		if (!$this->options['per_post']) {
			return true;
		}

		$post = get_post($post);
		$enabled = get_post_meta($post->ID, $this->metaKey('enabled'), true);

		if (!is_bool($enabled) && empty($enabled)) {
			$enabled = $this->options['per_post_default'];
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
		update_post_meta($post_id, $this->metaKey('enabled'), $enabled);
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
			|| !wp_verify_nonce($_POST[$key], $this->domain)
			|| (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			|| !current_user_can("edit_$type", $post_id)
			|| !isset($_POST[$this->domain]['Post']['enabled'])) {
			return;
		}

		$this->saveEnabled($post_id, $_POST[$this->domain]['Post']['enabled']);
	}
}