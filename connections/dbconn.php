<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_claims = "localhost"; //$hostname_claims = "www.theonthai.com";
$database_claims = "claims2";
$username_claims = "root";
$password_claims = "root";
$claims = mysql_pconnect($hostname_claims, $username_claims, $password_claims) or trigger_error(mysql_error(),E_USER_ERROR); 

mysql_select_db($database_claims, $claims);
?>
