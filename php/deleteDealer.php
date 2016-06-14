<?php
	
	require_once('vars/connectvars.php');
	
	// Connect to the database
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
		or die ('Fail to connect with database');

	$deleteStatus = array('success' => false, 'msg' => '');
	
	// Grab the profile data from the POST
	$dealerID = mysqli_real_escape_string($dbc, trim($_POST['dealerid']));
	$dealercode = mysqli_real_escape_string($dbc, trim($_POST['dealercode']));
	
	// Make sure someone isn't already registered using this username
	$query = "SELECT dealer_id FROM ws_dealers WHERE dealer_id = '$dealerID'";
	$data = mysqli_query($dbc, $query)
		or die ('Fail to query database 1');
	
	if (mysqli_num_rows($data) == 1) {
		// The username is unique, so insert the data into the database
		$query = "DELETE FROM ws_dealers WHERE dealer_id = '$dealerID'";
		mysqli_query($dbc, $query)
			or die ('Fail to query database 2');
		
		// Confirm success with the user
		$deleteStatus['success'] = true;
		$deleteStatus['msg'] = 'The user '. $username . ' was deleted.';
		
	} else {
		// No dealer found with this ID, so display an error message
		$deleteStatus['msg'] = 'No dealer found with this ID.';
	}
			
	mysqli_close($dbc);

	print json_encode($deleteStatus);
?>
