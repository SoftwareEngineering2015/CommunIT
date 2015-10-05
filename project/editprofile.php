<!DOCTYPE html>
<html>
<head>

<?php
  require_once( "template_class.php");       // css and headers
  $H = new template( "My Home" );
  $H->show_template( );

  // Create connection
  $P = new manage_db;
  $P->connect_db();

  $sql_head_residents = "SELECT head_resident_id, first_name, last_name, emergency_contact, phone_one, email_address FROM head_residents INNER JOIN residences ON head_residents.fk_residence_id = residences.residence_id WHERE username='$login_session'";
  $P->do_query($sql_head_residents);
  $head_residents_result = mysql_query($sql_head_residents); 

  $head_residents = array(); //Holds head residents' information

  // Checks to see if there is a head resident for the residence  
  if(mysql_num_rows($head_residents_result)==0) {

  	echo "<script type='text/javascript'>$(window).load(function(){ $('#myModal').modal('show'); }); </script>"; // Loads the popup window on page load
  	$hide_sub_resident = true; // Hides the sub resident table

  	$sql_residence_id = "SELECT residence_id FROM residences WHERE username='$login_session'";
  	$P->do_query($sql_residence_id);
  	$residence_id_result = mysql_query($sql_residence_id); 

  	// Goes through the result of the query to get the id of the current user's residence 
  	while ($row = mysql_fetch_assoc($residence_id_result))
  	{

  		array_push($head_residents, "N:" .$row['residence_id']); // Residence id for the current user; N denotes that the user is new

  	}

  	$require_first_name = true; // Variable that will be used later to require first name for head resident
  	$require_last_name = true; // Variable that will be used later to require last name for head resident
  	$require_emergency = true; // Variable that will be used later to require emergency contact for head resident

  	// Puts empty space in the array because this array is used to display the current information of the head resident
  	array_push($head_residents, " ");
  	array_push($head_residents, " ");
  	array_push($head_residents, " ");
  	array_push($head_residents, " ");
  	array_push($head_residents, " ");

  } else { // This section is for if there is a head resident registered to the residence

  	$hide_sub_resident = false; // Sub resident information will be displayed

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
  	}

  	// Query that gets the data for the sub resident table
  	$sql_sub_residents = "SELECT sub_residents.sub_residents_id AS sub_residents_id, sub_residents.first_name AS first_name, sub_residents.last_name AS last_name, sub_residents.phone_number AS phone_number FROM sub_residents INNER JOIN head_residents ON sub_residents.fk_head_id = head_residents.head_resident_id WHERE fk_head_id='$head_residents[0]'";
  	$P->do_query($sql_sub_residents);
  	$sub_residents_result = mysql_query($sql_sub_residents);
  }

  ?>
</head>
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
				<div class="col-md-6">
					<h3> Head Resident Information </h3>
					<table class="table table-striped table-hover ">
						<tr>
							<th> </th>
							<th> Current Information </th>
							<th> New Information </th>
						</tr>
						<tr>
							<th> First Name </th>
							<td> <?php echo "$head_residents[1]";?> </td> <!-- Head resident first name -->
							<td> <input id="head_resident_first_name" name="head_resident_first_name" type="text" placeholder="John" class="form-control input-md" <?php if ($require_first_name) echo 'required'; ?> > </td>
						</tr>
						<tr>
							<th> Last Name </th>
							<td> <?php echo "$head_residents[2]";?> </td> <!-- Head resident last name -->
							<td> <input id="head_resident_last_name" name="head_resident_last_name" type="text" placeholder="Smith" class="form-control input-md" <?php if ($require_last_name) echo 'required'; ?>> </td>
						</tr>
						<tr>
							<th> Emergency Contact </th>
							<td> <?php echo "$head_residents[3]";?> </td> <!-- Head resident Emergency Contact -->
							<td> <input id="head_resident_emergency" name="head_resident_emergency" type="text" placeholder="555-555-5555" class="form-control input-md" <?php if ($require_emergency) echo 'required'; ?>> </td>
						</tr>
						<tr>
							<th> Phone Number </th>
							<td> <?php echo "$head_residents[4]";?> </td>  <!-- Head resident phone number -->
							<td> <input id="head_resident_phone_one" name="head_resident_phone_one" type="text" placeholder="555-555-5555" class="form-control input-md"> </td>
						</tr>
						<tr>
							<th> E-mail Address </th>
							<td> <?php echo "$head_residents[5]";?> </td>  <!-- Head resident Email Address -->
							<td> <input id="head_resident_email_address" name="head_resident_email_address" type="text" placeholder="example@communit.com" class="form-control input-md"> </td>
						</tr>
					</table> 
					<div class="form-group last">
						<div class="col-sm-offset-3 col-sm-6"> <!-- Value for the button is needed to tell the update file what the id of the user is -->
							<button name="submit_head_resident" type="submit" value=<?php echo "'$head_residents[0]'" ?> class="btn btn-primary btn-lg" style="border: 2px solid black; width: 100%;"> Update Profile </button>
						</div>
					</div>
				</div>

			</form> 

			<!-- Form for the sub resident information -->
			<form action="updateprofile.php" method="POST">
				<div class="col-md-6" <?php if ($hide_sub_resident){ echo 'style="display:none;"'; } ?>> <!-- Hides this div / table if there isn't a head resident registered to the residence -->
					<h3> Resident Information </h3>
					<table class="table table-striped table-hover ">
						<tr>
							<th> First Name </th>
							<th> Last Name </th>
							<th> Phone Number </th>
							<th> </th>
						</tr>
						<?php

						// Displays the sub resident information
						while ($row = mysql_fetch_assoc($sub_residents_result))
						{
							$sub_residents_id =  $row ['sub_residents_id'];
							$first_name =  $row ['first_name'];
							$last_name =  $row ['last_name'];
							$phone_number =  $row ['phone_number'];
							echo "<tr> <td> $first_name </td> ";
							echo "<td> $last_name </td> ";
							echo "<td> $phone_number </td>";
							// Delete button for each sub resident row; Value for the button holds the id of the sub resident and the id of the head resident, separated by the colon ( neeeded for the delete query)
							echo "<td><button name='delete_sub_resident' type='submit' value=". $sub_residents_id . ":" . $head_residents[0] . " class='btn btn-danger btn-sm' style='border: 2px solid black; width: 100%;'> X </button> </td></tr>";
						}
						?>
						<tr>
							<td> <input id="sub_resident_first_name" name="sub_resident_first_name" type="text" placeholder="John" class="form-control input-md" > </td>
							<td> <input id="sub_resident_last_name" name="sub_resident_last_name" type="text" placeholder="Smith" class="form-control input-md" > </td>
							<td> <input id="sub_resident_phone_number" name="sub_resident_phone_number" type="text" placeholder="555-555-5555" class="form-control input-md" > </td>
							<td> </td>
						</tr>
					</table> 
					<div class="form-group last">
						<div class="col-sm-offset-3 col-sm-6"> <!-- Value for the button is needed to tell the update file what the id of the user is -->
							<button name="submit_sub_resident" type="submit" value=<?php echo "'$head_residents[0]'" ?> class="btn btn-primary btn-lg" style="border: 2px solid black; width: 100%;"> Add Resident </button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>


</body>
</html>