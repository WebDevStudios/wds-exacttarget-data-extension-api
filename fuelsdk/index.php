<?php
echo '<pre>';
require('ET_Client.php');
try {
	$myclient = new ET_Client();

	//DataExtension Testing
	// Get all Data Extensions
	print_r("Get all Data Extensions \n");
	$getDE = new ET_DataExtension();
	$getDE->authStub = $myclient;
	$getDE->props = array("CustomerKey", "Name");
	$getResult = $getDE->get();
	// print_r('Get Status: '.($getResult->status ? 'true' : 'false')."\n");
	// print 'Code: '.$getResult->code."\n";
	// print 'Message: '.$getResult->message."\n";
	// print_r('More Results: '.($getResult->moreResults ? 'true' : 'false')."\n");
	// print 'Result Count: '.count($getResult->results)."\n";
	print 'Results: '."\n";
	print_r($getResult->results);
	print "\n---------------\n";

	// die;


// We get these keys from ^^^^^^^^^^
$keys = array( 'B375AF00-44ED-4499-82E9-C4A52EE61D84' );


foreach ( $keys as $key ) {

		//Get single Data Extension
		// print_r("Get single Data Extension \n");
		// $getDE = new ET_DataExtension();
		// $getDE->authStub = $myclient;
		// $getDE->props = array("CustomerKey", "Name");
		// $getDE->filter = array('Property' => 'CustomerKey','SimpleOperator' => 'equals','Value' => $key );
		// $getResult = $getDE->get();
		// print_r('Get Status: '.($getResult->status ? 'true' : 'false')."\n");
		// print 'Code: '.$getResult->code."\n";
		// print 'Message: '.$getResult->message."\n";
		// print_r('More Results: '.($getResult->moreResults ? 'true' : 'false')."\n");
		// print 'Result Count: '.count($getResult->results)."\n";
		// print 'Results: '."\n";
		// print_r($getResult->results);
		// print "\n---------------\n";

		// These are different depending on the below.
		$prop_atts = array(
			'ReminderDate',
			'RemindMe',
			'EMailAddress',
			'SubmittedDate',
			'SubscriberKey',
			'Notes',
			'Event',
			'Active',
			'Recipe_Id',
		);

		$prop_vals = array(
			'ReminderDate' => '12/12/2106',
			'RemindMe' => time(),
			'EMailAddress' => 'aubrey_test@webdevstudios.com',
			'SubmittedDate' => '12/12/2106',
			'EventDate' => '12/12/2106',
			'SubscriberKey' => time(),
			'Notes' => time(),
			'Event' => time(),
			'Active' => time(),
			'Recipe_Id' => time(),
		);

		print_r("Get all Data Extensions Columns filter by specific DE \n");
		$getDEColumns = new ET_DataExtension_Column();
		$getDEColumns->authStub = $myclient;
		$getDEColumns->props = array("CustomerKey", "Name");
		$getDEColumns->filter = array('Property' => 'CustomerKey','SimpleOperator' => 'equals','Value' => $key );
		$getResult = $getDEColumns->get();
		print_r('Get Status: '.($getResult->status ? 'true' : 'false')."\n");
		print 'Code: '.$getResult->code."\n";
		print 'Message: '.$getResult->message."\n";
		print_r('More Results: '.($getResult->moreResults ? 'true' : 'false')."\n");
		print 'Result Count: '.count($getResult->results)."\n";
		print 'Results: '."\n";
		print_r($getResult->results);
		print "\n---------------\n";

		//Get all Data Extensions Rows (By CustomerKey)
		print_r("Get all Data Extensions Rows (By CustomerKey) \n");
		$getDERows = new ET_DataExtension_Row();
		$getDERows->authStub = $myclient;
		$getDERows->props = $prop_atts;
		$getDERows->CustomerKey = $key;
		$getResult = $getDERows->get();
		print_r('Get Status: '.($getResult->status ? 'true' : 'false')."\n");
		print 'Code: '.$getResult->code."\n";
		print 'Message: '.$getResult->message."\n";
		print_r('More Results: '.($getResult->moreResults ? 'true' : 'false')."\n");
		print 'Result Count: '.count($getResult->results)."\n";
		print 'Results: '."\n";
		print_r($getResult->results);
		print "\n---------------\n";

		// Add a row to a DataExtension
		// print_r("Add a row to a DataExtension  \n");
		// $postDRRow = new ET_DataExtension_Row();
		// $postDRRow->authStub = $myclient;
		// $postDRRow->props =  $prop_vals;
		// $postDRRow->Name = 'RECIPE_REMINDER_TEST';
		// $postResult = $postDRRow->post();
		// print_r('Post Status: '.($postResult->status ? 'true' : 'false')."\n");
		// print 'Code: '.$postResult->code."\n";
		// print 'Message: '.$postResult->message."\n";
		// print 'Result Count: '.count($postResult->results)."\n";
		// print 'Results: '."\n";
		// print_r($postResult->results);
		// print "\n---------------\n";


		echo "<hr>";

}




}
	catch (Exception $e) {
  echo 'Caught exception: ',  $e->getMessage(), "\n";
}

?>



