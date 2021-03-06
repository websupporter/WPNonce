<?php
/**
 * WPNonceAbstract
 * abstract class with the basic variables and functionalities of the Nonce API system.
 *
 * @package websupporter-wpnonce
 * @license GPL2+
 */

namespace websupporter\WPNonce;

/**
 * WPNonceAbstract class
 **/
abstract class WPNonceAbstract implements WPNonceInterface {


	/**
	 * The name of the action
	 *
	 * @var string
	 **/
	private $action = '';

	/**
	 * The name of the request
	 *
	 * @var string
	 **/
	private $request_name = '';

	/**
	 * The nonce
	 *
	 * @var string
	 **/
	private $nonce = '';

	/**
	 * The lifetime of a nonce in seconds
	 *
	 * @var int
	 **/
	private $lifetime = DAY_IN_SECONDS;


	/**
	 * Set the action
	 *
	 * @since 1.0.0
	 *
	 * @param string $new_action The new action name.
	 * @return string $action     The action.
	 **/
	public function set_action( string $new_action ) {
		$this->action = $new_action;
		return $this->get_action();
	}

	/**
	 * Get the action
	 *
	 * @since 1.0.0
	 *
	 * @return string $action The action.
	 **/
	public function get_action() {
		return $this->action;
	}

	/**
	 * Set the request name
	 *
	 * @since 1.0.0
	 *
	 * @param string $new_request_name The new request name.
	 * @return string $request_name    The request name.
	 **/
	public function set_request_name( string $new_request_name ) {
		$this->request_name = $new_request_name;
		return $this->get_request_name();
	}

	/**
	 * Get the request name
	 *
	 * @since 1.0.0
	 *
	 * @return string $request_name The request name.
	 **/
	public function get_request_name() {
		return $this->request_name;
	}

	/**
	 * Set the lifetime
	 *
	 * @since 1.0.0
	 *
	 * @param int $new_lifetime The new lifetime.
	 * @return int $lifetime    The current lifetime.
	 **/
	public function set_lifetime( int $new_lifetime ) {
		$this->lifetime = $new_lifetime;
		return $this->get_lifetime();
	}

	/**
	 * Get the lifetime
	 *
	 * @since 1.0.0
	 *
	 * @param boolean $actual_lifetime Whether to use the lifetime used by WordPress or the lifetime stored in $lifetime. Optional. Default: true.
	 * @return int $lifetime       The lifetime.
	 **/
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
	 *
	 * @since 1.0.0
	 *
	 * @param string $new_nonce The nonce to verify.
	 * @return string $nonce    The nonce
	 **/
	public function set_nonce( string $new_nonce ) {
		$this->nonce = $new_nonce;
		return $this->get_nonce();
	}

	/**
	 * Get the nonce
	 *
	 * @since 1.0.0
	 *
	 * @return (string) $nonce The nonce
	 **/
	public function get_nonce() {
		return $this->nonce;
	}
}
