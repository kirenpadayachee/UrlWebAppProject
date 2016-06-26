<?php

/*
	This class handles connection to the mySQL DB

	Author: Kiren Padayachee
*/

class MySqlDbConnection
{
	private static $dbhost = 'localhost:3306';
	private static $dbuser = 'root';
	private static $dbpass = 'mysql';
	private static $dbName = 'UrlWebAppDb';
	private static $httpPairTableName  = "HttpPair";
	private static $httpRequestUrlCol	= "httpRequestUrl";
	private static $httpRequestTypeCol	= "httpRequestType";
	private static $httpResponseStatusCodeCol	= "httpResponseStatusCode";
	private static $httpResponseMessageCol	= "httpResponseMessage";
	
	public function isDbConnected()
	{
		return $this->isDbConnected;
	}
	
	public static function getDbHost()
	{
		return self::$dbhost;
	}
	
	public static function getDbUser()
	{
		return self::$dbuser;
	}
	
	public static function getDbPassword()
	{
		return self::$dbpass;
	}
	
	public static function getDbName()
	{
		return self::$dbName;
	}
	
	public static function getHttpPairTableName()
	{
		return self::$httpPairTableName;
	}
	
	public static function getColumnNames()
	{
		return self::$httpRequestUrlCol . ", " . self::$httpRequestTypeCol . ", " . self::$httpResponseStatusCodeCol . ", " . self::$httpResponseMessageCol;
	}
	
	public static function getHttpRequestUrlColumnName()
	{
		return self::$httpRequestUrlCol;
	}
	
	public static function getHttpRequestTypeColumnName()
	{
		return self::$httpRequestTypeCol;
	}
	
	public static function getHttpResponseStatusCodeColumnName()
	{
		return self::$httpResponseStatusCodeCol;
	}
	
	public static function getHttpResponseMessageColumnName()
	{
		return self::$httpResponseMessageCol;
	}
	
	public static function getMySqlConnection()
	{
		// Create connection
		$conn = new mysqli(self::$dbhost, self::$dbuser, self::$dbpass);
		// Check connection
		if ($conn->connect_error) {
			echo("Connection failed: " . $conn->connect_error);
			return null;
		} 
		
		return $conn;
	}
	
	public static function getDbConnection()
	{
		// Create connection
		$conn = new mysqli(self::$dbhost, self::$dbuser, self::$dbpass, self::$dbName);
		// Check connection
		if ($conn->connect_error) {
			echo("Connection failed: " . $conn->connect_error);
			return null;
		} 
		
		return $conn;
	}
	
}

?>