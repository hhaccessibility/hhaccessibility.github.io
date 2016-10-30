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

-- A category for a question such as 'Parking', 'Mobility'...
create table question_category (
	id int primary key auto_increment,
	name varchar(100) not null
);

-- A question asked about every bathroom facility
-- For example, 'Is baby change provided?'
create table question (
	id int primary key auto_increment,
	question_text varchar(255) not null,
	question_category_id int references question_category(id)
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
	password_hash varchar(32),
	constraint uc_username unique(username)
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
	building_id int not null references building(id),
	answered_by_user_id int not null references `user`(id),
	answer_value varchar(255) not null,
	when_submitted datetime
);
