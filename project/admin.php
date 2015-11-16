<!DOCTYPE html>
<html>
<head>

	<?php
  require_once( "template_class.php");       // css and headers
  $H = new template( "Administration" );
  $H->show_template( );


  if(($_SESSION['login_user']) != "admin"){
  	header("location: index.php");
  	exit();
  }

  ?>
</head>

<body>


	<!-- Form for the sub resident information -->
	<form action="updateresidence.php" method="POST">
		<div class="container-fluid"> <!-- Hides this div / table if there isn't a head resident registered to the residence -->
			<table class="table table-striped table-nonfluid">
				<h3> Residence Information </h3>
				<tr>
					<th> Residence </th>
					<th> Address </th>
					<th> Latitude </th>
					<th> Longitude </th>
					<th> </th>
					<th> </th>
					<th> </th>
					<th> </th>
					<th> </th>
				</tr>
				<?php

				// Create connection
				$P = new manage_db;
				$P->connect_db();
				$sql_head_residents = "SELECT * FROM residences LEFT JOIN head_residents ON residences.residence_id = head_residents.fk_residence_id WHERE username != 'admin' AND username != 'guest' ORDER BY head_resident_id, address DESC";
				//$P->do_query($sql_head_residents);
				$head_residents_result = mysql_query($sql_head_residents); 
				// Displays the head resident information
				while ($row = mysql_fetch_assoc($head_residents_result))
				{

					echo "<tr> <td> " . $row['username'] . "</td>";
					echo "<td>" . $row['address'] . "</td> ";
					echo "<td>" . $row['latitude'] . "</td> ";
					echo "<td>" . $row['longitude'] . "</td> ";
					echo "<td><button onclick=edit_residence_password('". $row['password'] ."','" . $row['residence_id']. "') type='button' class='btn btn-primary btn-sm' style='  width: 100%;'> <b> Change Password </b> </button> </td>";
					
					/*echo "<td>" . $row['first_name'] . "</td> ";
					echo "<td>" . $row['last_name'] . "</td> ";
					echo "<td>" . $row['emergency_contact'] . "</td> ";
					echo "<td>" . $row['phone_one'] . "</td> ";
					echo "<td>" . $row['email_address'] . "</td> ";
					echo "<td>" . $row['date_added'] . "</td> ";*/
					

					// Delete button for each head resident row; Value for the button holds the id of the sub resident and the id of the head resident, separated by the colon ( neeeded for the delete query)
					if(isset($row['head_resident_id'])) {
						echo "<td><a href='editresidence.php?residence=". $row["residence_id"] ."' class='btn btn-info btn-sm' style='  width: 100%;'><b> Edit Residence </b></a> </td>";
						echo "<td><button onclick='show_delete(". $row['residence_id'] .")' type='button' class='btn btn-danger btn-sm' style='  width: 100%;'> <b> Delete Residence </b> </button> </td>";
						echo "<td><a href='editresident.php?resident=". $row["head_resident_id"] ."' class='btn btn-warning btn-sm' style='  width: 100%;'><b> Edit Residents </b></a> </td>";
						echo "<td><button onclick='show_clear(". $row['head_resident_id'] .")' type='button' class='btn btn-success btn-sm' style='  width: 100%;'> <b> Clear Head Resident</b> </button> </td></tr>";

					} else {
						echo "<td><a href='editresidence.php?residence=". $row["residence_id"] ."'  class='btn btn-info btn-sm' style='  width: 100%;'><b> Edit Residence </b></a> </td>";
						echo "<td><button onclick='show_delete(". $row['residence_id'] .")' type='button' class='btn btn-danger btn-sm' style='  width: 100%;'> <b> Delete Residence </b> </button> </td>";
						echo "<td><a href='editresident.php?residence=". $row["residence_id"] ."'  class='btn btn-warning btn-sm' style='  width: 100%;'><b> Add Head Resident </b> </button></a> </td><td></td></tr>";
						
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
function edit_residence_password(old_password, residence_id){
    // shows the modal on button press
    $('#edit_residence_password').modal('show');
    document.getElementById("old_residence_password").innerHTML = old_password;
    document.getElementById("admin_change_residence_password").value = residence_id;
    
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

//pass total # of characters, and []
function makepassword(total, chars){
    if(total!=0){
        //this switch statement selects a random character to be insteted into the character array
        var added = 'A';
        switch(1 + parseInt((Math.random() * ((46 - 1) + 1)))){
        case 1: added = 'a';break;
        case 2: added = 'b';break;
        case 3: added = 'c';break;
        case 4: added = 'd';break;
        case 5: added = 'e';break;
        case 6: added = 'f';break;
        case 7: added = 'g';break;
        case 8: added = 'h';break;
        case 9: added = 'i';break;
        case 10: added = 'j';break;
        case 11: added = 'k';break;
        case 12: added = 'l';break;
        case 13: added = 'm';break;
        case 14: added = 'n';break;
        case 15: added = 'o';break;
        case 16: added = 'p';break;
        case 17: added = 'q';break;
        case 18: added = 'r';break;
        case 19: added = 's';break;
        case 20: added = 't';break;
        case 21: added = 'u';break;
        case 22: added = 'v';break;
        case 23: added = 'w';break;
        case 24: added = 'x';break;
        case 25: added = 'y';break;
        case 26: added = 'z';break;
        case 27: added = '1';break;
        case 28: added = '2';break;
        case 29: added = '3';break;
        case 30: added = '4';break;
        case 31: added = '5';break;
        case 32: added = '6';break;
        case 33: added = '7';break;
        case 34: added = '8';break;
        case 35: added = '9';break;
        case 36: added = '0';break;
        case 37: added = '1';break;
        case 38: added = '2';break;
        case 39: added = '3';break;
        case 40: added = '4';break;
        case 41: added = '5';break;
        case 42: added = '6';break;
        case 43: added = '7';break;
        case 44: added = '8';break;
        case 45: added = '9';break;
        case 46: added = '0';break;
        }
        chars.push(added);
        makepassword((total-1), chars);
    }
    //the function escapes to here once total equals 0
	//.replace(literal comma, global replacement, empty space)
    return chars.toString().replace(/,/g,'');
}

// function : generate_password()
function generate_password(password_field){
    // generate a password and fill the field with the value
    var rndm_password = makepassword(8,[]);
    document.getElementById(password_field).value = rndm_password;
}
// function : generate_password()
function clear_password_field(password_field){
    document.getElementById(password_field).value = "";
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
					<button type="submit" class="btn btn-success btn-md" name="delete_head_resident" id="delete_head_resident" value="">Yes</button>
					<button type="button" class="btn btn-danger btn-md" data-dismiss="modal">No</button>
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
						<button type="submit" class="btn btn-success btn-md" name="delete_residence" id="delete_residence" value="">Yes</button>
						<button type="button" class="btn btn-danger btn-md" data-dismiss="modal">No</button>
					</div>
				</div>

			</div>
		</div>
	</form>

			<form action="updateprofile.php" method="POST">
			<!-- Modal -->
			<div class="modal fade" id="edit_residence_password" role="dialog">
				<div class="modal-dialog">

					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"></button>
							<h4 class="modal-title" style="text-align: center; font-size: 200%;"><b>Change Residence Password</b></h4>
						</div>
						<div class="modal-body">
							<b><p  style="font-size: 120%;">
								<span style="color: red"> Generate a new password or add your own. </span>
								<table class="table table-striped table-nonfluid">
									<tr>
										<th> Old Password </th>
										<th> New Password </th>
									</tr>
									<tr>
										<td id="old_residence_password"></td>
										<td> <input id='new_residence_password' name="new_residence_password" type="text" class="form-control input-md" minlength='8' maxlength='25' required> </td>
									</tr>
								</table></div>
								<div class="modal-footer">
									<button type="button" class="btn btn-info btn-md" onclick="generate_password('new_residence_password')">Generate Password</button>
									<button type="submit" class="btn btn-success btn-md" name="admin_change_residence_password" id="admin_change_residence_password" value="">Change Password</button>
									<button type="button" class="btn btn-danger btn-md" onclick="clear_password_field('new_residence_password')" data-dismiss="modal">Close</button>
								</div>
							</div>

						</div>
					</div>
				</form>

				</body>
				</html>