<?php
/**
 * Class WP_Hatena_Notation_Cache
 */
class WP_Hatena_Notation_Cache extends WP_Hatena_Notation_Domain {
	/**
	 * Get cache key
	 *
	 * @param string $key
	 * @return string
	 */
	public function key($key) {
		return $this->name . ':' . $key;
	}

	/**
	 * Set cache
	 *
	 * @param WP_Post $post
	 * @param string $key
	 * @param mixed $value
	 * @return bool
	 */
	public function set($post, $key, $value) {
		return update_post_meta($post, $this->key($key), $value);
	}

	/**
	 * Get cache
	 *
	 * @param WP_Post $post
	 * @param string $key
	 * @return mixed
	 */
	public function get($post, $key) {
		return get_post_meta($post, $this->key($key), true);
	}

	/**
	 * Delete cache
	 *
	 * @param $post
	 * @param $key
	 * @return bool
	 */
	public function delete($post, $key) {
		return delete_post_meta($post, $this->key($key));
	}
}