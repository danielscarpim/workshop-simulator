<?php
	
	require_once('vars/connectvars.php');
	
	// Connect to the database
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
		or die ('Fail to connect with database');

	$updateStatus = array('success' => false, 'msg' => '');
	
			// Grab the profile data from the POST
		$userID = mysqli_real_escape_string($dbc, trim($_POST['adminID']));
		$username = mysqli_real_escape_string($dbc, trim($_POST['username']));
		$password = mysqli_real_escape_string($dbc, trim($_POST['password']));
		
			// Make sure someone isn't already registered using this username
		$query = "SELECT admin_id, admin_password FROM ws_admin WHERE admin_id = '$userID'";
		$data = mysqli_query($dbc, $query)
			or die ('Fail to query database');
		
		if (mysqli_num_rows($data) == 1) {
				// The exists, update its info
			$row = mysqli_fetch_array($data);
			$yourID = $row['admin_id'];
			
			if (!empty($password) && $password != $row['admin_password']) {
					// Password changed, so change it too
				$query = "UPDATE ws_admin SET admin_name = '$username', admin_password = SHA('$password') WHERE admin_id = '$yourID'";
				mysqli_query($dbc, $query);
				
					// Confirm success with the user
				$updateStatus['success'] = true;
				$updateStatus['msg'] = 'Your name and password info were changed.';
				
				
			} else {
					// Password hasn't changed, so change only the rest
				$query = "UPDATE ws_admin SET admin_name = '$username' WHERE admin_id = '$yourID'";
				mysqli_query($dbc, $query);
				
					// Confirm success with the user
				$updateStatus['success'] = true;
				$updateStatus['msg'] = 'Your name was changed.';
			}
			
			
		} else {
				// No location was found with the given ID, so display an error message
			$updateStatus['msg'] = 'No admin was found with that ID.';
	}
	
	mysqli_close($dbc);

	print json_encode($updateStatus);
?>