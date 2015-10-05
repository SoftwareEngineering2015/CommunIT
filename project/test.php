<!DOCTYPE html>
<html>
<head>
<?php
  require_once( "template_class.php");       // css and headers
  $H = new template( "My Home" );
  $H->show_template( );
  //require_once("db_class.php");
 ?>
</head>
<body>
	<div>
		<b id="welcome"> Welcome <?php echo $login_session; ?>!</b>
		<?php
	//		$P = new manage_db;
 	//		$P->connect_db();

			//$P->do_query( "SELECT * FROM communit.residences;" );

  	//		$P->do_query();
  	//		$P->fetch_assoc();
	//		print_r($P->DATA ); 
  //			$results = $P->DATA;

// Create connection
			$P = new manage_db;
 			$P->connect_db();
// Check connection
 			$sql = "SELECT address FROM residences WHERE address";
			$P->do_query($sql);
  			//$P->fetch_assoc();
  			//$result = $P->DATA;
 
			//print "<br> DATA=<pre>"; print_r( $P->DATA ); //exit;


$result = mysql_query($sql);
while($row = mysql_fetch_array($result)) {
print ("</br>" . $row['address']);
}


	/*		
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        echo "Address: " . $row["address"]."<br>";
        //print_r ($row["address"]);
    }
} else {
    echo "0 results";
}
*/
?>

	</div>

	<div>
	<br/>
		<a href="communitymap.php" class="col-xs-2 col-xs-offset-5 btn btn-primary btn-lg" style="font-size: 100%; height: 10%;">Go to <br/> CommunIT Map</a>
	</div>

</body>
</html>