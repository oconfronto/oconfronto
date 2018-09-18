<?php
$query = $db->execute("select `id` from `guilds` where `pagopor`<?", array(time()));
if ($query->recordcount() > 0)
{
	$query0 = $db->execute("select `id`, `leader`, `name`, `gold` from `guilds` where `pagopor`<?", array(time()));
	 	while($guild = $query0->fetchrow()) {

			$query4 = $db->execute("select `id` from `players` where `guild`=?", array($guild['id']));
				while($member = $query4->fetchrow()) {
				$logmsg = "A gangue " . $guild['name'] . " foi deletada, pois seus administradores deixaram de paga-la.";
				addlog($member['id'], $logmsg, $db);
				}

        		$db->execute("delete from `guilds` where `id`=?", array($guild['id']));
			$db->execute("update `players` set `bank`=`bank`+? where `username`=?", array($guild['gold'], $guild['leader']));
        		$db->execute("delete from `guild_invites` where `guild_id`=?", array($guild['id']));
        		$db->execute("delete from `guild_chat` where `guild_id`=?", array($guild['id']));
        		$db->execute("delete from `guild_enemy` where (`guild_na`=? or `enemy_na`=?)", array($guild['id'], $guild['id']));
        		$db->execute("delete from `guild_aliance` where (`guild_na`=? or `aled_na`=?)", array($guild['id'], $guild['id']));
        		$db->execute("delete from `guild_paliance` where (`guild_na`=? or `aled_na`=?)", array($guild['id'], $guild['id']));
			$db->execute("update `players` set `guild`=? where `guild`=?", array(NULL, $guild['id']));

		}
}
?>