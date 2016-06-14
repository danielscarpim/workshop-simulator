<?php
	
	require_once('vars/connectvars.php');
	
	// Connect to the database
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
		or die ('Fail to connect with database');

	$updateStatus = array('success' => false, 'msg' => '');
	
	// Grab the profile data from the POST
	$locID = mysqli_real_escape_string($dbc, trim($_POST['locationid']));
	$wslocation = mysqli_real_escape_string($dbc, trim($_POST['locationname']));
	$wsurl = mysqli_real_escape_string($dbc, trim($_POST['locationurl']));
	
	// Make sure someone isn't already registered using this username
	$query = "SELECT location_id FROM ws_locations WHERE location_id = '$locID'";
	$data = mysqli_query($dbc, $query)
		or die ('Fail to query database 1');
	
	if (mysqli_num_rows($data) == 1) {
		// The exists, update its info
		$row = mysqli_fetch_array($data);
		
		// Password hasn't changed, so change only the rest
		$query = "UPDATE ws_locations SET location_name = '$wslocation', location_url = '$wsurl' WHERE location_id = '$locID'";
		mysqli_query($dbc, $query)
			or die ('Fail to query database 2');
		
		// Confirm success with the user
		$updateStatus['success'] = true;
		$updateStatus['msg'] = 'Location info was changed.';
		
	} else {
		// No location was found with the given ID, so display an error message
		$updateStatus['msg'] = 'No location was found with that ID.';
	}
			
	mysqli_close($dbc);

	print json_encode($updateStatus);
?>