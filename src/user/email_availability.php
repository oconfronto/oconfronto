<?php
declare(strict_types=1);

include(__DIR__ . "/../config.php");
$email=$_POST['email_name'];
$query = $db->execute("Select `id` from `accounts` where `email`=?", [$email]);

if ($query->recordcount() > 0) {
    //Username already exist
    echo "no";
} elseif (!preg_match("/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+@([-0-9A-Z]+\.)+([0-9A-Z]){2,4}$/i", $email)) {
    echo "no";
} elseif (strlen($email) < 5) {
    echo "no";
} else{
echo "yes";
}

?>
