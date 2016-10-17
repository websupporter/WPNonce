<?php
namespace websupporter\WPNonce;

/**
 * WPNonceCreateField
 * The class to create a nonce field.
 *
 * @package websupporter-wpnonce
 * @license GPL2+
 */

class WPNonceCreateField extends WPNonceCreate {

	private $field = "";

	function __construct( WPNonceConfig $config ) {
		parent::__construct( $config );
	}

	/**
	 * Verify a nonce
	 * @since 2.0.0
	 *
	 * @param (boolean) $referer Whether to add a referer field or not.
	 * @param (boolean) $echo    Whether to echo the field immediatly or not.
	 * @return (string) $field   The created field.
	 **/
	public function create_field( bool $referer = null, bool $echo = null ) {
		//Make sure, we have booleans
		$referer = (bool) $referer;
		$echo = (bool) $echo;

		//Let's create a nonce to populate $nonce
		$this->create();

		$field = wp_nonce_field( $this->get_action(), $this->get_request_name(), $referer, false );
		$this->set_field( $field );

		if ( true === $echo ) {
			echo $this->get_field();
		}

		return $this->get_field();
	}

	/**
	 * Set the URL
	 * @since 2.0.0
	 *
	 * @param (string)  $new_field   The new field
	 * @return (string) $field       The field
	 **/
	public function set_field( string $new_field ) {
		$this->field = $new_field;
		return $this->get_field();
	}

	/**
	 * Get the URL
	 * @since 2.0.0
	 *
	 * @return (string) $url The URL
	 **/
	public function get_field() {
		return $this->field;
	}

}