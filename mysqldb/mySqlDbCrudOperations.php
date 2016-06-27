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
		self::$selectQueryMain = "SELECT " .  MySqlDbConnection::getColumnNames() . " FROM " . MySqlDbConnection::getHttpPairTableName() . " ";
		self::$updateQueryMain = "UPDATE " .  MySqlDbConnection::getHttpPairTableName() . " ";
		self::$deleteQueryMain = "DELETE FROM " . MySqlDbConnection::getHttpPairTableName() . " ";
	}

	public static function insertOrUpdateHttpPairs($httpRequestUrl, $httpRequestType, $httpResponseStatusCode, $httpResponseMessage)
	{
	    $conn = MySqlDbConnection::getDbConnection();
		
		if(!is_null($conn))
		{
			$insertValues = " VALUES ('{$httpRequestUrl}', '{$httpRequestType}', {$httpResponseStatusCode}, '{$httpResponseMessage}') ";
			$insertOnDuplicateKeys = " ON DUPLICATE KEY UPDATE " . MySqlDbConnection::getHttpResponseStatusCodeColumnName() . "=VALUES(" . MySqlDbConnection::getHttpResponseStatusCodeColumnName() . "), " . MySqlDbConnection::getHttpResponseMessageColumnName() . "=VALUES(" . MySqlDbConnection::getHttpResponseMessageColumnName() . ")";
			$insertQuery = self::$insertQueryMain . $insertValues . $insertOnDuplicateKeys;
			echo nl2br("{$insertQuery} \n");
			
			if ($result = $conn->query($insertQuery) === TRUE) 
			{
				//echo nl2br("Table Insert Success \n");
			} 
			else 
			{
				echo nl2br("Error inserting into table: " . $conn->error . "\n");
			}
			
			$conn->close();
		}
	}
	
	public static function updateHttpPairs($httpRequestUrl, $httpRequestType, $httpResponseStatusCode, $httpResponseMessage)
	{
	    $conn = MySqlDbConnection::getDbConnection();
		
		if(!is_null($conn))
		{
			$updateQuerySet = "SET " . MySqlDbConnection::getHttpResponseStatusCodeColumnName() . "='" .  $httpResponseStatusCode . "' , " . MySqlDbConnection::getHttpResponseMessageColumnName() . "='" .  $httpResponseMessage . "' ";
			$updateQueryWhere = "WHERE " . MySqlDbConnection::getHttpRequestUrlColumnName() . " LIKE '" . $httpRequestUrl . "' AND " . MySqlDbConnection::getHttpRequestTypeColumnName() . " LIKE '" . $httpRequestType . "'";
			$updateQuery = self::$updateQueryMain . $updateQuerySet . $updateQueryWhere;
			//echo nl2br("{$updateQuery} \n");
			
			if ($result = $conn->query($updateQuery) === TRUE) 
			{
				//echo nl2br("Table Update Success \n");
			} 
			else 
			{
				echo nl2br("Error updating table: " . $conn->error . "\n");
			}
			
			$conn->close();
		}
	}
	
	public static function deleteFromHttpPairs($httpRequestUrl, $httpRequestType)
	{
	    $conn = MySqlDbConnection::getDbConnection();
		
		if(!is_null($conn))
		{
			$deleteQuery = self::$deleteQueryMain . "WHERE " . MySqlDbConnection::getHttpRequestUrlColumnName() . " LIKE '" . $httpRequestUrl . "' AND " . MySqlDbConnection::getHttpRequestTypeColumnName() . " LIKE '" . $httpRequestType . "'";
			//echo nl2br("{$deleteQuery} \n");
			
			if ($result = $conn->query($deleteQuery) === TRUE) 
			{
				//echo nl2br("Table record deletion Success \n");
			} 
			else 
			{
				echo nl2br("Error deleting from table: " . $conn->error . "\n");
			}
			
			$conn->close();
		}
	}
	
	public static function getResultSetForSelectAllFromHttpPairs()
	{
	    $conn = MySqlDbConnection::getDbConnection();
		$result = null;
		
		if(!is_null($conn))
		{
			$selectAllQuery = self::$selectQueryMain;
			//echo nl2br("{$selectAllQuery} \n");
			$result = $conn->query($selectAllQuery);

			$conn->close();
		}
		
		return $result;
	}
	
	public static function getResultSetForSelectOneFromHttpPairs($httpRequestUrl, $httpRequestType)
	{
	    $conn = MySqlDbConnection::getDbConnection();
		$result = null;
		
		if(!is_null($conn))
		{
			$selectOneQuery = self::$selectQueryMain . "WHERE " . MySqlDbConnection::getHttpRequestUrlColumnName() . " LIKE '" . $httpRequestUrl . "' AND " . MySqlDbConnection::getHttpRequestTypeColumnName() . " LIKE '" . $httpRequestType . "'";
			
			//echo nl2br("{$selectOneQuery} \n");
			$result = $conn->query($selectOneQuery);

			$conn->close();
		}
		
		return $result;
	}
	
	public static function getResultSetAsArray($resultSet)
	{
		$tableArray = array();
		
		if(!is_null($resultSet))
		{
			while($row = $resultSet->fetch_assoc()) 
			{
				$rowArray = array();
				$rowArray[MySqlDbConnection::getHttpRequestUrlColumnName()] = $row[MySqlDbConnection::getHttpRequestUrlColumnName()];
				$rowArray[MySqlDbConnection::getHttpRequestTypeColumnName()] = $row[MySqlDbConnection::getHttpRequestTypeColumnName()];
				$rowArray[MySqlDbConnection::getHttpResponseStatusCodeColumnName()] = $row[MySqlDbConnection::getHttpResponseStatusCodeColumnName()];
				$rowArray[MySqlDbConnection::getHttpResponseMessageColumnName()] = $row[MySqlDbConnection::getHttpResponseMessageColumnName()];
				array_push($tableArray,$rowArray);
			}
		}
		
		return $tableArray;
	}
	
	public static function getResultSetAsHtmlString($resultSet)
	{
		$resultString = "";
		
		if(!is_null($resultSet))
		{
			$resultString .= '<table border="1" cellpadding="0" cellspacing="0" class="db-table">';
			$resultString .=  "<tr><th>" . MySqlDbConnection::getHttpRequestUrlColumnName() . "</th><th>" . MySqlDbConnection::getHttpRequestTypeColumnName() . "</th><th>" . MySqlDbConnection::getHttpResponseStatusCodeColumnName() . "</th><th>" . MySqlDbConnection::getHttpResponseMessageColumnName() . "</th></tr>";
			while($row = $resultSet->fetch_assoc()) 
			{
				$resultString .=  "<tr><th>" . $row[MySqlDbConnection::getHttpRequestUrlColumnName()] . "</th><th>" . $row[MySqlDbConnection::getHttpRequestTypeColumnName()] . "</th><th>" . $row[MySqlDbConnection::getHttpResponseStatusCodeColumnName()] . "</th><th>" . $row[MySqlDbConnection::getHttpResponseMessageColumnName()] . "</th></tr>";
			}
			$resultString .=  '</table><br />';
		}
		
		return $resultString;
	}
	
	public static function getResultSetAsXmlString($resultSet)
	{
		$resultString = "";
		
		if(!is_null($resultSet))
		{
			$resultString .= '<HttpPairs>';

			while($row = $resultSet->fetch_assoc()) 
			{
				$resultString .= '<HttpPair>';
				$resultString .= "<" . MySqlDbConnection::getHttpRequestUrlColumnName() . ">" . $row[MySqlDbConnection::getHttpRequestUrlColumnName()] . "</" . MySqlDbConnection::getHttpRequestUrlColumnName() . ">";
				$resultString .= "<" . MySqlDbConnection::getHttpRequestTypeColumnName() . ">" . $row[MySqlDbConnection::getHttpRequestTypeColumnName()] . "</" . MySqlDbConnection::getHttpRequestTypeColumnName() . ">";
				$resultString .= "<" . MySqlDbConnection::getHttpResponseStatusCodeColumnName() . ">" . $row[MySqlDbConnection::getHttpResponseStatusCodeColumnName()] . "</" . MySqlDbConnection::getHttpResponseStatusCodeColumnName() . ">";
				$resultString .= "<" . MySqlDbConnection::getHttpResponseMessageColumnName() . ">" . $row[MySqlDbConnection::getHttpResponseMessageColumnName()] . "</" . MySqlDbConnection::getHttpResponseMessageColumnName() . ">";
				$resultString .= '</HttpPair>';
			}
			
			$resultString .=  '</HttpPairs>';
		}
		
		return $resultString;
	}
	
	public static function printResultSet($resultSet)
	{
		if(!is_null($resultSet))
		{
			echo '<table border="1" cellpadding="0" cellspacing="0" class="db-table">';
			echo "<tr><th>" . MySqlDbConnection::getHttpRequestUrlColumnName() . "</th><th>" . MySqlDbConnection::getHttpRequestTypeColumnName() . "</th><th>" . MySqlDbConnection::getHttpResponseStatusCodeColumnName() . "</th><th>" . MySqlDbConnection::getHttpResponseMessageColumnName() . "</th></tr>";
			while($row = $resultSet->fetch_assoc()) 
			{
				echo "<tr><th>" . $row[MySqlDbConnection::getHttpRequestUrlColumnName()] . "</th><th>" . $row[MySqlDbConnection::getHttpRequestTypeColumnName()] . "</th><th>" . $row[MySqlDbConnection::getHttpResponseStatusCodeColumnName()] . "</th><th>" . $row[MySqlDbConnection::getHttpResponseMessageColumnName()] . "</th></tr>";
			}
			echo '</table><br />';
		}
	}
}

MySqlDbCrudOperations::init();

?>