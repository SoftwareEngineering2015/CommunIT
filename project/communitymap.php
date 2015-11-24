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
  <!-- Add the dropdown files -->
  <link rel="stylesheet" type="text/css" href="css/dropdown.css" />
  <script type="text/javascript" src="js/dropdown.js"></script>
  <script type="text/javascript" src="js/colorpins.js"></script>
  

  <!-- Load In Google Maps -->
  <script>
//Begin the process of changing the image color
pincolor = []; //Make pin colors global

var map;
var panorama;
var streetview = new google.maps.StreetViewService();
var iconbase = 'images/';
var myCenter = new google.maps.LatLng(41.7605556, -88.3200);

function initialize(){

  var mapProp = {
    zoom:10,
    center: myCenter,
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
 $sqlResidences = "SELECT CONCAT(first_name, ' ', last_name) as 'head_full_name', head_resident_id, address, latitude, longitude, emergency_contact, phone_one, email_address, miscinfo, pin_color FROM residences INNER JOIN head_residents ON head_residents.fk_residence_id = residences.residence_id WHERE address IS NOT NULL ORDER BY username='$login_session' DESC";
 $P->do_query($sqlResidences);
 $resultResidences = mysql_query($sqlResidences);    
 ?>

    //Gets the information of the sub residents
    <?php
    $sqlResidents = "SELECT CONCAT(first_name, ' ', last_name) as 'sub_full_name', phone_number, email_address, fk_head_id FROM sub_residents";
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
    communityname = [];
    //The limit of residents per residence
    max_residents = [];
    
    
    //RESIDENCE AND HEAD RESIDENT INFORMATION
    addresses = [];
    //holds phone1 number
    phone_one = [];
    //holds emergency numbers from database
    emergencies = [];
    //holds email addresses from database
    email_address = [];
    //holds created markers
    markers = [];
    
    
    //holds infowindow information
    infowindows = [];
    //used in the infowindow status logic
    infowindow_status = [];
    
    
    //holds parsed latlng location data
    latlng = [];
    //holds latitude and longitude location from database
    latitudes = [];
    longitudes = [];
    //creates a bounds object that is extended in the main loop
    bounds = new google.maps.LatLngBounds();

    head_resident_ids = [];
    head_full_names = [];
    miscinfo = [];
    
    //SUB RESIDENT INFORMATION
    //Holds the information of the sub_residents
    sub_full_names = [];
    sub_phone_numbers = [];
    sub_emails = [];
    //this array holds the key of the head resident at the same index as sub-residents 
    sub_head_tie = [];

    //Holds values for the dropdown menu
    divOptions = [];
    optionsDiv = [];
    options = [];
    
    //populates configuration data from database
    <?php while ($row3 = mysql_fetch_assoc($resultConfig)) { ?>
      communityname.push(<?php echo '"'. $row3['community_name'] .'"'?>);
      max_residents.push(<?php echo '"'. $row3['max_per_residence'] .'"'?>);
      <?php } ?>

    //populates subresident data from database
    <?php while ($row2 = mysql_fetch_assoc($resultResidents)) { ?>
      sub_full_names.push(<?php echo '"'. $row2['sub_full_name'] .'"'?>);
      sub_phone_numbers.push(<?php echo '"'. $row2['phone_number'] .'"'?>);
      sub_emails.push(<?php echo '"'. $row2['email_address'] .'"'?>);
      sub_head_tie.push(<?php echo '"'. $row2['fk_head_id'] .'"'?>);
      <?php } ?>

    //specify the community name on the page
    document.getElementById("community_name").innerHTML = communityname[0];
    
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

    <?php $miscString = "<div>" . preg_replace( "/\r|\n/", "</div><div>", $row['miscinfo'] ) . "</div>"; ?>
    miscinfo.push(<?php echo '"'. $miscString .'"'?>);

    pincolor.push(<?php echo '"'. $row['pin_color'] .'"'?>);

    <?php } ?>

    //this loop will create all of the markers and infowindow content for those markers, then invoke the addlistener function
    for(i in addresses){
        //extend the bounds object to fit the iterated marker
        bounds.extend(new google.maps.LatLng(latitudes[i], longitudes[i]));
      infowindow_status[i] = 0;

        //Change the color of each image through this function
        overalayColor(pincolor[i]);
        //creates a marker in the markers array
        markers.push(new google.maps.Marker({
          map: map, 
          position: latlng[i],
          title: (head_full_names[i] + "\n" + addresses[i]),
         // hue: '#19A3FF',
         //Place changed image as the icon
         icon: fullimg,
         animation: google.maps.Animation.DROP

       }));
        infowindows.push('<div style="font-size: 120%"><span style="font-size: 100%; font-weight: bold;">' + head_full_names[i] +  '<br/></span><span style="font-size: 100%; font-weight: bold;">' + addresses[i] + '</span><br/><span style="font-size: 100%; font-weight: bold; color:  #FF6666;">' +emergencies[i]+'</span></div>');

        //invoke the addlistener function
        addlistener(i);

        //start process to set up custom drop down
        //create the options that respond to click
        divOptions.push({
          gmap: map,
          name: head_full_names[i],
          title: "Residence of " + head_full_names[i],
          id: head_resident_ids[i],
          latlng: latlng[i],
          identifier: i
        });

        optionsDiv.push(new optionDiv(divOptions[i]));

        options.push(optionsDiv[i]);
        
      }

    options = options.reverse(); //Sort the array

    <?php 
    if($login_session != 'admin' && $login_session != 'guest') {
      echo "options.pop()"; // Remove the user from the list
    }
    ?>

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
    //End the process for the dropdown menu

    //Creates the ONLY infowindow with the default information
    infowindow = new google.maps.InfoWindow({
      content: '<div style="font-size: 120%"> DEFAULT... JS ERROR</div>'
    });

    if(addresses.length != 0){
      //calling the centermap function to initially center the map on the community
      centermap();
      //these next four lines are for the centering button
      var centerControlDiv = document.createElement('div');
      var centerControl = new centerbutton(centerControlDiv, map);
      centerControlDiv.index = 1;
      //puts the centering button on the map
      map.controls[google.maps.ControlPosition.TOP_CENTER].push(centerControlDiv);

      <?php 

      if ($login_session != 'admin' && $login_session != 'guest') {
        //these next four lines are for the find my house button
        echo "var FindMyHouseControlDiv = document.createElement('div');";
        echo "var FindMyHouseControl = new findmyhouse(FindMyHouseControlDiv, map);";
        echo "FindMyHouseControlDiv.index = 1;";
        //puts the centering button on the map
        echo "map.controls[google.maps.ControlPosition.TOP_CENTER].push(FindMyHouseControlDiv);";
      }

      ?>

    }
    //this is a click listener that closes the infowindow when the map is clicked. Dragging the map does NOT close the infowindow. 
    google.maps.event.addListener(map, 'click',function(){
        //this loop resets all infowindow counters to zero
        for(i in addresses)
            infowindow_status[i] = 0;
        //closes the infowindow, if open
        infowindow.close();
    });

  }
//----------------------END OF INITIALIZE FUNCTION

//---------------------------------------------JS FUNCTIONS BELOW THIS LINE-----------------------------------------------

//This function accepts an index from the iterated for loop. This function then creates a click listener based on the objects in the arrays at the passed index
function addlistener(x){ google.maps.event.addListener(markers[x], 'click',function(){
    //this loop sets all markers that are not markers[x] to 0 (not clicked)
    for(i in addresses){
        if(i!=x)
            infowindow_status[i] = 0;
    }
    //invoked if the infowindow is currently NOT open on markers[x]
    if(infowindow_status[x]==0){
        //populate the side panel with information
        populatetable(x);
        //pan the map to the lat/lng of markers[x]
        map.panTo(latlng[x]);

        streetview.getPanoramaByLocation(latlng[x], 50, function(data, status) {
            if (status == 'OK') {
              document.getElementById('street-view').style.display = 'block';
           //configure panorama
           panorama = new google.maps.StreetViewPanorama(
            document.getElementById('street-view'),
            {
              position: latlng[x],
              pov: {heading: 0, pitch: 0},
              zoom: 1,
              linksControl: false,
              addressControl: false
            });
         } else {
            document.getElementById('street-view').style.display = 'none';
          }
        });
        
        //sets the content of the infowindow
        infowindow.setContent(infowindows[x]);
        //opens the infowindow on markers[x]
        infowindow.open(map,markers[x]);
        //sets the infowindow status to open on markers[x]
        infowindow_status[x] = 1;
    }
    //invoked if the infowindow IS currently open on markers[x]
    else if(infowindow_status[x]==1){
        //closes the marker
        infowindow.close();
        //sets the status to closed
        infowindow_status[x] = 0;
    }
    
});}

//this function centers the map on the community based on the bounds object
function centermap(){
  map.fitBounds(bounds);
}
    //this function is called in the marker event listener, and it populates the information pane table with the correct information
    function populatetable(x){
//----------------------------FIRST HALF OF FUNCTION ADDS TOP HALF OF THE SIDE PANEL-----------------------------------
//these call various ids in the information pane and add html to them

document.getElementById("head_resident_panel").innerHTML = "<b>"+head_full_names[x]+"</b>";
document.getElementById("address_panel").innerHTML = addresses[x];
document.getElementById("em_phone_panel").innerHTML = "<td>Emergency Contact:</td><td style='text-align:center;'>" + emergencies[x] + "</td>";

if(phone_one[x]==""){
  document.getElementById("phone_panel").innerHTML = "<td style='font-weight: bold;'>Phone Number:</td><td style='text-align: center; color:#B8B8B8;'>Unavailable</td>";
}else{
  document.getElementById("phone_panel").innerHTML = "<td style='font-weight: bold;'>Phone Number:</td><td style='text-align: center;'>" + phone_one[x] + "</td>";
}
if(email_address[x]==""){
  document.getElementById("email_panel").innerHTML = "<td style='font-weight: bold;'>E-mail:</td><td style='color:#B8B8B8;text-align: center;'>Unavailable</td>";
}else{
  document.getElementById("email_panel").innerHTML = "<td style='font-weight: bold;'>E-mail:</td><td style='text-align: center;'>" + "<a href='mailto:" + email_address[x] + "'>" + email_address[x] + "</a></td>";
}
if(miscinfo[x]=="<div></div>"){
  document.getElementById("misc_panel").innerHTML = "";
}else{
  document.getElementById("misc_panel").innerHTML = "<tr><th style='font-weight: bold;  text-align: center; text-align:Center; font-size: 110%;'>Misc</th></tr> <tr><td style='text-indent: 25%;'>" +miscinfo[x]+  "</td></tr>";
}

    //---------------------THIS SECOND HALF OF THE FUNCTION ADDS THE BOTTOM HALF OF THE SIDE PANEL-------------------------
    //variable setup for the loop
    var i = 0; var residentsSidePanelText = "";
    //This IF block adds the Head Resident to the residents table
    var headname;
    var headphone;
    var heademail;
    headname = "<td>" + head_full_names[x] + "</td>";
    headphone = "<td>" + phone_one[x] + "</td>";
    heademail = "<td>" + email_address[x] + "</td>";

    if(head_full_names[x]==""){
      headname = "<td style='color:#B8B8B8;'>Unavailable</td>";
    }
    if(phone_one[x]==""){
      headphone = "<td style='color:#B8B8B8;'>Unavailable</td>";
    }
    if(email_address[x]==""){
      heademail = "<td style='color:#B8B8B8;'>Unavailable</td>";
    }

      residentsSidePanelText = "<tr>" + headname + headphone + heademail + "</tr>";
    
    //this loop iterates through all sub-residents and puts the correct sub-residents in a table with the id of sub_residents
    //this loop is dependent on the fact that we will not allow any head residents to ever have more than the max sub residents, otherwise this loop will potentially not function properly
 // if(($_SESSION['login_user']) != "guest"){
    var subname;
    var subphone;
    var subemail;
    while(i<=(sub_head_tie.length)){
      if(sub_head_tie[i] == head_resident_ids[x]){
        subname = "<td>" + sub_full_names[i] + "</td>";
        subphone = "<td>" + sub_phone_numbers[i] + "</td>";
        subemail = "<td>" + sub_emails[i] + "</td>";
        if(sub_phone_numbers[i]==""){
          subphone = "<td style='color:#B8B8B8;'>Unavailable</td>"
        }
        if(sub_emails[i]==""){
          subemail = "<td style='color:#B8B8B8;'>Unavailable</td>"
        }
        residentsSidePanelText+= "<tr>"+subname+subphone+subemail+"</tr>";
      }
      i++;
    }
  //}
    //populates the sub_residents table with the looped information
    document.getElementById("sub_residents").innerHTML = "<tr><th style='font-size: 125%;text-align: center;'>Residents </th><th style='font-size: 125%;text-align: center;'>Phone</th><th style='font-size: 125%;text-align: center;'>E-mail</th></tr>"+residentsSidePanelText;
  
  }
//-----------------------------------------END OF POPULATETABLE FUNCTION------------------------------------------
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
    controlText.innerHTML = 'Center on Your Community';
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
    controlUI.title = 'Click to find your house in your community';
    controlDiv.appendChild(controlUI);

    // Set CSS for the control interior.
    var controlText = document.createElement('div');
    controlText.style.color = 'rgb(250,250,250)';
    controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
    controlText.style.fontSize = '16px';
    controlText.style.lineHeight = '38px';
    controlText.style.paddingLeft = '5px';
    controlText.style.paddingRight = '5px';
    controlText.innerHTML = 'Find My House';
    controlUI.appendChild(controlText);

    // Setup the click event listeners: calls the centermap function
    controlUI.addEventListener('click', function() {

        //sets center position
        map.panTo(latlng[0]);
        //sets map zoom (zoom amount is up for debate)
        map.setZoom(17);
      });
  }

  function optionDiv(options){
    var control = document.createElement('DIV');
    control.className = "dropDownItemDiv";
    control.title = options.title;
    control.id = options.id;
    control.innerHTML = options.name;
    control.action = function() { 
      map.panTo(options.latlng);
      infowindow.setContent(infowindows[options.identifier]);
      infowindow.open(map,markers[options.identifier]); 
      populatetable(options.identifier);
      streetview.getPanoramaByLocation(options.latlng, 50, function(data, status) {
        if (status == 'OK') {
          document.getElementById('street-view').style.display = 'block';
           //configure panorama
           panorama = new google.maps.StreetViewPanorama(
            document.getElementById('street-view'),
            {
              position: options.latlng,
              pov: {heading: 0, pitch: 0},
              zoom: 1,
              linksControl: false,
              addressControl: false
            });
         } else {
          document.getElementById('street-view').style.display = 'none';
        }
      });
    };
    google.maps.event.addDomListener(control,'click', control.action);
    return control;
  }
//Turns the map on.
google.maps.event.addDomListener(window, 'load', initialize);

</script>

</head>

<body>

  <div class="container" style="width:100%; height:95%;">
    <div class="col-sm-4" style="background-color: #19A3FF; height:100%;">
      <div id='community_name' style="text-align: center; color: #FFFFFF; text-style: bold;
      text-shadow: -1px -1px 0 #000000, 1px -1px 0 #000000, -1px 1px 0 #000000, 1px 1px 0 #000000;
      font-size: 300%; font-weight: bold;">
    </div>

    <div id='street-view' class="col-sm-12" style="background-color: #EEEEEE; height: 20%; width: 100%; text-align: center; font-size: 25px; font-weight: bold;">
     Select a Residence
   </div>
   <div> &nbsp </div>

   <div class="col-sm-12" style="background-color: #EEEEEE; font-size: 100%;">
    <b><div class="col-sm-12" Style="text-align: center; font-size: 20px;" id="head_resident_panel"></div></b>
    <div class="col-sm-12" Style="text-align: center; font-size: 15px; font-weight: bold;" id="address_panel"></div>
  </br>
</br>
<table class="table table-striped table-hover">
  <tr id='em_phone_panel' style="color: #FF6666; font-weight: bold;">
  </tr>
  <tr id='phone_panel'>
  </tr>
  <tr id='email_panel'>
  </tr>

  <table id='misc_panel'class="table table-striped table-hover">
  
  </table>
  
  <table id='sub_residents' class="table table-striped table-hover" style="text-align: center;">
  </table>
</table> 
</div>
</div>

<!--Google Map Div-->
<div class="col-sm-8 affix" id="googleMap" style="height:100%;" ></div>
</div>

</body>

</html>