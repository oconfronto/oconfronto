<?php

declare(strict_types=1);

$luck = random_int(0, 10);

// Calculate damage
$min_player = min(intval($player->mindmg), intval($player->maxdmg));
$max_player = max(intval($player->mindmg), intval($player->maxdmg));
$playerdamage = random_int($min_player, $max_player);

$min_monster = min(intval($enemy->mindmg), intval($enemy->maxdmg));
$max_monster = max(intval($enemy->mindmg), intval($enemy->maxdmg));
$monsterdamage = random_int($min_monster, $max_monster);

$monsterhp = $bixo->hp / $enemy->hp;
$monsterhp = ceil($monsterhp * 100);

$playerhp = $player->hp / $player->maxhp;
$playerhp = ceil($playerhp * 100);

if ($luck < 4 || $luck < 8 && $playerdamage > $monsterdamage || $playerhp > ($monsterhp * 1.75) || ($playerdamage - $monsterdamage) > 65) {
	$fugiu = 5;
} else {
	array_unshift($_SESSION['battlelog'], "5, Você tentou fugir mas falhou.");
	$morreu = 5;
}
