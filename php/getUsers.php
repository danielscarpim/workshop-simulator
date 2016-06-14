
<?php
	require_once('vars/connectvars.php');
	
	// Connect to the database 
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
		or die ('Fail to connect to database');

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
	
	function onGetName ($dlr_id, $dlr_ids, $dlr_cd) {
		for($i = 0; $i < count($dlr_ids); $i++) {
			if ($dlr_ids[$i] == $dlr_id)
				return $dlr_cd[$i];
		}
		
		return '';
	}

	// Retrieve the score data from MySQL
	$query = "SELECT user_id, user_name, user_stars, user_score, user_dealer FROM ws_users";
	$data = mysqli_query($dbc, $query)
		or die ('Fail to query database');
	
		// Loop through the array of users data, formatting it as json Object 
		while ($row = mysqli_fetch_assoc($data)) {
		    $user[] = [
		    	'user_id' => $row['user_id'],
		    	'user_name' => $row['user_name'], 
		    	'user_stars' => $row['user_stars'], 
		    	'user_dealer' => $row['user_dealer'],
		    	'user_dealercode' => onGetName($row['user_dealer'], $dealerssids, $dealerscodes),
		    	'user_score' => $row['user_score']
		    	];
		}
		$struct = array("users" => $user);
		print json_encode($struct);
	
	mysqli_close($dbc);
?>
