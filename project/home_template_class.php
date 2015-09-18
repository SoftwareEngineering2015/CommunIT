<?php

class template {
  function __construct( $title ) {
    $this->TITLE = $title;
  }
 
  function show_template( ) {
  	print "<html>\n<head> <title> $this->TITLE </title></head>";
  	print "<link rel='stylesheet' type='text/css' href='css/bootstrap.css'>";
   }
 }
    //Conatins the Site's Header Nav Bar
?>
      <nav class="navbar navbar-default">
        <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="communitymap.php" >CommunIT</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">

          <!--
            <li><a href="#"></a></li>

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Dropdown <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li><a href="#">Separated link</a></li>
                <li class="divider"></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li>
          -->
          </ul>

          

          <?php
          if(isset($_SESSION['login_user'])){
            print'
          <ul class="nav navbar-nav navbar-right">
            <!--Clear User Variable -->
            <li><a href="logout.php">Logout</a></li>
          </ul>';
          }
          ?>

      </div>
    </div>
  </nav>



