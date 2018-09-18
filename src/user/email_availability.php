<?php
include("../config.php");
$email=$_POST['email'];
$email=$_POST['email_name'];
$query = $db->execute("Select `id` from `accounts` where `email`=?", array($email));

if ($query->recordcount() > 0) {//Username already exist
echo "no";
}else if (!preg_match("/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+@([-0-9A-Z]+\.)+([0-9A-Z]){2,4}$/i", $email)){
echo "no";
} else if (strlen($email) < 5){
echo "no";
}else{
echo "yes";
}

?>