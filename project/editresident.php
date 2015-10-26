<?php

if (!isset($_GET['resident']) && !isset($_GET['residence']) ){
	header("location: admin.php");
	exit;
}

if (isset($_GET['resident']) && isset($_GET['residence']) ){
	header("location: admin.php");
	exit;
}

if (isset($_GET['residence'])){
	  require_once( "template_class.php");       // css and headers
	  $H = new template( "Administration" );
	  $H->show_template( );

	  if(($_SESSION['login_user']) != "admin"){
	  	header("location: myhome.php");
	  	exit();
	  }

	  $residence = $_GET['residence'];
	    // Create connection
	  $P = new manage_db;
	  $P->connect_db();
	  $sql_head_residents = "SELECT * FROM residences WHERE residence_id='$residence'";
	  $P->do_query($sql_head_residents);
	  $head_residents_result = mysql_query($sql_head_residents); 



  	// Checks to see if there is a head resident for the residence  
  	if(mysql_num_rows($head_residents_result)==0) {
  		header("location: admin.php");
  		exit;
  	} else {
  		$sql_head_residents = "SELECT * FROM head_residents WHERE fk_residence_id='$residence'";
	  	$P->do_query($sql_head_residents);
	  	$head_residents_result = mysql_query($sql_head_residents);
	  	if(mysql_num_rows($head_residents_result)==1) {
  			header("location: admin.php");
  			exit;
  		} else {
  			$hide_elements = "style='display:none'";

  			$require_first_name = true; // Variable that will be used later to require first name for head resident
  			$require_last_name = true; // Variable that will be used later to require last name for head resident
			$require_emergency = true; // Variable that will be used later to require emergency contact for head resident

			$head_residents = array();
			array_push($head_residents, "N:" .$residence);

  		}

  	}

}

if (isset($_GET['resident'])){
	  require_once( "template_class.php");       // css and headers
	  $H = new template( "Administration" );
	  $H->show_template( );

	  if(($_SESSION['login_user']) != "admin"){
	  	header("location: myhome.php");
	  	exit();
	  }

	  $resident = $_GET['resident'];
	   // Create connection
	  $P = new manage_db;
	  $P->connect_db();

	  $sql_head_residents = "SELECT * FROM head_residents WHERE head_resident_id='$resident'";
	  $P->do_query($sql_head_residents);
	  $head_residents_result = mysql_query($sql_head_residents); 

  $head_residents = array(); //Holds head residents' information

  // Checks to see if there is a head resident for the residence  
  if(mysql_num_rows($head_residents_result)==0) {
  	header("location: admin.php");
  	exit;

  } else { // This section is for if there is a head resident registered to the residence

  	$hide_elements = '';
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

  	$sql_max_per_residence = "SELECT max_per_residence FROM configuration";
  	$P->do_query($sql_max_per_residence);
  	$max_per_residence_result = mysql_query($sql_max_per_residence);
  	while ($row = mysql_fetch_assoc($max_per_residence_result))
  	{
  		$max_per_residence = $row['max_per_residence'];
  	}

  	// Query that gets the data for the sub resident table
  	$sql_sub_residents = "SELECT sub_residents.sub_residents_id AS sub_residents_id, sub_residents.first_name AS first_name, sub_residents.last_name AS last_name, sub_residents.phone_number AS phone_number FROM sub_residents INNER JOIN head_residents ON sub_residents.fk_head_id = head_residents.head_resident_id WHERE fk_head_id='$head_residents[0]'";
  	$P->do_query($sql_sub_residents);
  	$sub_residents_result = mysql_query($sql_sub_residents);
  }
}



?>
<!DOCTYPE html>
<html>
<head>

</head>
<body>

	<!-- Form for the update of head resident -->
	<form action="updateprofile.php" method="POST">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-6">
					<h3> Head Resident Information </h3>
					<table class="table table-striped table-hover ">
						<tr>
							<th> </th>
							<th <?php echo $hide_elements; ?>> Current Information </th>
							<th> New Information </th>
						</tr>
						<tr>
							<th> First Name </th>
							<td <?php echo $hide_elements; ?>> <?php echo "$head_residents[1]";?> </td> <!-- Head resident first name -->
							<td> <input id="head_resident_first_name" name="head_resident_first_name" type="text" class="form-control input-md" <?php if ($require_first_name) echo 'required'; ?> > </td>
						</tr>
						<tr>
							<th> Last Name </th>
							<td <?php echo $hide_elements; ?>> <?php echo "$head_residents[2]";?> </td> <!-- Head resident last name -->
							<td> <input id="head_resident_last_name" name="head_resident_last_name" type="text" class="form-control input-md" <?php if ($require_last_name) echo 'required'; ?>> </td>
						</tr>
						<tr>
							<th> Emergency Contact </th>
							<td <?php echo $hide_elements; ?>> <?php echo "$head_residents[3]";?> </td> <!-- Head resident Emergency Contact -->
							<td> <input id="head_resident_emergency" name="head_resident_emergency" type="text" class="form-control input-md" <?php if ($require_emergency) echo 'required'; ?>> </td>
						</tr>
						<tr>
							<th> Phone Number </th>
							<td <?php echo $hide_elements; ?>> <?php echo "$head_residents[4]";?> </td>  <!-- Head resident phone number -->
							<td> <input id="head_resident_phone_one" name="head_resident_phone_one" type="text" class="form-control input-md"> </td>
						</tr>
						<tr>
							<th> E-mail Address </th>
							<td <?php echo $hide_elements; ?>> <?php echo "$head_residents[5]";?> </td>  <!-- Head resident Email Address -->
							<td> <input id="head_resident_email_address" name="head_resident_email_address" type="text" class="form-control input-md"> </td>
						</tr>
					</table> 
					<div class="form-group last">
						<div class="col-sm-offset-3 col-sm-6"> <!-- Value for the button is needed to tell the update file what the id of the user is -->
							<button name="admin_update_head_resident" type="submit" value=<?php echo "'$head_residents[0]'" ?> class="btn btn-primary btn-lg" style="  width: 100%;"> Update Profile </button>
						</div>
					</div>
				</div>

			</form> 

			<div class="col-md-6" <?php echo $hide_elements; ?>> <!-- Hides this div / table if there isn't a head resident registered to the residence -->
				<h3> Sub Resident Information </h3>
				<table class="table table-striped table-hover ">
					<tr>
						<th> First Name </th>
						<th> Last Name </th>
						<th> Phone Number </th>
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
							echo "<tr> <td> <input name='update_sub_resident_first_name:".$sub_residents_id."' type='text' placeholder=" . $first_name . " class='form-control input-md' > </td>";
							echo "<td> <input name='update_sub_resident_last_name:".$sub_residents_id."' type='text' placeholder=" . $last_name . " class='form-control input-md' > </td> ";
							echo "<td> <input name='update_sub_resident_phone_number:".$sub_residents_id."' type='text' placeholder='";
							if (isset($phone_number))
								echo $phone_number;
							echo "'class='form-control input-md' > </td>";
							echo "<td><button name='admin_update_sub_resident' type='submit' value=". $sub_residents_id . ":" . $head_residents[0] . " class='btn btn-primary btn-sm glyphicon glyphicon-pencil' style='  width: 100%;'></button>";
							echo "<button name='admin_delete_sub_resident' type='submit' value=". $sub_residents_id . ":" . $head_residents[0] . " class='btn btn-danger btn-sm glyphicon glyphicon-remove' style='  width: 100%;'> </button> </td></tr>";
							
							$counter = $counter + 1;
						}
							if ($max_per_residence <= $counter) {
									$hide_add_new_sub_resident = "style = 'display:none;'";
							}
						?>
					</form>
					<!-- Form for the sub resident information -->
					<form action="updateprofile.php" method="POST">
						<tr <?php echo $hide_add_new_sub_resident; ?>>
							<td> <input id="sub_resident_first_name" name="sub_resident_first_name" type="text" class="form-control input-md" required> </td>
							<td> <input id="sub_resident_last_name" name="sub_resident_last_name" type="text"  class="form-control input-md" required> </td>
							<td> <input id="sub_resident_phone_number" name="sub_resident_phone_number" type="text" class="form-control input-md" > </td>
							<td> <button name="admin_add_sub_resident" type="submit" value=<?php echo "'$head_residents[0]'" ?> class="btn btn-success btn-lg" style="  width: 100%;"> Add Resident </button> </td>
						</tr>
					</table> 
				</form>
			</div>
		</div>
	</div>

</body>
</html>