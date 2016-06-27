<?php

include("mysqldb/mySqlDbInit.php");
include("mysqldb/mySqlDbCrudOperations.php");

echo MySqlDbCrudOperations::getResultSetAsHtmlString(MySqlDbCrudOperations::getResultSetForSelectAllFromHttpPairs());

?>