<?php
	
	require_once('vars/connectvars.php');
	
	// Connect to the database
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
		or die ('Fail to connect with database');

	$addStatus = array('success' => false, 'msg' => '');
	
	// Grab the profile data from the POST
	$wslocation = mysqli_real_escape_string($dbc, trim($_POST['locationname']));
	$wsurl = mysqli_real_escape_string($dbc, trim($_POST['locationurl']));
	
	if (!empty($wslocation) && !empty($wsurl)) {
		// Make sure someone isn't already registered using this username
		$query = "SELECT location_id FROM ws_locations WHERE location_name = '$wslocation'";
		
		$data = mysqli_query($dbc, $query)
			or die ('Fail to query database 1');
		
		if (mysqli_num_rows($data) < 1) {
		
			// The username is unique, so insert the data into the database
			$query = "INSERT INTO ws_locations (location_name, location_url) VALUES ('$wslocation', '$wsurl')";
			mysqli_query($dbc, $query)
				or die ('Fail to query database 2');
			
			// Confirm success with the user
			$addStatus['success'] = true;
			$addStatus['msg'] = 'New location has been successfully added.';
			
		} else {
			// A location already exists with this name, so display an error message
			$addStatus['msg'] = 'A location already exists with this name. Please enter a different one.';
		}
		
	} else {
		$addStatus['msg'] = 'You must enter all of the data.';
	}
		
	mysqli_close($dbc);

	print json_encode($addStatus);
?>
