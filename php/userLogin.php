<?php

	require_once('vars/connectvars.php');
	require_once('vars/phpvars.php');
	
	
		// Connect to the database
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
		or die ('Fail to connect to database');
	
	//================================ Getting Dealers' Locations
	$dealerssids = array();
	$dealerslocs = array();
	
		// Grab the user's data to create the form and edit the users
	if (count($dealerssids) < 1) {
		
		$query = "SELECT dealer_id, dealer_location FROM ws_dealers WHERE 1";
		$data = mysqli_query($dbc, $query)
			or die ('Fail to query database (locations)');
		
		if (mysqli_num_rows($data) >= 1) {
			while ($row = mysqli_fetch_array($data)) { 
					// save the data in the arrays
				array_push ($dealerssids, $row['dealer_id']);
				array_push ($dealerslocs, $row['dealer_location']);
			}
		}
	}
	
	function onGetLoc ($dlr_id, $dlr_ids, $dlr_locs) {
		for($i = 0; $i < count($dlr_ids); $i++) {
			if ($dlr_ids[$i] == $dlr_id)
				return $dlr_locs[$i];
		}
		
		return -1;
	}
	
	
	//================================ Getting Locations' URLs
	$locationsids = array();
	$locationsurls = array();
	
		// Grab the user's data to create the form and enter the profiles
	if (count($locationsids) < 1) {
		
		$query = "SELECT location_id, location_url FROM ws_locations WHERE 1";
		$data = mysqli_query($dbc, $query)
			or die ('Fail to query database (locations)');
		
		if (mysqli_num_rows($data) >= 1) {
			while ($row = mysqli_fetch_array($data)) { 
					// save the data in the arrays
				array_push ($locationsids, $row['location_id']);
				array_push ($locationsurls, $row['location_url']);
			}
		}
	}
	
	function onGetURL ($loc_id, $locsid, $locsurls) {
		for($i = 0; $i < count($locsid); $i++) {
			if ($locsid[$i] == $loc_id)
				return $locsurls[$i];
		}
		
		return '';
	}

	
	// Login variables
	$credentials = array('login' => false, 'error_msg' => '', 'url' => '' );
	

	$user_username = mysqli_real_escape_string($dbc, trim($_POST['username']));
	$user_starsid = mysqli_real_escape_string($dbc, trim($_POST['starsid']));
	
	// Look up the username and stars id
	$query = "SELECT user_id, user_dealer FROM ws_users WHERE user_name = '$user_username' AND user_stars = '$user_starsid'";
	$data = mysqli_query($dbc, $query)
		or die ('Fail to query database 1');
	
	if (mysqli_num_rows($data) == 1) {
		// The log-in is OK so set the user ID and navigate to the game
		$row = mysqli_fetch_array($data);

		$credentials['login'] = true;
		
		$locID = onGetLoc($row['user_dealer'], $dealerssids, $dealerslocs);
		
		if ($locID >= 0) {
			$home_url = onGetURL($locID, $locationsids, $locationsurls);
			
			if (strlen($home_url) > 0) {
				$home_url = $home_url . '?userid='. $row['user_id'];
				
				$credentials['url'] = $home_url;
				
			} else {

				// The URL's Location was not found
				$credentials['error_msg'] = 'url_not_found';

			}
			
		} else {

			// The Location was not found
			$credentials['error_msg'] = 'location_not_found';
		}
		
		
	} else {

		// Incorrect Login/password
		$credentials['error_msg'] = 'incorrect_login_user';
	}
	
	mysqli_close($dbc);

	// print json
	print json_encode($credentials);


?>