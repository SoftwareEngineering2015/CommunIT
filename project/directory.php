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
$sqlResidences = "SELECT CONCAT(first_name, ' ', last_name) as 'head_full_name', head_resident_id, address, latitude, longitude, emergency_contact, phone_one, email_address FROM residences INNER JOIN head_residents ON head_residents.fk_residence_id = residences.residence_id WHERE address IS NOT NULL ORDER BY last_name ";
$P->do_query($sqlResidences);
$resultResidences = mysql_query($sqlResidences);    
 // $row = mysql_fetch_assoc($resultResidences)


?>

</head>
<style>
@media print
{    
.no-print, .no-print *
{
    display: none !important;
}
}

</style>

<body>

<?php
if(($_SESSION['login_user']) != "guest"){
	print('
<div class="container-fluid-right no-print " style="width:100%; height:95%; ">
	<form class="form-horizontal col-xs-6 col-xs-offset-3">
		<fieldset>
			<div class="radio">
				<table class="table">
						<tr>
							<th><b>Choose Directory Type:</b></th>
							<td>
								<button type="submit" class="btn btn-primary btn-xs" name="directoryType" id="directoryTypeSimple" value="simple"><b> Simple </b></button>
							</td>
							<td>
							
							
							 <button type="submit" class="btn btn-primary btn-xs" name="directoryType" id="directoryTypeDetailed" value="detailed"><b> Detailed </b></button>
								

							</td>
					</table>
					<hr>


');
}

							?>

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

		if($directoryType == 'detailed' &&  ($_SESSION['login_user']) != "guest") {
			while ($row = mysql_fetch_assoc($resultResidences)) { 
				if($row['phone_one'] == ''){
					$row['phone_one'] = 'unavailable';
				}
				if($row['email_address'] == ''){
					$row['email_address'] = 'unavailable';
				}

				print("
					<div class='col-xs-8'>
						<table class='table table-hover'>
							<tbody>
								<tr>
									<th>Address</th>
									<td>".$row['address']."</td>
								</tr>
								<tr>
									<th>Phone Number One</th>
									<td>".$row['emergency_contact']."</td>
								</tr>
								<tr>
									<th>Phone Number Two</th>
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
									<th>E-Mail</th>
								</tr>
								<tr>
									<td>".$row['head_full_name']."</td>
									<td>".$row['phone_one']."</td>
									<td>".$row['email_address']."</td>
								</tr>
								");

				$sqlResidents = "SELECT CONCAT(first_name, ' ', last_name) as 'sub_full_name', phone_number, email_address FROM sub_residents WHERE fk_head_id = ".$row['head_resident_id']." ORDER BY last_name";
				$P->do_query($sqlResidents);
				$resultResidents = mysql_query($sqlResidents);  
// $row2 = mysql_fetch_assoc($resultResidents);
				while ($row2 = mysql_fetch_assoc($resultResidents)) {
					if($row2['phone_number'] == ''){
						$row2['phone_number'] = 'unavailable';
					}
					if($row2['email_address'] == ''){
						$row2['email_address'] = 'unavailable';
					}
					print("
						<tr>
							<td>".$row2['sub_full_name']."</td>
							<td>".$row2['phone_number']."</td>
							<td>".$row2['email_address']."</td>
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
								<th>Head Resident</th>
								<th>Phone Number One</th>
								<th>Phone Number Two</th>
								<th>E-Mail Address</th>
								<th>Address</th>
							</tr>
							<tbody>
								");

			while ($row = mysql_fetch_assoc($resultResidences)) { 
				
			if($row['phone_one'] == ''){
					$row['phone_one'] = 'unavailable';
				}
				if($row['email_address'] == ''){
					$row['email_address'] = 'unavailable';
				}

				print("

					<tr>
						<td>".$row['head_full_name']."</td>

						<td>".$row['emergency_contact']."</td>

						<td>".$row['phone_one']."</td>

						<td>".$row['email_address']."</td>

						<td>".$row['address']."</td>
					</tr>
					");




			}

			print("
		</tbody>
	</table>
</div>
");



		}else{
			$directoryType = 'simple';
			print("
				<div class='col-xs-12'>
					<table class='table table-hover'>
						<thead>
							<tr>
								<th>Head Resident</th>
								<th>Phone Number One</th>
								<th>Phone Number Two</th>
								<th>E-Mail Address</th>
								<th>Address</th>
							</tr>
							<tbody>
								");

			while ($row = mysql_fetch_assoc($resultResidences)) { 
				
			if($row['phone_one'] == ''){
					$row['phone_one'] = 'unavailable';
				}
				if($row['email_address'] == ''){
					$row['email_address'] = 'unavailable';
				}

				print("

					<tr>
						<td>".$row['head_full_name']."</td>

						<td>".$row['emergency_contact']."</td>

						<td>".$row['phone_one']."</td>

						<td>".$row['email_address']."</td>

						<td>".$row['address']."</td>
					</tr>
					");
			}

			print("
		</tbody>
	</table>
</div>
");
			
		}

	}




	?>
</div>
</div>

</body>
</html>