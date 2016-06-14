<?php
	
	require_once('vars/connectvars.php');
	
	// Connect to the database
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
		or die ('Fail to connect with database');

	$deleteStatus = array('success' => false, 'msg' => '');

	// Grab the profile data from the POST
	$locID = mysqli_real_escape_string($dbc, trim($_POST['locationid']));
	$wslocation = mysqli_real_escape_string($dbc, trim($_POST['locationname']));
	
	// Make sure someone isn't already registered using this username
	$query = "SELECT location_id FROM ws_locations WHERE location_id = '$locID'";
	$data = mysqli_query($dbc, $query)
		or die ('Fail to query database 1');
	
	if (mysqli_num_rows($data) == 1) {
		// The username is unique, so insert the data into the database
		$query = "DELETE FROM ws_locations WHERE location_id = '$locID'";
		mysqli_query($dbc, $query)
			or die ('Fail to query database 2');
		
		// Confirm success with the user
	$deleteStatus['success'] = true;
	$deleteStatus['msg'] = 'The location '. $wslocation . ' was deleted.';
		
	} else {
		// An account already exists for this username, so display an error message
		$deleteStatus['msg'] = 'No Location found with this ID.';
	}
	
	mysqli_close($dbc);

	print json_encode($deleteStatus);

?>