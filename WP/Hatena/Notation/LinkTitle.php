<?php
/**
 * WP Hatena Notation LinkTitle
 */
class WP_Hatena_Notation_LinkTitle {
	/**
	 * wpdb instance
	 * @var wpdb
	 */
	protected $wpdb;

	/**
	 * Table name
	 * @var string
	 */
	protected $table;

	/**
	 * Title titles
	 * @var array
	 */
	protected $titles;

	/**
	 * Expired
	 *
	 * @param stdClass $row
	 * @param int $expiryDays
	 * @return bool
	 * @TODO Kirei Kirei
	 */
	public static function isExpired($row, $expiryDays) {
		if ($expiryDays <= 0 || !$row || empty($row->modified_at)) {
			return true;
		}

		return time() < strtotime("+{$expiryDays} day", strtotime($row->modified_at));
	}

	/**
	 * Get instance
	 *
	 * @staticvar WP_Hatena_Notation_LinkTitle $instance
	 * @return WP_Hatena_Notation_LinkTitle
	 */
	public static function getInstance() {
		static $instance = null;
		return $instance ? $instance : $instance = new self();
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->table = $wpdb->prefix . 'hatena_notation_link_titles';
	}

	/**
	 * Get title
	 *
	 * @param string $url
	 * @param int $expiryDays
	 * @return string
	 */
	public function get($url, $expiryDays = 90) {
		$key = sha1($url);

		if (!empty($this->titles[$key])) {
			return $this->titles[$key];
		}

		$row = $this->find($url);

		if (!self::isExpired($row, $expiryDays)) {
			return $row->title;
		}

		$title = $this->fetch($url);

		if (!$row) {
			$this->insert($url, $title);
		} else {
			$this->update($url, $title);
		}

		return $this->titles[$key] = $title;
	}

	/**
	 * Fetch title
	 *
	 * @param string $url
	 * @return string
	 */
	public function fetch($url) {
		$response = wp_remote_get($url);

		if (is_wp_error($response) || empty($response['body'])) {
			return $url;
		}

		$body = $response['body'];
		$title = null;

		if (!preg_match('_<title.*?>(.+)</title>_is', $body, $title)) {
			return $url;
		}

		$encoding = mb_internal_encoding();
		$detected = mb_detect_encoding($body, 'JIS, UTF-8, eucjp-win, sjis-win');
		$encodedTitle = mb_convert_encoding($title[1], $encoding, $detected);

		return html_entity_decode($encodedTitle, ENT_NOQUOTES, $encoding);
	}

	/**
	 * Find by URL
	 *
	 * @param string $url
	 * @return stdClass
	 */
	public function find($url) {
		return $this->wpdb->get_row("SELECT `title`, `modified_at` FROM `$this->table` WHERE `url` = '$url' LIMIT 1");
	}

	/**
	 * Insert
	 *
	 * @param string $url
	 * @param string $title
	 */
	public function insert($url, $title) {
		$this->wpdb->query("INSERT INTO `$this->table` (`url`, `title`) VALUES ('$url', '$title')");
	}

	/**
	 * Update
	 *
	 * @param string $url
	 * @param string $title
	 */
	public function update($url, $title) {
		$this->wpdb->query("UPDATE `$this->table` SET `title` = '$title' WHERE `url` = '$url'");
	}

	/**
	 * Create table
	 */
	public function create() {
		$this->wpdb->query("CREATE TABLE IF NOT EXISTS `$this->table` (
			`url` varchar(255) NOT NULL,
			`title` varchar(255) NOT NULL,
			`modified_at` TIMESTAMP,
			PRIMARY KEY (`url`)
		) " . $this->wpdb->get_charset_collate());
	}

	/**
	 * Truncate table
	 */
	public function truncate() {
		$this->wpdb->query("TRUNCATE TABLE IF EXISTS `$this->table`");
	}

	/**
	 * Drop table
	 *
	 * @return boolean
	 */
	public function drop() {
		$this->wpdb->query("DROP TABLE IF EXISTS `$this->table`");
	}
}