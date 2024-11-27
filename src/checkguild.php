<?php

declare(strict_types=1);

$query = $db->execute("select `id` from `guilds` where `pagopor`<?", [time()]);
if ($query->recordcount() > 0) {
	$query0 = $db->execute("select `id`, `leader`, `name`, `gold` from `guilds` where `pagopor`<?", [time()]);
	while ($guild = $query0->fetchrow()) {

		$query4 = $db->execute("select `id` from `players` where `guild`=?", [$guild['id'] ?? null]);
		while ($member = $query4->fetchrow()) {
			$logmsg = "A gangue " . $guild['name'] . " foi deletada, pois seus administradores deixaram de pagá-la.";
			addlog($member['id'], $logmsg, $db);
		}

		$db->execute("delete from `guilds` where `id`=?", [$guild['id'] ?? null]);
		$db->execute("update `players` set `bank`=`bank`+? where `username`=?", [$guild['gold'] ?? null, $guild['leader'] ?? null]);
		$db->execute("delete from `guild_invites` where `guild_id`=?", [$guild['id'] ?? null]);
		$db->execute("delete from `guild_chat` where `guild_id`=?", [$guild['id'] ?? null]);
		$db->execute("delete from `guild_enemy` where (`guild_na`=? or `enemy_na`=?)", [$guild['id'] ?? null, $guild['id'] ?? null]);
		$db->execute("delete from `guild_aliance` where (`guild_na`=? or `aled_na`=?)", [$guild['id'] ?? null, $guild['id'] ?? null]);
		$db->execute("delete from `guild_paliance` where (`guild_na`=? or `aled_na`=?)", [$guild['id'] ?? null, $guild['id'] ?? null]);
		$db->execute("update `players` set `guild`=? where `guild`=?", [NULL, $guild['id'] ?? null]);
	}
}
