<?php

$config_server = "host.docker.internal:3306";
$config_database = "database";
$config_username = "usuariosql";
$config_password = "senhasql";
$secret_key = "8ds937285odsivjs021302134dw";
$pass_encode = "f590324befwsio410-fsdor";

include('adodb/adodb.inc.php'); //Include adodb files
$db = &ADONewConnection('mysql'); //Connect to database
$conn = $db->Connect($config_server, $config_username, $config_password, $config_database); //Select table

// Define o charset para utf8
$db->Execute("SET NAMES 'utf8'");
$db->Execute("SET CHARACTER SET 'utf8'");

$db->SetFetchMode(ADODB_FETCH_ASSOC); //Fetch associative arrays
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC; //Fetch associative arrays
//$db->debug = true; //Debug

$smtp_host = "smtp.server.com";
$has_smtp_auth = true;
$smtp_username = "user@domain.com";
$smtp_password = "suasenha";
$smtp_security_method = "tls";
$smtp_port = 587;

$domain = 'ocrpg.net';
$domain_url = 'http://ocrpg.net';

?>
