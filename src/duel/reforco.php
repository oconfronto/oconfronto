<?php

declare(strict_types=1);

$selectmana = $db->GetOne("select `mana` from `blueprint_magias` where `id`=1");
$mana = $player->reino == '1' || $player->vip > time() ? $selectmana - 5 : $selectmana;

$magiaatual = $player->id == ($luta['p_id'] ?? null) ? $luta['p_turnos'] ?? null : $luta['e_turnos'] ?? null;

$log = explode(", ", (string) ($duellog[0] ?? null));
if ($player->mana < $mana) {
	if (($log[0] ?? null) != 6) {
		array_unshift($duellog, "6, " . $player->username . "");
	}

	$otroatak = 5;
} elseif ($magiaatual != 0) {
	if (($log[0] ?? null) != 7) {
		array_unshift($duellog, "7, " . $player->username . "");
	}

	$otroatak = 5;
} else {
	if ($player->id == ($luta['p_id'] ?? null)) {
		$db->execute("update `duels` set `p_magia`='1', `p_turnos`='6' where `id`=?", [$luta['id'] ?? null]);
	} else {
		$db->execute("update `duels` set `e_magia`='1', `e_turnos`='6' where `id`=?", [$luta['id'] ?? null]);
	}

	$db->execute("update `players` set `mana`=`mana`-? where `id`=?", [$mana, $player->id]);
	array_unshift($duellog, "3, " . $player->username . ", reforço");
}
