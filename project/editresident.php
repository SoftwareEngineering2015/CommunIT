<?php

// This checks to see if the resident id is set and residence id is set so thr program knows which residence the admin is updating or what head resident the admin is updating
if (!isset($_GET['resident']) && !isset($_GET['residence']) ){
	header("location: admin.php"); //Return the admin if neither are set
	exit;
}

//If they are both set then return the admin because only one instance can be updated
if (isset($_GET['resident']) && isset($_GET['residence']) ){
	header("location: admin.php");
	exit;
}


// If the residence id is set in the url then that means that the admin is adding a new head resident to the residence
if (isset($_GET['residence'])){
	require_once( "template_class.php");       // css and headers
	$H = new template( "Administration" );
	$H->show_template( );

	// If the user is not an admin then redirect them
	if(($_SESSION['login_user']) != "admin"){
	  	header("location: myhome.php");
	  	exit();
	}

	$residence = $_GET['residence']; //Get the residence id in the url

	$error = ""; //Setup the error variable

	// If the emergency error is set in the url print the error message 
  	if (isset($_GET['error']) && $_GET['error'] == 'emergency') {
		$error = "<span style='color:red;'> Emergency contact number must be in xxx-xxx-xxxx format. </span><br />";
  	}
  	// if the phone error is set in the url then print the error message
  	if (isset($_GET['error']) && $_GET['error'] == 'phone') {
		$error = "<span style='color:red;'> Additional phone number must be in xxx-xxx-xxxx format. </span><br />";
  	}
  	// If the email error is set in the url then print the error message
  	if (isset($_GET['error']) && $_GET['error'] == 'email') {
		$error = "<span style='color:red;'> E-mail address must be a valid e-mail. </span><br />";
  	}


	// Create connection
	$P = new manage_db;
	$P->connect_db();

	// Check if the residence id set in the url exists
	$sql_head_residents = "SELECT * FROM residences WHERE residence_id='$residence'";
	$P->do_query($sql_head_residents);
	$head_residents_result = mysql_query($sql_head_residents); 



  	// Checks to see if there is a head resident for the residence  
  	if(mysql_num_rows($head_residents_result)==0) {
  		header("location: admin.php");
  		exit;
  	} else { //Run this the residence does exist
  		$sql_head_residents = "SELECT * FROM head_residents WHERE fk_residence_id='$residence'";
	  	$P->do_query($sql_head_residents);
	  	$head_residents_result = mysql_query($sql_head_residents);
	  	if(mysql_num_rows($head_residents_result)==1) {
  			header("location: admin.php");
  			exit;
  		} else {
  			$hide_elements = "style='display:none'"; //Hide the elements if adding a new head resident

  			$require_first_name = true; // Variable that will be used later to require first name for head resident
  			$require_last_name = true; // Variable that will be used later to require last name for head resident
			$require_emergency = true; // Variable that will be used later to require emergency contact for head resident

			$head_residents = array(); // Setup the array
			array_push($head_residents, "N:" .$residence); //Specify that it is a new head resident that we are adding
			
			// Puts empty space in the array because this array is used to display the current information of the head resident
			array_push($head_residents, "");
			array_push($head_residents, "");
			array_push($head_residents, "");
			array_push($head_residents, "");
			array_push($head_residents, "");
			array_push($head_residents, "");

			//Get the default pin color value
			$sql_default_color = "DESCRIBE head_residents";
			$P->do_query($sql_default_color);
			$default_color_result = mysql_query($sql_default_color); 
			while ($row = mysql_fetch_assoc($default_color_result))
	  		{
	  			if ($row['Field'] == 'pin_color') { 
	  				array_push($head_residents, $row['Default']);
	  			}
	  		}

  		}

  	}

}

// If the resident id is set in the url then run this code
if (isset($_GET['resident'])){
	require_once( "template_class.php");       // css and headers
	$H = new template( "Administration" );
	$H->show_template( );

	//If the user is not an admin then redirect them
	if(($_SESSION['login_user']) != "admin"){
	  	header("location: myhome.php");
	  	exit();
	}

	$resident = $_GET['resident']; //Get the id of the resident in the url
	// Create connection
	$P = new manage_db;
	$P->connect_db();

	//Get the information of the head resident
	$sql_head_residents = "SELECT * FROM head_residents WHERE head_resident_id='$resident'";
	$P->do_query($sql_head_residents);
	$head_residents_result = mysql_query($sql_head_residents); 

  	$head_residents = array(); //Holds head residents' information

  	$error = ""; //Set up the error message

  	//Print the error message for an incorrect emergency number
  	if (isset($_GET['error']) && $_GET['error'] == 'emergency') {
		$error = "<span style='color:red;'> Contact Phone Number must be in xxx-xxx-xxxx format. </span><br />";
  	}
  	//Print the error message for incorrect phone number
  	if (isset($_GET['error']) && $_GET['error'] == 'phone') {
		$error = "<span style='color:red;'> Additional phone number must be in xxx-xxx-xxxx format. </span><br />";
  	}
  	//Print this for an incorrect email address
  	if (isset($_GET['error']) && $_GET['error'] == 'email') {
		$error = "<span style='color:red;'> E-mail address must be a valid e-mail. </span><br />";
  	}

  	$sub_error = ""; // Set up the sub resident error message

  	//Print the error message for incorrect phone number
  	if (isset($_GET['sub_error']) && $_GET['sub_error'] == 'phone') {
		$sub_error = "<span style='color:red;'> Sub Resident phone number must be in xxx-xxx-xxxx format. </span><br />";
  	}
  	//Print this for an incorrect email address
  	if (isset($_GET['sub_error']) && $_GET['sub_error'] == 'email') {
		$sub_error = "<span style='color:red;'> Sub Resident e-mail address must be a valid e-mail. </span><br />";
  	}

  	// Checks to see if there is a head resident for the residence  
  	if(mysql_num_rows($head_residents_result)==0) {
  		header("location: admin.php");
  		exit;

  	} else { // This section is for if there is a head resident registered to the residence

  	$hide_elements = ''; //Do not hide the sub resident information
  	$require_first_name = false; // Required first name is not needed when updating head resident data
  	$require_last_name = false; // Required last name is not needed when updating head resident data
  	$require_emergency = false; // Required emergency contact is not needed when updating head resident data

  	// Goes through the query results
  	while ($row = mysql_fetch_assoc($head_residents_result))
  	{

  		array_push($head_residents, $row['head_resident_id']); // Head resident id; used for updating columns for the current user
  		array_push($head_residents, $row['first_name']); // Current head resident first name
  		array_push($head_residents, $row['last_name']); // Current head resident last name
  		array_push($head_residents, $row['emergency_contact']); // Current head resident emergency contact
  		array_push($head_residents, $row['phone_one']); // Current head resident phone number
  		array_push($head_residents, $row['email_address']); // Current head resident email address
  		array_push($head_residents, $row['miscinfo']); // Current misc information
  		array_push($head_residents, $row['pin_color']); // Current misc information
  	}

  	//Get the max residents per residence number
  	$sql_max_per_residence = "SELECT max_per_residence FROM configuration";
  	$P->do_query($sql_max_per_residence);
  	$max_per_residence_result = mysql_query($sql_max_per_residence);
  	//Store the max resident per residence number
  	while ($row = mysql_fetch_assoc($max_per_residence_result))
  	{
  		$max_per_residence = $row['max_per_residence'];
  	}

  	// Query that gets the data for the sub resident table
  	$sql_sub_residents = "SELECT sub_residents.sub_residents_id AS sub_residents_id, sub_residents.first_name AS first_name, sub_residents.last_name AS last_name, sub_residents.phone_number AS phone_number, sub_residents.email_address AS email_address FROM sub_residents INNER JOIN head_residents ON sub_residents.fk_head_id = head_residents.head_resident_id WHERE fk_head_id='$head_residents[0]'";
  	$P->do_query($sql_sub_residents);
  	$sub_residents_result = mysql_query($sql_sub_residents);
  }
}



?>
<!DOCTYPE html>
<html>
<head>
<script>
// Change the image to the default pin color on page load
		$( window ).bind('load',function() {
    		pin_color =  document.getElementById('pincolor').value;
    		overalayColor(pin_color);
    		document.getElementById('house_pin').src = fullimg;
		});

		$(document).ready(function() {
			//Change pin color on change of color select
			$( "#pincolor" ).change(function() {
  				pin_color =  document.getElementById('pincolor').value;
    			overalayColor(pin_color);
    			document.getElementById('house_pin').src = fullimg;
			});
		});
</script>
</head>
<body>

	<!-- Form for the update of head resident -->
	<form action="updateprofile.php" method="POST">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-5">
					<h3> Head Resident Information </h3>
					<?php echo $error; ?>
					<?php if ($require_emergency) echo '<span style="color:red;">*</span><span style="font-size:90%;color:#B8B8B8"> REQUIRED</span>'; ?>
					<table class="table table-striped table-hover ">
						<tr>
                            <th> First Name <?php if ($require_first_name) echo '<span style="color:red">*</span>'; ?></th>
                            <td> </td>
							<td> <input id="head_resident_first_name" name="head_resident_first_name" value="<?php echo ("$head_residents[1]");?>" placeholder="<?php echo ("$head_residents[1]");?>" type="text" class="form-control input-md" <?php if ($require_first_name) echo 'required'; ?> > </td>
						</tr>
						<tr>
							<th> Last Name <?php if ($require_last_name) echo '<span style="color:red">*</span>'; ?></th>
							<td> </td>
							<td> <input id="head_resident_last_name" name="head_resident_last_name" value="<?php echo ("$head_residents[2]");?>" placeholder="<?php echo ("$head_residents[2]");?>" type="text" class="form-control input-md" <?php if ($require_last_name) echo 'required'; ?>> </td>
						</tr>
						<tr>
							<th> Contact Phone Number <?php if ($require_emergency) echo '<span style="color:red">*</span>'; ?></th>
							<td> </td>
							<td> <input pattern='\d{3}[\-]\d{3}[\-]\d{4}' title='xxx-xxx-xxxx' id="head_resident_emergency" name="head_resident_emergency" value="<?php echo "$head_residents[3]";?>" placeholder=<?php echo "'$head_residents[3]'";?> type="tel" class="form-control input-md" <?php if ($require_emergency) echo 'required'; ?>> </td>
						</tr>
						<tr>
							<th> Additional Phone Number </th>
							<td> </td>
							<td> <input id="head_resident_phone_one" name="head_resident_phone_one" value="<?php echo "$head_residents[4]";?>" placeholder="<?php echo "$head_residents[4]";?>" type="tel" class="form-control input-md"> </td>
						</tr>
						<tr>
							<th> E-mail Address </th>
							<td> </td>
							<td> <input id="head_resident_email_address" name="head_resident_email_address" value="<?php echo "$head_residents[5]";?>" placeholder="<?php echo "$head_residents[5]";?>" type="email" class="form-control input-md"> </td>
						</tr>
						<tr>
							<th> Misc Information</th>
							<td> </td>
							<td> <textarea class="form-control" name="miscinfo" id="miscinfo" placeholder="<?php echo ("$head_residents[6]");?>" wrap="soft" rows="5" maxlength="255"><?php echo ("$head_residents[6]");?></textarea>
						</tr>
						<tr>
							<th> Pin Color </th>
							<td> <img id="house_pin" alt="" style="width:auto; height;auto"> </td> 
							<td> <input type="color" name="pincolor" id="pincolor" value=<?php echo "'$head_residents[7]'";?> style="width: 100%"> </td>
						</tr>
						<tr>
							<td></td>
							<td> </td>
							<td> <button name="admin_update_head_resident" type="submit" value=<?php echo "'$head_residents[0]'" ?> class="btn btn-primary btn-lg" style="  width: 100%;"> Update Profile </button>
						</tr>
					</table> 
				</div>
			</form> 

			<div class="col-md-7" <?php echo $hide_elements; ?>> <!-- Hides this div / table if there isn't a head resident registered to the residence -->
				<h3> Resident Information </h3>
				<?php echo $sub_error; ?>
				<table class="table table-striped table-hover ">
					<tr>
						<th> First Name </th>
						<th> Last Name </th>
						<th> Phone Number </th>
						<th> E-mail Address </th>
						<th> Update / Delete </th>
					</tr>
					<!-- Form for the sub resident information -->
					<form action="updateprofile.php" method="POST">
						<?php

						$hide_add_new_sub_resident = ""; //Do not hide the add new sub resident inputs
						$counter = 0;
						// Displays the sub resident information
						while ($row = mysql_fetch_assoc($sub_residents_result))
						{
							$sub_residents_id =  $row ['sub_residents_id'];
							$first_name =  $row ['first_name'];
							$last_name =  $row ['last_name'];
							$phone_number =  $row ['phone_number'];
							$email_address = $row ['email_address'];
							echo "<tr> <td> <input name='update_sub_resident_first_name:".$sub_residents_id."' type='text' value=" . $first_name . " placeholder=" . $first_name . "  class='form-control input-md' > </td>";
							echo "<td> <input name='update_sub_resident_last_name:".$sub_residents_id."' type='text' value=" . $last_name . " placeholder=" . $last_name . "  class='form-control input-md' > </td> ";
							echo "<td> <input name='update_sub_resident_phone_number:".$sub_residents_id."' type='tel'";
							if (isset($phone_number))
								echo "value='$phone_number' placeholder='$phone_number'";
							echo "class='form-control input-md' > </td>";
							echo "<td> <input name='update_sub_resident_email_address:".$sub_residents_id."' type='email'";
							if (isset($email_address))
								echo "value='$email_address' placeholder='$email_address'";
							echo "class='form-control input-md' > </td>";
							echo "<td><button name='admin_update_sub_resident' type='submit' value=". $sub_residents_id . ":" . $head_residents[0] . " class='btn btn-primary btn-sm glyphicon glyphicon-pencil' style='  width: 100%;'></button>";
							echo "<button name='admin_delete_sub_resident' type='submit' value=". $sub_residents_id . ":" . $head_residents[0] . " class='btn btn-danger btn-sm glyphicon glyphicon-remove' style='  width: 100%;'> </button> </td></tr>";
							
							$counter = $counter + 1;
						}
							//If the max residents per residence is less than the counter than they cannot add a new sub resident
							if ($max_per_residence <= $counter) {
									$hide_add_new_sub_resident = "style = 'display:none;'"; //Hide the add new sub resident inputs
							}
						?>
					</form>
					<!-- Form for the sub resident information -->
					<form action="updateprofile.php" method="POST">
						<tr <?php echo $hide_add_new_sub_resident; ?>>
							<td> <input id="sub_resident_first_name" name="sub_resident_first_name" type="text" class="form-control input-md" required> </td>
							<td> <input id="sub_resident_last_name" name="sub_resident_last_name" type="text"  class="form-control input-md" required> </td>
							<td> <input id="sub_resident_phone_number" name="sub_resident_phone_number" type="tel" class="form-control input-md" > </td>
							<td> <input id="sub_resident_email_address" name="sub_resident_email_address" type="email" class="form-control input-md" > </td>
							<td> <button name="admin_add_sub_resident" type="submit" value=<?php echo "'$head_residents[0]'" ?> class="btn btn-success btn-lg" style="  width: 100%;"> Add Resident </button> </td>
						</tr>
					</table> 
				</form>
			</div>
		</div>
	</div>
<script type="text/javascript" src="js/colorpins.js"></script>
</body>
</html>