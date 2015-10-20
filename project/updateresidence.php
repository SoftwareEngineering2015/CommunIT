<?php
// Run this code if a delete sub resident button was hit
if(isset($_POST['add_new_residence'])) {

	include('db_class.php');
	$P = new manage_db;
	$P->connect_db();

	// Define variables
	$residence_name = $_POST['residence_name'];
	$address = $_POST['address'];
	$latitude = $_POST['latitude'];
	$longitude = $_POST['longitude'];

	// To protect MySQL injection for Security purpose
	$residence_name = stripslashes($residence_name);
	$address = stripslashes($address);

	$residence_name = mysql_real_escape_string($residence_name);
	$address = mysql_real_escape_string($address);

	// Check connection
	$sql_add_new_residence = "INSERT INTO residences (address, latitude, longitude, username, password) VALUES ('$address','$latitude','$longitude', '$residence_name','password')";
	$P->do_query($sql_add_new_residence);

	header("location: admin.php");
		exit; // Just in case, exit the file so the rest of the code will never run

	}

// Run this code if a delete head resident button was hit
	elseif(isset($_POST['update_residence'])) {

		include('db_class.php');
		$P = new manage_db;
		$P->connect_db();

		// Get the head resident id
		$residence_id = $_POST['update_residence'];

		$P = new manage_db;
		$P->connect_db();

		// Do individual queries for each input since each input is not required 
		// Trim the input of the form so that blank data will not be inputed into the database
		if (trim($_POST['residence_name']) != "") {
			$residence_name=$_POST['residence_name'];
			$residence_name = stripslashes($residence_name);
			$residence_name = mysql_real_escape_string($residence_name);
			$sql_residence_update = "UPDATE residences SET username = '$residence_name' WHERE residence_id = '$residence_id'";
			$P->do_query($sql_residence_update);
		}
		if (trim($_POST['address']) != "") {
			$address=$_POST['address'];
			$address = stripslashes($address);
			$address = mysql_real_escape_string($address);
			$sql_residence_update = "UPDATE residences SET address = '$address' WHERE residence_id = '$residence_id'";
			$P->do_query($sql_residence_update);
		}
		if (trim($_POST['latitude']) != "") {
			$latitude=$_POST['latitude'];
			$latitude = stripslashes($latitude);
			$latitude = mysql_real_escape_string($latitude);
			$sql_residence_update = "UPDATE residences SET latitude = '$latitude' WHERE residence_id = '$residence_id'";
			$P->do_query($sql_residence_update);
		}
		if (trim($_POST['longitude']) != "") {
			$longitude=$_POST['longitude'];
			$longitude = stripslashes($longitude);
			$longitude = mysql_real_escape_string($longitude);
			$sql_residence_update = "UPDATE residences SET longitude = '$longitude' WHERE residence_id = '$residence_id'";
			$P->do_query($sql_residence_update);
		}

		header("location: admin.php");
	exit; // Just in case, exit the file so the rest of the code will never run

}

// Run this code if a delete head resident button was hit
elseif(isset($_POST['delete_head_resident'])) {

	include('db_class.php');
	$P = new manage_db;
	$P->connect_db();

	// Define variables
	$head_resident_id = $_POST['delete_head_resident'];

	$sql_head_resident_delete = "DELETE FROM head_residents WHERE head_resident_id = '$head_resident_id'";
	$P->do_query($sql_head_resident_delete);

	header("location: admin.php");
	exit; // Just in case, exit the file so the rest of the code will never run

}
// Run this code if a delete residence button was hit
elseif(isset($_POST['delete_residence'])) {

	include('db_class.php');
	$P = new manage_db;
	$P->connect_db();

	// Define variables
	$residence_id = $_POST['delete_residence'];

	$sql_head_resident_delete = "DELETE FROM residences WHERE residence_id = '$residence_id'";
	$P->do_query($sql_head_resident_delete);

	header("location: admin.php");
	exit; // Just in case, exit the file so the rest of the code will never run

}

else {
	header("location: admin.php");
	exit;
}
?>