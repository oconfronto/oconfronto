<?php
declare(strict_types=1);

include(__DIR__ . "/../config.php");
$username = $_POST['user_name'];

$pat[0] = "/^\s+/";
$pat[1] = "/\s{2,}/";
$pat[2] = "/\s+\$/";
$rep[0] = "";
$rep[1] = " ";
$rep[2] = "";
$nomedouser = ucwords(preg_replace($pat, $rep, $username));
$query = $db->execute("select * from `players` where `username`=?", array($nomedouser));
// $query = ("Select * from $tb_name where username='$nomedouser'");
// $result = mysql_query($query);
// $num = mysql_num_rows($result);
if ($query->recordcount() > 0) {
    //Username already exist
    echo "no";
} elseif (strlen($nomedouser) < 3) {
    echo "no";
} elseif (strlen($nomedouser) > 15) {
    echo "no";
} elseif (!preg_match("/^[A-Za-z[:space:]\-]+$/", $username)) {
    echo "no";
} else {
    echo "yes";
}
