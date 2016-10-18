[![Build Status](https://travis-ci.org/websupporter/WPNonce.svg?branch=master)](https://travis-ci.org/websupporter/WPNonce) [![codecov](https://codecov.io/gh/websupporter/WPNonce/branch/master/graph/badge.svg)](https://codecov.io/gh/websupporter/WPNonce)
WPNonce
===================

An objectoriented approach to use the WordPress Nonce System
----------

## Get started

### Requirements:
* PHP 7
* WordPress 2.0.3


### The configuration:
```
$config = new WPNonceConfig( 
	'action', 
	'request_name' 
);
```
WordPress Nonces need an action name, something like "create-post", to identify the current action, which is supposed to be secured by a nonce. The first parameter of the configuration defines this name. Nonces are passed through Forms or URLs, so usually via `$_POST` or `$_GET`. The second parameter identifies the key. In this case, we would expect the nonce to be in `$_REQUEST['request_name']`.


### Ways to create a nonce
If you just want to get the nonce, you can use `WPNonceCreate`:
```
$create = new WPNonceCreate( $config );
$nonce = $create->create();
```

But WordPress gives you two shortcuts to implement nonces quickly into URLs or into forms. To add a nonce to an URL, you can use

```
$create = new WPNonceCreateURL( $config );
$url = $create->create_url( 'http://example.com/' );
```
This would add the nonce to the given URL. With the `$config` from above, we would get this URL:
`http://example.com/?request_name=$nonce`

With `WPNonceCreateField` you can create a hidden form field to use it in your forms:
```
$create = new WPNonceCreateField( $config );
$field = $create->create_field();
```

`$field` would now contain the HTML string for an hidden field. With the `$config` from above, this would look like
`<input type="hidden" name="request_name" value="$nonce">`

To replicate the `wp_nonce_field()` functionality, you can add two parameters: `(bool) $referer` and `(bool) $echo`. Both are set to `false` by default. 

If you set `$referer` `true` an additional field will be appended, containing the URL of the current page as a value. 

If you set `$echo` `true`, you will immediatly `echo` the field, before `create_url()` returns it.

### Validate a nonce

To validate a nonce, you can use `WPNonceVerify`:
```
$create = new WPNonceVerify( $config );
$is_valid = $create->verify( $nonce );
```

This will check if the nonce, you have passed is valid for the current configuration. Since you most likely want to validate the nonce, which is passed via `$_POST` or `$_GET` with the request name given in `$config`, you can just use `$create->verify()` since `WPNonceVerify` has already set this nonce (if given).

## A quick demo plugin
```
<?php
/**
 * WPNonceCreateField
 * The class to create a nonce field.
 *
 * @package websupporter-wpnonce
 * @subpackage WPNonceCreate
 * @license GPL2+
 */

namespace websupporter\WPNonce;

/**
 * WPNonceCreateField
 **/
class WPNonceCreateField extends WPNonceCreate {

	/**
	 * The field
	 *
	 * @var string
	 **/
	private $field = '';

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
	 * @since 2.0.0
	 *
	 * @param boolean $referer Whether to add a referer field or not.
	 * @param boolean $echo    Whether to echo the field immediatly or not.
	 * @return string $field   The created field.
	 **/
	public function create_field( bool $referer = null, bool $echo = null ) {
		// Make sure, we have booleans.
		$referer = (bool) $referer;
		$echo = (bool) $echo;

		// Let's create a nonce to populate $nonce.
		$this->create();

		$field = wp_nonce_field( $this->get_action(), $this->get_request_name(), $referer, false );
		$this->set_field( $field );

		if ( true === $echo ) {
			echo wp_kses(
				$this->get_field(),
				array(
					'input' => array(
						'type'  => array(),
						'id'    => array(),
						'name'  => array(),
						'value' => array(),
					),
				)
			);
		}

		return $this->get_field();
	}

	/**
	 * Set the URL
	 *
	 * @since 2.0.0
	 *
	 * @param  string $new_field   The new field.
	 * @return string $field       The field
	 **/
	public function set_field( string $new_field ) {
		$this->field = $new_field;
		return $this->get_field();
	}

	/**
	 * Get the URL
	 *
	 * @since 2.0.0
	 *
	 * @return string $url The URL
	 **/
	public function get_field() {
		return $this->field;
	}

}
```