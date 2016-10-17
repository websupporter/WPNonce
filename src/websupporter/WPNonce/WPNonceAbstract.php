<?php
namespace websupporter\WPNonce;

/**
 * WPNonceAbstract
 *
 * @package websupporter-wpnonce
 * @license GPL2+
 */

abstract class WPNonceAbstract implements WPNonceInterface {


	/**
	 * The name of the action
	 **/
	private $action = '';

	/**
	 * The name of the request
	 **/
	private $request_name = '';

	/**
	 * The nonce
	 **/
	private $nonce = '';

	/**
	 * The lifetime of a nonce in seconds
	 **/
	private $lifetime = DAY_IN_SECONDS;

	public function set_action( string $new_action ) {
		$this->action = $new_action;
	}

	public function get_action() {
		return $this->action;
	}

	public function set_request_name( string $new_request_name ) {
		$this->request_name = $new_request_name;
	}

	public function get_request_name() {
		return $this->request_name;
	}

	public function set_lifetime( int $new_lifetime ) {
		$this->lifetime = $new_lifetime;
	}

	public function get_lifetime( bool $actual_lifetime = true ) {
		if ( $actual_lifetime ) {
			// We run $lifetime through the 'nonce_life' to get the actual lifetime, which
			// the system is using right now, since other systems might interfere with
			// this filter.
			return apply_filters( 'nonce_life', $this->lifetime );
		}
		return $this->lifetime;
	}

	/**
	 * Set the nonce
	 * @since 2.0.0
	 *
	 * @param (string)  $new_nonce The nonce to verify
	 * @return (string) $nonce     The nonce
	 **/
	public function set_nonce( string $new_nonce ) {
		$this->nonce = $new_nonce;
		return $this->get_nonce();
	}

	/**
	 * Get the nonce
	 * @since 2.0.0
	 *
	 * @return (string) $nonce The nonce
	 **/
	public function get_nonce() {
		return $this->nonce;
	}
}