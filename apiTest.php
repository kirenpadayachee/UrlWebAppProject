<?php

include("mysqldb/mySqlDbInit.php");
include("mysqldb/mySqlDbCrudOperations.php");

echo MySqlDbCrudOperations::getResultSetAsHtmlString(MySqlDbCrudOperations::getResultSetForSelectAllFromHttpPairs());

$html = file_get_contents("apiTestHtmlTemplate.html");
echo $html;



?>