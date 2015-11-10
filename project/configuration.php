<?php

	  require_once( "template_class.php");       // css and headers
	  $H = new template( "Administration" );
	  $H->show_template( );

	  if(($_SESSION['login_user']) != "admin"){
	  	header("location: myhome.php");
	  	exit();
	  }

	  $P = new manage_db;
	  $P->connect_db();

	  $sql_configuration = "SELECT * FROM configuration";
	  $P->do_query($sql_configuration);
	  $configuration_results = mysql_query($sql_configuration); 

	  $configuration = array();
		// Displays the head resident information
	  while ($row = mysql_fetch_assoc($configuration_results))
	  {
	  	array_push($configuration, $row['community_name']);
	  	array_push($configuration, $row['max_per_residence']);
	  }

	  ?>
	  <!DOCTYPE html>
	  <html>
	  <head>

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
	  							<th> Max Residents Per Residence </th>
	  							<td> <?php echo "$configuration[1]";?> </td> <!-- Head resident last name -->
	  							<td> <input id="max_per_residence" name="max_per_residence" type="text" class="form-control input-md">  </td>
	  						</tr>
	  					</table> 
	  					<div class="form-group last">
	  						<div class="col-sm-offset-3 col-sm-6"> <!-- Value for the button is needed to tell the update file what the id of the user is -->
	  							<button name="update_configuration" type="submit" class="btn btn-primary btn-lg" style="  width: 100%;"> Update Settings </button>
	  						</div>
	  					</div>
	  				</div>
	  			</div>
	  		</div>
	  	</form> 
	  </body>
	  </html>