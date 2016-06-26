<?php

/*
	This class provides CRUD operations for the mySQL DB

	Author: Kiren Padayachee
*/

class MySqlDbCrudOperations
{
	private static $insertQueryMain;
	private static $selectQueryMain;
	private static $updateQueryMain;
	private static $deleteQueryMain;
	
	public static function init()
	{
		self::$insertQueryMain = "INSERT INTO " . MySqlDbConnection::getHttpPairTableName() . "(" . MySqlDbConnection::getColumnNames() . ") ";
		self::$selectQueryMain = "SELECT " .  MySqlDbConnection::getColumnNames() . " FROM " . MySqlDbConnection::getHttpPairTableName();
		self::$updateQueryMain = "UPDATE " .  MySqlDbConnection::getHttpPairTableName() . " ";
		self::$deleteQueryMain = "DELETE FROM " . MySqlDbConnection::getHttpPairTableName() . " ";
	}

	public static function insertIntoHttpPairs($httpRequestUrl, $httpRequestType, $httpResponseStatusCode, $httpResponseMessage)
	{
	    $conn = MySqlDbConnection::getDbConnection();
		$insertQuery = self::$insertQueryMain . "VALUES ('{$httpRequestUrl}', '{$httpRequestType}', {$httpResponseStatusCode}, '{$httpResponseMessage}')";
		//echo nl2br("{$insertQuery} \n");
		
		if ($result = $conn->query($insertQuery) === TRUE) 
		{
			echo nl2br("Table Insert Success \n");
		} 
		else 
		{
			echo nl2br("Error inserting into table: " . $conn->error . "\n");
		}
		
		$conn->close();
	}
	
	public static function updateHttpPairs($httpRequestUrl, $httpRequestType, $httpResponseStatusCode, $httpResponseMessage)
	{
	    $conn = MySqlDbConnection::getDbConnection();
		$updateQuerySet = "SET " . MySqlDbConnection::getHttpResponseStatusCodeColumnName() . "='" .  $httpResponseStatusCode . "' , " . MySqlDbConnection::getHttpResponseMessageColumnName() . "='" .  $httpResponseMessage . "' ";
		$updateQueryWhere = "WHERE " . MySqlDbConnection::getHttpRequestUrlColumnName() . " LIKE '" . $httpRequestUrl . "' AND " . MySqlDbConnection::getHttpRequestTypeColumnName() . " LIKE '" . $httpRequestType . "'";
		$updateQuery = self::$updateQueryMain . $updateQuerySet . $updateQueryWhere;
		echo nl2br("{$updateQuery} \n");
		
		if ($result = $conn->query($updateQuery) === TRUE) 
		{
			echo nl2br("Table Update Success \n");
		} 
		else 
		{
			echo nl2br("Error updating table: " . $conn->error . "\n");
		}
		
		$conn->close();
	}
	
	public static function deleteFromHttpPairs($httpRequestUrl, $httpRequestType)
	{
	    $conn = MySqlDbConnection::getDbConnection();
		$deleteQuery = self::$deleteQueryMain . "WHERE " . MySqlDbConnection::getHttpRequestUrlColumnName() . " LIKE '" . $httpRequestUrl . "' AND " . MySqlDbConnection::getHttpRequestTypeColumnName() . " LIKE '" . $httpRequestType . "'";
		//echo nl2br("{$deleteQuery} \n");
		
		if ($result = $conn->query($deleteQuery) === TRUE) 
		{
			echo nl2br("Table record deletion Success \n");
		} 
		else 
		{
			echo nl2br("Error deleting from table: " . $conn->error . "\n");
		}
		
		$conn->close();
	}
}

MySqlDbCrudOperations::init();

?>