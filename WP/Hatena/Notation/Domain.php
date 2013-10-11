<?php
/**
 * Class WP_Hatena_Notation_Domain
 */
abstract class WP_Hatena_Notation_Domain {
	/**
	 * Domain name
	 * @var string
	 */
	protected $domain = WP_HATENA_NOTATION_DOMAIN;

	/**
	 * Get ID
	 *
	 * @param string $key
	 * @return string
	 */
	public function id($key) {
		return $this->domain . '_' . $key;
	}

	/**
	 * Get nonce key
	 *
	 * @return string
	 */
	public function nonceKey() {
		return $this->domain . '_nonce';
	}

	/**
	 * Get field name
	 *
	 * @param  string $key
	 * @return string
	 */
	public function fieldName($key) {
		return $this->domain . '[' . implode('][', explode('.', $key)) . ']';
	}

	/**
	 * Get meta key
	 *
	 * @param string $key
	 * @return string
	 */
	public function metaKey($key) {
		return '_' . $this->id($key);
	}
}