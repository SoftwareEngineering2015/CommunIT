<?php
class manage_db {
  function __construct( $DBG = false ) {
    $this->DBG = $DBG;
  }
  function connect_db(){
     $server = '127.0.0.1';
     $user = 'root';
     $pass = '';
     $mydb = 'communit';
     $this->DBH = mysqli_connect($server, $user, $pass, $mydb ) 
         or die ("Cannot connect to $server using $user Errst=" .  mysql_error());

  }


  function do_query( $input ) {
       $query = $input;
       $this->results = mysqli_query( $this->DBH, $query ) 
          or die ("Database query failed SQLcmd=$query Error_str=" .  mysqli_error());
  } 

    function fetch_assoc() {
    while ($line = mysqli_fetch_array( $this->results, MYSQL_ASSOC)) {
         $this->DATA[] = $line;
      }


}
}