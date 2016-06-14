<?php
	require_once('vars/connectvars.php');
	
		// Connect to the database 
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
		or die ('Fail to connect to database');
	
		// Retrieve the score data from MySQL
	$query = "SELECT admin_id, admin_name, admin_password FROM ws_admin ORDER BY admin_id DESC";
	$data = mysqli_query($dbc, $query)
		or die ('Fail to query database');
	
	// Loop through the array of locations data, formatting it as json Object 
		while ($row = mysqli_fetch_assoc($data)) {
		    $admin[] = $row;
		}
		$struct = array("admins" => $admin);
		print json_encode($struct);
	
	mysqli_close($dbc);
?>