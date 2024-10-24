<?php
declare(strict_types=1);

$luck = rand(0, 10);
$playerdamage = rand($player->mindmg, $player->maxdmg);
$enemydamage = rand($enemy->mindmg, $enemy->maxdmg);

$enemyhp = $enemy->hp / $enemy->maxhp;
$enemyhp = ceil($enemyhp * 100);

$playerhp = $player->hp / $player->maxhp;
$playerhp = ceil($playerhp * 100);

	if ($luck < 4 || $luck <= 5 && $playerdamage > $enemydamage || $playerhp > ($enemyhp * 1.75) && $luck <= 7 || ($playerdamage - $enemydamage) > 65){
		$fugiu = 5;
        array_unshift($duellog, "12, " . $player->username . ", fugiu");
	} else {
		$morreu = 5;
        array_unshift($duellog, "11, " . $player->username . ", morreu");
	}
?>
