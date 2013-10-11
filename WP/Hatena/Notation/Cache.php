<?php
/**
 * Class WP_Hatena_Notation_Cache
 */
class WP_Hatena_Notation_Cache extends WP_Hatena_Notation_Domain {
	/**
	 * Set cache
	 *
	 * @param WP_Post $post
	 * @param string $key
	 * @param mixed $value
	 * @return bool
	 */
	public function set($post, $key, $value) {
		$post = get_post($post);
		return update_post_meta($post->ID, $this->metaKey($key), $value);
	}

	/**
	 * Get cache
	 *
	 * @param WP_Post $post
	 * @param string $key
	 * @return mixed
	 */
	public function get($post, $key) {
		$post = get_post($post);
		return get_post_meta($post->ID, $this->metaKey($key), true);
	}

	/**
	 * Delete cache
	 *
	 * @param $post
	 * @param $key
	 * @return bool
	 */
	public function delete($post, $key) {
		$post = get_post($post);
		return delete_post_meta($post->ID, $this->metaKey($key));
	}
}