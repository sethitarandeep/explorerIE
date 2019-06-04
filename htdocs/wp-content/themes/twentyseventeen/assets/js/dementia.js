//var slideIndex = 1;
/*showDivs(slideIndex);

function plusDivs(n) {
    showDivs(slideIndex += n);
}

function currentDiv(n) {
    showDivs(slideIndex = n);
}

(function showDivs(n) {
    var i;
    var x = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("demo");
    if (n > x.length) {slideIndex = 1}
    if (n < 1) {slideIndex = x.length}
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" w3-white", "");
    }
    x[slideIndex-1].style.display = "block";
    dots[slideIndex-1].className += " w3-white";
}*/

// Accordion
/*
function myAccFunc() {
    var x = document.getElementById("demoAcc");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else {
        x.className = x.className.replace(" w3-show", "");
    }
}*/

// Click on the "issues" link on page load to open the accordion for demo purposes
//document.getElementById("myBtn").click();


// Open and close sidebar
function w3_open() {
    document.getElementById("mySidebar").style.display = "block";
    document.getElementById("myOverlay").style.display = "block";
}

function w3_close() {
    document.getElementById("mySidebar").style.display = "none";
    document.getElementById("myOverlay").style.display = "none";
}

var map;
      var markerPos;
      var directionsDisplay;
      var directionsService;
      var mycoords;
      function initAutocomplete() {
          directionsService = new google.maps.DirectionsService;
          map = new google.maps.Map(document.getElementById('map'), {
              center: {lat: -37.8136, lng: 144.9631},
              zoom: 12,
              mapTypeId: 'roadmap',
              gestureHandling: 'greedy'
          });

           directionsDisplay = new google.maps.DirectionsRenderer({map: map});
          var input =  document.getElementById('pac-input');
          var searchBox = new google.maps.places.SearchBox(input);
          map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

          // Bias the SearchBox results towards current map's viewport.
          map.addListener('bounds_changed', function() {
              searchBox.setBounds(map.getBounds());
          });
          var markers = [];
          // Listen for the event fired when the user selects a prediction and retrieve
          // more details for that place.
          searchBox.addListener('places_changed', function() {
              var places = searchBox.getPlaces();

              if (places.length == 0) {
                  return;
              }

              // Clear out the old markers.
              markers.forEach(function(marker) {
                  marker.setMap(null);
              });
              markers = [];

              // For each place, get the icon, name and location.
              var bounds = new google.maps.LatLngBounds();
              places.forEach(function(place) {


                  if (!place.geometry) {
                      console.log("Returned place contains no geometry");
                      return;
                  }
                  var latitude = place.geometry.location.lat();
                  var longitude = place.geometry.location.lng();
                  mycoords = place.geometry.location;

                  var icon = {
                      url: place.icon,
                      size: new google.maps.Size(71, 71),
                      origin: new google.maps.Point(0, 0),
                      anchor: new google.maps.Point(17, 34),
                      scaledSize: new google.maps.Size(25, 25)
                  };
                  // Create a marker for each place.
                  markers.push(new google.maps.Marker({

                      map:map,
                      position:place.geometry.location,
                      title: place.name
                  }));

                  if (place.geometry.viewport) {
                      // Only geocodes have viewport.
                      bounds.union(place.geometry.viewport);
                  } else {
                      bounds.extend(place.geometry.location);
                  }
                  addNearByParks(place.geometry.location);
                  addNearByHospitals(place.geometry.location);
              });
              map.fitBounds(bounds);

              zoomChangeBoundsListener =
                  google.maps.event.addListenerOnce(map, 'bounds_changed', function(event) {
                      if (this.getZoom()){
                          this.setZoom(15);
                      }
                  });
              setTimeout(function(){google.maps.event.removeListener(zoomChangeBoundsListener)}, 5000);
          });
		       directionsDisplay.addListener('directions_changed', function() {
              computeTotalDistance(directionsDisplay.getDirections());
          });


      }
      function addNearByParks(coords){

          var service = new google.maps.places.PlacesService(map);
          var request = {
              location: coords,
              //Defines the distance (in meters)
              radius: 3000,
              types: ['park']
          };
          service.nearbySearch(request, callback2);

      }

      function addNearByHospitals(coords){

          var service = new google.maps.places.PlacesService(map);
          var request = {
              location: coords,
              //Defines the distance (in meters)
              radius: 3000,
              types: ['hospital']
          };
          service.nearbySearch(request, callback);

      }

      function callback(results, status) {
          var markers = [];
          if (status === google.maps.places.PlacesServiceStatus.OK) {
              for (var i = 0; i < results.length; i++) {
                  markers.push(createMarker(results[i]));
              }
          }
      }

      function createMarker(place) {

          var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
          };
          var placeLoc = place.geometry.location;
          var marker = new google.maps.Marker({
             map: map,
              icon: icon,
              position: place.geometry.location,
              title: place.name
          });
		  
		  //  var infowindow = new google.maps.InfoWindow({
           //  content: place.name
          // });

          


          marker.addListener('click', function(event) {
              var latitude = place.geometry.location.lat();
              var longitude = place.geometry.location.lng();
              markerPos = place.geometry.location;
                // infowindow.open(map, marker);
             //setTimeout(function () { infowindow.close(); }, 2000);
              calculateAndDisplayRoute(directionsService, directionsDisplay, mycoords, markerPos);
          });


      }


      function callback2(results, status) {
          var markers = [];
          if (status === google.maps.places.PlacesServiceStatus.OK) {
              for (var i = 0; i < results.length; i++) {
                  markers.push(createMarker2(results[i]));
              }
          }
      }

      function createMarker2(place) {

          var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
          };
          var placeLoc = place.geometry.location;
          var marker = new google.maps.Marker({
              map: map,
              icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png',

          position: place.geometry.location,
              title: place.name
          });

         // var infowindow = new google.maps.InfoWindow({
          //    content: place.name
         // });


          marker.addListener('click', function(event) {
              var latitude = place.geometry.location.lat();
              var longitude = place.geometry.location.lng();
              markerPos = place.geometry.location;
             // infowindow.open(map, marker);
             // setTimeout(function () { infowindow.close(); }, 2000);
              calculateAndDisplayRoute(directionsService, directionsDisplay, mycoords, markerPos);
          });


      }

 function computeTotalDistance(result) {
          var total = 0;
          var myroute = result.routes[0];
          for (var i = 0; i < myroute.legs.length; i++) {
              total += myroute.legs[i].distance.value;
          }
          total = total / 1000;
          document.getElementById('total').innerHTML = 'Distance: ' + total + ' km';
      }


      function calculateAndDisplayRoute(directionsService, directionsDisplay, coords, markerPos) {

          directionsService.route({
              origin: coords,
              destination: markerPos,
              travelMode: google.maps.TravelMode.WALKING
          }, function(response, status) {
              if (status == 'OK') {
                  directionsDisplay.setDirections(response);
              }
              else {
                  window.alert('Directions request failed due to ' + status);
              }
          });
      }

 

