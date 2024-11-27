<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");

if (($_SESSION['Login'] ?? null) && ($_SESSION['Login']['player_id'] ?? null) && (($_SESSION['Login'] ?? null)['player_id'] ?? null) > 0) {
	$player = check_user($db);
	$querydelete = $db->execute("select * from `user_online` where `player_id`=?", [$player->id]);
	if ($querydelete->recordcount() == 1) {
		$delete = $querydelete->fetchrow();
		$db->execute("update `players` set `uptime`=`uptime`+? where `id`=?", [$delete['time'] - $delete['login'], $delete['player_id'] ?? null]);
		$db->execute("delete from `user_online` where `id`=?", [$delete['id'] ?? null]);
	}
}

session_unset();
session_destroy();

header("Location: index.php");
exit;
