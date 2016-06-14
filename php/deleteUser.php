<?php
	
	require_once('vars/connectvars.php');
	
	// Connect to the database
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
		or die ('Fail to connect with database');

	$deleteStatus = array('success' => false, 'msg' => '');
	

	// Grab the profile data from the POST
	$userID = mysqli_real_escape_string($dbc, trim($_POST['userid']));
	$username = mysqli_real_escape_string($dbc, trim($_POST['username']));
	
	// Make sure someone isn't already registered using this username
	$query = "SELECT user_id FROM ws_users WHERE user_id = '$userID'";
	$data = mysqli_query($dbc, $query)
		or die ('Fail to query database 1');
	
	if (mysqli_num_rows($data) == 1) {
		// Found the user, so delete him/her from the database
		$query = "DELETE FROM ws_users WHERE user_id = '$userID'";
		mysqli_query($dbc, $query)
			or die ('Fail to query database 2');
		
		// Confirm success with the user
		$deleteStatus['success'] = true;
		$deleteStatus['msg'] = 'The user '. $username . ' was deleted.';
		
	} else {
		// No user was found, so display an error message
		$deleteStatus['msg'] = 'No user found with this ID.';
	}

	mysqli_close($dbc);
	
	print json_encode($deleteStatus);
	
?>

