-- A group of closely related buildings.
-- For example, University of Windsor
create table building_group (
	id int primary key auto_increment,
	name varchar(255)
);

-- A specific building.
-- For example, CAW Student Centre
create table building (
	id int primary key auto_increment,
	building_group_id int references building_group(id),
	name varchar(255),
	longitude float not null, -- in degrees for centre of the building
	latitude float not null -- in degrees
);

-- A specific bathroom facility in a building
-- Each building might have multiple bathroom facilities.
-- Facilities such as outhouses may keep building_id null and set both latitude and longitude.
-- longitude or latitude being NULL implies building_id must not be null.
create table bathroom_facility (
	id int primary key auto_increment,
	building_id int references building(id),
	longitude float, -- degrees.  NULL implies to use longitude for corresponding building
	latitude float
);

-- A question asked about every bathroom facility
-- For example, 'Is baby change provided?'
create table question (
	id int primary key auto_increment,
	question_text varchar(255) not null
);

-- Represents Roles available for users
-- For example, 'Administrator', 'Tester'.
create table role (
	id int primary key auto_increment,
	name varchar(100) not null,
	description varchar(255)
);

-- Represents each person who uses our application
create table `user` (
	id int primary key auto_increment,
	username varchar(100) not null,
	password_hash varchar(32)
);

-- Represents what roles each user has
-- Each user could have multiple roles.
create table user_role (
	id int primary key auto_increment,
	user_id int not null references `user`(id),
	role_id int not null references role(id),
	CONSTRAINT uc_role_and_user UNIQUE(user_id, role_id)
);

-- Represents an answer given to a specific question on a specific facility
create table user_answer (
	id int primary key auto_increment,
	question_id int not null references question(id),
	bathroom_facility_id int not null references bathroom_facility(id),
	answered_by_user_id int not null references `user`(id),
	answer_value varchar(255),
	when_submitted datetime
);
