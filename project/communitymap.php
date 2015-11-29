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
<script type="text/javascript" src="js/dropdown.js"></script>
<script type="text/javascript" src="js/colorpins.js"></script>
<script type="text/javascript" src="js/map.js"></script>

</body>

</html>