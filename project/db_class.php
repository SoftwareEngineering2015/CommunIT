<?php


class manage_db {
  function __construct( $DBG = false ) {
    $this->DBG = $DBG;
  }
  function connect_db(){
        //include("db_config.php");
   $server = '127.0.0.1';
     $user = 'root';
     $pass = '';
     $mydb = 'communit';
     $this->DBH = mysqli_connect($server, $user, $pass, $mydb ) 
         or die ("Cannot connect to $server using $user" .  mysql_error());

  }

  function do_query( $input ) {
      $error = ' ';
       $query = $input;
         $this->results = mysqli_query( $this->DBH, $query )
          //or die ("Database query failed SQLcmd=$query Error_str=" .  mysqli_error() );
         or ($this->results = 'false');
  } 

  function do_residence_query( $input, $redirect ) {
      $error = ' ';
       $query = $input;
         $this->results = mysqli_query( $this->DBH, $query )
          //or die ("Database query failed SQLcmd=$query Error_str=" .  mysqli_error() );
        or die ("<META HTTP-EQUIV='Refresh' CONTENT='0;URL=$redirect'>");
  } 

  function check_rows( $input ) {
       $query = $input;
       $rows = mysqli_query( $this->DBH, $query ) 
          or die ("Database query failed SQLcmd=$query Error_str=" .  mysqli_error());
        $this->results = mysql_num_rows($rows)
        or die ("Database query failed SQLcmd=$query Error_str=" .  mysqli_error());
  } 

  function affected_rows() {
      // $query = $input;
        $this->results = mysqli_affected_rows($this->DBH)
        or die ("Database query failed SQLcmd=$query Error_str=" .  mysqli_error());
  } 

  function doesUserExist($username, $password) {
    $query = "SELECT * FROM residences WHERE password = '".$password."' AND username = '".$username."'";
    $result = mysqli_query($this->DBH, $query);
    $userExists = false;

    if (mysqli_num_rows($result) > 0) {
        $userExists = true;
    }
    return $userExists;
}

  function userCheck($user_check) {
    $query = "SELECT username FROM residences WHERE username='".$user_check."'";
    $result = mysqli_query( $this->DBH, $query);
    $row =  mysqli_fetch_assoc($result);

    $check = $row['username'];

    return $check;
}

    function fetch_assoc() {
    while ($line = mysqli_fetch_array( $this->results, MYSQL_ASSOC)) {
         $this->DATA[] = $line;
      }
    }

    function close_db(){
    mysqli_close($this->DBH); // Closing Connection
  }




}

?>