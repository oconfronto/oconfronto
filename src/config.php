<?php

$config_server = "mysql";
$config_database = getenv('MYSQL_DATABASE');
$config_username = getenv('MYSQL_USER');
$config_password = getenv('MYSQL_PASSWORD');

include('../vendor/adodb/adodb-php/adodb.inc.php'); //Include adodb files
$db = &ADONewConnection('mysql'); //Connect to database
$conn = $db->Connect($config_server, $config_username, $config_password, $config_database); //Select table

// Define o charset para utf8
$db->Execute("SET NAMES 'utf8'");
$db->Execute("SET CHARACTER SET 'utf8'");

$db->SetFetchMode(ADODB_FETCH_ASSOC); //Fetch associative arrays
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC; //Fetch associative arrays
//$db->debug = true; //Debug

$smtp_host = getenv('SMTP_HOST');
$smtp_password = getenv('SMTP_PASSWORD');
$smtp_username = getenv('SMTP_USER');
$has_smtp_auth = getenv('SMTP_AUTH') == "true" ? true : false;
$smtp_security_method = getenv('SMTP_SECURITY_METHOD');
$smtp_port = getenv('SMTP_PORT');

$domain = getenv('DOMAIN');
$domain_url = getenv('DOMAIN_URL');

?>
