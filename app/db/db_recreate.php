<?php

function dropAllTables() {
	
}

function createTables() {
	
}

function insertTestData() {
	
}

function doesDatabaseExist() {
	return true;
}

function recreateDatabase() {
	// if database doesn't exist, create it.
	if (doesDatabaseExist())
		createDatabase();
	else
		dropAllTables();
	
	createTables();
	insertTestData();
}

recreateDatabase();