<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");

if (isset($_SESSION['Login']) && isset($_SESSION['Login']['player_id']) && $_SESSION['Login']['player_id'] > 0) {
	$player = check_user($db);
	$querydelete = $db->execute("select * from `user_online` where `player_id`=?", [$player->id]);
	if ($querydelete->recordcount() == 1) {
		$delete = $querydelete->fetchrow();
		$db->execute("update `players` set `uptime`=`uptime`+? where `id`=?", [$delete['time'] - $delete['login'], $delete['player_id']]);
		$db->execute("delete from `user_online` where `id`=?", [$delete['id']]);
	}
}

session_unset();
session_destroy();

header("Location: index.php");
exit;
