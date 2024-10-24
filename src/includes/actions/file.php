<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
$player = check_user($secret_key, $db);

$db->execute("update `players` set `hp`=? where `id`=?", array($_GET['StatusId'], $player->id));

echo 'Employee Updated';

?>
