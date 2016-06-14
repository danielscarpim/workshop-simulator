<?php
	
	require_once('vars/connectvars.php');
	
	// Connect to the database
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
		or die ('Fail to connect with database');

	$addStatus = array('success' => false, 'msg' => '');

	// Grab the profile data from the POST
	$dealercode = mysqli_real_escape_string($dbc, trim($_POST['dealercode']));
	$locationid = mysqli_real_escape_string($dbc, trim($_POST['locationid']));
	
	if (!empty($dealercode) && !empty($locationid)){
		// Make sure someone hasn't already made a dealer with that code
		$query = "SELECT dealer_code FROM ws_dealers WHERE dealer_code = '$dealercode'";
		$data = mysqli_query($dbc, $query)
			or die ('Fail to query database 1');
		
		if (mysqli_num_rows($data) < 1) {
			
			// The dealer code is unique, so insert the data into the database
			$query = "INSERT INTO ws_dealers (dealer_code, dealer_location) VALUES ('$dealercode', '$locationid')";
			mysqli_query($dbc, $query)
				or die ('Fail to query database 2');
			
			// Confirm success with the user
			$addStatus['success'] = true;
			$addStatus['msg'] = 'New user has been successfully added.';
			
		} else {
			// An account already exists for this username, so display an error message
			$addStatus['msg'] = 'A dealer already exists with this Dealer Code. Please enter a different one.';
		}
		
	} else {
		$addStatus['msg'] = 'You must enter all of the data.';
	}
	
	mysqli_close($dbc);

	print json_encode($addStatus);
?>

