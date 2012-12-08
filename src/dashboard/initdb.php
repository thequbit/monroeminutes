<?php

	// this page will create the database and load it with a default admin login

	require_once("debug.php");
	require_once("sqlcredentials.php");
	
	dprint("starting initdb php script ...");
	
	//
	// connect to db
	//
	
	// connect to the mysql database server.  Constants taken from sqlcredentials.php
	$chandle = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS)
		or die("Connection Failure to Database");				// TODO: something more elegant than this
	dprint("connected to MySQL.");
	
	//
	// create database
	//
	
	$query("create database " . MYSQL_DATABASE);
	mysql_mysql_query($query, $chandle)
	dprint("database created.");

	//
	// use database we just created
	//

	mysql_select_db(MYSQL_DATABASE, $chandle)
		or die (MYSQL_DATABASE . " Database not found. " . MYSQL_USER);	// TODO: something more elegant than this
	dprint("database selected.");

	//
	// create tables
	//

	// wordfrequency table
	$query("create table wordfrequency (wordfrequencyid int not null auto_increment primary key, documentid int not null, word text, frequency int)");
	$result = mysql_db_query(MYSQL_DATABASE, $query)
		or die("Failed Query of " . $query);  			// TODO: something more elegant than this
	dprint("wordfrequency table created.");

	// documents table
	$query("create table documents ( documentid int not null auto_increment primary key, suborganizationid int not null, sourceurl text, date datetime, name text)");
	$result = mysql_db_query(MYSQL_DATABASE, $query)
		or die("Failed Query of " . $query);  			// TODO: something more elegant than this
	dprint("documents table created.");

	// organization table
	$query("create table organization (organizationid int not null auto_increment primary key, name text, websiteurl text)");
	$result = mysql_db_query(MYSQL_DATABASE, $query)
		or die("Failed Query of " . $query);  			// TODO: something more elegant than this
	dprint("orgnaization table created.");

	// suborganization table
	$query("create table suborganization(suborganizationid int not null auto_increment primary key, organizationid int, name text, websiteurl text, documentsurl text, sciptname text)");
	$result = mysql_db_query(MYSQL_DATABASE, $query)
		or die("Failed Query of " . $query);  			// TODO: something more elegant than this
	dprint("suborganization table created.");

	// users table
	$query("create table users( userid int not null auto_increment primary key, displayname text not null, username text not null, passwordhash text not null, permissionsid int not null)");
	$result = mysql_db_query(MYSQL_DATABASE, $query)
		or die("Failed Query of " . $query);  			// TODO: something more elegant than this
	dprint("users table created.");

	// permissions table
	$query("create table permissions( permissionsid int not null auto_increment primary key, canlogin bool not null, isadmin bool not null, enabled bool not null)");
	$result = mysql_db_query(MYSQL_DATABASE, $query)
		or die("Failed Query of " . $query);  			// TODO: something more elegant than this
	dprint("permissions table created.");

	// searches table
	$query("create table searches( searchid int not null auto_increment primary key, searchstring text, date datetime, querytime float)");
	$result = mysql_db_query(MYSQL_DATABASE, $query)
		or die("Failed Query of " . $query);  			// TODO: something more elegant than this
	dprint("permissions table created.");

	//
	// create default admin login
	//

	// create permissions for admin user
	$query("insert into permissions (canlogin,isadmin,enabled) (true,true,true)");
	$result = mysql_db_query(MYSQL_DATABASE, $query)
		or die("Failed Query of " . $query);  			// TODO: something more elegant than this
	dprint("permissions for admin user created.");

	// add user to database with
	//		username: admin
	//		password: password
	$password = md5("password");
	$query('insert into users (displayname,username,passwordhash,permissionsid) values("Admin","admin","' . $password . '",1)');
	$result = mysql_db_query(MYSQL_DATABASE, $query)
		or die("Failed Query of " . $query);  			// TODO: something more elegant than this
	dprint("default admin user added to users table.");

	dprint("Done.");

?>
