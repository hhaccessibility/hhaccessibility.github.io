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