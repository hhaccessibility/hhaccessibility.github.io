create table login (
id SERIAL PRIMARY KEY,
username TEXT,	
password TEXT,
photoid	TEXT,
type TEXT
);

create table student (
id SERIAL PRIMARY KEY,
studentid TEXT,
firstname TEXT,
middlename TEXT,
lastname TEXT,
email TEXT,
telephone TEXT,
gender TEXT,
residentstatus TEXT,
country TEXT,
semesterregistered TEXT,
currentgpa real,
internshipstatus TEXT);

create table internship (
id SERIAL PRIMARY KEY,
studentid TEXT,
companyid TEXT,
notes TEXT);

create table job (
id SERIAL PRIMARY KEY,
companyid TEXT,
position TEXT,
description TEXT,
responsibilities TEXT,
requirements TEXT,
salary TEXT,
availability TEXT);

create table company (
id SERIAL PRIMARY KEY,
photoid	TEXT,
companyname TEXT,
address TEXT,
city TEXT,
postalcode TEXT,
country TEXT,
contactpersonfirstname TEXT,
contactpersonlastname TEXT,
contactpersonposition TEXT,
telephone TEXT,
email TEXT,
type TEXT,
companywebsite TEXT);

create table student_job_achieved (
id SERIAL PRIMARY KEY,
studentid TEXT,
jobid TEXT);

create table student_job_interest (
id SERIAL PRIMARY KEY,
studentid TEXT,
jobid TEXT);

create table semesterregistered (
id SERIAL PRIMARY KEY,
semester TEXT,
year TEXT);

create table education (
id SERIAL PRIMARY KEY,
studentid TEXT,
degreetype TEXT,
major TEXT,
gpa real,
university TEXT,
location TEXT,
certifications TEXT);

create table workexperience (
id SERIAL PRIMARY KEY,
studentid TEXT,
companyid TEXT,
location TEXT,
startdate date,
enddate date,
position TEXT);

create table feed(
id SERIAL PRIMARY KEY,
studentid TEXT,
value TEXT,
datetime timestamp
);

insert into semesterregistered (semester, year) values ('fall', '2015');
insert into semesterregistered (semester, year) values ('winter', '2016');

create table skill (
studentid TEXT,
a001 int default 0,
a002 int default 0,
a003 int default 0,
a004 int default 0,
a005 int default 0,
a006 int default 0,
a007 int default 0,
a008 int default 0,
a009 int default 0,
a010 int default 0,
a011 int default 0,
a012 int default 0,
a013 int default 0,
a014 int default 0,
a015 int default 0,
a016 int default 0,
a017 int default 0,
a018 int default 0,
a019 int default 0,
a020 int default 0,
a021 int default 0,
a022 int default 0
);

