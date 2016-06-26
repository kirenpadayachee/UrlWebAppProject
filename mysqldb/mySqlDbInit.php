<?php

// Global variables ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$isDbConnected = false;
$conn = null;
$dbhost = 'localhost:3306';
$dbuser = 'root';
$dbpass = 'mysql';
$dbName = 'UrlWebAppDb';
$dbCreateDbQuery = 'CREATE DATABASE ' . $dbName;
$tableName = "HttpPair";
$tableCreateQuery = "CREATE TABLE " . $tableName . " (
httpRequest VARCHAR(255) NOT NULL PRIMARY KEY,
httpResponse TEXT NOT NULL
)";
$tableCheckQuery = "SHOW TABLES LIKE '" . $tableName . "' ";

// Handle Connecting and Disconnecting ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function connectToDb()
{
	global $isDbConnected;
	global $conn;
	global $dbuser;
	global $dbpass;
	global $dbhost;
   
   $conn = mysql_connect($dbhost, $dbuser, $dbpass);
   
   if(! $conn ) {
      die("Could not connect: " . mysql_error() . "\n");
   }
   
   $isDbConnected = true;
   
   echo nl2br("Connected successfully \n");
}

function closeDb()
{
	global $conn;
	mysql_close($conn);
	echo nl2br("Closed successfully \n");
}

//Create DB ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function createDb()
{
	global $dbName;
	global $dbCreateDbQuery;
	
	if (!mysql_select_db($dbName)) 
	{
		echo nl2br("Creating database!\n");
		mysql_query($dbCreateDbQuery);
	}
}

// Create Table ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function createTable()
{
	global $dbName;
	global $tableCheckQuery;
	global $tableCreateQuery;
	
	mysql_select_db($dbName);
	
	$result = mysql_query($tableCheckQuery);
	$tableExists = mysql_num_rows($result) > 0;
	
	if(!$tableExists)
	{
		echo nl2br("Creating table!\n");
		mysql_query($tableCreateQuery);
	}
}

// MAIN ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

connectToDb();


if ($isDbConnected)
{
	createDb();
	createTable();
	closeDb();
}

?>