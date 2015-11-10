<?php

if (!isset($_GET['residence'])){
  header("location: admin.php");
  exit;
}
 
  $residence = $_GET['residence'];

  require_once( "template_class.php");       // css and headers

  if(($_SESSION['login_user']) != "admin"){
    header("location: home.php");
    exit();
  }


  $P = new manage_db;
  $P->connect_db();

    // Check connection
  $sql_get_residence_info = "SELECT * FROM residences WHERE residence_id='$residence'";
  $P->do_query($sql_get_residence_info);
  $residence_info_result = mysql_query($sql_get_residence_info);

      // Goes through the result of the query to get the id of the current user's residence 
  while ($row = mysql_fetch_assoc($residence_info_result))
  {
    $username = $row['username'];
    $address = $row['address'];
    $latitude = $row['latitude'];
    $longitude = $row['longitude'];
  }

    if ( ($username == "admin") ||  ($username == "guest") || $username == NULL){
    header("location: admin.php");
    exit();
  }

  ?>


  <!DOCTYPE html>
  <html>
  <head>
    <!-- Google API KEY for accessing a broader spectrum of Google APIs-->
    <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCTUwndh9ZED3trNlGZqcCEjkAb5-bpoUw"></script>
    <!-- Load in classes and Libraries -->

  <!-- Load In Google Maps -->
  <script>
  var map;
  var panorama;
  var iconbase = 'images/';
//Sets the default center of the Map
//Should change to Community Location (if set)
var latitude = <?php  if (isset($latitude)){echo $latitude ;}?>;
var longitude = <?php if (isset($longitude)){echo $longitude ;}?>;

var myCenter=new google.maps.LatLng(latitude, longitude);
var markers = [];

function initialize(){

  var mapProp = {
    center:myCenter,
    zoom:18,
    mapTypeId:google.maps.MapTypeId.ROADMAP
  };


    //this creates our map
    map = new google.maps.Map(document.getElementById("googleMap"),mapProp);

    //Sets a Marker at the locations in the Geocoder search
  var marker = new google.maps.Marker({
    map: map,
    draggable: true,
    icon: iconbase + 'house_pin.png',
    position: myCenter
  }); 
  markers.push(marker);
    
    var geocoder = new google.maps.Geocoder();

//This puts a marker based on the string in submitG
document.getElementById('submitAddress').addEventListener('click', function() {
  geocodeAddress(geocoder, map);
});
google.maps.event.addListener(marker, 'dragend', function (event) {
  document.getElementById("latitude").value = this.getPosition().lat();
  document.getElementById("longitude").value = this.getPosition().lng();
});

}
//----------------------END OF INITIALIZE FUNCTION

//Turns the map on.
google.maps.event.addDomListener(window, 'load', initialize);

function geocodeAddress(geocoder, resultsMap) {

  clearMarkers();
  var address = document.getElementById('address').value;
  geocoder.geocode({'address': address}, function(results, status) {
    if (status === google.maps.GeocoderStatus.OK) {
      resultsMap.setCenter(results[0].geometry.location);
      resultsMap.setZoom(18);
//Sets a Marker at the locations in the Geocoder search
var marker = new google.maps.Marker({
  map: resultsMap,
  draggable: true,
  icon: iconbase + 'house_pin.png',
  position: results[0].geometry.location
});
markers.push(marker);

  document.getElementById("latitude").value = marker.getPosition().lat();
  document.getElementById("longitude").value = marker.getPosition().lng();

// Zoom to 15 when clicking on marker and opens the infow window if its closed
google.maps.event.addListener(marker,'click',function() {
  map.setZoom(18);
  map.setCenter(marker.getPosition());
});

} else {
  alert('Geocode was not successful for the following reason: ' + status);
}

google.maps.event.addListener(marker, 'dragend', function (event) {
  document.getElementById("latitude").value = this.getPosition().lat();
  document.getElementById("longitude").value = this.getPosition().lng();
});
});
}

// Sets the map on all markers in the array.
function setMapOnAll(map) {
  for (var i = 0; i < markers.length; i++) {
    markers[i].setMap(map);
  }
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
  setMapOnAll(null);
}
function show_confirm(residence_id){

          // shows the modal on button press
          $('#confirm_modal').modal('show');
          document.getElementById("submit_residence_name").innerHTML = document.getElementById("residence_name").value;
          document.getElementById("submit_address").innerHTML = document.getElementById("address").value;
          document.getElementById("submit_latitude").innerHTML = document.getElementById("latitude").value;
          document.getElementById("submit_longitude").innerHTML = document.getElementById("longitude").value;
          document.getElementById("update_residence").value = <?php echo $residence; ?>;
      }


      </script>

    </head>

    <?php

    $H = new template( "Administration" );
    $H->show_template( );

  ?>

  <body>

   <!-- Form for the update of head resident -->
   <form action="updateresidence.php" method="POST">
    <div class="container-fluid">
     <div class="row">
      <div class="col-md-6">
       <h3> Residence Information </h3>
       <table class="table table-striped table-hover ">
        <tr>
         <th> Residence Name </th>
         <!-- Head resident first name -->
         <td> <input id="residence_name" name="residence_name" type="text" placeholder="<?php  if (isset($username)){echo $username ;} ?>" value="<?php if (isset($username)){echo $username ;}?>" class="form-control input-md"> </td>
       </tr>
       <tr>
         <th> Address </th>
         <!-- Head resident Emergency Contact -->
         <td> <input id="address" name="address" type="text" placeholder="<?php if (isset($address)){echo  $address ;}?>" value="<?php if (isset($address)){echo $address ;}?>" class="form-control input-md" > </td>
       </tr>
       <tr>
        <th> </th> 
        <td>
          <button type="button"  name="submitAddress" id="submitAddress"  value="Reverse Geocode" class="btn btn-info btn-lg" style="  width: 100%;"> Drop New Pin</button>
        </td>
      </tr>
    </table> <br/>
    <h3> Location </h3>
    <table class="table table-striped table-hover ">
      <tr>
        <th> Latitude &nbsp &nbsp  &nbsp  &nbsp  &nbsp  &nbsp</th>
        <!-- Head resident first name -->
        <td> <input id="latitude" name="latitude" type="text" placeholder=<?php echo "'". $latitude . "'"?> class="form-control input-md" readonly> </td>
      </tr>
      <tr>
        <th> Longitude </th>
        <!-- Head resident Emergency Contact -->
        <td> <input id="longitude" name="longitude" type="text" placeholder=<?php echo "'". $longitude . "'"?> class="form-control input-md" readonly> </td>
      </tr>
      <tr>
        <th> </th> 
        <td>
          <button type="button" onclick="show_confirm()" class="btn btn-primary btn-lg" style="  width: 100%;"> Update Residence </button>
        </td>
      </tr>
    </table>
  </div>
  <!--Google Map Div-->
  <div class="col-xs-12 col-sm-12 col-md-6  col-md-offset-6" id="googleMap" style="position: absolute; height:100%;" ></div>
  <!-- Modal -->
  <div class="modal fade" id="confirm_modal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"></button>
          <h4 class="modal-title" style="text-align: center; font-size: 200%;"><b>Update Residence</b></h4>
        </div>
        <div class="modal-body">
          <b><p  style="font-size: 120%;">
            Are you sure you want to update this residence with this information? </p></b> <br/>
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
              <button type="submit" class="btn btn-success btn-lg" name="update_residence" id="update_residence" value="">Yes</button>
              <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">No</button>
            </div>
          </div>

        </div>
      </div>
    </form> 

  </body>
  </html>