<?php
declare(strict_types=1);

//setcookie("battlelog", serialize(array()), time()+3600);
//array_unshift(unserialize($_COOKIE["battlelog"]), "testkhdsjadhkasjdee");

//echo array_keys(unserialize($_COOKIE["battlelog"]));

//setcookie("battlelog", "", time()-3600);

//setcookie("battlelog", serialize(array("laranja", "banana")), time()+3600);
//array_unshift(serialize($_COOKIE["battlelog"]), "melancia", "morango");

//print_r(unserialize($_COOKIE["battlelog"]));


//setcookie("battlelog", implode("+",array("laranja", "banana")), time()+3600);
//$array = explode("+",$_COOKIE["battlelog"]);
//array_unshift($array, "melancia");
//print_r($array);

//setcookie("battlelog", implode("+",array()), time()+3600);
$battlelog = explode("+",(string) $_COOKIE["battlelog"]);
array_unshift($battlelog, "atacou blablabla");
print_r($battlelog);

?>
