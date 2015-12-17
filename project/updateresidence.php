<?php

// Run this code if a delete sub resident button was hit
if(isset($_POST['add_new_residence'])) {

	include('db_class.php');
	$P = new manage_db;
	$P->connect_db();

	// Check if there is a space in the residence name
	if(preg_match('/\s/', $_POST['residence_name'])) {
		header("location: addresidence.php?error=space");
		exit; // Just in case, exit the file so the rest of the code will never run
	}
	// Check if the residence name is in alphanum format
	elseif(preg_match('/[^a-z0-9]/i', $_POST['residence_name'] )) {
		header("location: addresidence.php?error=alphanum");
		exit; // Just in case, exit the file so the rest of the code will never run
	} else { 
		$residence_name = $_POST['residence_name']; // Store the residence name
	}

	$address = $_POST['address']; //Store the address

	//Make sure the lat and long are set in the form
	if ($_POST['latitude'] != "" && $_POST['longitude'] != "") {
		$latitude = $_POST['latitude'];
		$longitude = $_POST['longitude'];
	} else {
		header("location: addresidence.php?error=latlng");
		exit; // Just in case, exit the file so the rest of the code will never run
	}

	//This is the function that gives the user a random password
    //$password = "password";
    $total = 8;
	while ($total != 0) {
        //this switch statement selects a random character to be insteted into the character array
        $added = 'A';
        switch(rand(1,46)){
        case 1: $added = 'a';break;
        case 2: $added = 'b';break;
        case 3: $added = 'c';break;
        case 4: $added = 'd';break;
        case 5: $added = 'e';break;
        case 6: $added = 'f';break;
        case 7: $added = 'g';break;
        case 8: $added = 'h';break;
        case 9: $added = 'i';break;
        case 10: $added = 'j';break;
        case 11: $added = 'k';break;
        case 12: $added = 'l';break;
        case 13: $added = 'm';break;
        case 14: $added = 'n';break;
        case 15: $added = 'o';break;
        case 16: $added = 'p';break;
        case 17: $added = 'q';break;
        case 18: $added = 'r';break;
        case 19: $added = 's';break;
        case 20: $added = 't';break;
        case 21: $added = 'u';break;
        case 22: $added = 'v';break;
        case 23: $added = 'w';break;
        case 24: $added = 'x';break;
        case 25: $added = 'y';break;
        case 26: $added = 'z';break;
        case 27: $added = '1';break;
        case 28: $added = '2';break;
        case 29: $added = '3';break;
        case 30: $added = '4';break;
        case 31: $added = '5';break;
        case 32: $added = '6';break;
        case 33: $added = '7';break;
        case 34: $added = '8';break;
        case 35: $added = '9';break;
        case 36: $added = '0';break;
        case 37: $added = '1';break;
        case 38: $added = '2';break;
        case 39: $added = '3';break;
        case 40: $added = '4';break;
        case 41: $added = '5';break;
        case 42: $added = '6';break;
        case 43: $added = '7';break;
        case 44: $added = '8';break;
        case 45: $added = '9';break;
        case 46: $added = '0';break;
        }
        $password = $password . $added;
        $total = $total - 1;
    }

	// To protect MySQL injection for Security purpose
	$residence_name = stripslashes($residence_name);
	$address = stripslashes($address);
	$latitude = stripslashes($latitude);
	$longitude = stripslashes($longitude);
	$password = stripslashes($password);

	// To protect MySQL injection for Security purpose
	$residence_name = mysql_real_escape_string($residence_name);
	$address = mysql_real_escape_string($address);
	$latitude = mysql_real_escape_string($latitude);
	$longitude = mysql_real_escape_string($longitude);
	$password = mysql_real_escape_string($password);

	// Check connection
	$sql_add_new_residence = "INSERT INTO residences (address, latitude, longitude, username, password) VALUES ('$address','$latitude','$longitude', '$residence_name','$password')";
	 $P->do_residence_query($sql_add_new_residence, 'addresidence.php?error=exists');	

	header("location: admin.php");
		exit; // Just in case, exit the file so the rest of the code will never run

	}

// Run this code if the admin is updating a residence information
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
			//Check if there are spaces in the residence name
			if(preg_match('/\s/', $_POST['residence_name']) ) {
				header("location: editresidence.php?residence=$residence_id&error=space");
				exit; // Just in case, exit the file so the rest of the code will never run
			}
			//Check that the residence name is in alphanum format
			elseif(preg_match('/[^a-z0-9]/i', $_POST['residence_name'] )) {
				header("location: editresidence.php?residence=$residence_id&error=alphanum");
				exit; // Just in case, exit the file so the rest of the code will never run
			} else { 
				$residence_name=$_POST['residence_name'];
				$residence_name = stripslashes($residence_name);
				$residence_name = mysql_real_escape_string($residence_name);
				$sql_residence_update = "UPDATE residences SET username = '$residence_name' WHERE residence_id = '$residence_id'";
				$P->do_residence_query($sql_residence_update, "editresidence.php?residence=$residence_id&error=exists");
			}
		}
		// Trim the input of the form so that blank data will not be inputed into the database
		if (trim($_POST['address']) != "") {
			$address=$_POST['address'];
			$address = stripslashes($address);
			$address = mysql_real_escape_string($address);
			$sql_residence_update = "UPDATE residences SET address = '$address' WHERE residence_id = '$residence_id'";
			$P->do_query($sql_residence_update);
		}
		// Trim the input of the form so that blank data will not be inputed into the database
		if (trim($_POST['latitude']) != "") {
			$latitude=$_POST['latitude'];
			$latitude = stripslashes($latitude);
			$latitude = mysql_real_escape_string($latitude);
			$sql_residence_update = "UPDATE residences SET latitude = '$latitude' WHERE residence_id = '$residence_id'";
			$P->do_query($sql_residence_update);
		}
		// Trim the input of the form so that blank data will not be inputed into the database
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