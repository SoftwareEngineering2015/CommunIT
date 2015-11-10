<!DOCTYPE html>
<html>
<head>
  <!-- Google API KEY for accessing a broader spectrum of Google APIs-->
  <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCTUwndh9ZED3trNlGZqcCEjkAb5-bpoUw"></script>
  <!-- Load in classes and Libraries -->
  <?php
  require_once( "template_class.php");       // css and headers
  ?>

  <!-- Load In Google Maps -->
  <script>
  var map;
  var panorama;
  var iconbase = 'images/';
//Sets the default center of the Map
//Should change to Community Location (if set)
var myCenter=new google.maps.LatLng(41.7605556, -88.3200);

function initialize(){

  <?php
// Create connection
    $P = new manage_db;
    $P->connect_db();
//Gets the information of a residence and it's head resident 
    $sqlResidences = "SELECT CONCAT(first_name, ' ', last_name) as 'head_full_name', head_resident_id, address, latitude, longitude, emergency_contact, phone_one, email_address FROM residences LEFT JOIN head_residents ON head_residents.fk_residence_id = residences.residence_id WHERE address IS NOT NULL ORDER BY username='$login_session' DESC";
    $P->do_query($sqlResidences);
    $resultResidences = mysql_query($sqlResidences);    
    ?>

        //this will be populated with the total lat and longitude for the average to be computed
    center_lat = 0;
    center_lon = 0;

    addresses = [];
    head_full_names = [];

  //holds parsed latlng location data
    latlng = [];
    //holds latitude and longitude location from database
    latitudes = [];
    longitudes = [];

    //holds created markers
    markers = [];

      var mapProp = {
    center:myCenter,
    zoom:10,
    mapTypeId:google.maps.MapTypeId.ROADMAP
  };
    //this creates our map
    map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
    var geocoder = new google.maps.Geocoder();

    //populates residence data from database
    <?php while ($row = mysql_fetch_assoc($resultResidences)) { ?>
      addresses.push(<?php echo '"'. $row['address'] .'"'?>);
      head_full_names.push(<?php echo '"'. $row['head_full_name'] .'"'?>);
    //populates the latlng array by creating an object based on the queryd data
    latitudes.push(<?php echo '"'. $row['latitude'] .'"'?>);
    longitudes.push(<?php echo '"'. $row['longitude'] .'"'?>); 
    latlng.push(new google.maps.LatLng((<?php echo '"'. $row['latitude'] .'"'?>), (<?php echo '"'. $row['longitude'] .'"'?>)));

    <?php } ?>
    //this loop will create all of the markers and infowindow content for those markers, then invoke the addlistener function
    for(i in addresses) {
        center_lat += parseFloat(latitudes[i]);
        center_lon += parseFloat(longitudes[i]);
        //creates a marker in the markers array
        markers.push(new google.maps.Marker({
          map: map, 
          position: latlng[i],
          title: addresses[i],
          title: (head_full_names[i] + "\n" + addresses[i]),
          icon: iconbase + 'house_pin.png',
          animation: google.maps.Animation.DROP
        }));

    }

//This puts a marker based on the string in submitG
document.getElementById('submitAddress').addEventListener('click', function() {
  geocodeAddress(geocoder, map);
});

    if (addresses.length != 0) {
      //calling the centermap function to initially center the map on the community
      centermap();
      //these next four lines are for the centering button
      var centerControlDiv = document.createElement('div');
      var centerControl = new centerbutton(centerControlDiv, map);
      centerControlDiv.index = 1;
      //puts the centering button on the map
      map.controls[google.maps.ControlPosition.TOP_CENTER].push(centerControlDiv);

      var FindMyHouseControlDiv = document.createElement('div');
      var FindMyHouseControl = new findmyhouse(FindMyHouseControlDiv, map);
      FindMyHouseControlDiv.index = 1;
        //puts the centering button on the map
      map.controls[google.maps.ControlPosition.TOP_CENTER].push(FindMyHouseControlDiv);
  }

}
//----------------------END OF INITIALIZE FUNCTION

//Turns the map on.
google.maps.event.addDomListener(window, 'load', initialize);

//this function centers the map on the community based on average latitude and longitude
function centermap(){
    var final_lat_center = (center_lat/latitudes.length);
    var final_lon_center = (center_lon/longitudes.length);
    //sets center position
    map.setCenter(new google.maps.LatLng(final_lat_center, final_lon_center));
    //sets map zoom (zoom amount is up for debate)
    map.setZoom(17);
}

//this function styles and sets up the button
function centerbutton(controlDiv, map) {
    // Set CSS for the control border.
    var controlUI = document.createElement('div');
    controlUI.style.backgroundColor = '#3399FF';
    controlUI.style.border = '2px solid #00000';
    controlUI.style.borderRadius = '3px';
    controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
    controlUI.style.cursor = 'pointer';
    controlUI.style.marginBottom = '22px';
    controlUI.style.textAlign = 'right';
    controlUI.title = 'Click to recenter the map on your community';
    controlDiv.appendChild(controlUI);

    // Set CSS for the control interior.
    var controlText = document.createElement('div');
    controlText.style.color = 'rgb(250,250,250)';
    controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
    controlText.style.fontSize = '16px';
    controlText.style.lineHeight = '38px';
    controlText.style.paddingLeft = '5px';
    controlText.style.paddingRight = '5px';
    controlText.innerHTML = 'Center On Your Community';
    controlUI.appendChild(controlText);

    // Setup the click event listeners: calls the centermap function
    controlUI.addEventListener('click', function() {
        centermap();
    });
}

//this function styles and sets up the button
function findmyhouse(controlDiv, map) {
    // Set CSS for the control border.
    var controlUI = document.createElement('div');
    controlUI.style.backgroundColor = '#3399FF';
    controlUI.style.border = '2px solid #00000';
    controlUI.style.borderRadius = '3px';
    controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
    controlUI.style.cursor = 'pointer';
    controlUI.style.marginBottom = '22px';
    controlUI.style.textAlign = 'right';
    controlUI.title = 'Click to find the house you are editing.';
    controlDiv.appendChild(controlUI);

    // Set CSS for the control interior.
    var controlText = document.createElement('div');
    controlText.style.color = 'rgb(250,250,250)';
    controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
    controlText.style.fontSize = '16px';
    controlText.style.lineHeight = '38px';
    controlText.style.paddingLeft = '5px';
    controlText.style.paddingRight = '5px';
    controlText.innerHTML = 'Find Editing House';
    controlUI.appendChild(controlText);

    // Setup the click event listeners: calls the centermap function
    controlUI.addEventListener('click', function() {
      var houseLatitude = document.getElementById('latitude').value;
      var houseLongitude = document.getElementById('longitude').value;
      
        if (houseLatitude != "" && houseLongitude != "") {
          //sets center position
          map.setCenter(new google.maps.LatLng(houseLatitude, houseLongitude));
          //sets map zoom (zoom amount is up for debate)
          map.setZoom(18);
        }
    });
}



var marker_new = [];

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
  icon: iconbase + 'house_pin02.png',
  position: results[0].geometry.location
});
marker_new.push(marker);

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
  for (var i = 0; i < marker_new.length; i++) {
    marker_new[i].setMap(map);
  }
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
  setMapOnAll(null);
}
//pass total # of characters, and []
function makepassword(total, chars){
    if(total!=0){
        //this switch statement selects a random character to be insteted into the character array
        var added = 'A';
        switch(1 + parseInt((Math.random() * ((46 - 1) + 1)))){
        case 1: added = 'a';break;
        case 2: added = 'b';break;
        case 3: added = 'c';break;
        case 4: added = 'd';break;
        case 5: added = 'e';break;
        case 6: added = 'f';break;
        case 7: added = 'g';break;
        case 8: added = 'h';break;
        case 9: added = 'i';break;
        case 10: added = 'j';break;
        case 11: added = 'k';break;
        case 12: added = 'l';break;
        case 13: added = 'm';break;
        case 14: added = 'n';break;
        case 15: added = 'o';break;
        case 16: added = 'p';break;
        case 17: added = 'q';break;
        case 18: added = 'r';break;
        case 19: added = 's';break;
        case 20: added = 't';break;
        case 21: added = 'u';break;
        case 22: added = 'v';break;
        case 23: added = 'w';break;
        case 24: added = 'x';break;
        case 25: added = 'y';break;
        case 26: added = 'z';break;
        case 27: added = '1';break;
        case 28: added = '2';break;
        case 29: added = '3';break;
        case 30: added = '4';break;
        case 31: added = '5';break;
        case 32: added = '6';break;
        case 33: added = '7';break;
        case 34: added = '8';break;
        case 35: added = '9';break;
        case 36: added = '0';break;
        case 37: added = '1';break;
        case 38: added = '2';break;
        case 39: added = '3';break;
        case 40: added = '4';break;
        case 41: added = '5';break;
        case 42: added = '6';break;
        case 43: added = '7';break;
        case 44: added = '8';break;
        case 45: added = '9';break;
        case 46: added = '0';break;
        }
        chars.push(added);
        makepassword((total-1), chars);
    }
    //the function escapes to here once total equals 0
	//.replace(literal comma, global replacement, empty space)
    return chars.toString().replace(/,/g,'');
}
function show_confirm(){
  //var rndm_password = makepassword(6,[]);
    var rndm_password = "password";
  document.getElementById("password").value = rndm_password;
  var latitude = document.getElementById("latitude").value;
  var longitude = document.getElementById("longitude").value;
  if( latitude == "" || longitude =="" ){
   alert("Latitude and Longitude need a value.");
   return false;
 } else {
          // shows the modal on button press
          $('#confirm_modal').modal('show');
          document.getElementById("submit_residence_name").innerHTML = document.getElementById("residence_name").value;
          document.getElementById("submit_address").innerHTML = document.getElementById("address").value;
          document.getElementById("submit_latitude").innerHTML = document.getElementById("latitude").value;
          document.getElementById("submit_longitude").innerHTML = document.getElementById("longitude").value;
          document.getElementById("submit_password").innerHTML = rndm_password;
        }
      }


      </script>

    </head>

    <?php

    $H = new template( "Administration" );
    $H->show_template( );


    if(($_SESSION['login_user']) != "admin"){
     header("location: home.php");
     exit();
   }

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
        <tr>
            <td></td>
           <!--Password -->
           <td> <input id= "password" name="password" type="text" class="form-control input-md" style="visibility: hidden;"></td>
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
    <div class="col-xs-12 col-sm-12 col-md-6  col-md-offset-6" id="googleMap" style="position: absolute; height:100%;" ></div>
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
                  <th> Temporary Password </th>
                </tr>
                <tr>
                  <td id="submit_residence_name"> </td>
                  <td id="submit_address"> </td>
                  <td id="submit_latitude"> </td>
                  <td id="submit_longitude"> </td>
                  <td id="submit_password"></td>
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

    </body>
    </html>