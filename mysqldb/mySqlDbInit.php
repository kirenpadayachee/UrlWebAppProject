<?php

/*
	This class will do checks for the mySql DB and initialize it if needed.

	Author: Kiren Padayachee
*/

class MySqlDbInit
{

	// Global variables ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	private $isDbConnected;
	private $conn;
	private static $dbhost;
	private static $dbuser;
	private static $dbpass;
	private static $dbName;
	private static $dbCreateDbQuery;
	private static $tableName;
	private static $tableCreateQuery;
	private static $tableCheckQuery;
	
	// Handle Connecting and Disconnecting ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function MySqlDbInit()
	{
		$this->isDbConnected = false;
		$this->conn = null;
		self::$dbhost = 'localhost:3306';
		self::$dbuser = 'root';
		self::$dbpass = 'mysql';
		self::$dbName = 'UrlWebAppDb';
		self::$dbCreateDbQuery = "CREATE DATABASE " . self::$dbName;
		self::$tableName = "HttpPair";
		self::$tableCreateQuery = "CREATE TABLE " . self::$tableName . " (
		httpRequestUrl VARCHAR(255) NOT NULL PRIMARY KEY,
		httpRequestType VARCHAR(255) NOT NULL,
		httpResponseStatusCode int NOT NULL,
		httpResponseMessage TEXT NOT NULL
		)";
		self::$tableCheckQuery = "SHOW TABLES LIKE '" . self::$tableName . "' ";
	}

	public function connectToDb()
	{
	   $this->conn = mysql_connect(self::$dbhost, self::$dbuser, self::$dbpass);
	   
	   if(! $this->conn ) {
		  die("Could not connect: " . mysql_error() . "\n");
	   }
	   
	   $this->isDbConnected = true;
	   
	   echo nl2br("Connected successfully \n");
	}

	public function closeDb()
	{
		mysql_close($this->conn);
		echo nl2br("Closed successfully \n");
	}

	//Create DB ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function createDb()
	{
		if (!mysql_select_db(self::$dbName)) 
		{
			echo nl2br("Creating database!\n");
			mysql_query(self::$dbCreateDbQuery);
		}
	}

	// Create Table ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function createTable()
	{
		mysql_select_db(self::$dbName);
		
		$result = mysql_query(self::$tableCheckQuery);
		$tableExists = mysql_num_rows($result) > 0;
		
		if(!$tableExists)
		{
			echo nl2br("Creating table!\n");
			mysql_query(self::$tableCreateQuery);
		}
	}

	// Iniit DB ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function initDb()
	{
		$this->connectToDb();

		if ($this->isDbConnected)
		{
			$this->createDb();
			$this->createTable();
			$this->closeDb();
		}
	}
}

?>