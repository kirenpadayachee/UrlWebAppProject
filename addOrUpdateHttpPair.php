<?php

include("mysqldb/mySqlDbInit.php");
include("mysqldb/mySqlDbCrudOperations.php");

if(array_key_exists("insertOrUpdate",$_POST))
{
	if(array_key_exists("httpRequestUrl",$_POST) &&  array_key_exists("httpRequestType",$_POST) && array_key_exists("httpResponseStatusCode",$_POST) && array_key_exists("httpResponseMessage",$_POST))
	{
		$result = MySqlDbCrudOperations::insertOrUpdateHttpPairs($_POST["httpRequestUrl"], $_POST["httpRequestType"], $_POST["httpResponseStatusCode"], $_POST["httpResponseMessage"]);

		if($result == 0)
		{
			echo "Insert/Update success!";
		}
		else
		{
			echo "Insert/Update failed!";
		}
	}
	else
	{
		echo "Insert/Update failed. Check parameters : httpRequestUrl, httpRequestType, httpResponseStatusCode, httpResponseMessage";
	}
}

echo "<br><br>";		

$html = file_get_contents("addOrUpdateHttpPairHtmlTemplate.html");
echo $html;

echo MySqlDbCrudOperations::getResultSetAsHtmlString(MySqlDbCrudOperations::getResultSetForSelectAllFromHttpPairs());

?>