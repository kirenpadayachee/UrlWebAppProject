<?php

/*
	This class will do checks for the mySql DB and initialize it if needed.

	Author: Kiren Padayachee
*/

include("mysqldb/mySqlDbConnection.php");

class MySqlDbInit
{
	private static $dbCreateDbQuery;
	private static $tableCreateQuery;
	
	public static function init()
	{
		self::$dbCreateDbQuery = "CREATE DATABASE IF NOT EXISTS " . MySqlDbConnection::getDbName();
		self::$tableCreateQuery = "CREATE TABLE IF NOT EXISTS " . MySqlDbConnection::getHttpPairTableName() . " (
		httpRequestUrl VARCHAR(255) NOT NULL,
		httpRequestType VARCHAR(255) NOT NULL,
		httpResponseStatusCode int NOT NULL,
		httpResponseMessage TEXT NOT NULL,
		PRIMARY KEY (httpRequestUrl, httpRequestType)
		)";
	}

	//Create DB ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	private static function createDb()
	{
		$conn = MySqlDbConnection::getMySqlConnection();
		
		if(!is_null($conn))
		{
			if ($conn->query(self::$dbCreateDbQuery) === TRUE) 
			{
				echo nl2br("Database created successfully \n");
			} 
			else 
			{
				echo nl2br("Error creating database: " . $conn->error . "\n");
			}
			
			$conn->close();
		}
	}

	// Create Table ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	private static function createTable()
	{
		$conn = MySqlDbConnection::getDbConnection();
		
		if(!is_null($conn))
		{
			if ($result = $conn->query(self::$tableCreateQuery) === TRUE) 
			{
				echo nl2br("Table created successfully \n");
			} 
			else 
			{
				echo nl2br("Error creating table: " . $conn->error . "\n");
			}
			
			$conn->close();
		}
	}

	// Iniit DB ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public static function initDb()
	{
		self::createDb();
		self::createTable();
	}
}

MySqlDbInit::init();
MySqlDbInit::initDb();

?>