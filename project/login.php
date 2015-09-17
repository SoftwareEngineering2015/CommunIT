<head>
 <?php
  require_once( "template_class.php");       // css and headers
  $H = new template( "Prototype", "Database Prototype");
  $H->show_template( );
 ?>
</head>
<style>
    body {
    background: url('images/background.jpg') no-repeat center center fixed; 
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
}
</style>
<body>

    <div class="container">

      <form class="form-signin">
        <div class="container">
            <div class="col-md-6 col-md-offset-3"><br /><br />
                <div class="panel panel-default" style="border: 3px solid black">
                    <div class="panel-heading"> <b class="" style="color: #000000"> Oij's Neighborhood Login </b>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form">
                            <div class="form-group">
                                <b for="loginID" class="col-sm-3 control-label" style="color: #000000">Login ID</b>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="loginID" placeholder="Login ID" required="" style="border: 2px solid black"> <br />
                                </div>
                            </div>
                            <div class="form-group">
                                <b for="inputPassword3" class="col-sm-3 control-label" style="color: #000000">Password</b>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="inputPassword3" placeholder="Password" required="" style="border: 2px solid black"> <br />
                                </div>
                            </div>
                            <div class="form-group last">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="submit" class="btn btn-primary btn-lg" style="border: 2px solid black; width: 100%;">Sign In</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="panel-footer"><b style="color: #000000"> Not Registered? <a href="register.php" class="">Register Here</a></b>
                    </div>
                </div>
            </form>

        </div> <!-- /container -->
    </div>
</div>
</body>
