<?php
declare(strict_types=1);

$luck = random_int(0, 10);
$playerdamage = random_int($player->mindmg, $player->maxdmg);
$monsterdamage = random_int($enemy->mindmg, $enemy->maxdmg);

$monsterhp = $bixo->hp / $enemy->hp;
$monsterhp = ceil($monsterhp * 100);

$playerhp = $player->hp / $player->maxhp;
$playerhp = ceil($playerhp * 100);

	if ($luck < 4 || $luck < 8 && $playerdamage > $monsterdamage || $playerhp > ($monsterhp * 1.75) || ($playerdamage - $monsterdamage) > 65){
		$fugiu = 5;
	}else{
		array_unshift($_SESSION['battlelog'], "5, Você tentou fugir mas falhou.");
		$morreu = 5;
	}
?>
