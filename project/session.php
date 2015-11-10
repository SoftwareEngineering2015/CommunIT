
<?php

// Establishing Connection with Server by passing server_name, user_id and password as a parameter
$connection = mysql_connect("127.0.0.1", "root", "");
// Selecting Database
$db = mysql_select_db("communit", $connection);
session_start();// Starting Session
// Storing Session
$user_check=$_SESSION['login_user'];
// SQL Query To Fetch Complete Information Of User
$ses_sql=mysql_query("select username from residences where username='$user_check'", $connection);
$row = mysql_fetch_assoc($ses_sql);
$login_session = $row['username'];
if(!isset($login_session)){
mysql_close($connection); // Closing Connection
header('Location: index.php'); // Redirecting To Home Page
}


/*
include('db_class.php');

session_start();

$P = new manage_db;
$P->connect_db();

$user_check=$_SESSION['login_user'];

$login_session = $P->userCheck($user_check);

if(!isset($login_session)){
header('Location: index.php'); // Redirecting To Home Page
}

$P->close_db();

*/

?>