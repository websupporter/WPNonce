<?php

use Brain\Monkey;
use Brain\Monkey\Functions;
use Brain\Monkey\WP\Filters;

use websupporter\WPNonce\WPNonceConfig;
use websupporter\WPNonce\WPNonceCreate;

class WpNonceCreateTest extends \PHPUnit_Framework_TestCase{

	public $lifetime, $action,$request, $config;

	public function setUp() {
		if ( ! defined( 'DAY_IN_SECONDS' ) ) {
			define ( 'DAY_IN_SECONDS', 86400 );
		}
		//we mock wp_create_nonce with sha1()
        Functions::when('wp_create_nonce')->alias('sha1');

		parent::setUp();
		Monkey::setUpWP();

		$this->action   = 'action';
		$this->request  = 'request';
		$this->lifetime = 123;
		$this->config = new WPNonceConfig( $this->action, $this->request, $this->lifetime );
	}

	/**
	 * Check create()
	 */
	public function testCreate() {
		$create = new WPNonceCreate( $this->config );
		$nonce = $create->create();

		//Check if nonce is stored correctly
		self::assertSame( $nonce, $create->get_nonce() );
	}



	public function tearDown() {
		Monkey::tearDownWP();
		parent::tearDown();
	}

}