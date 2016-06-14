<?php
	
	require_once('vars/connectvars.php');
	
	// Connect to the database
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
		or die ('Fail to connect with database');
	
	$dealerssids = array();
	$dealerscodes = array();
	
		// Grab the user's data to create the form and edit the users
	if (count($dealerssids) < 1) {
		
		$query = "SELECT dealer_id, dealer_code FROM ws_dealers WHERE 1";
		$data = mysqli_query($dbc, $query)
			or die ('Fail to query database (locations)');
		
		if (mysqli_num_rows($data) >= 1) {
			while ($row = mysqli_fetch_array($data)) { 
					// save the data in the arrays
				array_push ($dealerssids, $row['dealer_id']);
				array_push ($dealerscodes, $row['dealer_code']);
			}
		}
	}
	
	function onGetName ($dlr_id) {
		for($i = 0; $i < count($dealerssids); $i++) {
			if ($dealerssids[$i] == $dlr_id)
				return $dealerscodes[$i];
		}
		
		return '';
	}

	$updateStatus = array('success' => false, 'msg' => '');

	// Grab the user data from the POST
	$userID = mysqli_real_escape_string($dbc, trim($_POST['userid']));
	$username = mysqli_real_escape_string($dbc, trim($_POST['username']));
	$starsid = mysqli_real_escape_string($dbc, trim($_POST['starsid']));
	$dealerid = mysqli_real_escape_string($dbc, trim($_POST['dealerid']));
	
	// Make sure there is a user with that id
	$query = "SELECT user_id FROM ws_users WHERE user_id = '$userID'";
	$data = mysqli_query($dbc, $query)
		or die ('Fail to query database 1');
		
	if (mysqli_num_rows($data) == 1) {
		// The user exists, update its info
		$query = "SELECT user_id FROM ws_users WHERE user_id <> '$userID' AND user_stars = '$starsid'";
		$data = mysqli_query($dbc, $query)
			or die ('Fail to query database 1');
			
		if (mysqli_num_rows($data) < 1) {
			// Valid Stars ID
			$query = "UPDATE ws_users SET user_name = '$username', user_stars = '$starsid', user_dealer = '$dealerid' WHERE user_id = '$userID'";
			mysqli_query($dbc, $query)
				or die ('Fail to query database 2');
			
			// Confirm success with the user
			$updateStatus['success'] = true;
			$updateStatus['msg'] = 'User info was changed.';
			
		} else {
			// There is already another user with this Stars ID, so display an error message
			$updateStatus['msg'] = 'Another user was found with that Stars ID.';

		}
		
	} else {
		// No user was found with the given ID, so display an error message
		$updateStatus['msg'] = 'No user was found with that ID.';
	}
			
	mysqli_close($dbc);

	print json_encode($updateStatus);
?>
	

