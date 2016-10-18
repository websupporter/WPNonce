<?php
/**
 * WPNonceInterface
 * The interface of the Nonce API system.
 *
 * @package websupporter-wpnonce
 * @license GPL2+
 */

namespace websupporter\WPNonce;

/**
 * Interface WPNonce
 *
 * @package websupporter-wpnonce
 * @license GPL2+
 */

interface WPNonceInterface {

	/**
	 * Set a new action
	 *
	 * @since 1.0.0
	 *
	 * @param  string $new_action The new action.
	 * @return string $action    The action
	 **/
	public function set_action( string $new_action );

	/**
	 * Get the action
	 *
	 * @since 1.0.0
	 *
	 * @return string $action The action
	 **/
	public function get_action();


	/**
	 * Set a new request name
	 *
	 * @since 1.0.0
	 *
	 * @param  string $new_request_name The new request name for $_REQUEST.
	 * @return string $request         The request
	 **/
	public function set_request_name( string $new_request_name );


	/**
	 * Get the request name
	 *
	 * @since 1.0.0
	 *
	 * @return string $request The request name
	 **/
	public function get_request_name();


	/**
	 * Set a new lifetime
	 *
	 * @since 1.0.0
	 *
	 * @param  int $new_lifetime The new lifetime.
	 * @return int $lifetime     The lifetime
	 **/
	public function set_lifetime( int $new_lifetime );


	/**
	 * Get the lifetime
	 *
	 * @since 1.0.0
	 *
	 * @param  boolean $actual_lifetime Whether to run the 'nonce_life' filter or not. Optional. Default is true.
	 * @return int     $lifetime     The lifetime
	 **/
	public function get_lifetime( bool $actual_lifetime = true );

	/**
	 * Set the nonce
	 *
	 * @since 1.0.0
	 *
	 * @param  string $new_nonce The nonce to verify.
	 * @return string $nonce     The nonce
	 **/
	public function set_nonce( string $new_nonce );

	/**
	 * Get the nonce
	 *
	 * @since 1.0.0
	 *
	 * @return (string) $nonce The nonce
	 **/
	public function get_nonce();

}
