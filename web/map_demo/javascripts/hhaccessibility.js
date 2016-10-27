
/*!
 * hhaccessibility v0.1
 * Copyright 2011-2016 JMCC
 * @author Jianye Liu(Bruce Lau)
 * Licensed under the MIT license
 */
$(function() {
    initializingGoogelMap();
    addMarkers();
    getUsersLocation();
  })

//initial map
function initializingGoogelMap(){
  var mapCenterParas = {
    zoom: 15, // Initial zoom level (optional)
    coords: getUsersLocation(), // Map center (optional)
    type: "ROADMAP" // Map type (optional)
  };
  $("#map").googleMap(mapCenterParas);
}

//get user's location
function getUsersLocation(){
  var pos=[42.307890, -83.068468];
  if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
        pos = [position.coords.latitude, position.coords.longitude];
      });
    }
  return pos;
}
//Markers operation selections
function addMarkers(){
  var markers=[
    {
      id: 'Marker 1',
      //coords: [42.307890, -83.068468], // GPS coords
      url: 'http://www.tiloweb.com', // Link to redirect onclick (optional)
      id: 'marker1', // Unique ID for your marker
      title: 'Marker n°1', // Title
      text:  '<Strong>University of Windsor, </Strong>', // HTML content
      address: '401 Sunset Avenue, Widnsor, Ontario, Canada'
    },
    {
    id: 'Marker 2',
    //coords: [42.307890, -83.068468], // GPS coords
    url: 'http://www.tiloweb.com', // Link to redirect onclick (optional)
    id: 'marker1', // Unique ID for your marker
    title: 'Marker n°1', // Title
    text:  '<Strong>University of Windsor, </Strong>', // HTML content
    address: '403 Sunset Avenue, Widnsor, Ontario, Canada'
   }
  ];

  addMarkersWithMarkers(markers);
}

function addMarkersWithMarkers(markers){

  if(isArray(markers)){
    for(i=0; i< markers.length ; i++)
    $("#map").addMarker(markers[i]);

  }
}

function isArray(array){
  if(Array.isArray(array)) return true;
  else return false;

}
