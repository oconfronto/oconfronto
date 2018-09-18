<?php
include("lib.php");
$player = check_user($secret_key, $db);

	$querydelete = $db->execute("select * from `user_online` where `player_id`=?", array($player->id));
	if ($querydelete->recordcount() == 1)
	{
		$delete = $querydelete->fetchrow();
		$db->execute("update `players` set `uptime`=`uptime`+? where `id`=?", array($delete['time'] - $delete['login'], $delete['player_id']));
		$db->execute("delete from `user_online` where `id`=?", array($delete['id']));
	}

	unset($_SESSION['Login']['player_id']);

	unset($_SESSION['battlelog']);
	unset($_SESSION['statuslog']);

	unset($_SESSION['chatHistory']);
	unset($_SESSION['openChatBoxes']);
	unset($_SESSION['tsChatBoxes']);

header("Location: characters.php");
exit;
?>