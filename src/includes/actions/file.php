<?php
include("lib.php");
$player = check_user($secret_key, $db);

$db->execute("update `players` set `hp`=? where `id`=?", array($_GET['StatusId'], $player->id));

echo 'Employee Updated';

?>