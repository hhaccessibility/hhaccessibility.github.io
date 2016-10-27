
/*!
 * hhaccessibility v0.1
 * Copyright 2011-2016 JMCC
 * @author Jianye Liu(Bruce Lau)
 * Licensed under the MIT license
 */
$(function() {
    $("#testbutton").on("click",function(){
      var crd= getUsersLocation();
      log(crd[0] + "" + crd[1]);
    });
  })

//
function getUsersLocation(){
  var pos=[42.307890, -83.068468];
  if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
        pos = [position.coords.latitude, position.coords.longitude];
      });
    }
  return pos;
}
function log(message){
  $("#textarea").text(message);
}
