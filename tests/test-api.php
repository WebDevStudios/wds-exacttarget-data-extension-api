<?php

class WDS_ET_DE_API_Test extends WP_UnitTestCase {

	protected $extension_key = '1EAFD6CE-9D53-4637-BA5B-0C18DD47D951';

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'WDS_ET_DE_API' ) );
	}

	function test_class_access() {
		$api = new WDS_ET_DE_API();

		$this->assertTrue( $api instanceof WDS_ET_DE_API );
	}

	function test_add_success() {

		$api    = new WDS_ET_DE_API();
		$result = $api->add( $this->extension_key, $this->get_test_add_parameters() );

		// todo: provide more specificity here rather than just checking for a truthy value?
		$this->assertTrue( $result );
	}

	function test_add_invalid_extension_key() {

		$api    = new WDS_ET_DE_API();
		$result = $api->add( '123', $this->get_test_add_parameters() );

		$this->assertTrue( false === $result );
	}

	function test_add_invalid_parameters() {

		$api    = new WDS_ET_DE_API();
		$result = $api->add( $this->extension_key, '' );

		$this->assertTrue( false === $result );
	}

	function get_test_add_parameters() {
		return array(
			'Email Address'   => time() . '@webdevstudios.com',
			'first_name'      => 'Elwood',
			'last_name'       => 'Blues',
			'zip'             => '60613',
			'store_address'   => '1060 W Addison St, Chicago, IL 60613',
			'WeeklySavor'     => 'True',
			'CookingSchool'   => 'False',
			'Festivals'       => 'True',
			'Account'         => 'False',
			'cooking_address' => '1060 W Addison St, Chicago, IL 60613',
		);
	}
}
