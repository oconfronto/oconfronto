<?php
$selectmana = $db->GetOne("select `mana` from `blueprint_magias` where `id`=4");
if (($player->reino == '1') or ($player->vip > time())) {
	$mana = ($selectmana - 5);
} else {
	$mana = $selectmana;
}

if ($player->level < 50){
	$curar = rand(30, 100);
}else{
	$curar = rand($player->level, ($player->level * 2));
}

	$player->mana -= $mana;

	if (($player->hp + $curar) > $player->maxhp){
		$player->hp = $player->maxhp;
		array_unshift($_SESSION['battlelog'], "3, Você fez um feitiço e recuperou toda sua vida.");
	}else{
		$player->hp += $curar;
		array_unshift($_SESSION['battlelog'], "3, Você fez um feitiço e recuperou " . $curar . " pontos de vida.");
	}
?>