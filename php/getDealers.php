
<?php
	require_once('vars/connectvars.php');

	// Connect to the database 
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
		or die ('Fail to connect to database');

	$locationIds = array();
	$locationNames = array();
	
	// Grab the user's data to create the form and edit the users
	if (count($locationIds) < 1) {
		
		$query = "SELECT location_id, location_name FROM ws_locations WHERE 1";
		$data = mysqli_query($dbc, $query)
			or die ('Fail to query database (locations)');
		
		if (mysqli_num_rows($data) >= 1) {
			while ($row = mysqli_fetch_array($data)) { 
					// save the data in the arrays
				array_push ($locationIds, $row['location_id']);
				array_push ($locationNames, $row['location_name']);
			}
		}
	}
	
	function onGetLocation ($loc_id, $loc_ids, $loc_name) {
		for($i = 0; $i < count($loc_ids); $i++) {
			if ($loc_ids[$i] == $loc_id)
				return $loc_name[$i];
		}
		
		return '';
	}
	
	
	// Retrieve the score data from MySQL
	$query = "SELECT dealer_id, dealer_code, dealer_location FROM ws_dealers";
	$data = mysqli_query($dbc, $query)
		or die ('Fail to query database');
	
		// Loop through the array of dealers data, formatting it as json Object 
		while ($row = mysqli_fetch_assoc($data)) {

		    $dealer[] = [
		    	'dealer_id' => $row['dealer_id'],
		    	'dealer_code' => $row['dealer_code'], 
		    	'dealer_location' => $row['dealer_location'], 
		    	'dealer_locname' => onGetLocation($row['dealer_location'], $locationIds, $locationNames),
		    	];
		}
		$struct = array("dealers" => $dealer);
		print json_encode($struct);
	
	mysqli_close($dbc);
?>
