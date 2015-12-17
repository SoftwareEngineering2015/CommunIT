<?php

class template {
  function __construct( $title ) {
    $this->TITLE = $title;
  }

  function show_template( ) {
    print "<html>\n<head> <title> $this->TITLE </title>
    <link rel='icon' type='image/icon' href='images/favicon.ico'></head>";
    print "<link rel='stylesheet' type='text/css' href='css/bootstrap.css'>";
    print "<script src='js/jquery-1.11.3.js'></script>";
    print "<script src='js/bootstrap.min.js'></script>";
    print "<script src='js/placeholders.js'></script>";


  }
}

?>
<?php
include('session.php');
include('db_class.php');
$error = '';
?>

<!-- Style the house icon to the left -->
<style type="text/css">
.navbar-brand
{
    margin-left: auto;
    margin-right: auto;
}
</style>

<!--Conatins the Site's Header Nav Bar-->
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <!-- image is needed so the community map icons change colors -->
      <!--<img src="images/house_pin.png" alt="" class="navbar-brand" data="images/house_pin.png"></img>-->
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      <a class="navbar-brand" href="communitymap.php">CommunIT Map</a></img>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" >
      <ul class="nav navbar-nav">

        <?php
        if((isset($_SESSION['login_user'])) && ( $login_session != "admin") && ( $login_session != "guest")) {
          print'<li><a href="myhome.php">My Home</a></li>';

        }

        ?>
        <?php
        if(isset($_SESSION['login_user'])){
          if($login_session == "admin"){
            print'
            <!--Go to the Admin Page -->
            <li><a href="admin.php">Admin</a></li>
            <li><a href="addresidence.php">Add Residence</a></li>
            <li><a href="configuration.php">Configuration</a></li>
            ';
          }
        }
        ?>
      </ul>


      <ul class="nav navbar-nav navbar-right">
        <li><a href="directory.php">Directory</a></li>
        <?php
        if(isset($_SESSION['login_user'])){
          print'
          <!--Clear User Variable -->
          <li><a href="logout.php">Logout</a></li>
          ';}
          ?>

        </ul>
      </div>
    </div>
  </nav>
   <div id="nav-bar-spacing" style="height: 55px">&nbsp</div>





  <!--
  <div>
  &nbsp
  <br/>
  <br/>
  <br/>
  </div>
-->



