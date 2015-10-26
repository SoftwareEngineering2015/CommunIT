<?php
include('db_class.php');

		// Create connection
	$P = new manage_db;
	$P->connect_db();

	if (trim($_POST['community_name']) != "") {
			$community_name=$_POST['community_name'];
			$community_name = stripslashes($community_name);
			$community_name = mysql_real_escape_string($community_name);
			$sql_update_configuration = "UPDATE configuration SET community_name = '$community_name'";
			$P->do_query($sql_update_configuration);
		}
	if (trim($_POST['max_per_residence']) != "") {
			$max_per_residence=$_POST['max_per_residence'];
			$max_per_residence = stripslashes($max_per_residence);
			$max_per_residence = mysql_real_escape_string($max_per_residence);
			$sql_update_configuration = "UPDATE configuration SET max_per_residence = '$max_per_residence'";
			$P->do_query($sql_update_configuration);
		}
	header("location: configuration.php");
	exit; // Just in case, exit the file so the rest of the code will never run
?>