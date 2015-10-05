 <!DOCTYPE html>
<html>
<head>
<!-- Google API KEY for accessing a broader spectrum of Google APIs-->
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
var iconbase = 'images/';
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
//Gets the information of a residence and it's head resident 
      $sqlResidences = "SELECT CONCAT(first_name, ' ', last_name) as 'head_full_name', head_resident_id, address, latitude, longitude, emergency_contact, phone_one, email_address FROM residences INNER JOIN head_residents ON head_residents.fk_residence_id = residences.residence_id WHERE address IS NOT NULL";
      $P->do_query($sqlResidences);
      $resultResidences = mysql_query($sqlResidences);    
    ?>
//Gets the information of the sub residents
  <?php $sqlResidents = "SELECT CONCAT(first_name, ' ', last_name) as 'sub_full_name', phone_number FROM sub_residents";
    $P->do_query($sqlResidents);
    $resultResidents = mysql_query($sqlResidents);  
  ?>

  <?php $sqlConfig = "SELECT community_name, max_per_residence FROM configuration";
    $P->do_query($sqlConfig);
    $resultConfig = mysql_query($sqlConfig);  
  ?>

    //Holds the community's name
    var communityname = [];
    //The limit of residents per residence
    var max_residents = [];
    //holds addresses from database
    var addresses = [];
    //holds phone1 number
    var phone_one = [];
    //holds emergency numbers from database
    var emergencies = [];
    //holds email addresses from database
    var email_address = [];

    //Holds information of the head_residents
    var head_resident_ids = [];
    var head_full_names = [];

    //Holds the information of the sub_residents
    var sub_full_names = [];
    var sub_phone_numbers = [];

    //holds created markers
    var markers = [];
    //holds created infowindows
    var infowindows = [];
    //holds parsed latlng location data
    var latlng = [];
    //holds latitude and longitude location from database
    var latitudes = [];
    var longitudes = [];

  <?php while ($row3 = mysql_fetch_assoc($resultConfig)) { ?>
    communityname.push(<?php echo '"'. $row3['community_name'] .'"'?>);
    max_residents.push(<?php echo '"'. $row3['max_per_residence'] .'"'?>);
  <?php } ?>

    document.getElementById("community_name").innerHTML = "<b>" + communityname[0]+ "</b>";

  <?php while ($row2 = mysql_fetch_assoc($resultResidents)) { ?>
    sub_full_names.push(<?php echo '"'. $row2['sub_full_name'] .'"'?>);
    sub_phone_numbers.push(<?php echo '"'. $row2['phone_number'] .'"'?>);
  <?php } ?>
    
    //pulls in from database and populates arrays 
    <?php while ($row = mysql_fetch_assoc($resultResidences)) { ?>
    addresses.push(<?php echo '"'. $row['address'] .'"'?>);
    emergencies.push(<?php echo '"'. $row['emergency_contact'] .'"'?>);
    head_resident_ids.push(<?php echo '"'. $row['head_resident_id'] .'"'?>);
    head_full_names.push(<?php echo '"'. $row['head_full_name'] .'"'?>);
    //populates the latlng array by creating an object based on the queryd data
    latitudes.push(<?php echo '"'. $row['latitude'] .'"'?>);
    longitudes.push(<?php echo '"'. $row['longitude'] .'"'?>); 
    latlng.push(new google.maps.LatLng((<?php echo '"'. $row['latitude'] .'"'?>), (<?php echo '"'. $row['longitude'] .'"'?>)));
    phone_one.push(<?php echo '"'. $row['phone_one'] .'"'?>);
    email_address.push(<?php echo '"'. $row['email_address'] .'"'?>);

    <?php } ?>

    for(i in addresses) {
        //these next two method invocations should be moved outside of the loop when we get to that point (maybe)
        //sets initial position
             map.setCenter(latlng[i]);
        //sets initial zoom
             map.setZoom(18);
        //creates a marker in the markers array
        markers.push(new google.maps.Marker({
            map: map, 
            position: latlng[i],
            title: addresses[i],
            icon: iconbase + 'house_pin.png',
            animation: google.maps.Animation.DROP
        }));
        //Creates an info Window in the infowindows array
        infowindows.push(new google.maps.InfoWindow({
           // content: 'Address: ' + addresses[i] + '<br/>Emergency Contact: ' + emergencies[i] + '<br/>Latitude: ' + latitudes[i] + '<br/>Longitude: ' + longitudes[i] + '<br/>Lat/Lng: ' + latlng[i]
            content: '<div style="font-size: 120%"> <b style="font-size: 100%">Address: </b>' + addresses[i] + '<br/><b style="font-size: 100%">Emergency Contact: </b> <span style="color: red;">' +emergencies[i]+'</span></div>'
        }));


        //invoke the addlistener function
        addlistener(i);
    }
    //This function accepts an index from the iterated for loop. This function then creates a click listener based on the objects in the arrays at the passed index
    function addlistener(x){ google.maps.event.addListener(markers[x], 'click',function() { 
        infowindows[x].open(map,markers[x]); 
        populatetable(x);
        markers[x].setAnimation(google.maps.Animation.BOUNCE);
        panorama = new google.maps.StreetViewPanorama(
              document.getElementById('street-view'),
              {
                position: latlng[x],
                pov: {heading: 0, pitch: 0},
                zoom: 1,
                linksControl: false,
                addressControl: false
              });
        google.maps.event.addListener(markers[x],'mousedown',function() {
          infowindows[x].close(map,markers[x]); 
          markers[x].setAnimation(google.maps.Animation.NULL);
        });
        

    });}
    //this function is called in the marker event listener, and it populates the left pane table with the correct information
    function populatetable(x){
        document.getElementById("address_panel").innerHTML = "<b>"+addresses[x]+"</b>";
        document.getElementById("em_phone_panel").innerHTML = "<td><b>Emergency Contact:</b></td> <td>" + emergencies[x] + "</td>";
        document.getElementById("phone_panel").innerHTML = "<td><b>Phone Number:</b></td> <td>" + phone_one[x] + "</td>";
        document.getElementById("email_panel").innerHTML = "<td><b>E-mail:</b></td> <td>" + "<a href='mailto:"+email_address[x]+"'>"+email_address[x]+"</a> </td>";
        document.getElementById("residenttitle").innerHTML =  "<b>Residents:</b>";
        document.getElementById("head_resident").innerHTML = "<td>" + head_full_names[x] + "</td> <td>" + phone_one[x] + "</td>";

      //Can't get it to show all of them, only the last one. This seems to be a reacurring problem for me XD
        for (j in sub_full_names) {  
        //  document.getElementById("sub_residents").innerHTML = "<td>" +sub_full_names[j] + "</td> <td>" +sub_phone_numbers[j]+ "</td>";
       // document.getElementById("sub_phone").innerHTML = sub_phone_numbers[j];
       // document.getElementById("sub_residents_"+j+"").innerHTML = "<td>"+sub_full_names[j]+"</td><td>"+sub_phone_numbers[j]+"</td>";
        
      }
    }
}
//----------------------END OF INITIALIZE FUNCTION

//Turns the map on.
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
        <div id='community_name' style="text-align: center;  color: #FFFFFF; text-style: bold;
              text-shadow: -1px -1px 0 #000000, 1px -1px 0 #000000, -1px 1px 0 #000000, 1px 1px 0 #000000;
              font-size: 300%;">
        </div>

      <div id='street-view' class="col-sm-12" style="background-color: #EEEEEE; height: 20%; width: 100%; text-align: center; font-size: 25px;">
       Select a Residence
      </div>
      <div> &nbsp </div>

      <div class="col-sm-12" style="background-color: #EEEEEE; font-size: 100%;">
        
        <span class="col-sm-12" Style="text-align: center; font-size: 25px;" id="address_panel">Select a Residence</span>
        </br>
         </br>
        <table class="table table-striped table-hover ">
            <tr id='em_phone_panel'>
            </tr>
             <tr id='phone_panel'>
            </tr>
             <tr id='email_panel'>
            </tr>
            <tr>
            <th id='residenttitle' style='font-size: 125%;'> </th>
            </tr>
            <tr id='head_resident'>
            </tr>
            <!-- <tr id='sub_residents'>-->
            <script>
            /*
            var i = 1;
            window.alert("cows");
            for (max_residents[0]){
              window.alert("cows");
              document.write("<tr id='sub_residents_'"+i+"> cows</tr>");
              i++;
            }
            */
            </script>
            <!--</tr>-->
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