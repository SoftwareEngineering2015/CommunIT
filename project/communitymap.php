<!DOCTYPE html>
<html>
<head>

<!-- Load In Google Maps -->
<!-- Key is Derek Lai's Google API KEY for accessing a broader spectrum of Google APIs-->
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCTUwndh9ZED3trNlGZqcCEjkAb5-bpoUw"></script>
<style>
body, html {
  height: 100%;
  width: 100%;
}
</style>
<!-- Load in classes and Libraries -->
<?php
  require_once("header_class.php");       // css and headers
  $H = new header( "Prototype", "Database Prototype");
  $H->show_header( );
 ?>

 <!-- Load In Google Maps -->
<script>
function initialize() {
  var mapProp = {
    //Need To Center at Center of Neighborhood
    //Decides default center of Google Maps
    //We should change these coordinates to account residence from database
    center:new google.maps.LatLng(41.7605556, -88.3200),
    zoom:12,
    mapTypeId:google.maps.MapTypeId.ROADMAP
  };
  var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
}
google.maps.event.addDomListener(window, 'load', initialize);

var myGeographicCoordinates = new LatLng(41.7605556, -88.3200);

// Zoom to 14 when clicking on marker
google.maps.event.addListener(marker,'click',function() {
  map.setZoom(14);
  map.setCenter(marker.getPosition());
  });

//Places a marker when clicking
google.maps.event.addListener(map, 'click', function(event) {
  placeMarker(event.latLng);
  });

function placeMarker(location) {
  var marker = new google.maps.Marker({
    position: location,
    map: map,
  });

  //Opens an info window on marker
  var infowindow = new google.maps.InfoWindow({
    content: 'Latitude: ' + location.lat() +
    '<br>Longitude: ' + location.lng()
  });

  //Opens the info window when clicking a marker
  google.maps.event.addListener(marker, 'click', function() {
  infowindow.open(map,marker);
  });

  
}

</script>

</head>

<body>

  <div class="container" style="width:100%; height:95%;">
    <div class="col-sm-4" style="background-color: #19A3FF; height:100%;"> A side window here</div>
    <div class="col-sm-8" id="googleMap" style="position: relative; height:100%;" ></div>
  </div>

</body>

</html>