<?php
namespace websupporter\WPNonce;

/**
 * WPNonceVerify
 * The class to verify a nonce.
 *
 * @package websupporter-wpnonce
 * @license GPL2+
 */

class WPNonceVerify extends WPNonceAbstract {

	public function __construct( WPNonceConfig $config ) {
		$this->set_action( $config->get_action() );
		$this->set_request_name( $config->get_request_name() );
		$this->set_lifetime( $config->get_lifetime() );

		//if the $_REQUEST is populated, we set the nonce already.
		if ( isset( $_REQUEST[ $this->get_request_name() ] ) ) {
			$this->set_nonce( $_REQUEST[ $this->get_request_name() ] );
		}
	}

	/**
	 * Verify a nonce
	 * @since 2.0.0
	 *
	 * @param (string)   $nonce The nonce to verify. (optional)
	 * @return (boolean) $valid Whether the nonce is valid or not.
	 **/
	public function verify( string $nonce = null ) {
		if( null != $nonce ) {
			$this->set_nonce( $nonce );
		}

		$valid = wp_verify_nonce( $this->get_nonce(), $this->get_action() );

		if ( $valid === false ) {
			return false;
		}
		return true;
	}

	/**
	 * Get the age of a nonce
	 * @since 2.0.0
	 *
	 * @return (string) $age  Whether the nonce is "young" (1), "old" (2) or invalid (false).
	 *                       "young" usually means 0 - 12 hours
	 *                       "old" usually means 12 - 24 hours
	 *                       it depends on the nonce lifetime
	 **/
	public function get_nonce_age() {
			$age = wp_verify_nonce( $this->get_nonce(), $this->get_action() );
			return $age;
	}

}