<?php

include("mysqldb/mySqlDbInit.php");
include("mysqldb/mySqlDbCrudOperations.php");

if(array_key_exists("delete",$_POST))
{
	if(array_key_exists("httpRequestUrl",$_POST) &&  array_key_exists("httpRequestType",$_POST))
	{
		$result = MySqlDbCrudOperations::deleteFromHttpPairs($_POST["httpRequestUrl"], $_POST["httpRequestType"]);

		if($result == 0)
		{
			echo "Delete success!";
		}
		else
		{
			echo "Delete failed!";
		}
	}
	else
	{
		echo "Delete failed. Check parameters : httpRequestUrl, httpRequestType";
	}
}

echo "<br><br>";		

$html = file_get_contents("deleteHttpPairHtmlTemplate.html");
echo $html;

echo MySqlDbCrudOperations::getResultSetAsHtmlString(MySqlDbCrudOperations::getResultSetForSelectAllFromHttpPairs());

?>