 <!DOCTYPE html>
 <html>
 <head>
 	<?php
  require_once( "template_class.php");       // css and headers
  $H = new template( "Directory" );
  $H->show_template( );
  ?>

  <?php
// Create connection
  $P = new manage_db;
  $P->connect_db();
//Gets the information of a residence and it's head resident 
  $sqlResidences = "SELECT CONCAT(first_name, ' ', last_name) as 'head_full_name', head_resident_id, address, latitude, longitude, emergency_contact, phone_one, email_address FROM residences INNER JOIN head_residents ON head_residents.fk_residence_id = residences.residence_id WHERE address IS NOT NULL ORDER BY address";
  $P->do_query($sqlResidences);
  $resultResidences = mysql_query($sqlResidences);    
     // $row = mysql_fetch_assoc($resultResidences)


  ?>

</head>
<body>

<div class="container-fluid-right " style="width:100%; height:95%; ">
		<form class="form-horizontal col-xs-6 col-xs-offset-3">
			<fieldset>
				<div class="radio">
					<table class='table'>
						<tbody>
							<tr>
								<th><b>Choose Directory Type:</b></th>
								<td>          <label>
									<input type="radio" name="directoryType" id="directoryTypeSimple" value="simple">
									Simple
								</label></td>
								<td>          <label>
									<input type="radio" name="directoryType" id="directoryTypeDetailed" value="detailed">
									Detailed
								</label></td>
								<td> <button type="submit" class="btn btn-primary btn-xs">Submit</button></td>


							</tbody>
						</table>
						<hr>




					</div>
				</div>
			</div>
		</fieldset>
	</form>


	<div class="col-xs-10 col-xs-offset-1" style=" height:100%;">
		<?php

		if(!isset($_REQUEST['directoryType'])) {
			$_REQUEST['directoryType'] = 'simple';
		}

		if(isset($_REQUEST['directoryType'])) {
			$directoryType=$_REQUEST['directoryType'];

			if($directoryType == 'detailed'){
				while ($row = mysql_fetch_assoc($resultResidences)) { 
					print("
						<div class='col-xs-8'>
							<table class='table table-hover'>
								<tbody>
									<tr>
										<th>Address</th>
										<td>".$row['address']."</td>
									</tr>
									<tr>
										<th>Emergency Contact</th>
										<td>".$row['emergency_contact']."</td>
									</tr>
									<tr>
										<th>Phone Number</th>
										<td>".$row['phone_one']."</td>
									</tr>
									<tr>
										<th>E-Mail Address</th>
										<td>".$row['email_address']."</td>
									</tr>
								</tbody>
							</table>
						</div>

						<div class='col-xs-4'>
							<table class='table table-hover'>
								<tbody>
									<tr>
										<th>Resident</th>
										<th>Phone</th>
									</tr>
									<tr>
										<td>".$row['head_full_name']."</td>
										<td>".$row['phone_one']."</td>
									</tr>
									");

					$sqlResidents = "SELECT CONCAT(first_name, ' ', last_name) as 'sub_full_name', phone_number FROM sub_residents WHERE fk_head_id = ".$row['head_resident_id']."";
					$P->do_query($sqlResidents);
					$resultResidents = mysql_query($sqlResidents);  
   // $row2 = mysql_fetch_assoc($resultResidents);
					while ($row2 = mysql_fetch_assoc($resultResidents)) {
						print("
							<tr>
								<td>".$row2['sub_full_name']."</td>
								<td>".$row2['phone_number']."</td>
							</tr>
							");

					}

					print("
				</tbody>
			</table>
		</div>
		");

				}
			}elseif($directoryType == 'simple'){

				print("
					<div class='col-xs-12'>
						<table class='table table-hover'>
							<thead>
								<tr>
									<th>Address</th>
									<th>Emergency Contact</th>
									<th>Head Resident</th>
									<th>Phone Number</th>
									<th>E-Mail Address</th>
								</tr>
								<tbody>
									");

				while ($row = mysql_fetch_assoc($resultResidences)) { 
					print("

						<tr>
							<td>".$row['address']."</td>

							<td>".$row['emergency_contact']."</td>

							<td>".$row['head_full_name']."</td>

							<td>".$row['phone_one']."</td>

							<td>".$row['email_address']."</td>
						</tr>
						");




				}

				print("
			</tbody>
		</table>
	</div>
	");



			}else{
				print("Error: pick a directory type.");
			}

		}




		?>
	</div>
</div>

</body>
</html>