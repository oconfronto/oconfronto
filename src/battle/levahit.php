<?php

declare(strict_types=1);

$magiaatual = $db->execute("select `magia`, `turnos` from `bixos` where `player_id`=?", [$player->id]);
$magiaatual2 = $magiaatual->fetchrow();

$misschance2 = intval(random_int(0, 100));
if ($misschance2 <= $enemy->miss || ($magiaatual2['magia'] ?? null) == 6 || ($magiaatual2['magia'] ?? null) == 9) {
	array_unshift($_SESSION['battlelog'], "6, " . ucfirst($enemy->prepo) . " " . $enemy->username . " tentou te atacar mas errou!");
} else {

	$min_player = min(intval($player->mindmg), intval($player->maxdmg));
	$max_player = max(intval($player->mindmg), intval($player->maxdmg));
	$playerdamage = random_int($min_player, $max_player);

	$min_monster = min(intval($enemy->mindmg), intval($enemy->maxdmg));
	$max_monster = max(intval($enemy->mindmg), intval($enemy->maxdmg));
	$monsterdamage = random_int($min_monster, $max_monster);

	if (($magiaatual2['magia'] ?? null) == 2) {
		$porcento = $monsterdamage / 100;
		$porcento = ceil($porcento * 15);
		$monsterdamage += $porcento;
	} elseif (($magiaatual2['magia'] ?? null) == 11) {
		$monsterdamage = ceil($monsterdamage / 2);
	} elseif (($magiaatual2['magia'] ?? null) == 7) {
		$porcento = $monsterdamage / 100;
		$porcento = ceil($porcento * 20);
		$monsterdamage -= $porcento;
	}

	if (($magiaatual2['magia'] ?? null) == 10) {
		if (($bixo->hp - $monsterdamage) < 1) {
			$db->execute("update `bixos` set `hp`=0 where `player_id`=?", [$player->id]);
			array_unshift($_SESSION['battlelog'], "2, " . ucfirst($enemy->prepo) . " " . $enemy->username . " tentou te atacar mas seu ataque voltou e ele perdeu " . $monsterdamage . " de vida.");
		} else {
			$db->execute("update `bixos` set `hp`=`hp`-? where `player_id`=?", [$monsterdamage, $player->id]);
			array_unshift($_SESSION['battlelog'], "2, " . ucfirst($enemy->prepo) . " " . $enemy->username . " tentou te atacar mas seu ataque voltou e ele perdeu " . $monsterdamage . " de vida.");
		}
	} else {

		$monsterhp = $bixo->hp / $enemy->hp;
		$monsterhp = ceil($monsterhp * 100);

		$playerhp = $player->hp / $player->maxhp;
		$playerhp = ceil($playerhp * 100);

		$chancemagia = random_int(1, 10);
		if ($playerdamage > $monsterdamage && $bixo->mana >= 15 && $chancemagia > 6 && $monsterhp < 45 && $playerhp > $monsterhp) {
			$curar = random_int(intval($enemy->level), intval($enemy->level * 2));
			if (($bixo->hp + $curar) > $enemy->hp) {
				$db->execute("update `bixos` set `hp`=`hp`+?, `mana`=`mana`-15 where `player_id`=?", [$curar, $player->id]);
				array_unshift($_SESSION['battlelog'], "4, " . ucfirst($enemy->prepo) . " " . $enemy->username . " fez um feitiço e recuperou toda sua vida.");
			} else {
				$db->execute("update `bixos` set `hp`=`hp`+?, `mana`=`mana`-15 where `player_id`=?", [$curar, $player->id]);
				array_unshift($_SESSION['battlelog'], "4, " . ucfirst($enemy->prepo) . " " . $enemy->username . " fez um feitiço e recuperou " . $curar . " pontos de vida.");
			}
		} elseif ($playerdamage > $monsterdamage && $bixo->mana >= 30 && $bixo->mana < 65 && $chancemagia <= 3 && $playerhp > $monsterhp) {
			if (($player->hp - ($monsterdamage * 2)) < 1) {
				$db->execute("update `players` set `hp`=0 where `id`=?", [$player->id]);
				$morreu = 5;
			} else {
				$db->execute("update `players` set `hp`=`hp`-? where `id`=?", [($monsterdamage * 2), $player->id]);
			}

			$db->execute("update `bixos` set `mana`=`mana`-30 where `player_id`=?", [$player->id]);
			array_unshift($_SESSION['battlelog'], "4, " . ucfirst($enemy->prepo) . " " . $enemy->username . " deu um ataque duplo em você e tirou " . ($monsterdamage * 2) . " pontos de vida.");
		} elseif ($playerdamage > $monsterdamage && $bixo->mana >= 65 && $chancemagia <= 3 && $playerhp > $monsterhp) {
			if (($player->hp - ($monsterdamage * 4)) < 1) {
				$db->execute("update `players` set `hp`=0 where `id`=?", [$player->id]);
				$morreu = 5;
			} else {
				$db->execute("update `players` set `hp`=`hp`-? where `id`=?", [($monsterdamage * 4), $player->id]);
			}

			$db->execute("update `bixos` set `mana`=`mana`-30 where `player_id`=?", [$player->id]);
			array_unshift($_SESSION['battlelog'], "4, " . ucfirst($enemy->prepo) . " " . $enemy->username . " deu um ataque quádruplo em você e tirou " . ($monsterdamage * 4) . " pontos de vida.");
		} elseif (($player->hp - $monsterdamage) < 1) {
			$db->execute("update `players` set `hp`=0 where `id`=?", [$player->id]);
			array_unshift($_SESSION['battlelog'], "2, " . ucfirst($enemy->prepo) . " " . $enemy->username . " te atacou e você perdeu " . $monsterdamage . " de vida.");
			$morreu = 5;
		} else {
			$db->execute("update `players` set `hp`=`hp`-? where `id`=?", [$monsterdamage, $player->id]);
			array_unshift($_SESSION['battlelog'], "2, " . ucfirst($enemy->prepo) . " " . $enemy->username . " te atacou e você perdeu " . $monsterdamage . " de vida.");
		}
	}
}
