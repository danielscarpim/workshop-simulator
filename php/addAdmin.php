<?php
	require_once('vars/connectvars.php');
	
	// Connect to the database
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
		or die ('Fail to connect with database');
	
	$addStatus = array('success' => false, 'msg' => '');
	
		// Grab the profile data from the POST
	$username = mysqli_real_escape_string($dbc, trim($_POST['username']));
	$password = mysqli_real_escape_string($dbc, trim($_POST['password']));
	
	if (!empty($username) && !empty($password)) {
		// Make sure someone isn't already registered using this username
		$query = "SELECT admin_id FROM ws_admin WHERE admin_name = '$username'";
		$data = mysqli_query($dbc, $query)
			or die ('Fail to query database 1');
		
		if (mysqli_num_rows($data) < 1) {
		
				// The username is unique, so insert the data into the database
			$query = "INSERT INTO ws_admin (admin_name, admin_password) VALUES ('$username', SHA('$password'))";
			mysqli_query($dbc, $query)
				or die ('Fail to query database 2');
			
			// Confirm success with the user
			$addStatus['success'] = true;
			$addStatus['msg'] = 'New admin has been successfully added.';
			
		} else {
			// A location already exists with this name, so display an error message
			$addStatus['msg'] = 'An admin already exists with this name. Please enter a different one.';
		}
		
	} else {
		$addStatus['msg'] = 'You must enter all of the data.';
	}
		
	mysqli_close($dbc);

	print json_encode($addStatus);
?>