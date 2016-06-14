<?php
	$path = "../language/";

	$languages = [];

	foreach (glob($path.'*.json') as $file) {
		$str_data = file_get_contents($path.$file);
		$json_a = json_decode($str_data, true);
		$language = $json_a['language'];
		$languages[$language] = $json_a;
		
	}

	print json_encode($languages);
?>