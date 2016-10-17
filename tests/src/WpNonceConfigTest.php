<?php

use Brain\Monkey;
use Brain\Monkey\Functions;
use Brain\Monkey\WP\Filters;

use websupporter\WPNonce\WPNonceConfig;

class WpNonceConfigTest extends \PHPUnit_Framework_TestCase{

	public $lifetime, $action,$request, $config;

	public function setUp() {
		if ( ! defined( 'DAY_IN_SECONDS' ) ) {
			define ( 'DAY_IN_SECONDS', 86400 );
		}

		parent::setUp();
		Monkey::setUpWP();

		$this->action   = 'action';
		$this->request  = 'request';
	}

	/**
	 * Check if WPNonceConfig stores the data correctly 
	 */
	public function testCreateConfig() {
		$this->lifetime = 123;

		//The filter should be added once
		Filters::expectAdded('nonce_life')
			->once();

		$this->config = new WPNonceConfig( $this->action, $this->request, $this->lifetime );

		self::assertSame( $this->config->get_action(),       $this->action );
		self::assertSame( $this->config->get_request_name(), $this->request );
		self::assertSame( $this->config->get_lifetime(),     $this->lifetime );

		//Check if nonce_life returns the right value
		self::assertSame( $this->config->nonce_life( DAY_IN_SECONDS ), $this->lifetime );
	}

	//Check if filter is not added, when lifetime is not set.
	public function test_no_filter_added() {
		$this->lifetime = null;

		//The filter should be added once
		Filters::expectAdded('nonce_life')
			->never();
		$this->config = new WPNonceConfig( $this->action, $this->request, $this->lifetime );
	}



	public function tearDown() {
		Monkey::tearDownWP();
		parent::tearDown();
	}

}