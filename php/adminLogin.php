<?php

	require_once('vars/connectvars.php');	
	
		// Connect to the database
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
		or die ('Fail to connect to database');
	
	// Login variables
	$credentials = array('login' => false, 'error_msg' => '', 'url' => '' );
	

	$admin_name = mysqli_real_escape_string($dbc, trim($_POST['adminname']));
	$admin_password = mysqli_real_escape_string($dbc, trim($_POST['adminpassword']));
	
	// Look up the username and stars id
	$query = "SELECT admin_id, admin_name FROM ws_admin WHERE admin_name = '$admin_name' AND admin_password = SHA('$admin_password')";
	$data = mysqli_query($dbc, $query)
		or die ('Fail to query database 1');
	
	if (mysqli_num_rows($data) == 1) {
		// The log-in is OK so set the user ID and navigate to the game
		$row = mysqli_fetch_array($data);

		$credentials['login'] = true;
		
	} else {

		$credentials['error_msg'] = 'incorrect_login_admin';
	}
	
	mysqli_close($dbc);

	// print json
	print json_encode($credentials);


?>