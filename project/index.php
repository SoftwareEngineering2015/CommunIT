
<?php
include('login.php'); // Includes Login Script

  require_once( "index_template_class.php");       // css and headers
  $H = new template( "CommunIT" );
  $H->show_template( );

if(isset($_SESSION['login_user'])){

	if(($_SESSION['login_user']) == "admin"){
		header("location: admin.php");
		exit();
	}
	elseif(($_SESSION['login_user']) == "guest"){
		header("location: communitymap.php");
		exit();
		}
	else{
		header("location: communitymap.php");
	}

}

?>

<!DOCTYPE html>
<html>
<head>

<style>

body {
background: url('images/background.jpg') no-repeat fixed;
-webkit-background-size: cover;
-moz-background-size: cover;
-o-background-size: cover;
background-size: cover;
}

</style>

</head>
<body>

	<div class="col-xs-12 " style="text-align: center; color: #FFFFFF; text-style: bold;
	text-shadow:
    -2px -2px 0 #000000,
    2px -2px 0 #000000,
    -2px 2px 0 #000000,
    2px 2px 0 #000000;  
	 ">
	 	
		<div style="font-size: 1000%;">  <img src="images/logo_02.png" alt="CommunIT" style="width:125px; height:125px;"> CommunIT </img> </div>
		<div style="font-size: 350%;"> Community Manager </div>
	</div>

	<div class="container">
		<div class="col-xs-6 col-xs-offset-3"><br/><br/><br/>
			<div class="panel panel-default" style="border: 1px solid black">

				<div class="panel-heading">
					<b class="" style="color: #000000" id="community_name">
						<!--Inline PHP Variable of Community Name--> 
						CommunIT Login
							
					</b>
				</div>

			<div class="panel-body">
				<form class="form-horizontal" action="" method="post">
					<div class="form-group">
						<b for="loginID" class="col-sm-3 control-label" style="color: #000000">Login ID</b>
						<div class="col-sm-9">
							<input id="loginID" name="username" type="text" class="form-control" placeholder="Login ID" required="" style="" autofocus="autofocus"> <br/>
						</div>
					</div>

				<div class="form-group">
					<b for="password" class="col-sm-3 control-label" style="color: #000000">Password</b>
					<div class="col-sm-9">
						<input  id="password" name="password" type="password" class="form-control" placeholder="Password" required="" style=""> <br/>
					</div>
				</div>

				<div class="form-group last">
					<div class="col-sm-offset-3 col-sm-9">
						<button name="submit" type="submit" value=" Login " class="btn btn-primary btn-lg" style=" width: 100%;">Sign In</button>
					</div>
				</div>

				<span><?php echo $error; ?></span>
				</form>
			</div>
			<div style="text-align: center;">

			<a href="disclaimer.php" class="btn btn-primary btn-xs" >View Disclaimer</a>
			<p></p>
			</div>
			</div>
		</div>
	</div>
</body>
</html>