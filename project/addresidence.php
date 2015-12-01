<!DOCTYPE html>
<html>
<head>
  <?php
  require_once( "template_class.php");       // css and headers
  $H = new template( "Administration" );
  $H->show_template( );
  
    if(isset($_POST['errormessage'])){
    $error = $_POST['errormessage']; 
  }else{
    $error='';
  }

    if(($_SESSION['login_user']) != "admin"){
     header("location: index.php");
     exit();
   }

   $error = "";

    if (isset($_GET['error']) && $_GET['error'] == 'space') {
      $error = "<span style='color:red;'> Residence name cannot have a space in it. </span><br />";
    }
    if (isset($_GET['error']) && $_GET['error'] == 'exists') {
      $error = "<span style='color:red;'> Residence name already exists. </span><br />";
    }
    if (isset($_GET['error']) && $_GET['error'] == 'latlng') {
      $error = "<span style='color:red;'> Latitude and longitude must be set. </span><br />";
    }
    if (isset($_GET['error']) && $_GET['error'] == 'alphanum') {
      $error = "<span style='color:red;'> Residence name must consist of only letters and numbers. </span><br />";
    }

// Create connection
    $P = new manage_db;
    $P->connect_db();

    $sql_default_color = "DESCRIBE head_residents";
      $P->do_query($sql_default_color);
      $default_color_result = mysql_query($sql_default_color); 
      while ($row = mysql_fetch_assoc($default_color_result))
        {
          if ($row['Field'] == 'pin_color') { 
            $default_pin_color = $row['Default'];
          }
        }

//Gets the information of a residence and it's head resident 
    $sqlResidences = "SELECT CONCAT(first_name, ' ', last_name) as 'head_full_name', head_resident_id, username, address, latitude, longitude, emergency_contact, phone_one, email_address, pin_color FROM residences LEFT JOIN head_residents ON head_residents.fk_residence_id = residences.residence_id WHERE address IS NOT NULL ORDER BY username DESC";
    $P->do_query($sqlResidences);
    $resultResidences = mysql_query($sqlResidences);    

  ?>
  <!-- Google API KEY for accessing a broader spectrum of Google APIs-->
  <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCTUwndh9ZED3trNlGZqcCEjkAb5-bpoUw"></script>
  <!-- Load in classes and Libraries -->

  <!-- Add the dropdown files and color pins file-->
  <link rel="stylesheet" type="text/css" href="css/dropdown.css" />
    <style>

    body, html {
      height: 100%;
      width: 100%;
    }

  </style>

  <!-- Load In Google Maps -->
  <script>
//Hold the pin color of each marker
pincolor = []; //Make pin colors global

  var map;
  var panorama;
  var iconbase = 'images/';
//Sets the default center of the Map
//Should change to Community Location (if set)
var myCenter=new google.maps.LatLng(41.7605556, -88.3200);

    addresses = [];
    residence_name = [];
    head_full_names = [];
    head_resident_ids = [];

  //holds parsed latlng location data
    latlng = [];
    //holds latitude and longitude location from database
    latitudes = [];
    longitudes = [];
    //creates a bounds object that is extended in the main loop
    bounds = new google.maps.LatLngBounds();

    //holds created markers
    markers = [];

     //Holds values for the dropdown menu
    divOptions = [];
    optionsDiv = [];
    options = [];

function initialize(){

  var mapProp = {
    center:myCenter,
    zoom:10,
    mapTypeId:google.maps.MapTypeId.ROADMAP
  };
    //this creates our map
    map = new google.maps.Map(document.getElementById("googleMap"),mapProp);

    geocoder = new google.maps.Geocoder();

    //This puts a marker based on the string in submitG
    document.getElementById('submitAddress').addEventListener('click', function() {
      geocodeAddress(geocoder, map);
    });

    //populates residence data from database
    <?php while ($row = mysql_fetch_assoc($resultResidences)) { ?>
      addresses.push(<?php echo '"'. $row['address'] .'"'?>);
      residence_name.push(<?php echo '"'. $row['username'] .'"'?>);
      head_full_names.push(<?php echo '"'. $row['head_full_name'] .'"'?>);
      head_resident_ids.push(<?php echo '"'. $row['head_resident_id'] .'"'?>);

    //populates the latlng array by creating an object based on the queryd data
    latitudes.push(<?php echo '"'. $row['latitude'] .'"'?>);
    longitudes.push(<?php echo '"'. $row['longitude'] .'"'?>); 
    latlng.push(new google.maps.LatLng((<?php echo '"'. $row['latitude'] .'"'?>), (<?php echo '"'. $row['longitude'] .'"'?>)));
    pincolor.push(<?php echo '"'. $row['pin_color'] .'"'?>);

    <?php } ?>
    //this loop will create all of the markers and infowindow content for those markers, then invoke the addlistener function
    for(i in addresses){
        //extend the bounds object to fit the iterated marker
        bounds.extend(new google.maps.LatLng(latitudes[i], longitudes[i]));

        //Change the color of each image through this function
        if (pincolor[i] == "") {
          pincolor[i] = <?php echo "'" . $default_pin_color . "'"?>;
        }
        overalayColor(pincolor[i]);

        //creates a marker in the markers array
        markers.push(new google.maps.Marker({
          map: map, 
          position: latlng[i],
          title: addresses[i],
          title: (head_full_names[i] + "\n" + addresses[i]),
          icon: fullimg,
          animation: google.maps.Animation.DROP
        }));

        //start process to set up custom drop down
        //create the options that respond to click
        if (head_resident_ids[i] == "") {
          divOptions.push({
              gmap: map,
              name: residence_name[i],
              title: residence_name[i],
              id: head_resident_ids[i],
              latlng: latlng[i],
              identifier: i
          });
        } else {
          divOptions.push({
              gmap: map,
              name: head_full_names[i],
              title: "Residence of " + head_full_names[i],
              id: head_resident_ids[i],
              latlng: latlng[i],
              identifier: i
          });
        }

        optionsDiv.push(new optionDiv(divOptions[i]));

        options.push(optionsDiv[i]);

    }

    if (addresses.length != 0) {

          options = options.reverse(); //Sort the array

      //put them all together to create the drop down       
      var ddDivOptions = {
        items: options,
        id: "myddOptsDiv"          
      }

        //alert(ddDivOptions.items[1]);
        var dropDownDiv = new dropDownOptionsDiv(ddDivOptions);               

        var dropDownOptions = {
          gmap: map,
          name: 'Find A Residence',
          id: 'ddControl',
          title: 'Find A Residence',
          position: google.maps.ControlPosition.TOP_CENTER,
          dropDown: dropDownDiv 
        }
        
      var dropDown1 = new dropDownControl(dropDownOptions);

      //calling the centermap function to initially center the map on the community
      centermap();
      //these next four lines are for the centering button
      var centerControlDiv = document.createElement('div');
      var centerControl = new centerbutton(centerControlDiv, map);
      centerControlDiv.index = 1;
      //puts the centering button on the map
      map.controls[google.maps.ControlPosition.TOP_CENTER].push(centerControlDiv);

      var FindMyHouseControlDiv = document.createElement('div');
      var FindMyHouseControl = new findeditinghouse(FindMyHouseControlDiv, map);
      FindMyHouseControlDiv.index = 1;
        //puts the centering button on the map
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(FindMyHouseControlDiv);
    }

}
//----------------------END OF INITIALIZE FUNCTION

//Turns the map on.
google.maps.event.addDomListener(window, 'load', initialize);

var marker_new = [];
      </script>

    </head>

   <body>

     <!-- Form for the update of head resident -->
     <form action="updateresidence.php" method="POST">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-5">

         <h3> Residence Information </h3>
         <?php echo $error; ?>
         <table class="table table-striped table-hover ">
          <tr>
           <th> Residence Name </th>
           <!-- Head resident first name -->
           <td> <input id="residence_name" name="residence_name" type="text" placeholder="House001" class="form-control input-md" required> </td>
         </tr>
         <tr>
           <th> Address </th>
           <!-- Head resident Emergency Contact -->
           <td> <input id="address" name="address" type="text" placeholder="123 Example Drive, Aurora, Illinois" class="form-control input-md" required> </td>
         </tr>
         <tr>
          <th> </th> 
          <td>
            <button type="button"  name="submitAddress" id="submitAddress"  value="Reverse Geocode" class="btn btn-info btn-lg" style="width: 100%;"> Drop Pin </button>
          </td>
        </tr>
      </table> <br/>
      <h3> Location </h3>
      <table class="table table-striped table-hover ">
        <tr>
          <th> Latitude &nbsp &nbsp  &nbsp  &nbsp  &nbsp  &nbsp</th>
          <!-- Head resident first name -->
          <td> <input id="latitude" name="latitude" type="text" class="form-control input-md" readonly> </td>
        </tr>
        <tr>
          <th> Longitude </th>
          <!-- Head resident Emergency Contact -->
          <td> <input id="longitude" name="longitude" type="text" class="form-control input-md" readonly> </td>
        </tr>
        <tr>
          <th> </th> 
          <td>
            <button type="button" onclick="show_confirm()" class="btn btn-primary btn-lg" style="width: 100%;"> Add Residence </button>
          </td>
        </tr>
      </table>
    </div>
    <!--Google Map Div-->
    <div class="col-xs-12 col-sm-12 col-md-7  col-md-offset-5" id="googleMap" style="position: absolute; height:100%;" ></div>
    <!-- Modal -->
    <div class="modal fade" id="confirm_modal" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"></button>
            <h4 class="modal-title" style="text-align: center; font-size: 200%;"><b>Add New Residence</b></h4>
          </div>
          <div class="modal-body">
            <b><p  style="font-size: 120%;">
              Are you sure you want to add a new residence with this information? </p></b> <br/>
              <table class="table table-striped table-hover ">
                <tr>
                  <th> Residence Name </th>
                  <th> Address </th>
                  <th> Latitude </th>
                  <th> Longitude </th>
                </tr>
                <tr>
                  <td id="submit_residence_name"> </td>
                  <td id="submit_address"> </td>
                  <td id="submit_latitude"> </td>
                  <td id="submit_longitude"> </td>
                </tr>
              </table>
              <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-lg" name="add_new_residence" id="add_new_residence" value="">Yes</button>
                <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">No</button>
              </div>
            </div>

          </div>
        </div>
      </form>
      <script type="text/javascript" src="js/dropdown.js"></script>
      <script type="text/javascript" src="js/colorpins.js"></script>
      <script type="text/javascript" src="js/map.js"></script>
    </body>
    </html>