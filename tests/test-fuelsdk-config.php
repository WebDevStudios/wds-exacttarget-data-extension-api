<?php

class WDS_ET_DE_Fuelsdk_Config_Test extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'WDS_ET_DE_Fuelsdk_Config') );
	}

	function test_class_access() {
		$this->assertTrue( wds_exacttarget_data_extension_api()->fuelsdk_config instanceof WDS_ET_DE_Fuelsdk_Config );
	}
}
