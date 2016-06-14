<?php
	
	require_once('vars/connectvars.php');
	
	// Connect to the database
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
		or die ('Fail to connect with database');

	$deleteStatus = array('success' => false, 'msg' => '');
	
		// Grab the profile data from the POST
	$username = mysqli_real_escape_string($dbc, trim($_POST['adminname']));
	$myindex = mysqli_real_escape_string($dbc, trim($_POST['adminID']));
	
		// Make sure someone isn't already registered using this username
	$query = "SELECT admin_id, admin_name FROM ws_admin WHERE admin_id = '$myindex'";
	$data = mysqli_query($dbc, $query)
		or die ('Fail to query database 1');
	
	if (mysqli_num_rows($data) == 1) {
			// The username is unique, so insert the data into the database
		$query = "DELETE FROM ws_admin WHERE admin_id = '$myindex'";
		mysqli_query($dbc, $query)
			or die ('Fail to query database 2');
		
			// Confirm success with the user
		$deleteStatus['success'] = true;
		$deleteStatus['msg'] = 'The admin '. $username . ' was deleted.';
		
	} else {
		// An account already exists for this username, so display an error message
		$deleteStatus['msg'] = 'No Admin found with this ID.';
	}
	
	mysqli_close($dbc);

	print json_encode($deleteStatus);

?>