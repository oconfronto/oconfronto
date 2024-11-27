<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
$player = check_user($db);

if (($_GET['type'] ?? null) != 96 && ($_GET['type'] ?? null) < 98 && ($_GET['type'] ?? null) > 0) {
	$db->execute("update `bixos` set `type`=? where `hp`>0 and `player_id`=?", [$_GET['type'] ?? null, $player->id]);
} elseif (($_GET['type'] ?? null) == 96) {
	$db->execute("update `bixos` set `type`=? where `hp`>0 and `player_id`=?", [$_GET['type'] ?? null, $player->id]);
	header("Location: monster.php?act=attack");
	exit;
} elseif ($_GET['alterar'] ?? null) {
	$modefastbattle = $db->execute("select * from `other` where `value`=? and `player_id`=?", ['fastbattle', $player->id]);
	if ($modefastbattle->recordcount() < 1) {
		$insert['player_id'] = $player->id;
		$insert['value'] = 'fastbattle';
		$db->autoexecute('other', $insert, 'INSERT');

		$enemyid = $db->GetOne("select `id` from `bixos` where `hp`>0 and `player_id`=?", [$player->id]);
		if ($enemyid) {
			$db->execute("update `bixos` set `type`=95 where `id`=? and `player_id`=?", [$enemyid, $player->id]);
		}
	} else {
		$db->execute("delete from `other` where `value`=? and `player_id`=?", ['fastbattle', $player->id]);
	}

	header("Location: monster.php?act=attack");
	exit;
} elseif ($_GET['descarregar'] ?? null) {

	if ($_GET['times'] ?? null) {
		$vezes = floor($_GET['times']);
		if ($vezes > 1 && $player->energy >= ($vezes * 10)) {
			$enemyid = $db->GetOne("select `id` from `bixos` where `hp`>0 and `player_id`=?", [$player->id]);
			if ($enemyid) {
				$monsterhp = $db->getone("select `hp` from `monsters` where `id`=?", [$enemyid]);
				$db->execute("update `bixos` set `type`=95, `hp`=`hp`+?, `mul`=? where `id`=? and `player_id`=?", [($monsterhp * ($vezes - 1)), $vezes, $enemyid, $player->id]);
			}
		}
	}

	header("Location: monster.php?act=attack");
	exit;
}
