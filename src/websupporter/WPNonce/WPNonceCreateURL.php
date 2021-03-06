<?php
/**
 * WPNonceCreateURL
 * To create a URL with a nonce.
 *
 * @package websupporter-wpnonce
 * @subpackage WPNonceCreate
 * @license GPL2+
 */

namespace websupporter\WPNonce;

/**
 * WPNonceCreateURL
 **/
class WPNonceCreateURL extends WPNonceCreate {

	/**
	 * The URL
	 *
	 * @var string
	 **/
	private $url = '';

	/**
	 * Configure the class.
	 *
	 * @param WPNonceConfig $config The configuration instance.
	 **/
	function __construct( WPNonceConfig $config ) {
		parent::__construct( $config );
	}

	/**
	 * Verify a nonce
	 *
	 * @since 1.0.0
	 *
	 * @param string $url The URL to append the Nonce.
	 * @return string $nonce The created nonce
	 **/
	public function create_url( string $url ) {
		// Let's create a nonce to populate $nonce.
		$this->create();

		$url = wp_nonce_url( $url, $this->get_action(), $this->get_request_name() );
		$this->set_url( $url );
		return $this->get_url();
	}

	/**
	 * Set the URL
	 *
	 * @since 1.0.0
	 *
	 * @param string $new_url The new URL.
	 * @return string $nonce  The URL
	 **/
	public function set_url( string $new_url ) {
		$this->url = $new_url;
		return $this->get_url();
	}

	/**
	 * Get the URL
	 *
	 * @since 1.0.0
	 *
	 * @return string $url The URL
	 **/
	public function get_url() {
		return $this->url;
	}

}
