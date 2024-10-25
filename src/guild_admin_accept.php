<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Aceitar Aliança");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkguild.php");


if (!$_GET['id']) {
	header("Location: home.php");
} else {
	$query = $db->execute("select * from `guild_paliance` where `id`=?", [$_GET['id']]);
	if ($query->recordcount() == 0) {
		header("Location: home.php");
	} else {
		$guild = $query->fetchrow();
	}

	$guildquery = $db->execute("select `leader`, `vice` from `guilds` where `id`=?", [$guild['aled_na']]);
	$check = $guildquery->fetchrow();

	$checkjaaly1 = $db->execute("select `id` from `guild_aliance` where `guild_na`=? and `aled_na`=?", [$guild['guild_na'], $guild['aled_na']]);
	$checkjaaly2 = $db->execute("select `id` from `guild_enemy` where `guild_na`=? and `enemy_na`=?", [$guild['guild_na'], $guild['aled_na']]);

	include(__DIR__ . "/templates/private_header.php");
	if ($player->username != $check['leader'] && $player->username != $check['vice']) {
		echo "Você não pode acessar esta página.<br/>";
		echo '<a href="home.php">Principal</a>';
	} elseif ($checkjaaly1->recordcount() > 0) {
		echo "Seu clã ja possui uma aliança com o clã " . $guild['guild_na'] . ".<br/>";
		echo '<a href="home.php">Principal</a>';
	} elseif ($checkjaaly2->recordcount() > 0) {
		echo "Este clã te marcou como inimigo! O pedido de aliança foi cancelado.<br/>";
		echo '<a href="home.php">Principal</a>';
	} else {
		$insert['guild_na'] = $guild['guild_na'];
		$insert['aled_na'] = $guild['aled_na'];
		$insert['time'] = time();
		$query = $db->autoexecute('guild_aliance', $insert, 'INSERT');

		$insert['guild_na'] = $guild['aled_na'];
		$insert['aled_na'] = $guild['guild_na'];
		$insert['time'] = time();
		$query = $db->autoexecute('guild_aliance', $insert, 'INSERT');

		$query = $db->execute("delete from `guild_paliance` where `id`=?", [$_GET['id']]);

		$msg1 = $db->GetOne("select `name` from `guilds` where `id`=?", [$guild['guild_na']]);
		$msg2 = $db->GetOne("select `name` from `guilds` where `id`=?", [$guild['aled_na']]);

		$log1 = $db->execute("select `id` from `players` where `guild`=?", [$guild['guild_na']]);
		while ($p1 = $log1->fetchrow()) {
			$logmsg1 = "Agora seu clã é aliado do clã <a href=\"guild_profile.php?id=" . $guild['aled_na'] . '">' . $msg1 . "</a>.";
			addlog($p1['id'], $logmsg1, $db);
		}

		$log2 = $db->execute("select `id` from `players` where `guild`=?", [$guild['aled_na']]);
		while ($p2 = $log2->fetchrow()) {
			$logmsg2 = "Agora seu clã é aliado do clã <a href=\"guild_profile.php?id=" . $guild['guild_na'] . '">' . $msg2 . "</a>.";
			addlog($p2['id'], $logmsg2, $db);
		}


		echo "Agora seu clã é aliado do clã " . $msg1 . ".<br/>";
		echo '<a href="home.php">Principal</a>';
	}

	include(__DIR__ . "/templates/private_footer.php");
}
