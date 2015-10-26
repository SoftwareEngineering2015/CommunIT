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

  $P = new manage_db;
  $P->connect_db();
  // Check connection
  $sql_head_residents = "SELECT head_resident_id, first_name, last_name, emergency_contact, phone_one, email_address FROM head_residents INNER JOIN residences ON head_residents.fk_residence_id = residences.residence_id WHERE username='$login_session'";
  $P->do_query($sql_head_residents);
  $head_residents_result = mysql_query($sql_head_residents); 

  $head_residents = array(); //Holds head residents' information
  
  //Checks to see if User has a head resident set, if not, redirect to editprofile.php. (If Admin or Guest, Ignore.)
  if ( (mysql_num_rows($head_residents_result)==0) && ($login_session != "admin") && ($login_session != "guest") ) {
    header("location: editprofile.php");
    exit;
  } 


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
    <?php
    $sqlResidents = "SELECT CONCAT(first_name, ' ', last_name) as 'sub_full_name', phone_number, fk_head_id FROM sub_residents";
    $P->do_query($sqlResidents);
    $resultResidents = mysql_query($sqlResidents);  
    ?>
    
    //Gets the configuration information
    <?php $sqlConfig = "SELECT community_name, max_per_residence FROM configuration";
    $P->do_query($sqlConfig);
    $resultConfig = mysql_query($sqlConfig);  
    ?>
    //These arrays hold information from the database. In general the different 'groups' of information are tied together based on index
    //CONFIGURATION INFORMATION
    //Holds the community's name
    var communityname = [];
    //The limit of residents per residence
    var max_residents = [];
    
    //RESIDENCE AND HEAD RESIDENT INFORMATION
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
    //holds latitude and longitude location from database
    var latitudes = [];
    var longitudes = []; 
    var head_resident_ids = [];
    var head_full_names = [];
    
    //SUB RESIDENT INFORMATION
    //Holds the information of the sub_residents
    var sub_full_names = [];
    var sub_phone_numbers = [];
    //this array holds the key of the head resident at the same index as sub-residents 
    var sub_head_tie = [];
    
    //populates configuration data from database
    <?php while ($row3 = mysql_fetch_assoc($resultConfig)) { ?>
      communityname.push(<?php echo '"'. $row3['community_name'] .'"'?>);
      max_residents.push(<?php echo '"'. $row3['max_per_residence'] .'"'?>);
      <?php } ?>

    //populates subresident data from database
    <?php while ($row2 = mysql_fetch_assoc($resultResidents)) { ?>
      sub_full_names.push(<?php echo '"'. $row2['sub_full_name'] .'"'?>);
      sub_phone_numbers.push(<?php echo '"'. $row2['phone_number'] .'"'?>);
      sub_head_tie.push(<?php echo '"'. $row2['fk_head_id'] .'"'?>);
      <?php } ?>

    //specify the community name on the page
    document.getElementById("community_name").innerHTML = "<b>" + communityname[0]+ "</b>";
    
    //populates residence data from database
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
    //this function is called in the marker event listener, and it populates the information pane table with the correct information
    function populatetable(x){
        //these call various ids in the information pane and add html to them
        document.getElementById("address_panel").innerHTML = "<b>"+addresses[x]+"</b>";
        document.getElementById("em_phone_panel").innerHTML = "<td><b>Emergency Contact:</b></td> <td>" + emergencies[x] + "</td>";
        document.getElementById("phone_panel").innerHTML = "<td><b>Phone Number:</b></td> <td>" + phone_one[x] + "</td>";
        document.getElementById("email_panel").innerHTML = "<td><b>E-mail:</b></td> <td>" + "<a href='mailto:"+email_address[x]+"'>"+email_address[x]+"</a> </td>";
        //document.getElementById("head_resident_header").innerHTML =  "Head Resident:";
        //document.getElementById("head_resident").innerHTML = "<td>" + head_full_names[x] + "</td> <td>" + phone_one[x] + "</td>";
        // document.getElementById("sub_resident_header").innerHTML =  "Sub-Residents:";
        //variable setup for the loop
        var i = 0, residentsSidePanelText = "";
        var counter = 0;
        //Adding Head Resident to residents table
        residentsSidePanelText = "<td>" + head_full_names[x] + "</td> <td>" + phone_one[x] + "</td>";
        //this loop iterates through all sub-residents and puts the correct sub-residents in a table with the id of sub_residents
        //this loop is dependent on the fact that we will not allow any head residents to ever have more than the max sub residents, otherwise this loop will potentially not function properly
        while(i<=(sub_head_tie.length)){
            //window.alert("i is : "+i+" sub_head_tie length is: "+(sub_head_tie.length)+ "max_residents is :  "+max_residents);

            if(sub_head_tie[i] == head_resident_ids[x] && counter < max_residents[0]){

              residentsSidePanelText+= "<tr><td>"+sub_full_names[i]+"</td><td>"+sub_phone_numbers[i]+"</td></tr>";
              counter++;
            }
            i++;
          }
        //populates the sub_residents table with the looped information
        document.getElementById("sub_residents").innerHTML = "<tr><th style='font-size: 125%;'>Residents </th><th></th></tr>"+residentsSidePanelText;
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
<table class="table table-striped table-hover">
  <tr id='em_phone_panel'>
  </tr>
  <tr id='phone_panel'>
  </tr>
  <tr id='email_panel'>
  </tr>
  <table id='sub_residents' class="table table-striped table-hover">
  </table>
</table> 
</div>
</div>

<!--Google Map Div-->
<div class="col-sm-8 affix" id="googleMap" style="height:100%;" ></div>
</div>

</body>

</html>