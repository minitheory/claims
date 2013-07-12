<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"

// old connection method
/* 
$hostname_claims = "localhost"; //$hostname_claims = "www.theonthai.com";
$database_claims = "claims2";
$username_claims = "root";
$password_claims = "root";
$claims = mysql_pconnect($hostname_claims, $username_claims, $password_claims) or trigger_error(mysql_error(),E_USER_ERROR); 

mysql_select_db($database_claims, $claims);

*/

//new PDO method
$db = new PDO('mysql:host=localhost;dbname=claims2','root','root');

//$db = new PDO('mysql:host=localhost;dbname=minith_claims2','minith_admin','qwer1234');

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>