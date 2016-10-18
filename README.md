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
 * Plugin Name: WPNonce Demo
 * Description: Utilize the WPNonce library in a small demo.
 * Author: David Remer
 * License: GPL2+
 *
 * @package Plugins
 **/

declare(strict_types = 1);
use websupporter\WPNonce\WPNonceConfig;
use websupporter\WPNonce\WPNonceCreateURL;
use websupporter\WPNonce\WPNonceCreateField;
use websupporter\WPNonce\WPNonceVerify;
require_once( __DIR__ . '/vendor/autoload.php' );

/**
 * WPNonceDemo
 * The demonstration class
 **/
class WPNonceDemo {

	/**
	 * The post ID to be protected by a post
	 *
	 * @var int
	 **/
	private $post_id = 726;

	/**
	 * The nonce configuration
	 *
	 * @var WPNonceConfig
	 **/
	private $nonceconfig;

	/**
	 * Starts the demo
	 **/
	function run() {

		// Configure the Nonce.
		$this->nonceconfig = new WPNonceConfig(
			'display-the-post-' . $this->post_id,  // The action.
			'_wpnonce_test',                       // The request name.
			60                                     // The lifetime.
		);

		add_action( 'template_redirect', array( $this, 'validate' ) );
		add_filter( 'document_title_parts', array( $this, 'validate_doc_title' ) );
	}

	/**
	 * Hooks into the document_title to overwrite it, in case no valid nonce is given.
	 *
	 * @param  array $title The title parts.
	 * @return array $title
	 **/
	function validate_doc_title( $title ) {
		$validate = new WPNonceVerify( $this->nonceconfig );
		if ( ! is_single( $this->post_id ) || $validate->verify() ) {
			return $title;
		}

		return array( __( 'Please use a valid nonce', 'wpnonce-test' ) );
	}
	/**
	 * Check if the correct nonce was send, when we try to read the nonce protected post.
	 **/
	function validate() {

		// Bail out if its not our protected Post.
		if ( ! is_single( $this->post_id ) ) {
			return;
		}

		// Display the nonces form, if no nonce was omitted.
		// It's not about security, it's about demonstration :).
		$validate = new WPNonceVerify( $this->nonceconfig );
		if ( ! $validate->verify() ) {
			$this->display_form();
			exit;
		}

	}


	/**
	 * Display the form with nonces and a URL with nonces.
	 **/
	function display_form() {
		get_header();

		$field = new WPNonceCreateField( $this->nonceconfig );
		?>
		<h1><?php esc_html_e( 'You may enter with the correct nonce.', 'wpnonce-test' ); ?></h1>
		<p>
			<?php esc_html_e( 'This form has a hidden field with the correct nonce. The nonces lifetime is 60 seconds, so hurry up :)' ,'wpnonce-test' ); ?>
		</p>
		<form method="post" action="<?php echo esc_url( get_permalink( $this->post_id ) ); ?>">
			<?php $field->create_field( false, true ); ?>
			<button><?php esc_html_e( 'Send', 'wpnonce-test' ); ?></button>
		</form>
		<?php
		$url = new WPNonceCreateURL( $this->nonceconfig );
		?>
		<hr />
		<p>
			<?php esc_html_e( 'You can also use the following link:' ,'wpnonce-test' ); ?>
			<a href="<?php echo esc_url( $url->create_url( get_permalink( $this->post_id ) ) ); ?>"><?php esc_html_e( 'Send nonce by URL', 'wpnonce-test' ); ?></a>
		</p>
		<?php

		get_footer();
	}
}

$noncedemo = new WPNonceDemo();
$noncedemo->run();


```

### Demo composer.json
{
    "repositories": [
        {
            "type": "vcs",
            "url" : "https://github.com/websupporter/wpnonce"
        }
    ],
    "require": {
        "websupporter/wpnonce" : "1.0.*"
    }
}
```