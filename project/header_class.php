<?php

class header {
  function __construct( $title, $h1 ) {
    $this->TITLE = $title;
    $this->h1 = $h1;
  }

  function show_header( ) {
  	print "<html>\n<head> <title> $this->TITLE </title></head>";
  	print "<link rel='stylesheet' type='text/css' href='css/bootstrap.css'>";
   
    //Conatins the Site's Header Nav Bar
    print'
      <nav class="navbar navbar-default">
        <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="home.php">Project Home</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><a href="#">LinkOneHere</a></li>
            <li><a href="#">LinkTwoHere</a></li>
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
          </ul>

          <ul class="nav navbar-nav navbar-right">
          <li><a href="#">LinkThreeHere</a></li>
        </ul>
      </div>
    </div>
  </nav>
 ';

  }

}
?>
