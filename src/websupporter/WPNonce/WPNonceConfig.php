<?php
/**
 * The configuration class.
 *
 * @package websupporter-wpnonce
 * @license GPL2+
 */

namespace websupporter\WPNonce;

/**
 * WPNonceConfig
 **/
class WPNonceConfig extends WPNonceAbstract {

	/**
	 * Configuration
	 *
	 * @since 1.0.0
	 *
	 * @param string $new_action       The new action.
	 * @param string $new_request_name The new request name.
	 * @param int    $new_lifetime     The new lifetime.
	 **/
	function __construct( string $new_action, string $new_request_name, int $new_lifetime = null ) {
		$this->set_action( $new_action );

		$this->set_request_name( $new_request_name );

		if ( null != $new_lifetime ) {
			$this->set_lifetime( $new_lifetime );

			// Since we want to alter the lifetime, we hook into the nonce_life filter.
			add_filter( 'nonce_life', array( $this, 'nonce_life' ) );
		}
	}

	/**
	 * Hooks into the nonce_life filter if necessary.
	 *
	 * @since 1.0.0
	 *
	 * @param  (integer) $old_lifetime The old lifetime.
	 * @return (integer) $lifetime     The lifetime.
	 **/
	public function nonce_life( $old_lifetime ) {
		// Since we want to set the lifetime to our $lifetime,
		// we call get_lifetime() with $actual_lifetime false.
		return $this->get_lifetime( false );
	}


}
