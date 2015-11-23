<!DOCTYPE html>
<html>
<head>

	<?php

  require_once( "template_class.php");       // css and headers
  $H = new template( "My Home" );
  $H->show_template( );

  	if(($_SESSION['login_user']) == "admin"){
		header("location: admin.php");
		exit();
	}

  // Create connection
  $P = new manage_db;
  $P->connect_db();

  $sql_head_residents = "SELECT * FROM head_residents INNER JOIN residences ON head_residents.fk_residence_id = residences.residence_id WHERE username='$login_session'";
  $P->do_query($sql_head_residents);
  $head_residents_result = mysql_query($sql_head_residents); 

  $head_residents = array(); //Holds head residents' information

  $error = "";

  if (isset($_GET['error']) && $_GET['error'] == 'emergency') {
	$error = "<span style='color:red;'> Emergency contact number must be in xxx-xxx-xxxx format. </span><br />";
  }
  if (isset($_GET['error']) && $_GET['error'] == 'phone') {
	$error = "<span style='color:red;'> Additional phone number must be in xxx-xxx-xxxx format. </span><br />";
  }
  if (isset($_GET['error']) && $_GET['error'] == 'email') {
	$error = "<span style='color:red;'> E-mail address must be a valid e-mail. </span><br />";
  }

  $sub_error = "";

  if (isset($_GET['sub_error']) && $_GET['sub_error'] == 'phone') {
	$sub_error = "<span style='color:red;'> Sub Resident phone number must be in xxx-xxx-xxxx format. </span><br />";
  }
  if (isset($_GET['sub_error']) && $_GET['sub_error'] == 'email') {
	$sub_error = "<span style='color:red;'> Sub Resident e-mail address must be a valid e-mail. </span><br />";
  }


  // Checks to see if there is a head resident for the residence  
  if(mysql_num_rows($head_residents_result)==0) {

  	echo "<script type='text/javascript'>$(window).load(function(){ $('#myModal').modal('show'); }); </script>"; // Loads the popup window on page load

  	$sql_residence_id = "SELECT residence_id FROM residences WHERE username='$login_session'";
  	$P->do_query($sql_residence_id);
  	$residence_id_result = mysql_query($sql_residence_id); 

  	// Goes through the result of the query to get the id of the current user's residence 
  	while ($row = mysql_fetch_assoc($residence_id_result))
  	{

  		array_push($head_residents, "N:" .$row['residence_id']); // Residence id for the current user; N denotes that the user is new

  	}

  	$hide_elements = "style='display:none'";
  	$require_first_name = true; // Variable that will be used later to require first name for head resident
  	$require_last_name = true; // Variable that will be used later to require last name for head resident
  	$require_emergency = true; // Variable that will be used later to require emergency contact for head resident
	$require_password = true; // Variable that will be used later to require emergency contact for head resident

  	// Puts empty space in the array because this array is used to display the current information of the head resident
	array_push($head_residents, "");
	array_push($head_residents, "");
	array_push($head_residents, "");
	array_push($head_residents, "");
	array_push($head_residents, "");
	array_push($head_residents, "");

	$sql_default_color = "DESCRIBE head_residents";
	$P->do_query($sql_default_color);
	$default_color_result = mysql_query($sql_default_color); 
	while ($row = mysql_fetch_assoc($default_color_result))
	  {
	  	if ($row['Field'] == 'pin_color') { 
	  			array_push($head_residents, $row['Default']);
	  	}
	  }

	
  } else { // This section is for if there is a head resident registered to the residence

  	$hide_elements = "";

  	$require_first_name = false; // Required first name is not needed when updating head resident data
  	$require_last_name = false; // Required last name is not needed when updating head resident data
  	$require_emergency = false; // Required emergency contact is not needed when updating head resident data
  	$require_password = false; // Required emergency contact is not needed when updating head resident data

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
  		array_push($head_residents, $row['pin_color']); // Current pin color
  	}

  	$sql_residence_password = "SELECT password FROM residences WHERE username='$login_session'";
  	$P->do_query($sql_residence_password);
  	$residence_password_result = mysql_query($sql_residence_password);
  	while ($row = mysql_fetch_assoc($residence_password_result))
  	{
  		array_push($head_residents, $row['password']); // Head resident id; used for updating columns for the current user
  		
  	}

  	// Query that gets the data for the sub resident table
  	$sql_max_per_residence = "SELECT max_per_residence FROM configuration";
  	$P->do_query($sql_max_per_residence);
  	$max_per_residence_result = mysql_query($sql_max_per_residence);
  	while ($row = mysql_fetch_assoc($max_per_residence_result))
  	{
  		$max_per_residence = $row['max_per_residence'];
  	}

  	// Query that gets the data for the sub resident table
  	$sql_sub_residents = "SELECT sub_residents.sub_residents_id AS sub_residents_id, sub_residents.first_name AS first_name, sub_residents.last_name AS last_name, sub_residents.phone_number AS phone_number, sub_residents.email_address AS email_address FROM sub_residents INNER JOIN head_residents ON sub_residents.fk_head_id = head_residents.head_resident_id WHERE fk_head_id='$head_residents[0]'";
  	$P->do_query($sql_sub_residents);
  	$sub_residents_result = mysql_query($sql_sub_residents);
  }

  ?>
</head>
<script type="text/javascript" src="js/colorpins.js"></script>
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
<body>

	<!-- Modal -->
	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"></button>
					<h4 class="modal-title" style="text-align: center; font-size: 200%;"><b>Welcome To CommunIT</b></h4>
				</div>
				<div class="modal-body">
					<b><p  style="font-size: 120%;">Welcome to CommunIT! <br/>
						You have not yet registered a Head Resident for this location.<br/>
						Please fill out the form below to set a Head Resident.</p></b>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary btn-lg" data-dismiss="modal">Okay!</button>
					</div>
				</div>

			</div>
		</div>

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
								<td> <input id="head_resident_first_name" name="head_resident_first_name" value=<?php echo "'$head_residents[1]'";?> placeholder=<?php echo "'$head_residents[1]'";?> type="text" class="form-control input-md" <?php if ($require_first_name) echo 'required'; ?> > </td>
							</tr>
							<tr>
								<th> Last Name <?php if ($require_last_name) echo '<span style="color:red">*</span>'; ?></th>
								<td> </td>
								<td> <input id="head_resident_last_name" name="head_resident_last_name" value=<?php echo "'$head_residents[2]'";?> placeholder=<?php echo "'$head_residents[2]'";?> type="text" class="form-control input-md" <?php if ($require_last_name) echo 'required'; ?>> </td>
							</tr>
							<tr>
								<th> Emergency Contact Number <?php if ($require_emergency) echo '<span style="color:red">*</span>'; ?></th>
								<td> </td>
								<td> <input pattern='\d{3}[\-]\d{3}[\-]\d{4}' title='xxx-xxx-xxxx' id="head_resident_emergency" name="head_resident_emergency" value=<?php echo "'$head_residents[3]'";?> placeholder=<?php echo "'$head_residents[3]'";?> type="tel" class="form-control input-md" <?php if ($require_emergency) echo 'required'; ?>> </td>
							</tr>
							<tr>
								<th> Additional Phone Number </th>
								<td> </td>
								<td> <input id="head_resident_phone_one" name="head_resident_phone_one" value=<?php echo "'$head_residents[4]'";?> placeholder=<?php echo "'$head_residents[4]'";?> type="tel" class="form-control input-md"> </td>
							</tr>
							<tr>
								<th> E-mail Address </th>
								<td> </td>
								<td> <input id="head_resident_email_address" name="head_resident_email_address" value=<?php echo "'$head_residents[5]'";?> placeholder=<?php echo "'$head_residents[5]'";?> type="email" class="form-control input-md"> </td>
							</tr>
							<tr>
								<th> Password <?php if ($require_password) echo '<span style="color:red">*</span>'; ?></th>
								<td> </td>
								<td> <input id="residence_password" name="residence_password" type="password" class="form-control input-md" minlength="8" maxlength="25" <?php if ($require_password) echo 'required'; ?>> </td>
							</tr>
							<tr>
								<th> Misc Information</th>
								<td> </td>
								<td> <textarea class="form-control" name="miscinfo" id="miscinfo" placeholder=<?php echo "'$head_residents[6]'";?> wrap="hard" rows="5" maxlength="255"><?php echo "$head_residents[6]";?></textarea>
							</tr>
							<tr>
								<th> Pin Color </th>
								<td> <img id="house_pin" alt="" style="width:auto; height;auto"> </td> 
								<td> <input type="color" name="pincolor" id="pincolor" value=<?php echo "'$head_residents[7]'";?> style="width: 100%"> </td>
							</tr>
							<tr>
								<td></td>
								<td> </td>
								<td> <button name="submit_head_resident" type="submit" value=<?php echo "'$head_residents[0]'" ?> class="btn btn-primary btn-lg" style=" width: 100%;"> Update Profile </button> </td>
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

							$hide_add_new_sub_resident = "";
							$counter = 0;
						// Displays the sub resident information
							while ($row = mysql_fetch_assoc($sub_residents_result))
							{
								$sub_residents_id =  $row ['sub_residents_id'];
								$first_name =  $row ['first_name'];
								$last_name =  $row ['last_name'];
								$phone_number =  $row ['phone_number'];
								$email_address =  $row ['email_address'];
								echo "<tr> <td> <input name='update_sub_resident_first_name:".$sub_residents_id."' type='text' value=" . $first_name . " placeholder=" . $first_name . " class='form-control input-md' > </td>";
								echo "<td> <input name='update_sub_resident_last_name:".$sub_residents_id."' type='text' value=" . $last_name . " placeholder=" . $last_name . " class='form-control input-md' > </td> ";
								echo "<td> <input name='update_sub_resident_phone_number:".$sub_residents_id."' type='tel'";
								if (isset($phone_number))
									echo "value='$phone_number' placeholder='$phone_number'";
								echo "class='form-control input-md' > </td>";
								echo "<td> <input name='update_sub_resident_email_address:".$sub_residents_id."' type='email'";
								if (isset($email_address))
									echo "value='$email_address' placeholder='$email_address'";
								echo "class='form-control input-md' > </td>";
								echo "<td><button name='update_sub_resident' type='submit' value=". $sub_residents_id . ":" . $head_residents[0] . " class='btn btn-primary btn-sm glyphicon glyphicon-pencil' style='   width: 100%;'></button>";
								echo "<button name='delete_sub_resident' type='submit' value=". $sub_residents_id . ":" . $head_residents[0] . " class='btn btn-danger btn-sm glyphicon glyphicon-remove' style='   width: 100%;'> </button> </td></tr>";
								$counter = $counter + 1;
							}
							if ($max_per_residence <= $counter) {
									$hide_add_new_sub_resident = "style = 'display:none;'";
								}
							?>
						</form>
						<!-- Form for the sub resident information -->
						<form action="updateprofile.php" method="POST">
							<tr id='add_new_sub_resident' <?php echo $hide_add_new_sub_resident; ?>>
								<td> <input id="sub_resident_first_name" name="sub_resident_first_name" type="text" class="form-control input-md" required> </td>
								<td> <input id="sub_resident_last_name" name="sub_resident_last_name" type="text" class="form-control input-md" required> </td>
								<td> <input id="sub_resident_phone_number" name="sub_resident_phone_number" type="tel" class="form-control input-md" > </td>
								<td> <input id="sub_resident_email_address" name="sub_resident_email_address" type="email" class="form-control input-md" > </td>
								<td> <button name="submit_sub_resident" type="submit" value=<?php echo "'$head_residents[0]'" ?> class="btn btn-success btn-lg" style="   width: 100%;"> Add Resident </button> </td>
							</tr>
						</table> 
					</form>
				</div>
			</div>
		</div>

	</body>
	</html>

