 <!DOCTYPE html>
<html>
<head>
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

function initialize(){

    var mapProp = {
      center:myCenter,
      zoom:10,
      mapTypeId:google.maps.MapTypeId.ROADMAP
    };
    //this creates our map
    map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
    var geocoder = new google.maps.Geocoder();
/*
This puts a marker based on the string in submitG
  document.getElementById('submitG').addEventListener('click', function() {
    geocodeLatLng(geocoder, map, infowindow);
  });
*/
  <?php
// Create connection
      $P = new manage_db;
      $P->connect_db();
// Check connection
      $sqlResidences = "SELECT address, latitude, longitude, emergency_contact, phone_one, email_address FROM residences INNER JOIN head_residents ON head_residents.fk_residence_id = residences.residence_id WHERE address IS NOT NULL";
      $P->do_query($sqlResidences);
      $result = mysql_query($sqlResidences);     
    ?>
    
    //holds addresses from database
    var addresses = [];
    //holds phone1 number
    var phone_one = [];
    //holds emergency numbers from database
    var emergencies = [];
    //holds email addresses from database
    var email_address = [];
    //holds created markers
    var markers = [];
    //holds created infowindows
    var infowindows = [];
    //holds parsed latlng location data
    var latlng = [];
    
    //pulls in from database and populates arrays 
    <?php while ($row = mysql_fetch_assoc($result)) { ?>
    addresses.push(<?php echo '"'. $row['address'] .'"'?>);
    emergencies.push(<?php echo '"'. $row['emergency_contact'] .'"'?>);
    //populates the latlng array by creating an object based on the queryd data
    latlng.push(new google.maps.LatLng((<?php echo '"'. $row['latitude'] .'"'?>), (<?php echo '"'. $row['longitude'] .'"'?>)));
    phone_one.push(<?php echo '"'. $row['phone_one'] .'"'?>);
    email_address.push(<?php echo '"'. $row['email_address'] .'"'?>);
    <?php } ?>

    for(i in addresses) {
        //these next two method invocations should be moved outside of the loop when we get to that point (maybe)
        //sets initial position
             map.setCenter(latlng[i]);
        //sets initial zoom
             map.setZoom(17);
        //creates a marker in the markers array
        markers.push(new google.maps.Marker({
            map: map, 
            position: latlng[i],
            title: addresses[i]//,
            //animation: BOUNCE
        }));
        //Creates an info Window in the infowindows array
        infowindows.push(new google.maps.InfoWindow({
            content: 'Address: ' + addresses[i] + '<br/>Emergency Contact: ' + emergencies[i] + '<br/>Lat/Lng: ' + latlng[i]
        }));
        //invoke the addlistener function
        addlistener(i);
    }
    //This function accepts an index from the iterated for loop. This function then creates a click listener based on the objects in the arrays at the passed index
    function addlistener(x){ google.maps.event.addListener(markers[x], 'click',function() { 
        infowindows[x].open(map,markers[x]); populatetable(x);panorama = new google.maps.StreetViewPanorama(
              document.getElementById('street-view'),
              {
                position: latlng[x],
                pov: {heading: 0, pitch: 0},
                zoom: 1
              });
    });}
    //this function is called in the marker event listener, and it populates the left pane table with the correct information
    function populatetable(x){
        document.getElementById("address_panel").innerHTML = "<b>"+addresses[x]+"</b>";
        document.getElementById("em_phone_panel").innerHTML = emergencies[x];
        document.getElementById("phone_panel").innerHTML = phone_one[x];
        document.getElementById("email_panel").innerHTML = email_address[x];
    }
}//----------------------END OF INITIALIZE FUNCTION

//what in the world does this do?
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
        <div  style="text-align: center;  color: #FFFFFF; text-style: bold;
              text-shadow: -1px -1px 0 #000000, 1px -1px 0 #000000, -1px 1px 0 #000000, 1px 1px 0 #000000;
              font-size: 300%;">
          CommunityName
        </div>

      <div id='street-view' class="col-sm-12" style="background-color: #EEEEEE; height: 20%; width: 100%">
      </div>
      <div> &nbsp </div>

      <div class="col-sm-12" style="background-color: #EEEEEE; font-size: 100%;">
        
        <span class="col-sm-12" Style="text-align: center; font-size: 25px;" id="address_panel">NULL</span>
        </br>
         </br>
        <table class="table table-striped table-hover ">
            <tr>
              <td><b>Emergency Contact:</b></td>
              <td id='em_phone_panel'>NULL</td>
            </tr>
             <tr>
              <td><b>Phone Number 1:</b></td>
              <td id='phone_panel'>NULL</td>
            </tr>
             <tr>
              <td><b>E-Mail:</b></td>
              <td id='email_panel'><!--a href="mailto:email01@aol.com">-->NULL<!--</a>--></td>
            </tr>
            <!-- Tried to get this to center but could not. taking it out until we can figure out a way to make it look better
             <tr>
              <th style="text-align: right; font-size: 125%;">RESIDENTS:</th>
            </tr>-->
            <tr>
              <td>RESIDENT 1</td>
              <td>RES1CELL</td>
            </tr>
            <tr>
              <td>RESIDENT 2</td>
              <td>RES2CELL</td>
            </tr>
            <tr>
              <td>RESIDENT 3</td>
              <td>RES3CELL</td>
            </tr>
        </table> 
      </div>
    </div>

  <!--Google Map Div-->
    <div class="col-sm-8" id="googleMap" style="position: relative; height:100%;" ></div>
  </div>

</body>

</html>

<!--
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


addresses.push(<?php echo '"' . $row['address'].', '.$row['latitude'].', '.$row['longitude'] . '"'?>);



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
          //put address and emergency numbers here?
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



// $sqlResidents = "";
     // $P->do_query($sqlResidents);
     // $result = mysql_query($sqlResidents); 