//This function accepts an index from the iterated for loop. This function then creates a click listener based on the objects in the arrays at the passed index
function addlistener(x, currentuser){ google.maps.event.addListener(markers[x], 'click',function(){
    //this loop sets all markers that are not markers[x] to 0 (not clicked)
    for(i in addresses){
        if(i!=x)
            infowindow_status[i] = 0;
    }
    //invoked if the infowindow is currently NOT open on markers[x]
    if(infowindow_status[x]==0){
        //populate the side panel with information
        populatetable(x, currentuser);
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

function centermap(){
    map.fitBounds(bounds);
}

//this function is called in the marker event listener, and it populates the information pane table with the correct information
function populatetable(x, currentuser){
    //----------------------------FIRST HALF OF FUNCTION ADDS TOP HALF OF THE SIDE PANEL-----------------------------------
    //these call various ids in the information pane and add html to them

    document.getElementById("head_resident_panel").innerHTML = "<b>"+head_full_names[x]+"</b>";
    document.getElementById("address_panel").innerHTML = addresses[x];
    document.getElementById("em_phone_panel").innerHTML = "<td>Contact Phone Number:</td><td style='text-align:center;'>" + emergencies[x] + "</td>";

    if(phone_one[x]==""){
        //document.getElementById("phone_panel").innerHTML = "<td style='font-weight: bold;'>Additional Phone Number:</td><td style='text-align: center; color:#B8B8B8;'>Unavailable</td>";
        document.getElementById("phone_panel").innerHTML = "";
    
    }else{
        document.getElementById("phone_panel").innerHTML = "<td style='font-weight: bold;'>Phone Number:</td><td style='text-align: center;'>" + phone_one[x] + "</td>";
    }
    if(email_address[x]==""){
        //document.getElementById("email_panel").innerHTML = "<td style='font-weight: bold;'>E-mail:</td><td style='color:#B8B8B8;text-align: center;'>Unavailable</td>";
        document.getElementById("email_panel").innerHTML = "";
    }else{
        document.getElementById("email_panel").innerHTML = "<td style='font-weight: bold;'>E-mail:</td><td style='text-align: center;'>" + "<a href='mailto:" + email_address[x] + "'>" + email_address[x] + "</a></td>";
    }
    if(miscinfo[x]=="<div></div>"){
        document.getElementById("misc_panel").innerHTML = "";
    }else{
        document.getElementById("misc_panel").innerHTML = "<tr><th style='font-weight: bold; text-align:Center; font-size: 110%;'>Misc</th></tr> <tr><td style='text-align:Left; text-indent: 10%;'>" +miscinfo[x]+  "</td></tr>";
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
    heademail = "<td><a href='mailto:" + email_address[x] + "'>" + email_address[x] + "</a></td>";

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
if( currentuser != "guest"){
    //this loop iterates through all sub-residents and puts the correct sub-residents in a table with the id of sub_residents
    //this loop is dependent on the fact that we will not allow any head residents to ever have more than the max sub residents, otherwise this loop will potentially not function properly
    //if(($_SESSION['login_user']) != "guest"){
    var subname;
    var subphone;
    var subemail;
    while(i<=(sub_head_tie.length)){
        if(sub_head_tie[i] == head_resident_ids[x]){
            subname = "<td>" + sub_full_names[i] + "</td>";
            subphone = "<td>" + sub_phone_numbers[i] + "</td>";
            subemail = "<td><a href='mailto:" + sub_emails[i] + "'>" + sub_emails[i] + "</a></td>";
            
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
}
  //}
    //populates the sub_residents table with the looped information
    document.getElementById("sub_residents").innerHTML = "<tr><th style='font-size: 125%;text-align: center;'>Residents </th><th style='font-size: 125%;text-align: center;'>Phone</th><th style='font-size: 125%;text-align: center;'>E-mail</th></tr>"+residentsSidePanelText;
}
//-----------------------------------------END OF POPULATETABLE FUNCTION------------------------------------------

function centerbutton(controlDiv, map){
    // Set CSS for the control border.
    var controlUI = document.createElement('div');
    controlUI.style.backgroundColor = '#3399FF';
    controlUI.style.border = '2px solid #00000';
    controlUI.style.borderRadius = '3px';
    controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
    controlUI.style.cursor = 'pointer';
    controlUI.style.marginBottom = '22px';
    controlUI.style.textAlign = 'center';
    controlUI.style.marginRight = '15px';
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
    controlUI.style.textAlign = 'center';
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

//this function styles and sets up the button
function findeditinghouse(controlDiv, map) {
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
          map.panTo(new google.maps.LatLng(houseLatitude, houseLongitude));
          //sets map zoom (zoom amount is up for debate)
          map.setZoom(18);
        }
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
function optionDiv_2(options){
    
    var control = document.createElement('DIV');
    control.className = "dropDownItemDiv";
    control.title = options.title;
    control.id = options.id;
    control.innerHTML = options.name;
    control.action = function() { 
        map.panTo(options.latlng);
    };
    google.maps.event.addDomListener(control,'click', control.action);
    return control;
}

function geocodeAddress(geocoder, resultsMap){
    clearMarkers();
    var address = document.getElementById('address').value;
    geocoder.geocode({'address': address}, function(results, status){
        if (status === google.maps.GeocoderStatus.OK){
            resultsMap.panTo(results[0].geometry.location);
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
            google.maps.event.addListener(marker,'click',function(){
            map.setZoom(18);
            map.panTo(marker.getPosition());
            });
        }
        else{
            alert('Geocode was not successful for the following reason: ' + status);
        }
        google.maps.event.addListener(marker, 'dragend', function (event) {
            document.getElementById("latitude").value = this.getPosition().lat();
            document.getElementById("longitude").value = this.getPosition().lng();
        });
    });
}

// Sets the map on all markers in the array.
function setMapOnAll(map){
    for (var i = 0; i < marker_new.length; i++){
        marker_new[i].setMap(map);
    }
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers(){
    setMapOnAll(null);
}

function show_confirm(){
    var latitude = document.getElementById("latitude").value;
    var longitude = document.getElementById("longitude").value;
    if( latitude == "" || longitude =="" ){
        alert("Latitude and Longitude need a value.");
        return false;
    }
    else{
        // shows the modal on button press
        $('#confirm_modal').modal('show');
        document.getElementById("submit_residence_name").innerHTML = document.getElementById("residence_name").value;
        document.getElementById("submit_address").innerHTML = document.getElementById("address").value;
        document.getElementById("submit_latitude").innerHTML = document.getElementById("latitude").value;
        document.getElementById("submit_longitude").innerHTML = document.getElementById("longitude").value;
    }
}