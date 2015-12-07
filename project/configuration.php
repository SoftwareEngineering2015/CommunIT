<?php

	require_once( "template_class.php");       // css and headers
	$H = new template( "Administration" );
	$H->show_template( ); // Sets up the template for the page

	// If the user is not an admin redirect them to the appropriate page
	if(($_SESSION['login_user']) != "admin"){
		header("location: myhome.php");
	  	exit();
	}

	// Connect to the database
	$P = new manage_db;
	$P->connect_db();

	// Get the configuration information
	$sql_configuration = "SELECT * FROM configuration";
	$P->do_query($sql_configuration);
	$configuration_results = mysql_query($sql_configuration); 

	// Initialize the array that will hold the configuration information
	$configuration = array();

	// Stores the information from the query in the array 
	while ($row = mysql_fetch_assoc($configuration_results))
	{
	  	array_push($configuration, $row['community_name']);
	  	array_push($configuration, $row['max_per_residence']);
	}

	// Get the default color information 
	$sql_default_color = "DESCRIBE head_residents";
	$P->do_query($sql_default_color);
	$default_color_result = mysql_query($sql_default_color); 

	// Stores the color information in the array
	while ($row = mysql_fetch_assoc($default_color_result))
	{
	  	// This specifies the field in the query that we want the information from
	  	if ($row['Field'] == 'pin_color') { 
	  		array_push($configuration, $row['Default']);
	  	}
	}

?>

<!DOCTYPE html>
 <html>
  <head>
  	<script>

		// Change the image to the default pin color on page load
		$( window ).bind('load',function() {
    		pin_color =  document.getElementById('default_pin_color').value;
    		overalayColor(pin_color);
    		document.getElementById('house_pin').src = fullimg;
		});

		$(document).ready(function() {
			//Change pin color on change of color select
			$( "#default_pin_color" ).change(function() {
  				pin_color =  document.getElementById('default_pin_color').value;
    			overalayColor(pin_color);
    			document.getElementById('house_pin').src = fullimg;
			});
		});

		// Displays the modal for the edit admin password
		function edit_admin_password(old_password){
    		// shows the modal on button press
    		$('#edit_admin_password').modal('show');
    		document.getElementById("old_admin_password").innerHTML = old_password;
    	}

		// Displays the modal for the edit guest password
		function edit_guest_password(old_password){
    		// shows the modal on button press
    		$('#edit_guest_password').modal('show');
    		document.getElementById("old_guest_password").innerHTML = old_password;
    	}

    	// This function generates a random password
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

	// This runs the generate password function and stores the random password in the input field
	function generate_password(password_field){
    	// generate a password and fill the field with the value
    	var rndm_password = makepassword(8,[]);
    	document.getElementById(password_field).value = rndm_password;
	}

	// This clears the password input field so that a new password can be stored in it
	function clear_password_field(password_field){
		document.getElementById(password_field).value = "";
	}

	</script>
</head>
<body>

	<!-- Form for the update of head resident -->
	<form action="updateconfiguration.php" method="POST">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-6">
					<h3> Community Settings </h3>
					<table class="table table-striped table-hover ">
						<tr>
							<th> </th>
							<th> Current Settings </th>
							<th> New Settings </th>
						</tr>
						<tr>
							<th> Community Name </th>
							<td> <?php echo "$configuration[0]";?> </td> <!-- Head resident first name -->
							<td> <input id="community_name" name="community_name" type="text" class="form-control input-md"></td>
						</tr>
						<tr>
							<th> Max Sub Residents Per Residence </th>
							<td> <?php echo "$configuration[1]";?> </td> <!-- Head resident last name -->
							<td> <input id="max_per_residence" name="max_per_residence" type="number" min="0" class="form-control input-md">  </td>
						</tr>
						<tr>
							<th> Default Residence Color </th>
							<td> <img id="house_pin" alt="" style="width:auto; height;auto"> </td>
							<td> <input id="default_pin_color" name="default_pin_color" type="color" value=<?php echo "'" . $configuration[2] . "'"; ?> style="width: 100%;">  </td>
						</tr>
						<tr><td></td><td></td>
							<td> <button name="update_configuration" type="submit" class="btn btn-primary btn-lg" style="  width: 100%;"> Update Settings </button></td>
						</tr>
					</table> 
				</div>
			</form>
			 
				<div class="col-md-6">
					<table class="table table-striped table-nonfluid">
						<h3> Admin / Guest Information </h3>
						<tr> <th> Username </th> <th> </th> <td colspan='4'> </td> </tr>

						<?php

				  		// Create connection
						$P = new manage_db;
						$P->connect_db();

						$sql_admin_guest = "SELECT * FROM residences WHERE username = 'admin' OR username = 'guest'";
						$head_admin_guest_result = mysql_query($sql_admin_guest); 
						
						// Displays the head resident information
						while ($row = mysql_fetch_assoc($head_admin_guest_result))
						{
							//Setup the buttons in the configuration pahe
							echo "<tr> <td> " . $row['username'] . "</td>";
							
							// Sets up the admin buttons 
							if ($row['username'] == "admin") {
								echo "<td><button onclick=edit_admin_password('". $row['password'] ."') type='button' class='btn btn-primary btn-sm' style='  width: 100%;'> <b> Change Password </b> </button> </td><td colspan='4'> </td> </tr>";
								continue;
							}

							//Sets up the guest buttons
							if ($row['username'] == "guest") {
								echo "<td><button onclick=edit_guest_password('". $row['password'] ."') type='button' class='btn btn-primary btn-sm' style='  width: 100%;'> <b> Change Password </b> </button> </td><td colspan='4'> </td> </tr>";
								continue;
							}
						}
						
						?>
					</table>
				</div>
			</div>
		</div>

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
						<span style="color: red"> Generate a new password or add your own. </span>
						<table class="table table-striped table-nonfluid">
							<tr>
								<th> Old Password </th>
								<th> New Password </th>
							</tr>
							<tr>
								<td id ="old_admin_password"></td>
								<td> <input id="new_admin_password" name="new_admin_password" type="text" class="form-control input-md" minlength='8' maxlength='25' required> </td>
							</tr>
						</table></div>
						<div class="modal-footer">
							<button type="button" class="btn btn-info btn-md" onclick="generate_password('new_admin_password')">Generate Password</button>	
							<button type="submit" class="btn btn-success btn-md" name="change_admin_password" id="change_admin_password" value="">Change Password</button>
							<button type="button" class="btn btn-danger btn-md" onclick="clear_password_field('new_admin_password')" data-dismiss="modal">Close</button>
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
								<span style="color: red"> Generate a new password or add your own. </span>
								<table class="table table-striped table-nonfluid">
									<tr>
										<th> Old Password </th>
										<th> New Password </th>
									</tr>
									<tr>
										<td id="old_guest_password"></td>
										<td> <input id="new_guest_password" name="new_guest_password" type="text" class="form-control input-md" minlength='8' maxlength='25' required> </td>
									</tr>
								</table></div>
								<div class="modal-footer">
									<button type="button" class="btn btn-info btn-md" onclick="generate_password('new_guest_password')">Generate Password</button>
									<button type="submit" class="btn btn-success btn-md" name="change_guest_password" id="change_guest_password" value="">Change Password</button>
									<button type="button" class="btn btn-danger btn-md" onclick="clear_password_field('new_guest_password')" data-dismiss="modal">Close</button>
								</div>
							</div>

						</div>
					</div>
				</form>
            <script type="text/javascript" src="js/colorpins.js"></script>

</body>
</html>