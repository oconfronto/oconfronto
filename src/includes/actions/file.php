<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
$player = check_user($db);

$db->execute("update `players` set `hp`=? where `id`=?", [$_GET['StatusId'], $player->id]);

echo 'Employee Updated';

?>
