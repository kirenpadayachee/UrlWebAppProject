<?php

include("mysqldb/mySqlDbInit.php");

$html = file_get_contents("apiTestHtmlTemplate.html");
echo $html;

?>