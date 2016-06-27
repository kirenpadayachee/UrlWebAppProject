<?php

include("mysqldb/mySqlDbInit.php");
include("mysqldb/mySqlDbCrudOperations.php");

$html = file_get_contents("apiTestHtmlTemplate.html");
echo $html;

echo MySqlDbCrudOperations::getResultSetAsHtmlString(MySqlDbCrudOperations::getResultSetForSelectAllFromHttpPairs());

?>