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

		// Check if the emergency phone number is in the correct format
		if(preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST['head_resident_emergency'])) {
  			$emergency=$_POST['head_resident_emergency']; // Store variable if correct
  		} 
		else { // Return the user to edit profile if the emergency contact number is not correct
			header("location: editprofile.php?error=emergency");
			exit; // Just in case, exit the file so the rest of the code will never run
		}

		// Check if the additional phone number is set
		if(trim($_POST['head_resident_phone_one']) != "") {
			// Check if the emergency phone number is in the correct format
			if(preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST['head_resident_phone_one'])) {
				$phone_one=$_POST['head_resident_phone_one'];
			} 
			else { // Return the user to edit profile if the phone number is not correct
				header("location: editprofile.php?error=phone");
				exit; // Just in case, exit the file so the rest of the code will never run
			}
		} 
		else {
			$phone_one = ""; // Set the variable to nothing to make sure the MySQL statement runs
		}

		// Filter the email address to be in the correct format
		if (trim($_POST['head_resident_email_address']) != "") {
			if (filter_var($_POST['head_resident_email_address'], FILTER_VALIDATE_EMAIL)) {
				$email_address=$_POST['head_resident_email_address'];
			}
			else { // Return the user to edit profile if the email address is not correct
				header("location: editprofile.php?error=email");
				exit; // Just in case, exit the file so the rest of the code will never run
			}
		}
		else {
			$email_address = ""; // Set the variable to nothing to make sure the MySQL statement runs
		}

		$password =$_POST['residence_password'];
		$miscinfo =$_POST['miscinfo'];
		$pincolor =$_POST['pincolor'];

		// To protect MySQL injection for Security purpose
		$first_name = stripslashes($first_name);
		$last_name = stripslashes($last_name);
		$emergency = stripslashes($emergency);
		$phone_one = stripslashes($phone_one);
		$email_address = stripslashes($email_address);
		$password = stripslashes($password);
		$miscinfo = stripslashes($miscinfo);
		$pincolor = stripslashes($pincolor);

		$first_name = mysql_real_escape_string($first_name);
		$last_name = mysql_real_escape_string($last_name);
		$emergency = mysql_real_escape_string($emergency);
		$phone_one = mysql_real_escape_string($phone_one);
		$email_address = mysql_real_escape_string($email_address);
		$password = mysql_real_escape_string($password);
		$miscinfo = mysql_real_escape_string($miscinfo);
		$pincolor = mysql_real_escape_string($pincolor);

		// Create connection
		$P = new manage_db;
		$P->connect_db();

		// Check connection
		$sql_head_residents_insert = "INSERT INTO head_residents (fk_residence_id, first_name, last_name, emergency_contact, phone_one, email_address, miscinfo, pin_color) VALUES ('$fk_residence_id','$first_name','$last_name', '$emergency','$phone_one','$email_address', '$miscinfo', '$pincolor')";
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

		// Check if emergency phone number is not blank
		if (trim($_POST['head_resident_emergency']) != "") {
			if (preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST['head_resident_emergency'])) {
				$emergency=$_POST['head_resident_emergency'];
				$emergency = stripslashes($emergency);
				$emergency = mysql_real_escape_string($emergency);
				$sql_head_residents_insert = "UPDATE head_residents SET emergency_contact = '$emergency' WHERE head_resident_id = '$head_resident_id'";
				$P->do_query($sql_head_residents_insert);
			} 
			else { // Return the user to edit profile if the emergency contact number is not correct
				header("location: editprofile.php?error=emergency");
				exit; // Just in case, exit the file so the rest of the code will never run
			}
			
		} 

		// If phone number is blank enter into the database
		if (trim($_POST['head_resident_phone_one']) == "") {
			$phone_one=$_POST['head_resident_phone_one'];
			$phone_one = stripslashes($phone_one);
			$phone_one = mysql_real_escape_string($phone_one);
			$sql_head_residents_insert = "UPDATE head_residents SET phone_one = '$phone_one' WHERE head_resident_id = '$head_resident_id'";
			$P->do_query($sql_head_residents_insert);
		}
			// Check if the emergency phone number is in the correct format
		elseif(preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST['head_resident_phone_one'])) {
			$phone_one=$_POST['head_resident_phone_one'];
			$phone_one = stripslashes($phone_one);
			$phone_one = mysql_real_escape_string($phone_one);
			$sql_head_residents_insert = "UPDATE head_residents SET phone_one = '$phone_one' WHERE head_resident_id = '$head_resident_id'";
			$P->do_query($sql_head_residents_insert);
		} 
				else { // Return the user to edit profile if the emergency contact number is not correct
					header("location: editprofile.php?error=phone");
					exit; // Just in case, exit the file so the rest of the code will never run
				}

		// If email address is blank enter into the database
				if (trim($_POST['head_resident_email_address']) == "") {
					$email_address=$_POST['head_resident_email_address'];
					$email_address = stripslashes($email_address);
					$email_address = mysql_real_escape_string($email_address);
					$sql_head_residents_insert = "UPDATE head_residents SET email_address = '$email_address' WHERE head_resident_id = '$head_resident_id'";
					$P->do_query($sql_head_residents_insert);

				}
			// Validate email address
				elseif (filter_var($_POST['head_resident_email_address'], FILTER_VALIDATE_EMAIL)) {
					$email_address=$_POST['head_resident_email_address'];
					$email_address = stripslashes($email_address);
					$email_address = mysql_real_escape_string($email_address);
					$sql_head_residents_insert = "UPDATE head_residents SET email_address = '$email_address' WHERE head_resident_id = '$head_resident_id'";
					$P->do_query($sql_head_residents_insert);
				}
				else { // Return the user to edit profile if the email address is not correct
					header("location: editprofile.php?error=email");
					exit; // Just in case, exit the file so the rest of the code will never run
				}

				if (trim($_POST['residence_password']) != "") {
					$password=$_POST['residence_password'];
					$password = stripslashes($password);
					$password = mysql_real_escape_string($password);
					$sql_update_residence_password = "UPDATE residences INNER JOIN head_residents ON residences.residence_id = head_residents.fk_residence_id SET password='$password' WHERE head_resident_id = '$head_resident_id'";
					$P->do_query($sql_update_residence_password);
				}

				if (trim($_POST['miscinfo']) == "") {
					$miscinfo=$_POST['miscinfo'];
					$miscinfo = stripslashes($miscinfo);
					$miscinfo = mysql_real_escape_string($miscinfo);
					$sql_update_miscinfo = "UPDATE residences INNER JOIN head_residents ON residences.residence_id = head_residents.fk_residence_id SET miscinfo='$miscinfo' WHERE head_resident_id = '$head_resident_id'";
					$P->do_query($sql_update_miscinfo);
				}
				else {
					$miscinfo=$_POST['miscinfo'];
					$miscinfo = stripslashes($miscinfo);
					$miscinfo = mysql_real_escape_string($miscinfo);
					$sql_update_miscinfo = "UPDATE residences INNER JOIN head_residents ON residences.residence_id = head_residents.fk_residence_id SET miscinfo='$miscinfo' WHERE head_resident_id = '$head_resident_id'";
					$P->do_query($sql_update_miscinfo);
				}

				$pincolor=$_POST['pincolor'];
				$pincolor = stripslashes($pincolor);
				$pincolor = mysql_real_escape_string($pincolor);
				$sql_update_pincolor= "UPDATE residences INNER JOIN head_residents ON residences.residence_id = head_residents.fk_residence_id SET pin_color='$pincolor' WHERE head_resident_id = '$head_resident_id'";			$P->do_query($sql_update_pincolor);

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

		// Check if the emergency phone number is in the correct format
		if(preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST['head_resident_emergency'])) {
  			$emergency=$_POST['head_resident_emergency']; // Store variable if correct
  		} 
		else { // Return the user to edit profile if the emergency contact number is not correct
			header("location: editresident.php?residence=$fk_residence_id&error=emergency");
			exit; // Just in case, exit the file so the rest of the code will never run
		}

		// Check if the additional phone number is set
		if(trim($_POST['head_resident_phone_one']) != "") {
			// Check if the emergency phone number is in the correct format
			if(preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST['head_resident_phone_one'])) {
				$phone_one=$_POST['head_resident_phone_one'];
			} 
			else { // Return the user to edit profile if the phone number is not correct
				header("location: editresident.php?residence=$fk_residence_id&error=phone");
				exit; // Just in case, exit the file so the rest of the code will never run
			}
		} 
		else {
			$phone_one = ""; // Set the variable to nothing to make sure the MySQL statement runs
		}

		// Filter the email address to be in the correct format
		if (trim($_POST['head_resident_email_address']) != "") {
			if (filter_var($_POST['head_resident_email_address'], FILTER_VALIDATE_EMAIL)) {
				$email_address=$_POST['head_resident_email_address'];
			}
			else { // Return the user to edit profile if the email address is not correct
				header("location: editresident.php?residence=$fk_residence_id&error=email");
				exit; // Just in case, exit the file so the rest of the code will never run
			}
		}
		else {
			$email_address = ""; // Set the variable to nothing to make sure the MySQL statement runs
		}

		$password =$_POST['residence_password'];
		$miscinfo =$_POST['miscinfo'];
		$pincolor =$_POST['pincolor'];

		// To protect MySQL injection for Security purpose
		$first_name = stripslashes($first_name);
		$last_name = stripslashes($last_name);
		$emergency = stripslashes($emergency);
		$phone_one = stripslashes($phone_one);
		$email_address = stripslashes($email_address);
		$password = stripslashes($password);
		$miscinfo = stripslashes($miscinfo);
		$pincolor = stripslashes($pincolor);

		$first_name = mysql_real_escape_string($first_name);
		$last_name = mysql_real_escape_string($last_name);
		$emergency = mysql_real_escape_string($emergency);
		$phone_one = mysql_real_escape_string($phone_one);
		$email_address = mysql_real_escape_string($email_address);
		$password = mysql_real_escape_string($password);
		$miscinfo = mysql_real_escape_string($miscinfo);
		$pincolor = mysql_real_escape_string($pincolor);


		// Create connection
		$P = new manage_db;
		$P->connect_db();

		// Check connection
		$sql_head_residents_insert = "INSERT INTO head_residents (fk_residence_id, first_name, last_name, emergency_contact, phone_one, email_address, miscinfo, pin_color) VALUES ('$fk_residence_id','$first_name','$last_name', '$emergency','$phone_one','$email_address', '$miscinfo', '$pincolor')";
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

		// Check if emergency phone number is not blank
		if (trim($_POST['head_resident_emergency']) != "") {
			if (preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST['head_resident_emergency'])) {
				$emergency=$_POST['head_resident_emergency'];
				$emergency = stripslashes($emergency);
				$emergency = mysql_real_escape_string($emergency);
				$sql_head_residents_insert = "UPDATE head_residents SET emergency_contact = '$emergency' WHERE head_resident_id = '$head_resident_id'";
				$P->do_query($sql_head_residents_insert);
			} 
			else { // Return the user to edit profile if the emergency contact number is not correct
				header("location: editresident.php?resident=$head_resident_id&error=emergency");
				exit; // Just in case, exit the file so the rest of the code will never run
			}
			
		} 

		// If phone number is blank enter into the database
		if (trim($_POST['head_resident_phone_one']) == "") {
			$phone_one=$_POST['head_resident_phone_one'];
			$phone_one = stripslashes($phone_one);
			$phone_one = mysql_real_escape_string($phone_one);
			$sql_head_residents_insert = "UPDATE head_residents SET phone_one = '$phone_one' WHERE head_resident_id = '$head_resident_id'";
			$P->do_query($sql_head_residents_insert);
		}
			// Check if the emergency phone number is in the correct format
		elseif(preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST['head_resident_phone_one'])) {
			$phone_one=$_POST['head_resident_phone_one'];
			$phone_one = stripslashes($phone_one);
			$phone_one = mysql_real_escape_string($phone_one);
			$sql_head_residents_insert = "UPDATE head_residents SET phone_one = '$phone_one' WHERE head_resident_id = '$head_resident_id'";
			$P->do_query($sql_head_residents_insert);
		} 
				else { // Return the user to edit profile if the emergency contact number is not correct
					header("location: editresident.php?resident=$head_resident_id&error=phone");
					exit; // Just in case, exit the file so the rest of the code will never run
				}

		// If email address is blank enter into the database
				if (trim($_POST['head_resident_email_address']) == "") {
					$email_address=$_POST['head_resident_email_address'];
					$email_address = stripslashes($email_address);
					$email_address = mysql_real_escape_string($email_address);
					$sql_head_residents_insert = "UPDATE head_residents SET email_address = '$email_address' WHERE head_resident_id = '$head_resident_id'";
					$P->do_query($sql_head_residents_insert);

				}
			// Validate email address
				elseif (filter_var($_POST['head_resident_email_address'], FILTER_VALIDATE_EMAIL)) {
					$email_address=$_POST['head_resident_email_address'];
					$email_address = stripslashes($email_address);
					$email_address = mysql_real_escape_string($email_address);
					$sql_head_residents_insert = "UPDATE head_residents SET email_address = '$email_address' WHERE head_resident_id = '$head_resident_id'";
					$P->do_query($sql_head_residents_insert);
				}
				else { // Return the user to edit profile if the email address is not correct
					header("location: editresident.php?resident=$head_resident_id&error=email");
					exit; // Just in case, exit the file so the rest of the code will never run
				}

				if (trim($_POST['residence_password']) != "") {
					$password=$_POST['residence_password'];
					$password = stripslashes($password);
					$password = mysql_real_escape_string($password);
					$sql_update_residence_password = "UPDATE residences INNER JOIN head_residents ON residences.residence_id = head_residents.fk_residence_id SET password='$password' WHERE head_resident_id = '$head_resident_id'";
					$P->do_query($sql_update_residence_password);
				}

				if (trim($_POST['miscinfo']) == "") {
					$miscinfo=$_POST['miscinfo'];
					$miscinfo = stripslashes($miscinfo);
					$miscinfo = mysql_real_escape_string($miscinfo);
					$sql_update_miscinfo = "UPDATE residences INNER JOIN head_residents ON residences.residence_id = head_residents.fk_residence_id SET miscinfo='$miscinfo' WHERE head_resident_id = '$head_resident_id'";
					$P->do_query($sql_update_miscinfo);
				}
				else {
					$miscinfo=$_POST['miscinfo'];
					$miscinfo = stripslashes($miscinfo);
					$miscinfo = mysql_real_escape_string($miscinfo);
					$sql_update_miscinfo = "UPDATE residences INNER JOIN head_residents ON residences.residence_id = head_residents.fk_residence_id SET miscinfo='$miscinfo' WHERE head_resident_id = '$head_resident_id'";
					$P->do_query($sql_update_miscinfo);
				}

				$pincolor=$_POST['pincolor'];
				$pincolor = stripslashes($pincolor);
				$pincolor = mysql_real_escape_string($pincolor);
				$sql_update_pincolor= "UPDATE residences INNER JOIN head_residents ON residences.residence_id = head_residents.fk_residence_id SET pin_color='$pincolor' WHERE head_resident_id = '$head_resident_id'";			$P->do_query($sql_update_pincolor);


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

	// Check if the additional phone number is set
	if(trim($_POST['sub_resident_phone_number']) != "") {
			// Check if the emergency phone number is in the correct format
		if(preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST['sub_resident_phone_number'])) {
			$phone_number=$_POST['sub_resident_phone_number'];
		} 
			else { // Return the user to edit profile if the phone number is not correct
				header("location: editprofile.php?sub_error=phone");
				exit; // Just in case, exit the file so the rest of the code will never run
			}
		} 
		else {
			$phone_number = ""; // Set the variable to nothing to make sure the MySQL statement runs
		}

		// Filter the email address to be in the correct format
		if (trim($_POST['sub_resident_email_address']) != "") {
			if (filter_var($_POST['sub_resident_email_address'], FILTER_VALIDATE_EMAIL)) {
				$email_address=$_POST['sub_resident_email_address'];
			}
			else { // Return the user to edit profile if the email address is not correct
				header("location: editprofile.php?sub_error=email");
				exit; // Just in case, exit the file so the rest of the code will never run
			}
		}
		else {
			$email_address = ""; // Set the variable to nothing to make sure the MySQL statement runs
		}

	// To protect MySQL injection for Security purpose
		$first_name = stripslashes($first_name);
		$last_name = stripslashes($last_name);
		$phone_number = stripslashes($phone_number);
		$email_address = stripslashes($email_address);

		$first_name = mysql_real_escape_string($first_name);
		$last_name = mysql_real_escape_string($last_name);
		$phone_number = mysql_real_escape_string($phone_number);
		$email_address = mysql_real_escape_string($email_address);

	// Create connection
		$P = new manage_db;
		$P->connect_db();
	// Check connection
		$sql_sub_residents_insert = "INSERT INTO sub_residents (fk_head_id, first_name, last_name, phone_number, email_address) VALUES ('$fk_head_id','$first_name','$last_name','$phone_number', '$email_address')";
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

	// If phone number is blank enter into the database
	if (trim($_POST["update_sub_resident_phone_number:$sub_residents_id"]) == "") {
		$phone_number=$_POST["update_sub_resident_phone_number:$sub_residents_id"];
		$phone_number = stripslashes($phone_number);
		$phone_number = mysql_real_escape_string($phone_number);
		$sql_sub_residents_update = "UPDATE sub_residents SET phone_number = '$phone_number' WHERE sub_residents_id = '$sub_residents_id' AND fk_head_id = '$fk_head_id'";
		$P->do_query($sql_sub_residents_update);

	}
			// Validate email address
	elseif (preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST["update_sub_resident_phone_number:$sub_residents_id"])) {
		$phone_number=$_POST["update_sub_resident_phone_number:$sub_residents_id"];
		$phone_number = stripslashes($phone_number);
		$phone_number = mysql_real_escape_string($phone_number);
		$sql_sub_residents_update = "UPDATE sub_residents SET phone_number = '$phone_number' WHERE sub_residents_id = '$sub_residents_id' AND fk_head_id = '$fk_head_id'";
		$P->do_query($sql_sub_residents_update);
	}
				else { // Return the user to edit profile if the email address is not correct
					header("location: editresident.php?resident=$fk_head_id&sub_error=phone");
					exit; // Just in case, exit the file so the rest of the code will never run
				}

	// If email address is blank enter into the database
				if (trim($_POST["update_sub_resident_email_address:$sub_residents_id"]) == "") {
					$email_address=$_POST["update_sub_resident_email_address:$sub_residents_id"];
					$email_address = stripslashes($email_address);
					$email_address = mysql_real_escape_string($email_address);
					$sql_sub_residents_update = "UPDATE sub_residents SET email_address = '$email_address' WHERE sub_residents_id = '$sub_residents_id' AND fk_head_id = '$fk_head_id'";
					$P->do_query($sql_sub_residents_update);

				}
			// Validate email address
				elseif (filter_var($_POST["update_sub_resident_email_address:$sub_residents_id"], FILTER_VALIDATE_EMAIL)) {
					$email_address=$_POST["update_sub_resident_email_address:$sub_residents_id"];
					$email_address = stripslashes($email_address);
					$email_address = mysql_real_escape_string($email_address);
					$sql_sub_residents_update = "UPDATE sub_residents SET email_address = '$email_address' WHERE sub_residents_id = '$sub_residents_id' AND fk_head_id = '$fk_head_id'";
					$P->do_query($sql_sub_residents_update);
				}
				else { // Return the user to edit profile if the email address is not correct
					header("location: editresident.php?resident=$fk_head_id&sub_error=email");
					exit; // Just in case, exit the file so the rest of the code will never run
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


	// If phone number is blank enter into the database
	if (trim($_POST["update_sub_resident_phone_number:$sub_residents_id"]) == "") {
		$phone_number=$_POST["update_sub_resident_phone_number:$sub_residents_id"];
		$phone_number = stripslashes($phone_number);
		$phone_number = mysql_real_escape_string($phone_number);
		$sql_sub_residents_update = "UPDATE sub_residents SET phone_number = '$phone_number' WHERE sub_residents_id = '$sub_residents_id' AND fk_head_id = '$fk_head_id'";
		$P->do_query($sql_sub_residents_update);

	}
			// Validate email address
	elseif (preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST["update_sub_resident_phone_number:$sub_residents_id"])) {
		$phone_number=$_POST["update_sub_resident_phone_number:$sub_residents_id"];
		$phone_number = stripslashes($phone_number);
		$phone_number = mysql_real_escape_string($phone_number);
		$sql_sub_residents_update = "UPDATE sub_residents SET phone_number = '$phone_number' WHERE sub_residents_id = '$sub_residents_id' AND fk_head_id = '$fk_head_id'";
		$P->do_query($sql_sub_residents_update);
	}
				else { // Return the user to edit profile if the email address is not correct
					header("location: editprofile.php?sub_error=phone");
					exit; // Just in case, exit the file so the rest of the code will never run
				}

	// If email address is blank enter into the database
				if (trim($_POST["update_sub_resident_email_address:$sub_residents_id"]) == "") {
					$email_address=$_POST["update_sub_resident_email_address:$sub_residents_id"];
					$email_address = stripslashes($email_address);
					$email_address = mysql_real_escape_string($email_address);
					$sql_sub_residents_update = "UPDATE sub_residents SET email_address = '$email_address' WHERE sub_residents_id = '$sub_residents_id' AND fk_head_id = '$fk_head_id'";
					$P->do_query($sql_sub_residents_update);

				}
			// Validate email address
				elseif (filter_var($_POST["update_sub_resident_email_address:$sub_residents_id"], FILTER_VALIDATE_EMAIL)) {
					$email_address=$_POST["update_sub_resident_email_address:$sub_residents_id"];
					$email_address = stripslashes($email_address);
					$email_address = mysql_real_escape_string($email_address);
					$sql_sub_residents_update = "UPDATE sub_residents SET email_address = '$email_address' WHERE sub_residents_id = '$sub_residents_id' AND fk_head_id = '$fk_head_id'";
					$P->do_query($sql_sub_residents_update);
				}
				else { // Return the user to edit profile if the email address is not correct
					header("location: editprofile.php?sub_error=email");
					exit; // Just in case, exit the file so the rest of the code will never run
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

		// Check if the additional phone number is set
	if(trim($_POST['sub_resident_phone_number']) != "") {
			// Check if the emergency phone number is in the correct format
		if(preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST['sub_resident_phone_number'])) {
			$phone_number=$_POST['sub_resident_phone_number'];
		} 
			else { // Return the user to edit profile if the phone number is not correct
				header("location: editresident.php?resident=$fk_head_id&sub_error=phone");
				exit; // Just in case, exit the file so the rest of the code will never run
			}
		} 
		else {
			$phone_number = ""; // Set the variable to nothing to make sure the MySQL statement runs
		}

		// Filter the email address to be in the correct format
		if (trim($_POST['sub_resident_email_address']) != "") {
			if (filter_var($_POST['sub_resident_email_address'], FILTER_VALIDATE_EMAIL)) {
				$email_address=$_POST['sub_resident_email_address'];
			}
			else { // Return the user to edit profile if the email address is not correct
				header("location: editresident.php?resident=$fk_head_id&sub_error=email");
				exit; // Just in case, exit the file so the rest of the code will never run
			}
		}
		else {
			$email_address = ""; // Set the variable to nothing to make sure the MySQL statement runs
		}

	// To protect MySQL injection for Security purpose
		$first_name = stripslashes($first_name);
		$last_name = stripslashes($last_name);
		$phone_number = stripslashes($phone_number);
		$email_address = stripslashes($email_address);

		$first_name = mysql_real_escape_string($first_name);
		$last_name = mysql_real_escape_string($last_name);
		$phone_number = mysql_real_escape_string($phone_number);
		$email_address = mysql_real_escape_string($email_address);

	// Create connection
		$P = new manage_db;
		$P->connect_db();
	// Check connection
		$sql_sub_residents_insert = "INSERT INTO sub_residents (fk_head_id, first_name, last_name, phone_number, email_address) VALUES ('$fk_head_id','$first_name','$last_name','$phone_number', '$email_address')";
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
elseif(isset($_POST['admin_change_residence_password'])) {

	include('db_class.php');
	$P = new manage_db;
	$P->connect_db();

	$residence_id = $_POST['admin_change_residence_password'];
	$password=$_POST["new_residence_password"];
	$password = stripslashes($password);
	$password = mysql_real_escape_string($password);
	$sql_change_residence_password = "UPDATE residences SET password = '$password' WHERE residence_id = '$residence_id'";
	$P->do_query($sql_change_residence_password);

	header("location: admin.php");
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

	header("location: configuration.php");
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

	header("location: configuration.php");
	exit; // Just in case, exit the file so the rest of the code will never run

}
else {
	header("location: myhome.php");
	exit;
}
?>