<!DOCTYPE html>
<html>
<head>
<?php
  require_once( "template_class.php");       // css and headers
  $H = new template( "My Home" );
  $H->show_template( );

	if($login_session == "admin"){
		header("location: admin.php");
	}
	elseif($login_session == "guest"){
		header("location: communitymap.php");
	}

 ?>
</head>
<body>
	<div>
		<b id="welcome"> Welcome <?php echo $login_session; ?>!</b>
	</div>

	<div>
	<br/>
		<a href="communitymap.php" class="col-xs-8 col-xs-offset-2 btn btn-primary btn-lg" style="font-size: 100%; height: 10%;">Go to <br/> CommunIT Map</a>
	</div>

</body>
</html>