<!DOCTYPE html>
<html>
<head>
 
<!-- Load In Google Maps -->
<!-- Key is Derek Lai's Google API KEY for accessing a broader spectrum of Google APIs-->
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCTUwndh9ZED3trNlGZqcCEjkAb5-bpoUw"></script>
<!-- Load in classes and Libraries -->
<?php
  require_once( "template_class.php");       // css and headers
 // require_once( "db_class.php");           // db class
  //require_once("INVENTORY_CONFIG.php");    // main configuration 
  //require_once("inventory_lib.php");       // output functions
  $H = new template( "Prototype", "Database Prototype");
  $H->show_template( );
 ?>

<style>
body, html {
  height: 100%;
  width: 100%;
}
    body {
   background-color: #1A4C80; 
}
</style>


 <!-- Load In Google Maps -->
<script>
var map;
//Sets the default center of the Map
//Should change to Community Location (if set)
var myCenter=new google.maps.LatLng(41.7605556, -88.3200);

function initialize()
{
var mapProp = {
  center:myCenter,
  zoom:10,
  mapTypeId:google.maps.MapTypeId.ROADMAP
  };

  map = new google.maps.Map(document.getElementById("googleMap"),mapProp);

  google.maps.event.addListener(map, 'click', function(event) {
    placeMarker(event.latLng);
  });

  var geocoder = new google.maps.Geocoder();

  var infowindow = new google.maps.InfoWindow;

  document.getElementById('submitG').addEventListener('click', function() {
    geocodeLatLng(geocoder, map, infowindow);
  });

  document.getElementById('submitA').addEventListener('click', function() {
    geocodeAddress(geocoder, map);
  });

}

//Need to put this into a loop to pipe in Addresses from Database
function geocodeAddress(geocoder, resultsMap) {
  var address = document.getElementById('address').value;
  geocoder.geocode({'address': address}, function(results, status) {
    if (status === google.maps.GeocoderStatus.OK) {
      resultsMap.setCenter(results[0].geometry.location);

//Sets a Marker at the locations in the Geocoder search
      var marker = new google.maps.Marker({
        map: resultsMap,
        position: results[0].geometry.location
      });
      
//Creates an info Window showing Latitude and Longitude
      var infowindow = new google.maps.InfoWindow({
        content: 'Getting Location By Address: </br>' + 'Latitude: ' + results[0].geometry.location.lat() + '<br>Longitude: ' + results[0].geometry.location.lng()
      });

//Sets the infor window on the marker 
      infowindow.open(map,marker);

// Zoom to 15 when clicking on marker and opens the infow window if its closed
      google.maps.event.addListener(marker,'click',function() {
        map.setZoom(18);
        map.setCenter(marker.getPosition());
        infowindow.open(map,marker);
      });

    } else {
      alert('Geocode was not successful for the following reason: ' + status);
    }
  });
}


function geocodeLatLng(geocoder, map, infowindow) {
  var input = document.getElementById('latlng').value;
  var latlngStr = input.split(',', 2);
  var latlng = {lat: parseFloat(latlngStr[0]), lng: parseFloat(latlngStr[1])};
  geocoder.geocode({'location': latlng}, function(results, status) {
    if (status === google.maps.GeocoderStatus.OK) {
      map.setCenter(results[0].geometry.location);
      if (results[1]) {
        map.setZoom(11);
        var marker = new google.maps.Marker({
          position: latlng,
          map: map
        });
        infowindow.setContent( 'Getting Location By Geocode: </br>' + results[1].formatted_address);
        infowindow.open(map, marker);

// Zoom to 15 when clicking on marker and opens the infow window if its closed
      google.maps.event.addListener(marker,'click',function() {
        map.setZoom(12);
        map.setCenter(marker.getPosition());
        infowindow.open(map,marker);
      });


      } else {
        window.alert('No results found');
      }
    } else {
      window.alert('Geocoder failed due to: ' + status);
    }
  });
}


function placeMarker(location) {

//Adds a Marker where the User Clicks
  var marker = new google.maps.Marker({
    position: location,
    map: map,
  });

//Adds an Info Window on the Marker
//Showing Latitude and Logitude
//Need to take Latlng into database
  var infowindow = new google.maps.InfoWindow({
    content: 'Latitude: ' + location.lat() + '<br>Longitude: ' + location.lng()
  });
  infowindow.open(map,marker);

// Zoom to 15 when clicking on marker
  google.maps.event.addListener(marker,'click',function() {
    map.setZoom(12);
    map.setCenter(marker.getPosition());
    infowindow.open(map,marker);
    });

}

google.maps.event.addDomListener(window, 'load', initialize);

</script>

</head>

<body>

  <div class="container" style="width:100%; height:95%;">
  <!--Side Panel Div-->
    <div class="col-sm-4" style="background-color: #19A3FF; height:100%;">
      <div>
        <input id="address" type="textbox" value="Aurora, IL">
        <input id="submitA" type="button" value="Geocode">
      </div>
      <div>
        <input id="latlng" type="text" value="41.7605849,-88.3200715">
        <input id="submitG" type="button" value="Reverse Geocode">
      </div>

      <div class="col-sm-4" style="background-color: #EEEEEE; height:60%; width:95%; font-size: 20px;">
          Address: 501 S Calumet Ave Aurora, IL 60506 </br>
          Residents: Joey Calzone, Penny Calzone, Walter Calzone </br>
          Emergency Number: 630-555-1234 </br>
          Phone Number One: 630-555-4321 </br>
          E-mail: Email01@aol.com </br>
          <hr>

      </div>
    </div>

  <!--Google Map Div-->
    <div class="col-sm-8" id="googleMap" style="position: relative; height:100%;" ></div>
  </div>

</body>

</html>