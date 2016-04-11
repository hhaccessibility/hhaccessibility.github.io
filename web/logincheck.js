HOST = "http://macinternship2.herokuapp.com";
if(localStorage.username == undefined){
	window.location.replace("login.html");
}
function toTitleCase(str)
{
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}
function onlyUnique(value, index, self) { 
    return self.indexOf(value) === index;
}
// Read a page's GET URL variables and return them as an associative array.
function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}
function deleteimagetap(that){
  localStorage.rowidtodelete = that.getAttribute("rowid");
  localStorage.rownametodelete = that.getAttribute("rowname");
  localStorage.tabletodelete = that.getAttribute("table");

  swal({   title: "Do you really want to delete "+
    localStorage.rownametodelete + " (id: " +
    localStorage.rowidtodelete + ") from "+
    localStorage.tabletodelete,   showCancelButton: true,   
    closeOnConfirm: false,   animation: "slide-from-top"}, 
    function(inputValue){   
      if (inputValue === false) 
        return false;      
      
      //send post call  
      $.post(HOST + "/deletedatafromtable",
      {
        id: localStorage.rowidtodelete,
        table: localStorage.tabletodelete
      },
      function(data, status){  
        swal("Deleted!", localStorage.rownametodelete +
          " was deleted"); 
        setTimeout(function(){ location.reload(); }, 3000);
        
      });
    });
}
function upimagetap(that){
  localStorage.studentid = that.getAttribute("studentid");  
  localStorage.table = that.getAttribute("table1");
  localStorage.rownametodelete = that.getAttribute("rowname");

  localStorage.jobid = that.getAttribute("jobid");
  localStorage.student_job_interestid = that.getAttribute("student_job_interestid");
  localStorage.tabletodelete2 = that.getAttribute("table2");

  swal({   title: "Do you really want to offer this job to "+
    localStorage.rownametodelete + " (id: " +
    localStorage.rowidtodelete + ")",   showCancelButton: true,   
    closeOnConfirm: false,   animation: "slide-from-top"}, 
    function(inputValue){   
      if (inputValue === false) 
        return false;      
      
      console.log(localStorage.student_job_interestid, localStorage.tabletodelete2)
      console.log(localStorage.jobid, localStorage.studentid)

      //send post call  
      $.post(HOST + "/deletedatafromtable",
      {
        id: localStorage.student_job_interestid,
        table: localStorage.tabletodelete2
      },
      function(data, status){  
      });

      // send post call  
      $.post(HOST + "/addstudentjobachieved",
      {
        jobid: parseInt(localStorage.jobid),
        username: parseInt(localStorage.studentid)
      },
      function(data, status){  
        swal("Congratulations!", localStorage.rownametodelete +
          " was offered this job"); 
        setTimeout(function(){ location.reload(); }, 3000);
        
      });
    });
}
function dateFormat(newdate){
  var monthNames = [
    "January", "February", "March",
    "April", "May", "June", "July",
    "August", "September", "October",
    "November", "December"
  ];

  var date = new Date(newdate);
  var day = date.getDate();
  var monthIndex = date.getMonth();
  var year = date.getFullYear();

  console.log(day, monthNames[monthIndex], year);
  return day + ' ' + monthNames[monthIndex] + ' ' + year;
}
function compare(a,b) {
  if (a.y < b.y)
    return -1;
  else if (a.y > b.y)
    return 1;
  else 
    return 0;
}