$('#photo1').attr('src',localStorage.photoid);
$('#photo3').attr('src',localStorage.photoid);
$('#nameofadmin1').text(localStorage.username);
$('#nameofadmin3').text(localStorage.username);

function getAppropriateName(value){
  var x;
  switch(value)
  {
    case 'a001': x = 'Ramp Grade'      ; break;
    case 'a002': x = 'Hand Rails' ; break;
    case 'a003': x = 'Zero Threshold' ; break;
    case 'a004': x = 'Auotmatic doors' ; break;
    case 'a005': x = 'Alternate accessible exit' ; break;
    case 'a006': x = 'Minimum 32" width doorways' ; break;
    case 'a007': x = 'Braille Signage' ; break;
    case 'a008': x = 'Non-slip flooring' ; break;
    case 'a009': x = 'Elevator' ; break;
    case 'a010': x = 'Auditory Cues on elevator' ; break;
    case 'a011': x = 'Hand rails on interior stairs' ; break;
    case 'a012': x = 'Automatic door opener washroom' ; break;
    case 'a013': x = 'Grab bars around toilet' ; break;
    case 'a014': x = 'Space on either side of toilet for caregivers to assist' ; break;
    case 'a015': x = 'Unisex weelchair accessible washroom' ; break;
    case 'a016': x = 'Sink with underclearence' ; break;
    case 'a017': x = 'Sensor hand soap' ; break;
    case 'a018': x = 'Lever taps or sensor faucet' ; break;
    case 'a019': x = 'Sensor hand dryer at accessible height' ; break;
    case 'a020': x = 'Large heigt adjustable change table in washroom' ; break;
    case 'a021': x = 'Ceilinng lift over toilet and change table'      ; break;
    case 'a022': x = 'Min 48" clearence through laneways between furniture' ; break;
  } 
  return x;

}