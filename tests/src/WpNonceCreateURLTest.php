<?php 

use Brain\Monkey;
use Brain\Monkey\Functions;
use Brain\Monkey\WP\Filters;

use websupporter\WPNonce\WPNonceConfig;
use websupporter\WPNonce\WPNonceCreateURL;

class WpNonceCreateURLTest extends \PHPUnit_Framework_TestCase{

	public $lifetime, $action,$request, $config;

	public function setUp() {
		if ( ! defined( 'DAY_IN_SECONDS' ) ) {
			define ( 'DAY_IN_SECONDS', 86400 );
		}
		//we mock wp_create_nonce with sha1()
		Functions::when('wp_create_nonce')->alias('sha1');

		//we mock wp_nonce_url
		Functions::expect('wp_nonce_url')->andReturnUsing(function ($url, $action, $request_name) {
			return $url . $action . $request_name;
		});

		parent::setUp();
		Monkey::setUpWP();

		$this->action   = 'action';
		$this->request  = 'request';
		$this->lifetime = 123;
		$this->config = new WPNonceConfig( $this->action, $this->request, $this->lifetime );
	}

	/**
	 * Test URL creation
	 */
	public function testCreateURL() {
		$create = new WPNonceCreateURL( $this->config );
		$url = 'http://example.com/';
		$url_with_nonce = $create->create_url( $url );
	
		self::assertSame( $url_with_nonce, $url . $this->action . $this->request );

		self::assertSame( $url_with_nonce, $create->get_url() );
		self::assertSame( $create->set_url( 'abc' ), 'abc' );
	}



	public function tearDown() {
		Monkey::tearDownWP();
		parent::tearDown();
	}

}