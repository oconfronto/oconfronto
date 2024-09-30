<?php

$config_server = "mysql";
$config_database = $_ENV['MYSQL_DATABASE'];
$config_username = $_ENV['MYSQL_USER'];
$config_password = $_ENV['MYSQL_PASSWORD'];

include('adodb/adodb.inc.php'); //Include adodb files
$db = &ADONewConnection('mysql'); //Connect to database
$conn = $db->Connect($config_server, $config_username, $config_password, $config_database); //Select table

// Define o charset para utf8
$db->Execute("SET NAMES 'utf8'");
$db->Execute("SET CHARACTER SET 'utf8'");

$db->SetFetchMode(ADODB_FETCH_ASSOC); //Fetch associative arrays
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC; //Fetch associative arrays
//$db->debug = true; //Debug

$smtp_host = $_ENV['SMTP_HOST'];
$smtp_password = $_ENV['SMTP_PASSWORD'];
$smtp_username = $_ENV['SMTP_USER'];
$has_smtp_auth = $_ENV['SMTP_AUTH'] == "true" ? true : false;
$smtp_security_method = $_ENV['SMTP_SECURITY_METHOD'];
$smtp_port = $_ENV['SMTP_PORT'];

$domain = $_ENV['DOMAIN'];
$domain_url = $_ENV['DOMAIN_URL'];

?>
