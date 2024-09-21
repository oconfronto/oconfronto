<?php
$luck = rand(0, 10);
$playerdamage = rand($player->mindmg, $player->maxdmg);
$monsterdamage = rand($enemy->mindmg, $enemy->maxdmg);

$monsterhp = $bixo->hp / $enemy->hp;
$monsterhp = ceil($monsterhp * 100);

$playerhp = $player->hp / $player->maxhp;
$playerhp = ceil($playerhp * 100);

	if (($luck < 4) or (($luck < 8) and ($playerdamage > $monsterdamage)) or ($playerhp > ($monsterhp * 1.75)) or (($playerdamage - $monsterdamage) > 65)){
		$fugiu = 5;
	}else{
		array_unshift($_SESSION['battlelog'], "5, Você tentou fugir mas falhou.");
		$morreu = 5;
	}
?>
