<?php

	require_once('vars/connectvars.php');
	
	// Connect to the database
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
		or die ('Fail to connect with database');
	
	$locationsids = array();
	$locationsnames = array();
	
		// Grab the user's data to create the form and enter the profiles
	if (count($locationsids) < 1) {
		
		$query = "SELECT location_id, location_name FROM ws_locations WHERE 1";
		$data = mysqli_query($dbc, $query)
			or die ('Fail to query database (locations)');
		
		if (mysqli_num_rows($data) >= 1) {
			while ($row = mysqli_fetch_array($data)) { 
					// save the data in the arrays
				array_push ($locationsids, $row['location_id']);
				array_push ($locationsnames, $row['location_name']);
			}
		}
	}
	
	function onGetName ($loc_id) {
		for($i = 0; $i < count($locationsids); $i++) {
			if ($locationsids[$i] == $loc_id)
				return $locationsnames[$i];
		}
		
		return '';
	}

	$updateStatus = array('success' => false, 'msg' => '');
	
	// Grab the profile data from the POST
	$locID = mysqli_real_escape_string($dbc, trim($_POST['locationid']));
	$dealercode = mysqli_real_escape_string($dbc, trim($_POST['dealercode']));
	$dealerid = mysqli_real_escape_string($dbc, trim($_POST['dealerid']));
	
	// Make sure there is a dealer with that id
	$query = "SELECT dealer_id FROM ws_dealers WHERE dealer_id = '$dealerid'";
	$data = mysqli_query($dbc, $query)
		or die ('Fail to query database 1');
	
	if (mysqli_num_rows($data) == 1) {
		// The dealer exists, update its info
		$row = mysqli_fetch_array($data);
		
		// Password hasn't changed, so change only the rest
		$query = "UPDATE ws_dealers SET dealer_code = '$dealercode', dealer_location = '$locID' WHERE dealer_id = '$dealerid'";
		mysqli_query($dbc, $query)
			or die ('Fail to query database 2');
		echo 'dealer_location: '. $locID;
		// Confirm success with the user
			$updateStatus['success'] = true;
			$updateStatus['msg'] = 'Dealer info was changed.';
		
		
	} else {
			// No location was found with the given ID, so display an error message
		$updateStatus['msg'] = 'No dealer was found with that ID.';
	}
		
	mysqli_close($dbc);

	print json_encode($updateStatus);
?>
