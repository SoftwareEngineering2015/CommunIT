<!DOCTYPE html>
<html>
<head>
	<?php

  require_once( "template_class.php");       // css and headers
  $H = new template( "My Home" );
  $H->show_template( );

  if($login_session == "admin"){
  	header("location: admin.php");
  	exit; //Exit so the rest of the code will not run
  }
  elseif($login_session == "guest"){
  	header("location: communitymap.php");
  	exit; //Exit so the rest of the code will not run
  }

  // Create connection
  $P = new manage_db;
  $P->connect_db();
	// Check connection
  $sql_head_residents = "SELECT head_resident_id, first_name, last_name, emergency_contact, phone_one, email_address FROM head_residents INNER JOIN residences ON head_residents.fk_residence_id = residences.residence_id WHERE username='$login_session'";
  $P->do_query($sql_head_residents);
  $head_residents_result = mysql_query($sql_head_residents); 

  $head_residents = array(); //Holds head residents' information
  
  if(mysql_num_rows($head_residents_result)==0) {
  	header("location: editprofile.php");
  	exit;
  } else {
  	while ($row = mysql_fetch_assoc($head_residents_result))
  	{
  		array_push($head_residents, $row['head_resident_id']);
  		array_push($head_residents, $row['first_name']);
  		array_push($head_residents, $row['last_name']);
  		array_push($head_residents, $row['emergency_contact']);
  		array_push($head_residents, $row['phone_one']);
  		array_push($head_residents, $row['email_address']);
  	}
  }

  $sql_sub_residents = "SELECT sub_residents.first_name AS first_name, sub_residents.last_name AS last_name, sub_residents.phone_number AS phone_number FROM sub_residents INNER JOIN head_residents ON sub_residents.fk_head_id = head_residents.head_resident_id WHERE fk_head_id='$head_residents[0]'";
  $P->do_query($sql_sub_residents);
  $sub_residents_result = mysql_query($sql_sub_residents)
  ?>
</head>
<body>
	<!--<div>
		<b id="welcome"> Welcome <?php echo $login_session; ?>!</b>
	</div>
	-->
	
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<h3> Head Resident Information </h3>
				<table class="table table-striped table-hover ">
					<tr>
						<th> First Name </th>
						<td> <?php echo "$head_residents[1]";?> </td>
					</tr>
					<tr>
						<th> Last Name </th>
						<td> <?php echo "$head_residents[2]";?> </td>
					</tr>
					<tr>
						<th> Emergency Contact Number </th>
						<td> <?php echo "$head_residents[3]";?> </td>
					</tr>
					<tr>
						<th> Phone One Number </th>
						<td> <?php echo "$head_residents[4]";?> </td>
					</tr>
					<tr>
						<th> Email Address </th>
						<td> <?php echo "$head_residents[5]";?> </td>
					</tr>
				</table> 
			</div>
			<div class="col-md-6">
				<h3> Sub Resident Information </h3>
				<table class="table table-striped table-hover ">
					<tr>
						<th> First Name </th>
						<th> Last Name </th>
						<th> Phone Number </th>
					</tr>
					<tr>
						<?php
						while ($row = mysql_fetch_assoc($sub_residents_result))
						{
							$first_name =  $row ['first_name'];
							$last_name =  $row ['last_name'];
							$phone_number =  $row ['phone_number'];
							echo "<td> $first_name </td> ";
							echo "<td> $last_name </td> ";
							echo "<td> $phone_number </td> </tr>";
						}
						?>
				</table> 
			</div>
		</div>
	</div>
<div>
	<br/>
	<!--
	<a href="communitymap.php" class="col-xs-2 col-xs-offset-5 btn btn-primary btn-lg" style="font-size: 100%; height: 10%;">Go to <br/> CommunIT Map</a>
	-->
	<a href="editprofile.php" class="col-xs-2 col-xs-offset-5 btn btn-primary btn-lg" style="font-size: 150%; height: 10%;"><b>Edit Profile</b></a>
</div>

</body>
</html>