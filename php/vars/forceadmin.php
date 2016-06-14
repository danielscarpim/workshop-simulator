<?php
		// Start the session
	require_once('startsession.php');
	
		// Make sure the user is logged in before going any further.
	if (!isset($_SESSION['username'])) {
		echo '<p class="login">Please <a href="../login.php">log in</a> to access this page.</p>';
		exit();
	}
	
?>