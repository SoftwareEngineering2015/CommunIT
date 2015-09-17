<head>
  <?php
  require_once( "template_class.php");       // css and headers
  
  $H = new template( "Prototype");
  $H->show_template( );
 ?>
</head>
<style>
  label {
    color: #000000;
  }
  .input {
    border: 2px solid black;
  }

</style>
<form class="form-horizontal">
<fieldset>

<!-- Form Name -->
<legend style="color: #000000">Register House</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="first_name">First Name</label>  
  <div class="col-md-3">
  <input id="first_name" name="first_name" type="text" placeholder="First Name" class="form-control input-md input" required="" >
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="last_name">Last Name</label>  
  <div class="col-md-3">
  <input id="last_name" name="last_name" type="text" placeholder="Last Name" class="form-control input-md input" required="" >
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="birth_date">Birth Date</label>  
  <div class="col-md-3">
  <input id="birth_date" name="birth_date" type="text" placeholder="placeholder" class="form-control input-md input" required="" >
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="emergency_number">Emergency Phone Number</label>  
  <div class="col-md-3">
  <input id="emergency_number" name="emergency_number" type="text" placeholder="555-555-5555" class="form-control input-md input" required="" >
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="phone_one">Phone Number 1</label>  
  <div class="col-md-3">
  <input id="phone_one" name="phone_one" type="text" placeholder="555-555-5555" class="form-control input-md input" required="" >
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="phone_two">Phone Number 2</label>  
  <div class="col-md-3">
  <input id="phone_two" name="phone_two" type="text" placeholder="555-555-5555" class="form-control input-md input" required="" >
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="email">E-mail</label>  
  <div class="col-md-3">
  <input id="email" name="email" type="text" placeholder="example@maps.com" class="form-control input-md input" required="" >
    
  </div>
</div>

<!-- Password input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="password">Password</label>
  <div class="col-md-3">
    <input id="password" name="password" type="password" placeholder="password" class="form-control input-md input" required="" >
    
  </div>
</div>

<!-- Text input--><!--
<div class="form-group">
  <label class="col-md-4 control-label" for="address">House Address</label>  
  <div class="col-md-3">
  <input id="address" name="address" type="text" placeholder="123 Example Drive, Aurora, Illinois" class="form-control input-md" required="" style="border: 2px solid black">
    
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="submit"></label>
  <div class="col-md-4">
    <button id="submit" name="submit" class="btn btn-primary btn-lg" style="border: 2px solid black; width: 100%"> Register </button>
  </div>
</div>

</fieldset>
</form>
<div class="form-group form-horizontal">
  <label class="col-md-4 control-label" for="submit"></label>
  <div class="col-md-4">
    <button id="submit" name="submit" class="btn btn-danger btn-lg" style="border: 2px solid black; width: 100%" onclick="window.location='login.php';"> Back </button>
  </div>
</div>