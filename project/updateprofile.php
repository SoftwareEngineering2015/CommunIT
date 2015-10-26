<?php
//If update head resident button was hit
if(isset($_POST['submit_head_resident'])) {
	// Checks to see if the user is registering their information for the first time ( "N" denotes that the user is new )
	if (preg_match("/^N/", $_POST['submit_head_resident'])) {

		include('db_class.php');
		// Defines the id of the residence; reads the number after the colon
		$fk_residence_id = substr($_POST['submit_head_resident'], strpos($_POST['submit_head_resident'], ":" ) + 1);

		$first_name=$_POST['head_resident_first_name'];
		$last_name=$_POST['head_resident_last_name'];
		$emergency=$_POST['head_resident_emergency'];
		$phone_one=$_POST['head_resident_phone_one'];
		$email_address=$_POST['head_resident_email_address'];
		$password =$_POST['residence_password'];

		// To protect MySQL injection for Security purpose
		$first_name = stripslashes($first_name);
		$last_name = stripslashes($last_name);
		$emergency = stripslashes($emergency);
		$phone_one = stripslashes($phone_one);
		$email_address = stripslashes($email_address);
		$password = stripslashes($password);

		$first_name = mysql_real_escape_string($first_name);
		$last_name = mysql_real_escape_string($last_name);
		$emergency = mysql_real_escape_string($emergency);
		$phone_one = mysql_real_escape_string($phone_one);
		$email_address = mysql_real_escape_string($email_address);
		$password = mysql_real_escape_string($password);

		// Create connection
		$P = new manage_db;
		$P->connect_db();

		// Check connection
		$sql_head_residents_insert = "INSERT INTO head_residents (fk_residence_id, first_name, last_name, emergency_contact, phone_one, email_address) VALUES ('$fk_residence_id','$first_name','$last_name', '$emergency','$phone_one','$email_address')";
		$P->do_query($sql_head_residents_insert);

		$sql_update_residence_password = "UPDATE residences INNER JOIN head_residents ON residences.residence_id = head_residents.fk_residence_id SET password='$password' WHERE fk_residence_id='$fk_residence_id'";
		$P->do_query($sql_update_residence_password);

		header("location: myhome.php");
		exit; // Just in case, exit the file so the rest of the code will never run
		
	}
	// Run this code if the user is not new and updating their current information 
	else {

		include('db_class.php');

		// Get the head resident id
		$head_resident_id = $_POST['submit_head_resident'];

		$P = new manage_db;
		$P->connect_db();

		// Do individual queries for each input since each input is not required 
		// Trim the input of the form so that blank data will not be inputed into the database
		if (trim($_POST['head_resident_first_name']) != "") {
			$first_name=$_POST['head_resident_first_name'];
			$first_name = stripslashes($first_name);
			$first_name = mysql_real_escape_string($first_name);
			$sql_head_residents_insert = "UPDATE head_residents SET first_name = '$first_name' WHERE head_resident_id = '$head_resident_id'";
			$P->do_query($sql_head_residents_insert);
		}

		if (trim($_POST['head_resident_last_name']) != "") {
			$last_name=$_POST['head_resident_last_name'];
			$last_name = stripslashes($last_name);
			$last_name = mysql_real_escape_string($last_name);
			$sql_head_residents_insert = "UPDATE head_residents SET last_name = '$last_name' WHERE head_resident_id = '$head_resident_id'";
			$P->do_query($sql_head_residents_insert);
		}

		if (trim($_POST['head_resident_emergency']) != "") {
			$emergency=$_POST['head_resident_emergency'];
			$emergency = stripslashes($emergency);
			$emergency = mysql_real_escape_string($emergency);
			$sql_head_residents_insert = "UPDATE head_residents SET emergency_contact = '$emergency' WHERE head_resident_id = '$head_resident_id'";
			$P->do_query($sql_head_residents_insert);
		}

		if (trim($_POST['head_resident_phone_one']) != "") {
			$phone_one=$_POST['head_resident_phone_one'];
			$phone_one = stripslashes($phone_one);
			$phone_one = mysql_real_escape_string($phone_one);
			$sql_head_residents_insert = "UPDATE head_residents SET phone_one = '$phone_one' WHERE head_resident_id = '$head_resident_id'";
			$P->do_query($sql_head_residents_insert);
		}

		if (trim($_POST['head_resident_email_address']) != "") {
			$email_address=$_POST['head_resident_email_address'];
			$email_address = stripslashes($email_address);
			$email_address = mysql_real_escape_string($email_address);
			$sql_head_residents_insert = "UPDATE head_residents SET email_address = '$email_address' WHERE head_resident_id = '$head_resident_id'";
			$P->do_query($sql_head_residents_insert);
		}

		if (trim($_POST['residence_password']) != "") {
			$password=$_POST['residence_password'];
			$password = stripslashes($password);
			$password = mysql_real_escape_string($password);
			$sql_update_residence_password = "UPDATE residences INNER JOIN head_residents ON residences.residence_id = head_residents.fk_residence_id SET password='$password' WHERE head_resident_id = '$head_resident_id'";
			$P->do_query($sql_update_residence_password);
		}

		header("location: editprofile.php");
		exit; // Just in case, exit the file so the rest of the code will never run

	}
}
// Run this code if the user is not new and updating their current information 
elseif (isset($_POST['admin_update_head_resident'])) {
	// Checks to see if the user is registering their information for the first time ( "N" denotes that the user is new )
	if (preg_match("/^N/", $_POST['admin_update_head_resident'])) {

		include('db_class.php');
		// Defines the id of the residence; reads the number after the colon
		$fk_residence_id = substr($_POST['admin_update_head_resident'], strpos($_POST['admin_update_head_resident'], ":" ) + 1);

		$first_name=$_POST['head_resident_first_name'];
		$last_name=$_POST['head_resident_last_name'];
		$emergency=$_POST['head_resident_emergency'];
		$phone_one=$_POST['head_resident_phone_one'];
		$email_address=$_POST['head_resident_email_address'];

		// To protect MySQL injection for Security purpose
		$first_name = stripslashes($first_name);
		$last_name = stripslashes($last_name);
		$emergency = stripslashes($emergency);
		$phone_one = stripslashes($phone_one);
		$email_address = stripslashes($email_address);

		$first_name = mysql_real_escape_string($first_name);
		$last_name = mysql_real_escape_string($last_name);
		$emergency = mysql_real_escape_string($emergency);
		$phone_one = mysql_real_escape_string($phone_one);
		$email_address = mysql_real_escape_string($email_address);

		// Create connection
		$P = new manage_db;
		$P->connect_db();

		// Check connection
		$sql_head_residents_insert = "INSERT INTO head_residents (fk_residence_id, first_name, last_name, emergency_contact, phone_one, email_address) VALUES ('$fk_residence_id','$first_name','$last_name', '$emergency','$phone_one','$email_address')";
		$P->do_query($sql_head_residents_insert);

		header("location: admin.php");
		exit; // Just in case, exit the file so the rest of the code will never run
		} 
		else {
			include('db_class.php');

		// Get the head resident id
			$head_resident_id = $_POST['admin_update_head_resident'];

			$P = new manage_db;
			$P->connect_db();

		// Do individual queries for each input since each input is not required 
		// Trim the input of the form so that blank data will not be inputed into the database
			if (trim($_POST['head_resident_first_name']) != "") {
				$first_name=$_POST['head_resident_first_name'];
				$first_name = stripslashes($first_name);
				$first_name = mysql_real_escape_string($first_name);
				$sql_head_residents_insert = "UPDATE head_residents SET first_name = '$first_name' WHERE head_resident_id = '$head_resident_id'";
				$P->do_query($sql_head_residents_insert);
			}

			if (trim($_POST['head_resident_last_name']) != "") {
				$last_name=$_POST['head_resident_last_name'];
				$last_name = stripslashes($last_name);
				$last_name = mysql_real_escape_string($last_name);
				$sql_head_residents_insert = "UPDATE head_residents SET last_name = '$last_name' WHERE head_resident_id = '$head_resident_id'";
				$P->do_query($sql_head_residents_insert);
			}

			if (trim($_POST['head_resident_emergency']) != "") {
				$emergency=$_POST['head_resident_emergency'];
				$emergency = stripslashes($emergency);
				$emergency = mysql_real_escape_string($emergency);
				$sql_head_residents_insert = "UPDATE head_residents SET emergency_contact = '$emergency' WHERE head_resident_id = '$head_resident_id'";
				$P->do_query($sql_head_residents_insert);
			}

			if (trim($_POST['head_resident_phone_one']) != "") {
				$phone_one=$_POST['head_resident_phone_one'];
				$phone_one = stripslashes($phone_one);
				$phone_one = mysql_real_escape_string($phone_one);
				$sql_head_residents_insert = "UPDATE head_residents SET phone_one = '$phone_one' WHERE head_resident_id = '$head_resident_id'";
				$P->do_query($sql_head_residents_insert);
			}

			if (trim($_POST['head_resident_email_address']) != "") {
				$email_address=$_POST['head_resident_email_address'];
				$email_address = stripslashes($email_address);
				$email_address = mysql_real_escape_string($email_address);
				$sql_head_residents_insert = "UPDATE head_residents SET email_address = '$email_address' WHERE head_resident_id = '$head_resident_id'";
				$P->do_query($sql_head_residents_insert);
			}

			header("location: editresident.php?resident=$head_resident_id");
		exit; // Just in case, exit the file so the rest of the code will never run
	}

}
// Run this code if the sub resident button was hit 
elseif (isset($_POST['submit_sub_resident'])) {
	include('db_class.php');

	// Define variables
	$fk_head_id = $_POST['submit_sub_resident'];
	$first_name=$_POST['sub_resident_first_name'];
	$last_name=$_POST['sub_resident_last_name'];
	$phone_number=$_POST['sub_resident_phone_number'];

	// To protect MySQL injection for Security purpose
	$first_name = stripslashes($first_name);
	$last_name = stripslashes($last_name);
	$phone_number = stripslashes($phone_number);

	$first_name = mysql_real_escape_string($first_name);
	$last_name = mysql_real_escape_string($last_name);
	$phone_number = mysql_real_escape_string($phone_number);

	// Create connection
	$P = new manage_db;
	$P->connect_db();
	// Check connection
	$sql_sub_residents_insert = "INSERT INTO sub_residents (fk_head_id, first_name, last_name, phone_number) VALUES ('$fk_head_id','$first_name','$last_name','$phone_number')";
	$P->do_query($sql_sub_residents_insert);

	header("location: editprofile.php");
	exit; // Just in case, exit the file so the rest of the code will never run

}
// Run this code if an update sub resident button was hit
elseif(isset($_POST['admin_update_sub_resident'])) {

	include('db_class.php');
	$P = new manage_db;
	$P->connect_db();

	// Define variables
	$sub_residents_id = substr($_POST['admin_update_sub_resident'], 0, strpos($_POST['admin_update_sub_resident'], ":" )); // Strip the value of the button to get the sub resident id 
	$fk_head_id = substr($_POST['admin_update_sub_resident'], strpos($_POST['admin_update_sub_resident'], ":" ) + 1); // Strip the value of the button to get the head resident id 
			// Trim the input of the form so that blank data will not be inputed into the database
	if (trim($_POST["update_sub_resident_first_name:$sub_residents_id"]) != "") {
		$first_name=$_POST["update_sub_resident_first_name:$sub_residents_id"];
		$first_name = stripslashes($first_name);
		$first_name = mysql_real_escape_string($first_name);
		$sql_sub_residents_update = "UPDATE sub_residents SET first_name = '$first_name' WHERE sub_residents_id = '$sub_residents_id' AND fk_head_id = '$fk_head_id'";
		$P->do_query($sql_sub_residents_update);
	}

	if (trim($_POST["update_sub_resident_last_name:$sub_residents_id"]) != "") {
		$last_name=$_POST["update_sub_resident_last_name:$sub_residents_id"];
		$last_name = stripslashes($last_name);
		$last_name = mysql_real_escape_string($last_name);
		$sql_sub_residents_update = "UPDATE sub_residents SET last_name = '$last_name' WHERE sub_residents_id = '$sub_residents_id' AND fk_head_id = '$fk_head_id'";
		$P->do_query($sql_sub_residents_update);
	}

	if (trim($_POST["update_sub_resident_phone_number:$sub_residents_id"]) != "") {
		$phone_number=$_POST["update_sub_resident_phone_number:$sub_residents_id"];
		$phone_number = stripslashes($phone_number);
		$phone_number = mysql_real_escape_string($phone_number);
		$sql_sub_residents_update = "UPDATE sub_residents SET phone_number = '$phone_number' WHERE sub_residents_id = '$sub_residents_id' AND fk_head_id = '$fk_head_id'";
		$P->do_query($sql_sub_residents_update);
	}


	header("location: editresident.php?resident=$fk_head_id");
	exit; // Just in case, exit the file so the rest of the code will never run

} 
// Run this code if an update sub resident button was hit
elseif(isset($_POST['update_sub_resident'])) {

	include('db_class.php');
	$P = new manage_db;
	$P->connect_db();

	// Define variables
	$sub_residents_id = substr($_POST['update_sub_resident'], 0, strpos($_POST['update_sub_resident'], ":" )); // Strip the value of the button to get the sub resident id 
	$fk_head_id = substr($_POST['update_sub_resident'], strpos($_POST['update_sub_resident'], ":" ) + 1); // Strip the value of the button to get the head resident id 
			// Trim the input of the form so that blank data will not be inputed into the database
	if (trim($_POST["update_sub_resident_first_name:$sub_residents_id"]) != "") {
		$first_name=$_POST["update_sub_resident_first_name:$sub_residents_id"];
		$first_name = stripslashes($first_name);
		$first_name = mysql_real_escape_string($first_name);
		$sql_sub_residents_update = "UPDATE sub_residents SET first_name = '$first_name' WHERE sub_residents_id = '$sub_residents_id' AND fk_head_id = '$fk_head_id'";
		$P->do_query($sql_sub_residents_update);
	}

	if (trim($_POST["update_sub_resident_last_name:$sub_residents_id"]) != "") {
		$last_name=$_POST["update_sub_resident_last_name:$sub_residents_id"];
		$last_name = stripslashes($last_name);
		$last_name = mysql_real_escape_string($last_name);
		$sql_sub_residents_update = "UPDATE sub_residents SET last_name = '$last_name' WHERE sub_residents_id = '$sub_residents_id' AND fk_head_id = '$fk_head_id'";
		$P->do_query($sql_sub_residents_update);
	}

	if (trim($_POST["update_sub_resident_phone_number:$sub_residents_id"]) != "") {
		$phone_number=$_POST["update_sub_resident_phone_number:$sub_residents_id"];
		$phone_number = stripslashes($phone_number);
		$phone_number = mysql_real_escape_string($phone_number);
		$sql_sub_residents_update = "UPDATE sub_residents SET phone_number = '$phone_number' WHERE sub_residents_id = '$sub_residents_id' AND fk_head_id = '$fk_head_id'";
		$P->do_query($sql_sub_residents_update);
	}


	header("location: editprofile.php");
	exit; // Just in case, exit the file so the rest of the code will never run

} 
// Run this code if an update sub resident button was hit
elseif(isset($_POST['admin_add_sub_resident'])) {

	include('db_class.php');

	// Define variables
	$fk_head_id = $_POST['admin_add_sub_resident'];
	$first_name=$_POST['sub_resident_first_name'];
	$last_name=$_POST['sub_resident_last_name'];
	$phone_number=$_POST['sub_resident_phone_number'];

	// To protect MySQL injection for Security purpose
	$first_name = stripslashes($first_name);
	$last_name = stripslashes($last_name);
	$phone_number = stripslashes($phone_number);

	$first_name = mysql_real_escape_string($first_name);
	$last_name = mysql_real_escape_string($last_name);
	$phone_number = mysql_real_escape_string($phone_number);

	// Create connection
	$P = new manage_db;
	$P->connect_db();
	// Check connection
	$sql_sub_residents_insert = "INSERT INTO sub_residents (fk_head_id, first_name, last_name, phone_number) VALUES ('$fk_head_id','$first_name','$last_name','$phone_number')";
	$P->do_query($sql_sub_residents_insert);

	header("location: editresident.php?resident=$fk_head_id");
	exit; // Just in case, exit the file so the rest of the code will never run

} 

// Run this code if a delete sub resident button was hit
elseif(isset($_POST['admin_delete_sub_resident'])) {

	include('db_class.php');
	$P = new manage_db;
	$P->connect_db();

	// Define variables
	$sub_residents_id = substr($_POST['admin_delete_sub_resident'], 0, strpos($_POST['admin_delete_sub_resident'], ":" )); // Strip the value of the button to get the sub resident id 
	$fk_head_id = substr($_POST['admin_delete_sub_resident'], strpos($_POST['admin_delete_sub_resident'], ":" ) + 1); // Strip the value of the button to get the head resident id 
	$sql_sub_resident_delete = "DELETE FROM sub_residents WHERE sub_residents_id = '$sub_residents_id' AND fk_head_id = '$fk_head_id'";
	$P->do_query($sql_sub_resident_delete);

	header("location: editresident.php?resident=$fk_head_id");
	exit; // Just in case, exit the file so the rest of the code will never run

}
// Run this code if a delete sub resident button was hit
elseif(isset($_POST['delete_sub_resident'])) {

	include('db_class.php');
	$P = new manage_db;
	$P->connect_db();

	// Define variables
	$sub_residents_id = substr($_POST['delete_sub_resident'], 0, strpos($_POST['delete_sub_resident'], ":" )); // Strip the value of the button to get the sub resident id 
	$fk_head_id = substr($_POST['delete_sub_resident'], strpos($_POST['delete_sub_resident'], ":" ) + 1); // Strip the value of the button to get the head resident id 
	$sql_sub_resident_delete = "DELETE FROM sub_residents WHERE sub_residents_id = '$sub_residents_id' AND fk_head_id = '$fk_head_id'";
	$P->do_query($sql_sub_resident_delete);

	header("location: editprofile.php");
	exit; // Just in case, exit the file so the rest of the code will never run

} 
// Run this code if a delete sub resident button was hit
elseif(isset($_POST['change_admin_password'])) {

	include('db_class.php');
	$P = new manage_db;
	$P->connect_db();

	$password=$_POST["new_admin_password"];
	$password = stripslashes($password);
	$password = mysql_real_escape_string($password);
	$sql_change_admin_password = "UPDATE residences SET password = '$password' WHERE username = 'admin'";
	$P->do_query($sql_change_admin_password);

	header("location: admin.php");
	exit; // Just in case, exit the file so the rest of the code will never run

}
// Run this code if a delete sub resident button was hit
elseif(isset($_POST['change_guest_password'])) {

	include('db_class.php');
	$P = new manage_db;
	$P->connect_db();

	$password=$_POST["new_guest_password"];
	$password = stripslashes($password);
	$password = mysql_real_escape_string($password);
	$sql_change_admin_password = "UPDATE residences SET password = '$password' WHERE username = 'guest'";
	$P->do_query($sql_change_admin_password);

	header("location: admin.php");
	exit; // Just in case, exit the file so the rest of the code will never run

}
else {
	header("location: myhome.php");
	exit;
}
?>