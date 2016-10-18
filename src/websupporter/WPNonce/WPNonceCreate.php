<?php
/**
 * Create Nonces.
 *
 * @package websupporter-wpnonce
 * @license GPL2+
 */

namespace websupporter\WPNonce;

/**
 * WPNonceCreate
 **/
class WPNonceCreate extends WPNonceAbstract {

	/**
	 * Configures the settings
	 *
	 * @since 1.0.0
	 * @param WPNonceConfig $config The configuration.
	 **/
	function __construct( WPNonceConfig $config ) {
		$this->set_action( $config->get_action() );
		$this->set_request_name( $config->get_request_name() );
		$this->set_lifetime( $config->get_lifetime() );
	}

	/**
	 * Verify a nonce
	 *
	 * @since 1.0.0
	 *
	 * @return (string) $nonce The created nonce
	 **/
	public function create() {
		$this->set_nonce( wp_create_nonce( $this->get_action() ) );
		return $this->get_nonce();
	}
}
