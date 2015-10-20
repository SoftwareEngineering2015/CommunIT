<!DOCTYPE html>
<html>
<head>

	<?php
  require_once( "template_class.php");       // css and headers
  $H = new template( "Administration" );
  $H->show_template( );


  if(($_SESSION['login_user']) != "admin"){
  	header("location: home.php");
  	exit();
  }

  ?>
</head>

<body>


	<!-- Form for the sub resident information -->
	<form action="updateresidence.php" method="POST">
		<div class="container-fluid"> <!-- Hides this div / table if there isn't a head resident registered to the residence -->
			<h3> Residence Information </h3>
			<table class="table table-striped table-nonfluid">
				<tr>
					<th> Residence </th>
					<th> Password </th>
					<th> Address </th>
					<th> Latitude </th>
					<th> Longitude </th>
					<th> </th>
					<th> </th>
					<th> </th>
					<th> </th>
				</tr>
				<?php

				  // Create connection
				$P = new manage_db;
				$P->connect_db();

				$sql_head_residents = "SELECT * FROM residences LEFT JOIN head_residents ON residences.residence_id = head_residents.fk_residence_id ORDER BY head_resident_id, address, username='admin'  DESC";
				$P->do_query($sql_head_residents);
				$head_residents_result = mysql_query($sql_head_residents); 

				// Displays the head resident information
				while ($row = mysql_fetch_assoc($head_residents_result))
				{

					echo "<tr> <td> " . $row['username'] . "</td>";
					echo "<td>" . $row['password'] . "</td> ";
					echo "<td>" . $row['address'] . "</td> ";
					echo "<td>" . $row['latitude'] . "</td> ";
					echo "<td>" . $row['longitude'] . "</td> ";
					/*echo "<td>" . $row['first_name'] . "</td> ";
					echo "<td>" . $row['last_name'] . "</td> ";
					echo "<td>" . $row['emergency_contact'] . "</td> ";
					echo "<td>" . $row['phone_one'] . "</td> ";
					echo "<td>" . $row['email_address'] . "</td> ";
					echo "<td>" . $row['date_added'] . "</td> ";*/
					
					if ($row['username'] == "admin") {
						echo "<td><button onclick=edit_admin_password('". $row['password'] ."') type='button' class='btn btn-primary btn-sm' style='  width: 100%;'> <b> Change Password </b> </button> </td><td></td><td></td><td></td></tr>";
						continue;
					}
					if ($row['username'] == "guest") {
						echo "<td><button onclick=edit_guest_password('". $row['password'] ."') type='button' class='btn btn-primary btn-sm' style='  width: 100%;'> <b> Change Password </b> </button> </td><td><td></td></td><td></td></tr>";
						continue;
					}
					// Delete button for each head resident row; Value for the button holds the id of the sub resident and the id of the head resident, separated by the colon ( neeeded for the delete query)
					if(isset($row['head_resident_id'])) {
						echo "<td><a href='editresidence.php?residence=". $row["residence_id"] ."'><button type='button' class='btn btn-info btn-sm' style='  width: 100%;'> <b> Edit Residence </b> </button></a> </td>";
						echo "<td><button onclick='show_delete(". $row['residence_id'] .")' type='button' class='btn btn-danger btn-sm' style='  width: 100%;'> <b> Delete Residence </b> </button> </td>";
						echo "<td><a href='editresident.php?resident=". $row["head_resident_id"] ."'><button type='button' class='btn btn-warning btn-sm' style='  width: 100%;'> <b> Edit Resident </b> </button></a> </td>";
						echo "<td><button onclick='show_clear(". $row['head_resident_id'] .")' type='button' class='btn btn-success btn-sm' style='  width: 100%;'> <b> Clear Head Resident</b> </button> </td></tr>";

					} else {
						echo "<td><a href='editresidence.php?residence=". $row["residence_id"] ."'><button type='button' class='btn btn-info btn-sm' style='  width: 100%;'> <b> Edit Residence </b> </button></a> </td>";
						echo "<td><button onclick='show_delete(". $row['residence_id'] .")' type='button' class='btn btn-danger btn-sm' style='  width: 100%;'> <b> Delete Residence </b> </button> </td>";
						echo "<td><a href='editresident.php?residence=". $row["residence_id"] ."'><button type='button' class='btn btn-warning btn-sm' style='  width: 100%;'> <b> Add Head Resident </b> </button></a> </td><td></td></tr>";
						
					}
				}

				?>
			</table> 
		</div>
	</div>
</div>
<script>
// function : show_confirm()
function edit_admin_password(old_password){
    // shows the modal on button press
    $('#edit_admin_password').modal('show');
    document.getElementById("old_admin_password").innerHTML = old_password;

}

// function : show_confirm()
function edit_guest_password(old_password){
    // shows the modal on button press
    $('#edit_guest_password').modal('show');
    document.getElementById("old_guest_password").innerHTML = old_password;
    
}

// function : show_confirm()
function show_clear(head_resident_id){
    // shows the modal on button press
    $('#confirm_clear').modal('show');
    $('#delete_head_resident').val(head_resident_id);
}
// function : show_confirm()
function show_delete(residence_id){
    // shows the modal on button press
    $('#confirm_delete').modal('show');
    $('#delete_residence').val(residence_id);
}

</script>

<!-- Modal -->
<div class="modal fade" id="confirm_clear" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"></button>
				<h4 class="modal-title" style="text-align: center; font-size: 200%;"><b>Clear Head Resident</b></h4>
			</div>
			<div class="modal-body">
				<b><p  style="font-size: 120%;">
					Are you sure you want to clear the Head Resident for this residence? </p> <br/>
					<p style="color:red">This action will clear the Head Resident information and Sub Resident information for this residence.</p></b>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success btn-lg" name="delete_head_resident" id="delete_head_resident" value="">Yes</button>
					<button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">No</button>
				</div>
			</div>

		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="confirm_delete" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"></button>
					<h4 class="modal-title" style="text-align: center; font-size: 200%;"><b>Delete Residence</b></h4>
				</div>
				<div class="modal-body">
					<b><p  style="font-size: 120%;">
						Are you sure you want to delete this residence? </p> <br/>
						<p style="color:red">This action will delete all information for this residence.</p></b>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-success btn-lg" name="delete_residence" id="delete_residence" value="">Yes</button>
						<button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">No</button>
					</div>
				</div>

			</div>
		</div>
	</form>

	<form action="updateprofile.php" method="POST">
	<!-- Modal -->
	<div class="modal fade" id="edit_admin_password" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"></button>
					<h4 class="modal-title" style="text-align: center; font-size: 200%;"><b>Change Admin Password</b></h4>
				</div>
				<div class="modal-body">
					<b><p  style="font-size: 120%;">
						<table class="table table-striped table-nonfluid">
							<tr>
								<th> Old Password </th>
								<th> New Password </th>
							</tr>
							<tr>
								<td id ="old_admin_password"></td>
								<td> <input name="new_admin_password" type="password" class="form-control input-md" required> </td>
							</tr>
						</table></div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-success btn-lg" name="change_admin_password" id="change_admin_password" value="">Change Password</button>
							<button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Close</button>
						</div>
					</div>

				</div>
			</div>
		</form>

		<form action="updateprofile.php" method="POST">
			<!-- Modal -->
			<div class="modal fade" id="edit_guest_password" role="dialog">
				<div class="modal-dialog">

					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"></button>
							<h4 class="modal-title" style="text-align: center; font-size: 200%;"><b>Change Guest Password</b></h4>
						</div>
						<div class="modal-body">
							<b><p  style="font-size: 120%;">
								<table class="table table-striped table-nonfluid">
									<tr>
										<th> Old Password </th>
										<th> New Password </th>
									</tr>
									<tr>
										<td id="old_guest_password"></td>
										<td> <input name="new_guest_password" type="password" class="form-control input-md" required> </td>
									</tr>
								</table></div>
								<div class="modal-footer">
									<button type="submit" class="btn btn-success btn-lg" name="change_guest_password" id="change_guest_password" value="">Change Password</button>
									<button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">Close</button>
								</div>
							</div>

						</div>
					</div>
				</form>

				</body>
				</html>