 <!DOCTYPE html>
<html>
<head>
<!-- Load In Google Maps -->
<!-- Key is Derek Lai's Google API KEY for accessing a broader spectrum of Google APIs-->
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCTUwndh9ZED3trNlGZqcCEjkAb5-bpoUw"></script>
<!-- Load in classes and Libraries -->
<?php
  require_once( "template_class.php");       // css and headers
  $H = new template( "CommunIT Map" );
  $H->show_template( );
 ?>

<style>
body, html {
  height: 100%;
  width: 100%;
}

</style>


 <!-- Load In Google Maps -->
<script>
var map;
var panorama;
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
/*
  document.getElementById('submitG').addEventListener('click', function() {
    geocodeLatLng(geocoder, map, infowindow);
  });

  document.getElementById('submitA').addEventListener('click', function() {
    geocodeAddress(geocoder, map);
  });
*/
  <?php
// Create connection
      $P = new manage_db;
      $P->connect_db();
// Check connection
      $sqlResidences = "SELECT address, latitude, longitude, emergency_contact FROM residences INNER JOIN head_residents ON head_residents.fk_residence_id = residences.residence_id WHERE address IS NOT NULL";
      $P->do_query($sqlResidences);
      $result = mysql_query($sqlResidences); 

     // $sqlResidents = "";
     // $P->do_query($sqlResidents);
     // $result = mysql_query($sqlResidents);     
?>

var addresses = [];
var emergencies = [];
var latitudes = [];
var longitudes = [];

<?php while ($row = mysql_fetch_assoc($result)) { ?>
addresses.push(<?php echo '"'. $row['address'] .'"'?>);
emergencies.push(<?php echo '"'. $row['emergency_contact'] .'"'?>);
latitudes.push(<?php echo '"'. $row['latitude'] .'"'?>);
longitudes.push(<?php echo '"'. $row['longitude'] .'"'?>);
<?php } ?>

/*
for(i in addresses) {
    var address = addresses[i];
    geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
                    map.setZoom(18);

            var marker = new google.maps.Marker({
                map: map, 
                position: results[0].geometry.location
            });

            //Creates an info Window showing Latitude and Longitude
            var infowindow = new google.maps.InfoWindow({
              content: 'Getting Location By Address: </br>' + 'Latitude: ' + results[0].geometry.location.lat() + '<br>Longitude: ' + results[0].geometry.location.lng()
            });

           //Sets the infor window on the marker 
            info0window.open(map,marker);

        } else {
            // alert("Geocode was not successful for the following reason: " + status);
        }
    });
}
*/

//addresses.push(<?php echo '"' . $row['address'].', '.$row['latitude'].', '.$row['longitude'] . '"'?>);

for(i in addresses) {
     var lat = latitudes[i];
     var log = longitudes[i];
     var latlng = new google.maps.LatLng(lat, log);

         map.setCenter(latlng);
         map.setZoom(19);

            var marker = new google.maps.Marker({
                map: map, 
                position: latlng
            });

            //Creates an info Window showing Latitude and Longitude
            var infowindow = new google.maps.InfoWindow({
              content: 'Address: ' + addresses[i] + '<br/>Emergency Contact: ' + emergencies[i] + '<br/>Latitude: ' + latitudes[i] + '<br>Longitude: ' + longitudes[i]
            });
            infowindow.open(map,marker);


           //Sets the infor window on the marker
          google.maps.event.addListener(marker,'click',function() { 
            infowindow.open(map,marker);
            panorama = new google.maps.StreetViewPanorama(
              document.getElementById('street-view'),
              {
                position: latlng,
                pov: {heading: 0, pitch: 0},
                zoom: 1
              });



          });
    }

}


/*

var lat = resultP[i].get("lat");
var log = resultP[i].get("long");
var latlng = new google.maps.LatLng(lat, log);
var marker = new google.maps.Marker({
    map: map,
    position: latlng 
});

*/





/*
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

*/

google.maps.event.addDomListener(window, 'load', initialize);


</script>


</head>

<body>

  <div class="container" style="width:100%; height:95%;">
    <div class="col-sm-4" style="background-color: #19A3FF; height:100%;">
  <!--Side Panel Div
    <div class="col-sm-4" style="background-color: #19A3FF; height:100%;">
      <div>
        <input id="address" type="textbox" value="Aurora, IL">
        <input id="submitA" type="button" value="Geocode">
      </div>
      <div>
        <input id="latlng" type="text" value="41.7605849,-88.3200715">
        <input id="submitG" type="button" value="Reverse Geocode">
      </div>
-->
<!--Information here needs to be grabbed from the database-->
        <div  style="text-align: center;  color: #FFFFFF; text-style: bold;
              text-shadow: -1px -1px 0 #000000, 1px -1px 0 #000000, -1px 1px 0 #000000, 1px 1px 0 #000000;
              font-size: 300%;">
          CommunityName
        </div>

      <div id='street-view' class="col-sm-12" style="background-color: #EEEEEE; height: 20%; width: 100%">
      </div>
      <div> &nbsp </div>



      <div class="col-sm-12" style="background-color: #EEEEEE; font-size: 100%;">
        
        <span class="col-sm-12" Style="text-align: center; font-size: 25px;"><b>501 S Calumet Ave Aurora, IL 60506</b></span>
        </br>
         </br>
        <table class="table table-striped table-hover ">
            <tr>
              <td><b>Emergency Contact:</b></td>
              <td>(630)-867-5309</td>
            </tr>
             <tr>
              <td><b>Phone Number:</b></td>
              <td>(630)-867-5309</td>
            </tr>
             <tr>
              <td><b>E-Mail:</b></td>
              <td><a href="mailto:email01@aol.com">Email01@aol.com</a></td>
            </tr>
             <tr>
              <td><b>Residents:</b></td>
            </tr>
            <tr>
              <td>Joey Calzone</td>
              <td>(630)-867-5309</td>
            </tr>
            <tr>
              <td>Carrie Calzone</td>
              <td>(630)-555-5309</td>
            </tr>
            <tr>
              <td>Penne Pasta</td>
              <td>(630)-555-5555</td>
            </tr>
        </table> 
      </div>
    </div>

  <!--Google Map Div-->
    <div class="col-sm-8" id="googleMap" style="position: relative; height:100%;" ></div>
  </div>

</body>

</html>












