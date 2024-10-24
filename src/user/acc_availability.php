<?php
declare(strict_types=1);

include(__DIR__ . "/../config.php");
$tb_name = "accounts";
$username=$_POST['user_name'];

$pat[0] = "/^\s+/";
$pat[1] = "/\s{2,}/";
$pat[2] = "/\s+\$/";
$rep[0] = "";
$rep[1] = " ";
$rep[2] = "";
$nomedouser = ucwords(preg_replace($pat,$rep,$username));

$query=(sprintf("Select * from %s where conta='%s'", $tb_name, $nomedouser));
$result= $db->execute($query);
$num=$result->recordCount();
if ($num > 0) {
    //Username already exist
    echo "no";
} elseif (strlen($nomedouser) < 3) {
    echo "no";
} elseif (strlen($nomedouser) > 20) {
    echo "no";
} elseif (!preg_match("/^[A-Za-z[:space:]\-]+$/", $username)) {
    echo "no";
} else{
echo "yes";
}
?>
