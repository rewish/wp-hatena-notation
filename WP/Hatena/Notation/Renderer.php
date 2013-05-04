<?php
/**
 * WP Hatena Notation Renderer
 */
class WP_Hatena_Notation_Renderer extends HatenaSyntax_Renderer {
	/**
	 * LinkTitle instance
	 * @var WP_Hatena_Notation_LinkTitle
	 */
	protected $LinkTitle;

	/**
	 * Constructor
	 *
	 * @param array $config
	 */
	public function __construct(Array $config = array()) {
		parent::__construct($config + array(
			'htmlescape'         => false,
			'keywordlinkhandler' => array($this, 'onKeywordLink'),
			'superprehandler'    => array($this, 'onSuperPre'),
			'linktitlehandler'   => array($this, 'onLinkTitle')
		));
	}

	/**
	 * Render
	 *
	 * @param  HatenaSyntax_Node $rootnode
	 * @return string
	 * @throws InvalidArgumentException
	 */
	public function render(HatenaSyntax_Node $rootnode) {
		if ($rootnode->getType() !== 'root') {
			throw new InvalidArgumentException();
		}

		$this->footnote = '';
		$this->fncount = 0;
		$this->root = $rootnode;
		$this->headerCount = 0;

		$ret = str_replace('%content%', $this->renderNode($rootnode), $this->config['textbody_html']);

		if ($this->fncount > 0) {
			$ret .= PHP_EOL . str_replace('%content%', $this->footnote, $this->config['footnote_html']);
		}

		return $ret;
	}

	/**
	 * Render HTTP link
	 *
	 * @param  array $data
	 * @return string
	 */
	protected function renderHttpLink(Array $data) {
		$ret = parent::renderHttpLink($data);
		if ($this->config['link_target_blank']) {
			return strtr($ret, array('">' => '" target="_blank">'));
		}
		return $ret;
	}

	/**
	 * Render paragraph
	 *
	 * @param  array $data
	 * @return string
	 */
	protected function renderParagraph(Array $data) {
		switch ($this->config['linebreak_method']) {
			case 'plugin':
				return '<p>' . $this->renderLineSegment($data) . '</p>';
			case 'wpautop':
				return wpautop($this->renderLineSegment($data));
			case 'wordpress':
			default:
				return $this->renderLineSegment($data);
		}
	}

	/**
	 * Render separator
	 *
	 * @return string
	 */
	protected function renderSeparator() {
		return '<!--more-->' . PHP_EOL;
	}

	/**
	 * Super pre handler
	 *
	 * @param  string $type
	 * @param  array $lines
	 * @return string
	 */
	public function onSuperPre($type, $lines) {
		if ($this->config['superpre_method'] === 'geshi') {
			$geshi = new GeSHi(implode(PHP_EOL, $lines), $type);
			$geshi->enable_classes();
			$geshi->set_overall_class('highlight');
			return $geshi->parse_code();
		}

		foreach ($lines as &$line) {
			$line = self::escape($line);
		}

		return str_replace(
			array('%type%', '%content%'),
			array(self::escape($type), implode(PHP_EOL, $lines)),
			$this->config['superpre_html']
		);
	}

	/**
	 * Keyword link handler
	 *
	 * @global WP_Rewrite $wp_rewrite
	 * @param  string $path
	 * @return string Keyword page URL
	 */
	public function onKeywordLink($path) {
		global $wp_rewrite;

		$tagURL = $wp_rewrite->get_tag_permastruct();
		$path = urlencode($path);

		if (empty($tagURL)) {
			$tagURL = '?tag=' . $path;
		} else {
			$tagURL = str_replace('%tag%', $path, $tagURL);
		}

		return get_option('home') . user_trailingslashit($tagURL, 'category');
	}

	/**
	 * Link title handler
	 *
	 * @param  string $url
	 * @return string Page title
	 */
	public function onLinkTitle($url) {
		return WP_Hatena_Notation_LinkTitle::getInstance()->get($url, $this->config['title_expires']);
	}
}
