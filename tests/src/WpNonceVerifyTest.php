<?php

use Brain\Monkey;
use Brain\Monkey\Functions;
use Brain\Monkey\WP\Filters;

use websupporter\WPNonce\WPNonceConfig;
use websupporter\WPNonce\WPNonceCreate;
use websupporter\WPNonce\WPNonceVerify;

class WpNonceVerifyTest extends \PHPUnit_Framework_TestCase{

	public $lifetime, $action,$request, $config;

	public function setUp() {
		if ( ! defined( 'DAY_IN_SECONDS' ) ) {
			define ( 'DAY_IN_SECONDS', 86400 );
		}
		//we mock wp_create_nonce with sha1()
        Functions::when('wp_create_nonce')->alias('sha1');

		//we mock wp_verify_nonce
		Functions::expect('wp_verify_nonce')->andReturnUsing(function ($nonce, $action) {
			return sha1($action) === $nonce;
		});

		//we mock wp_unslash
		Functions::expect('wp_unslash')->andReturnUsing(function ($string) {
			return $string;
		});
		//we mock sanitize_text_field
		Functions::expect('sanitize_text_field')->andReturnUsing(function ($string) {
			return $string;
		});

		parent::setUp();
		Monkey::setUpWP();

		$this->action   = 'action';
		$this->request  = 'request';
		$this->lifetime = 123;
		$this->config = new WPNonceConfig( $this->action, $this->request, $this->lifetime );
	}

	/**
	 * Check validation
	 */
	public function testValidity() {
		$create = new WPNonceCreate( $this->config );
		$nonce = $create->create();

		$verify = new WPNonceVerify( $this->config );
		$valid = $verify->verify( $nonce );

		//Check if nonce is valid
		self::assertTrue( $valid );

		//Check if nonce is not valid
		$not_valid = $verify->verify( 'not-valid' . $nonce );
		self::assertFalse( $not_valid );

		//Check auto-nonce assignment
		$_REQUEST[ $this->request ] = $nonce;
		$verify = new WPNonceVerify( $this->config );
		$valid = $verify->verify();
		self::assertTrue( $valid );
	}

	/**
	 * Check age
	 **/
	public function testAge() {
		self::markTestSkipped( 'Skipped. wp_verify_nonce() needs a better mockup to test this functionality.' );
		$create = new WPNoncecreate( $this->config );
		$nonce = $create->create();

		$verify = new WPNonceVerify( $this->config );
		$age = $verify->get_nonce_age( $nonce );

		self::assertSame( 1, $age );
	}



	public function tearDown() {
		Monkey::tearDownWP();
		parent::tearDown();
	}

}