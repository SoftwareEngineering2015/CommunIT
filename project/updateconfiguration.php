<?php
include('db_class.php');

		// Create connection
	$P = new manage_db;
	$P->connect_db();

	// Trim the input of the form so that blank data will not be inputed into the database
	if (trim($_POST['community_name']) != "") {
			$community_name=$_POST['community_name'];
			$community_name = stripslashes($community_name);
			$community_name = mysql_real_escape_string($community_name);
			$sql_update_configuration = "UPDATE configuration SET community_name = '$community_name'";
			$P->do_query($sql_update_configuration);
		}
		
	// Trim the input of the form so that blank data will not be inputed into the database	
	if (trim($_POST['max_per_residence']) != "") {
			$max_per_residence=$_POST['max_per_residence'];
			$max_per_residence = stripslashes($max_per_residence);
			$max_per_residence = mysql_real_escape_string($max_per_residence);
			$sql_update_configuration = "UPDATE configuration SET max_per_residence = '$max_per_residence'";
			$P->do_query($sql_update_configuration);
		}

	// Store the pin color in the database
	$default_pin_color=$_POST['default_pin_color'];
	$default_pin_color = stripslashes($default_pin_color);
	$default_pin_color = mysql_real_escape_string($default_pin_color);
	$sql_update_default_pin_color = "ALTER TABLE head_residents ALTER pin_color SET DEFAULT '$default_pin_color'";
	$P->do_query($sql_update_default_pin_color);

	header("location: configuration.php");
	exit; // Just in case, exit the file so the rest of the code will never run
?>