<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
$player = check_user($db);

$querydelete = $db->execute("select * from `user_online` where `player_id`=?", [$player->id]);
if ($querydelete->recordcount() == 1) {
	$delete = $querydelete->fetchrow();
	$db->execute("update `players` set `uptime`=`uptime`+? where `id`=?", [$delete['time'] - $delete['login'], $delete['player_id']]);
	$db->execute("delete from `user_online` where `id`=?", [$delete['id']]);
}

unset($_SESSION['Login']['player_id']);

unset($_SESSION['battlelog']);
unset($_SESSION['statuslog']);

unset($_SESSION['chatHistory']);
unset($_SESSION['openChatBoxes']);
unset($_SESSION['tsChatBoxes']);

header("Location: characters.php");
exit;
