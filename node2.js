var http = require("http")
var express = require("express")
var app = express()
var port = process.env.PORT || 5000
var pg = require('pg');

var bodyParser = require('body-parser');
var app = express()
app.use(bodyParser.urlencoded({ extended: false }))
app.use(bodyParser.json())

app.use(express.static(__dirname + "/"))

app.all('*', function(req, res, next) {
  res.header('Access-Control-Allow-Origin', '*');
  res.header('Access-Control-Allow-Headers', 'X-Requested-With, Authorization, Content-Type');
  next();
});

var server = http.createServer(app)
server.listen(port)

console.log("Listening on %d", port)

var baseClient;
pg.connect(process.env.DATABASE_URL, function(err, client) {
    baseClient = client;
});
var that = this;

function insertFeed(studentid, value){
    var queryString = "INSERT INTO feed (studentid, value, datetime) values('" + studentid + "', '" + value + "',now());";
    if(baseClient != null)
    var query = baseClient.query(queryString);
}

app.post('/login', function (req, res) {
    console.log('login:' + req.body.username);
    
    insertFeed(req.body.username, 'logged in');
    var rows = [];
    var queryString = "SELECT * FROM login where username = '" + req.body.username + "' and password='" + req.body.password + "';";
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('login: ' + result.rowCount + ' rows');
        // console.log(rows);
        res.json(rows);
    });
});

app.post('/updatepassword', function (req, res) {
    console.log('updatepassword:' + req.body.username);
    
    var rows = [];
    var queryString = "SELECT * FROM login where username = '" + req.body.username + "' and password='" + req.body.password + "';";
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
        insertFeed(req.body.username, 'changed password');
        var queryStringInner = "UPDATE login SET password = '" + req.body.newpassword +"' where username = '" + req.body.username + "';";
        console.log(queryStringInner);
        var queryInner = baseClient.query(queryStringInner);
        res.json('password changed');
    });
    query.on('end', function(result) {
        console.log('updatepassword: ' + result.rowCount + ' rows');
        if(result.rowCount == 0)
        res.json('password not changed');
    });
});

app.post('/createaccount', function (req, res) {
    console.log('createaccount:' + req.body.username);
    console.log('createaccount:' + req.body.type);
    if(req.body.type == 'admin'){
        if(req.body.promo == 'macadmin'){
            insertFeed(req.body.username, 'created a new account');
            createaccount(req.body.username, req.body.password, req.body.photoid, req.body.type);            
        }else{
            res.json('invalid promo code');
        }
    }else{
        insertFeed(req.body.username, 'created a new student account');
        createaccount(req.body.username, req.body.password, req.body.photoid, req.body.type);       
        createstudent(req.body.username, req.body.firstname, req.body.middlename, 
            req.body.lastname, req.body.email, req.body.telephone, req.body.gender, 
            req.body.residentstatus, req.body.country, req.body.semesterregistered,
            req.body.currentgpa, req.body.internshipstatus);
        createzerovaluedskills(req.body.username);
    }
    res.json('created');
});

function createaccount(username, password, photoid, type){
    var rows = [];
    var queryString = "INSERT INTO login (username, password, photoid, type) VALUES ('" + 
    username + "', '" + 
    password + "', '" + 
    photoid + "', '" + 
    type + "');";

    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('createaccount: ' + result.rowCount + ' rows');
    });
}

function createstudent(username, firstname, middlename, 
            lastname, email, telephone, gender, 
            residentstatus, country, semesterregistered, currentgpa, internshipstatus){
    var rows = [];
    var queryString = "INSERT INTO student (studentid, firstname, middlename, "+
            "lastname, email, telephone, gender, " +
            "residentstatus, country, semesterregistered, currentgpa, internshipstatus) VALUES ('" + 
    username + "', '" +  
    firstname + "', '" +  
    middlename + "', '" +  
    lastname + "', '" +  
    email + "', '" +  
    telephone + "', '" +  
    gender + "', '" +  
    residentstatus + "', '" +  
    country + "', '" +  
    semesterregistered + "', '" +  
    currentgpa + "', '" +  
    internshipstatus + "');";

    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('createaccount: ' + result.rowCount + ' rows');
    });
}

app.post('/updatestudent', function (req, res) {
    console.log('updatestudent:' + req.body.username);
    insertFeed(req.body.username, 'updated student information');
    updatestudent(req.body.username, req.body.firstname, req.body.middlename, 
        req.body.lastname, req.body.email, req.body.telephone);
    res.json('updated');
});

function updatestudent(username, firstname, middlename, 
            lastname, email, telephone){
    var rows = [];
    var queryString = "UPDATE student SET " +
    "firstname = '" + firstname + "', " +  
    "middlename = '" + middlename + "', " +  
    "lastname = '" + lastname + "', " +  
    "email = '" + email + "', " +  
    "telephone = '" + telephone + "' where " +
    "studentid = '" + username + "';";    

    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('createaccount: ' + result.rowCount + ' rows');
    });
}

app.post('/addeducation', function (req, res) {
    console.log('addeducation:' + req.body.username);
    insertFeed(req.body.username, 'added new education information');

    var rows = [];
    var queryString = "INSERT INTO education (studentid, degreetype, major, "+
            "gpa, university, location, certifications) VALUES ('" + 
    req.body.username + "', '" +  
    req.body.degreetype + "', '" +  
    req.body.major + "', '" +  
    req.body.gpa + "', '" +  
    req.body.university + "', '" +  
    req.body.location + "', '" +  
    req.body.certifications + "');";

    var query = baseClient.query(queryString);
    res.json('added');
});

app.post('/addworkexperience', function (req, res) {
    console.log('addworkexperience:' + req.body.username);
    insertFeed(req.body.username, 'added new work experience');

    var rows = [];
    var queryString = "INSERT INTO workexperience (studentid, companyid, location, "+
            "startdate, enddate, position) VALUES ('" + 
    req.body.username + "', '" +  
    req.body.companyid + "', '" +  
    req.body.location + "', '" +  
    req.body.startdate + "', '" +  
    req.body.enddate + "', '" +  
    req.body.position + "');";

    var query = baseClient.query(queryString);
    res.json('added');
});

app.post('/addcompany', function (req, res) {
    console.log('addcompany:' + req.body.username);
    insertFeed(req.body.username, 'added new company ' + toTitleCase(req.body.companyname));

    var rows = [];
    var queryString = "INSERT INTO company (photoid, companyname, address, "+
            "city, postalcode, country, contactpersonfirstname, contactpersonlastname, "+
            "contactpersonposition, telephone, email, type, companywebsite) VALUES ('" + 
    req.body.photoid + "', '" +  
    req.body.companyname + "', '" +  
    req.body.address + "', '" +  

    req.body.city + "', '" +  
    req.body.postalcode + "', '" +  
    req.body.country + "', '" +  
    req.body.contactpersonfirstname + "', '" +  
    req.body.contactpersonlastname + "', '" +  

    req.body.contactpersonposition + "', '" +  
    req.body.telephone + "', '" +  
    req.body.email + "', '" +  
    req.body.type + "', '" +  
    req.body.companywebsite + "');";

    var query = baseClient.query(queryString);
    res.json('added');
});

app.post('/addinternship', function (req, res) {
    console.log('addinternship:' + req.body.username);
    insertFeed(req.body.username, 'added new internship');

    var rows = [];
    var queryString = "INSERT INTO internship (studentid, companyid, "+
            "notes) VALUES ('" + 
    req.body.username + "', '" +  
    req.body.companyid + "', '" +  
    req.body.notes + "');";

    var query = baseClient.query(queryString);
    res.json('added');
});

app.post('/viewinternship', function (req, res) {
    console.log('viewinternship:' + req.body.username);

    var rows = [];
    var queryString = "select internship.id as superid,* from internship "+
    "inner join company on "+
    "cast(internship.companyid as int) = company.id where studentid = '"+
    req.body.username +"'";

     var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('viewstudent: ' + result.rowCount + ' rows');
        res.json(rows);
    });
});

app.post('/addjob', function (req, res) {
    console.log('addjob:' + req.body.username);
    insertFeed(req.body.username, 'posted a new job');

    var rows = [];
    var queryString = "INSERT INTO job (companyid, position, description, "+
            "responsibilities, requirements, salary, availability) VALUES ('" + 
    req.body.companyid + "', '" +  
    req.body.position + "', '" +  
    req.body.description + "', '" +  
    req.body.responsibilities + "', '" +  
    req.body.requirements + "', '" +  
    req.body.salary + "', '" +  
    req.body.availability + "');";

    var query = baseClient.query(queryString);
    res.json('added');
});

function createzerovaluedskills(username){
    var queryString = "INSERT INTO skill (studentid) values('" + username + "');";
    if(baseClient != null)
    var query = baseClient.query(queryString);
}

app.post('/updateskill', function (req, res) {
    console.log('updateskill:' + req.body.username);
    insertFeed(req.body.username, 'updated skills set');

    var rows = [];
    var queryString = "UPDATE skill SET " +
    "a001 = " + req.body.a001 + "," +
"a002 = " + req.body.a002 + "," +
"a003 = " + req.body.a003 + "," +
"a004 = " + req.body.a004 + "," +
"a005 = " + req.body.a005 + "," +
"a006 = " + req.body.a006 + "," +
"a007 = " + req.body.a007 + "," +
"a008 = " + req.body.a008 + "," +
"a009 = " + req.body.a009 + "," +
"a010 = " + req.body.a010 + "," +
"a011 = " + req.body.a011 + "," +
"a012 = " + req.body.a012 + "," +
"a013 = " + req.body.a013 + "," +
"a014 = " + req.body.a014 + "," +
"a015 = " + req.body.a015 + "," +
"a016 = " + req.body.a016 + "," +
"a017 = " + req.body.a017 + "," +
"a018 = " + req.body.a018 + "," +
"a019 = " + req.body.a019 + "," +
"a020 = " + req.body.a020 + "," +
"a021 = " + req.body.a021 + "," +
"a022 = " + req.body.a022 + " where " +
    "studentid = '" + req.body.username + "';"; 
    console.log(queryString)
    var query = baseClient.query(queryString);
    res.json('updateskill');
});

app.post('/addstudentjobachieved', function (req, res) {
    console.log('addstudentjobachieved:' + req.body.username);
    insertFeed(req.body.username, 'got a new job');

    var rows = [];
    var queryString = "INSERT INTO student_job_achieved (studentid, jobid) VALUES ('" + 
    req.body.username + "', '" +  
    req.body.jobid + "');";

    var query = baseClient.query(queryString);
    res.json('added');
});

app.post('/addstudentjobinterest', function (req, res) {
    console.log('addstudentjobinterest:' + req.body.username);
    insertFeed(req.body.username, 'is interested in a job');

    var rows = [];
    var queryString = "INSERT INTO student_job_interest (studentid, jobid) VALUES ('" + 
    req.body.username + "', '" +  
    req.body.jobid + "');";

    var query = baseClient.query(queryString);
    res.json('added');
});

app.post('/removestudentjobachieved', function (req, res) {
    console.log('removestudentjobachieved:' + req.body.username);
    insertFeed(req.body.username, 'is removed from a job');

    var rows = [];
    var queryString = "DELETE FROM student_job_achieved WHERE " + 
    "studentid = '" + req.body.username + "' AND " +  
    "jobid = '" + req.body.jobid + "';";

    var query = baseClient.query(queryString);
    res.json('removed');
});

app.post('/removestudentjobinterest', function (req, res) {
    console.log('removestudentjobinterest:' + req.body.username);
    insertFeed(req.body.username, 'removed a job interest');

    var rows = [];
    var queryString = "DELETE FROM student_job_interest WHERE " + 
    "studentid = '" + req.body.username + "' AND " +  
    "jobid = '" + req.body.jobid + "';";

    var query = baseClient.query(queryString);
    res.json('removed');
});






function toTitleCase(str)
{
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}

function parseTwitterDate(tdate) {
    var system_date = new Date(Date.parse(tdate));
    var user_date = new Date();
    var diff = Math.floor((user_date - system_date) / 1000);
    if (diff <= 1) {return "just now";}
    if (diff < 20) {return diff + " seconds ago";}
    if (diff < 40) {return "half a minute ago";}
    if (diff < 60) {return "less than a minute ago";}
    if (diff <= 90) {return "one minute ago";}
    if (diff <= 3540) {return Math.round(diff / 60) + " minutes ago";}
    if (diff <= 5400) {return "1 hour ago";}
    if (diff <= 86400) {return Math.round(diff / 3600) + " hours ago";}
    if (diff <= 129600) {return "1 day ago";}
    if (diff < 604800) {return Math.round(diff / 86400) + " days ago";}
    if (diff <= 777600) {return "1 week ago";}
    return "on " + system_date;
}

//SELECT STUDENTS
app.post('/showstudents', function (req, res) {
    console.log('showstudents: parameters');
    console.log('1');
    
    //search
    var searchQuery = "((firstname is NOT NULL OR firstname is NULL) OR " +
        "(middlename is NOT NULL OR middlename is NULL) OR " + 
        "(lastname is NOT NULL OR lastname is NULL))";
    if(req.body.search != undefined){
        if(req.body.search.length > 0){
            searchQuery = "((firstname like '%" + req.body.search + "%') OR " +
                "(middlename like '%" + req.body.search + "%') OR " + 
                "(student.studentid like '%" + req.body.search + "%') OR " + 
                "(lastname like '%" + req.body.search + "%'))";
        }
    }

    var rows = [];
    var studentids = '';
    //student info
    
    var queryString = "SELECT distinct on (student.id) student.id " + 
    "FROM login inner join student on login.username = student.studentid "+
    "left join " + 
    " student_job_achieved on student.studentid = student_job_achieved.studentid " +
    "left join job on cast(student_job_achieved.jobid as int) = job.id where " + 
    searchQuery;

    console.log(queryString);
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        // rows.push(row);
        studentids += "'" + row.id + "',";
    });
    query.on('end', function(result) {
        console.log('showstudents: ' + result.rowCount + ' rows');
        console.log('studentids: ' + studentids);
        if(studentids.length == 0){
            rows = [];
            res.json(rows);
            // return 1;
        }else{
            studentids = studentids.substring(0, studentids.length-1);
            showStudents(studentids, res);
        }
    });
});

function showStudents(studentids, res){
    var queryString = "select login.photoid, student.id, login.username, "+
        "student.firstname, student.middlename, student.lastname, student.email, "+
        "student.telephone, skill.* from login inner join student on "+
        "login.username = student.studentid inner join skill on "+
        "student.studentid = skill.studentid " +
        "where student.id in(" + studentids + ");"
    var rows = [];
    console.log('this is what we need');
    console.log(queryString);
    // res.json(queryString);
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        row.sumSkill += row.a001
        row.sumSkill += row.a002
        row.sumSkill += row.a003
        row.sumSkill += row.a004
        row.sumSkill += row.a005
        row.sumSkill += row.a006
        row.sumSkill += row.a007
        row.sumSkill += row.a008
        row.sumSkill += row.a009
        row.sumSkill += row.a010
        row.sumSkill += row.a011
        row.sumSkill += row.a012
        row.sumSkill += row.a013
        row.sumSkill += row.a014
        row.sumSkill += row.a015
        row.sumSkill += row.a016
        row.sumSkill += row.a017
        row.sumSkill += row.a018
        row.sumSkill += row.a019
        row.sumSkill += row.a020
        row.sumSkill += row.a021
        row.sumSkill += row.a022
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('selectstudent: ' + result.rowCount + ' rows');
        // console.log(rows);
        res.json(rows);
    });
}

//average gpa
app.post('/getgpa', function (req, res) {
    console.log('getgpa: ' + req.body.studentids);
    
    var queryString = "select avg(gpa), studentid from education group by " +
    "studentid having studentid in (" + req.body.studentids + ") and avg(gpa) "+req.body.gpa+";"
    
    var rows = [];
    // res.json(queryString);
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('getgpa: ' + result.rowCount + ' rows');
        // console.log(rows);
        res.json(rows);
    });
});

app.post('/viewstudent', function (req, res) {
    console.log('viewstudent:' + req.body.username);
    
   var rows = [];
    var queryString = "SELECT login.photoid, student.*"+
    " FROM login inner join student on "+
    "login.username = student.studentid "+
    "where studentid = '" + req.body.username + "';";
    console.log(queryString)
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('viewstudent: ' + result.rowCount + ' rows');
        res.json(rows);
    });
});

app.post('/viewstudenteducation', function (req, res) {
    console.log('viewstudenteducation:' + req.body.username);
    
   var rows = [];
    var queryString = "SELECT * FROM education where studentid = '" + req.body.username + "';";
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('viewstudenteducation: ' + result.rowCount + ' rows');
        res.json(rows);
    });
});

app.post('/viewstudentworkexperience', function (req, res) {
    console.log('viewstudentworkexperience:' + req.body.username);
    
   var rows = [];
    var queryString = "SELECT workexperience.id as superid, * FROM workexperience inner join company " +
    "on cast(workexperience.companyid as int) = company.id where studentid = '" + req.body.username + "';";
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('viewstudentworkexperience: ' + result.rowCount + ' rows');
        res.json(rows);
    });
});

app.post('/viewallcompany', function (req, res) {
    console.log('viewallcompany:' + req.body.search);
    
    var queryString = "SELECT * FROM company where ";

    queryString += req.body.search == "all"?"(companyname like '%')":"(companyname like '%" + req.body.search + "%')";
    queryString += " and ";
    queryString += req.body.city == "all"?"(city like '%')":"(city like '%" + req.body.city + "%')";
    queryString += " and ";
    queryString += req.body.country == "all"?"(country like '%')":"(country like '%" + req.body.country + "%')";

    // res.json(queryString);

    var rows = [];
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('viewallcompany: ' + result.rowCount + ' rows');
        res.json(rows);
    });
});

app.post('/viewcompanybyid', function (req, res) {
    console.log('viewcompanybyid:' + req.body.id);
    
    var queryString = "SELECT * FROM company where id=" + req.body.id;

    var rows = [];
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('viewallcompany: ' + result.rowCount + ' rows');
        res.json(rows);
    });
});

app.post('/viewalljob', function (req, res) {
    console.log('viewalljob:' + req.body.search);
    
    var queryString = "SELECT job.id as jobid, * FROM job inner join company on cast(job.companyid as int) = company.id where ";
    
    queryString += req.body.search == "all"?"(position like '%')":"(position like '%" + req.body.search + "%')";
    queryString += " and ";
    queryString += req.body.city == "all"?"(city like '%')":"(city like '%" + req.body.city + "%')";
    queryString += " and ";
    queryString += req.body.country == "all"?"(country like '%')":"(country like '%" + req.body.country + "%')";

    // res.json(queryString);
    
    var rows = [];
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('viewalljob: ' + result.rowCount + ' rows');
        res.json(rows);
    });
});

app.post('/viewjobcompanybyid', function (req, res) {
    console.log('viewjobcompanybyid:' + req.body.id);
    
    var queryString = "SELECT job.id as jobid, * FROM job inner join company on cast(job.companyid as int) = company.id where ";
    queryString += " company.id = " + req.body.id;
    
    var rows = [];
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('viewjobcompanybyid: ' + result.rowCount + ' rows');
        res.json(rows);
    });
});

app.post('/viewjobbyid', function (req, res) {
    console.log('viewjobbyid:' + req.body.id);
    
    var queryString = "SELECT job.id as jobid, * FROM job inner join company on cast(job.companyid as int) = company.id where ";
    queryString += " job.id = " + req.body.id;
    
    var rows = [];
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('viewjobbyid: ' + result.rowCount + ' rows');
        res.json(rows);
    });
});

app.post('/viewallsemester', function (req, res) {
    console.log('viewallsemester:');
    
    var queryString = "SELECT * FROM semesterregistered ";
    
    // res.json(queryString);
    
    var rows = [];
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('viewalljob: ' + result.rowCount + ' rows');
        res.json(rows);
    });
});

app.post('/addsemester', function (req, res) {
    console.log('addsemester:' + req.body.semester + ', ' + req.body.year);
    
    var queryString = "SELECT * FROM semesterregistered where "+
    "semester='" + req.body.semester+"' and "+
    "year='" + req.body.year + "'";
    
    // res.json(queryString);
    
    var rows = [];
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('addsemestercheck: ' + result.rowCount + ' rows');
        // res.json(rows);
        if(result.rowCount < 1){
            var queryString2 = "INSERT INTO semesterregistered (semester, year) values " +
                "('" + req.body.semester + "','" + req.body.year + "');";
                
            // res.json(queryString);
            
            var rows2 = [];
            var query2 = baseClient.query(queryString2);
            query2.on('row', function(row) {
                rows2.push(row);
            });
            query2.on('end', function(result) {
                res.json('added');
            });
        }else{
            res.json('not added');
        }
    });

    
});

app.post('/deletedatafromtable', function (req, res) {
    console.log('deletedataofid:' + req.body.id + ', table:' + req.body.table);
    
    var queryString = "delete from " + req.body.table + " where id = " + req.body.id ;
    
    // res.json(queryString);
    
    var rows = [];
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        res.json('deleted');
    });
});

app.post('/getfeed', function(req, res) {
    console.log('getfeed:');

    var queryString = "select login.id, login.username, photoid, login.type, " +
    "student.firstname, student.lastname, feed.value, feed.datetime  from feed inner join login " +
    "on feed.studentid = login.username left join student on login.username = " +
    "student.studentid";

    if(req.body.username != null){
        queryString += " where login.username = '" + req.body.username +"'";
    }

    queryString += " where feed.value != 'logged in' order by feed.datetime desc";

    var rows = [];
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        row.newdatetime = parseTwitterDate(row.datetime);
        rows.push(row);
        // console.log(row.datetime);
    });
    query.on('end', function(result) {
        console.log('viewalljob: ' + result.rowCount + ' rows');
        res.json(rows);
    });

});

app.post('/getcount', function(req, res) {
    console.log('getcount:' + req.body.table);
    var rows = [];
    var table = req.body.table;
    if(table == 'job' || table == 'student' || table == 'semesterregistered' ||
        table == 'student_job_achieved' || table == 'student_job_interest'){
        var queryString = "select count(*) from " + req.body.table;
        var query = baseClient.query(queryString);
        
        query.on('row', function(row) {
            rows.push(row);
        });
        query.on('end', function(result) {
            console.log('getcount: ' + result.rowCount + ' rows');
            res.json(rows);
        });
    }else if(table == 'company'){
        var queryString = "select count(distinct(lastname)) from student";
        var query = baseClient.query(queryString);
        
        query.on('row', function(row) {
            rows.push(row);
        });
        query.on('end', function(result) {
            console.log('getcount: ' + result.rowCount + ' rows');
            res.json(rows);
        });
    } else
    {
        res.json('invalid table name');
    }

});

app.post('/checkusername', function (req, res) {
    console.log('checkusername:' + req.body.username);
    
    var rows = [];
    var queryString = "SELECT * FROM login where username = '" + req.body.username + "';";
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('checkusername: ' + result.rowCount + ' rows');
        // console.log(rows);
        if(result.rowCount > 0){
            res.json('exists');
        }else{
            res.json('unique');
        }
    });
});


app.post('/dashboardjob', function (req, res) {
    console.log('dashboardjob:');
    
    var queryString = "SELECT count(*), companyname FROM job inner join company on cast(job.companyid as int) = company.id group by companyname";
    
    // res.json(queryString);
    
    var rows = [];
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('dashboardjob: ' + result.rowCount + ' rows');
        res.json(rows);
    });
});

app.post('/dashboardstudentcountry', function (req, res) {
    console.log('dashboardstudentcountry:');
    
    var queryString = "select count(*), country from student group by country;";
    
    // res.json(queryString);
    
    var rows = [];
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('dashboardstudentcountry: ' + result.rowCount + ' rows');
        res.json(rows);
    });
});

app.post('/dashboardlikedpositions', function (req, res) {
    console.log('dashboardlikedpositions:');
    
    var queryString = "select count(*), position from job inner join student_job_interest on job.id = cast(student_job_interest.jobid as int) group by position";

    // res.json(queryString);
    
    var rows = [];
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('dashboardlikedpositions: ' + result.rowCount + ' rows');
        res.json(rows);
    });
});

app.post('/dashboardskilltotal', function (req, res) {
    console.log('dashboardskilltotal:');
    
    var queryString = "select sum(a001) as a001, sum(a002) as a002, sum(a003) as a003, sum(a004) as a004, sum(a005) as a005, sum(a006) as a006, sum(a007) as a007, sum(a008) as a008, sum(a009) as a009, sum(a010) as a010, sum(a011) as a011, sum(a012) as a012, sum(a013) as a013, sum(a014) as a014, sum(a015) as a015, sum(a016) as a016, sum(a017) as a017, sum(a018) as a018, sum(a019) as a019, sum(a020) as a020, sum(a021) as a021, sum(a022) as a022 from skill";

    var rows = [];
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('dashboardskilltotal: ' + result.rowCount + ' rows');
        res.json(rows);
    });
});

app.post('/dashboardeducationcompany', function (req, res) {
    console.log('dashboardeducationcompany:');
    
    var queryString = "select count(*), education.university, company.companyname from education inner join student_job_achieved on education.studentid = student_job_achieved.studentid inner join job on cast(student_job_achieved.jobid as int) = job.id inner join company on cast(job.companyid as int) = company.id group by education.university, company.companyname";

    var rows = [];
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('dashboardeducationcompany: ' + result.rowCount + ' rows');
        res.json(rows);
    });
});


app.post('/viewstudentachievedbyjobid', function (req, res) {
    console.log('viewstudentachievedbyjobid:' + req.body.id);
    
    var queryString = "select student_job_achieved.id, login.photoid, "+
    "student.firstname, "+
    "student.lastname, student.country, student.gender, "+
    "student.studentid from login inner join student on "+
    "login.username = student.studentid "+
    "inner join student_job_achieved on cast(student.studentid as int) "+
    "= cast(student_job_achieved.studentid as int) inner join job on "+
    "cast(student_job_achieved.jobid as int) = job.id "+
    "where ";
    queryString += " job.id = " + req.body.id;
    
    var rows = [];
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('viewstudentachievedbyjobid: ' + result.rowCount + ' rows');
        res.json(rows);
    });
});

app.post('/viewstudentinterestbyjobid', function (req, res) {
    console.log('viewstudentinterestbyjobid:' + req.body.id);
    
    var queryString = "select student_job_interest.id, login.photoid, "+
    "student.firstname, "+
    "student.lastname, student.country, student.gender, "+
    "student.studentid from login inner join student on "+
    "login.username = student.studentid "+
    "inner join student_job_interest on cast(student.studentid as int) "+
    "= cast(student_job_interest.studentid as int) inner join job on "+
    "cast(student_job_interest.jobid as int) = job.id "+
    "where ";
    queryString += " job.id = " + req.body.id;
    
    var rows = [];
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('viewstudentinterestbyjobid: ' + result.rowCount + ' rows');
        res.json(rows);
    });
});

app.post('/viewstudentachievedbystudentid', function (req, res) {
    console.log('viewstudentachievedbystudentid:' + req.body.username);
    
    var queryString = "select student_job_achieved.id as superid, * "+
    "from login inner join student on "+
    "login.username = student.studentid "+
    "inner join student_job_achieved on cast(student.studentid as int) "+
    "= cast(student_job_achieved.studentid as int) inner join job on "+
    "cast(student_job_achieved.jobid as int) = job.id "+
    "inner join company on company.id = cast(job.companyid as int) "+
    "where ";
    queryString += " cast(student.studentid as int) = " + req.body.username;
    
    var rows = [];
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('viewstudentachievedbystudentid: ' + result.rowCount + ' rows');
        res.json(rows);
    });
});

app.post('/viewstudentinterestbystudentid', function (req, res) {
    console.log('viewstudentinterestbystudentid:' + req.body.username);
    
    var queryString = "select student_job_interest.id as superid, * "+
    "from login inner join student on "+
    "login.username = student.studentid "+
    "inner join student_job_interest on cast(student.studentid as int) "+
    "= cast(student_job_interest.studentid as int) inner join job on "+
    "cast(student_job_interest.jobid as int) = job.id "+
    "inner join company on company.id = cast(job.companyid as int) "+
    "where ";
    queryString += " cast(student.studentid as int) = " + req.body.username;
    
    var rows = [];
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('viewstudentinterestbystudentid: ' + result.rowCount + ' rows');
        res.json(rows);
    });
});

app.post('/addskill', function (req, res) {
    console.log('addskill:' + req.body.username);
    insertFeed(req.body.username, 'added skills set');

    var rows = [];
    var queryString = "INSERT INTO skill VALUES (" +
        "'" + req.body.username + "'," + 
        "'" + req.body.asp_dot_net + "'," + 
        "'" + req.body.c + "'," + 
        "'" + req.body.cplusplus + "'," + 
        "'" + req.body.csharp + "'," + 
        "'" + req.body.flex + "'," + 
        "'" + req.body.java + "'," + 
        "'" + req.body.javascript + "'," + 
        "'" + req.body.lisp + "'," + 
        "'" + req.body.matlab + "'," + 
        "'" + req.body.mysql + "'," + 
        "'" + req.body.objectivec + "'," + 
        "'" + req.body.pascal + "'," + 
        "'" + req.body.perl + "'," + 
        "'" + req.body.php + "'," + 
        "'" + req.body.prolog + "'," + 
        "'" + req.body.python + "'," + 
        "'" + req.body.r + "'," + 
        "'" + req.body.ruby + "'," + 
        "'" + req.body.sql_oracle + "'," + 
        "'" + req.body.tcl + "'," + 
        "'" + req.body.t_sql + "'," + 
        "'" + req.body.vb_dot_net + "'," + 
        "'" + req.body.concrete + "'," + 
        "'" + req.body.dotnetnuke + "'," + 
        "'" + req.body.drupal + "'," + 
        "'" + req.body.joomla + "'," + 
        "'" + req.body.wordpress + "'," + 
        "'" + req.body.android + "'," + 
        "'" + req.body.chromeos + "'," + 
        "'" + req.body.ios + "'," + 
        "'" + req.body.linux + "'," + 
        "'" + req.body.macos + "'," + 
        "'" + req.body.unix + "'," + 
        "'" + req.body.windows + "')";
    console.log(queryString)
    var query = baseClient.query(queryString);
    res.json('addskill');
});


app.post('/viewskill', function (req, res) {
    console.log('viewskill:' + req.body.username);

    var rows = [];
    var queryString = "select * from skill where " +
    "studentid = '" + req.body.username + "';"; 
    // console.log(queryString)
    var rows = [];
    var query = baseClient.query(queryString);
    query.on('row', function(row) {
        rows.push(row);
    });
    query.on('end', function(result) {
        console.log('viewskill: ' + result.rowCount + ' rows');
        res.json(rows);
    });
});
