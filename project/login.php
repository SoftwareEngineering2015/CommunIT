<?php
include('db_class.php');
session_start(); // Starting Session


if(isset($_SESSION['login_user'])){

	if(($_SESSION['login_user']) == "admin"){
		header("location: admin.php");
		exit();
	}
	elseif(($_SESSION['login_user']) == "guest"){
		header("location: communitymap.php");
	}
	else{
		header("location: myhome.php");
	}

}

$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
	if (empty($_POST['username']) || empty($_POST['password'])) {
		$error = "Username or Password is invalid";
	}
	else
	{
// Define $username and $password
		$username=strtolower($_POST['username']);
		$password=$_POST['password'];
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
//$connection = mysql_connect("localhost", "root", "");

		$P = new manage_db;
		$P->connect_db();


// To protect MySQL injection for Security purpose
		$username = stripslashes($username);
		$password = stripslashes($password);
		$username = mysql_real_escape_string($username);
		$password = mysql_real_escape_string($password);
// Selecting Database
//$db = mysql_select_db("communit", $connection);
// SQL query to fetch information of registerd users and finds user match.
//$query = mysql_query("select * from residences where password='$password' AND username='$username'", $connection);
		//$query = "SELECT * from residences where password='$password' AND username='$username'";
		//$results = mysql_query($query);
		//$results = $P->do_query($query);
		//print_r($results);
		//exit;
//$rows = $P->check_rows($query);
		//$rows = mysql_num_rows($results);

		$userExists = ($P->doesUserExist($username, $password));
		
		if ($userExists) {
			$_SESSION['login_user']=$username; // Initializing Session
			header("location: index.php"); // Redirecting To Other Page
		} else {
			$error = "Username or Password is invalid";
		}
//mysql_close($connection); // Closing Connection

		$P->close_db();
	}
}
?>