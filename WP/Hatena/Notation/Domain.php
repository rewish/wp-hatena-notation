<?php
/**
 * Class WP_Hatena_Notation_Domain
 */
abstract class WP_Hatena_Notation_Domain {
	/**
	 * Domain name
	 * @var string
	 */
	protected $name;

	/**
	 * Constructor
	 *
	 * @param string $name
	 */
	public function __construct($name) {
		$this->name = $name;
	}

	/**
	 * Field name
	 *
	 * @param  string $key
	 * @return string
	 */
	public function fieldName($key) {
		return $this->name . '[' . implode('][', explode('.', $key)) . ']';
	}
}