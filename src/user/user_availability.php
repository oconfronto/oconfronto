<?php
include("../config.php");
$tb_name = "players";
mysql_connect($config_server, $config_username, $config_password) or die ("Can’t connect to Datebase");
mysql_select_db($config_database) or die ("Couldn’t successfully connected");
$username=$_POST['username'];
$username=$_POST['user_name'];

$pat[0] = "/^\s+/";
$pat[1] = "/\s{2,}/";
$pat[2] = "/\s+\$/";
$rep[0] = "";
$rep[1] = " ";
$rep[2] = "";
$nomedouser = ucwords(preg_replace($pat,$rep,$username));

$query=("Select * from $tb_name where username='$nomedouser'");
$result= mysql_query($query);
$num=mysql_num_rows($result);
if ($num > 0) {//Username already exist
echo "no";
} else if (strlen($nomedouser) < 3){
echo "no";
} else if (strlen($nomedouser) > 15){
echo "no";
} else if (!preg_match("/^[A-Za-z[:space:]\-]+$/", $username)){
echo "no";
}else{
echo "yes";
}
?>