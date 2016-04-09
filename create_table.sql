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
asp_dot_net int default 0,
c int default 0,
cplusplus int default 0,
csharp int default 0,
flex int default 0,
java int default 0,
javascript int default 0,
lisp int default 0,
matlab int default 0,
mysql int default 0,
objectivec int default 0,
pascal int default 0,
perl int default 0,
php int default 0,
prolog int default 0,
python int default 0,
r int default 0,
ruby int default 0,
sql_oracle int default 0,
tcl int default 0,
t_sql int default 0,
vb_dot_net int default 0,

concrete int default 0,
dotnetnuke int default 0,
drupal int default 0,
joomla int default 0,
wordpress int default 0,

android int default 0,
chromeos int default 0,
ios int default 0,
linux int default 0,
macos int default 0,
unix int default 0,
windows int default 0);