<?php

include("mysqldb/mySqlDbInit.php");

$html = file_get_contents("unitTestsHtmlTemplate.html");
echo $html;

?>