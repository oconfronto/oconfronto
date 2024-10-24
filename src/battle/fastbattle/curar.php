<?php
declare(strict_types=1);

$selectmana = $db->GetOne("select `mana` from `blueprint_magias` where `id`=4");
$mana = $player->reino == '1' || $player->vip > time() ? $selectmana - 5 : $selectmana;

$curar = $player->level < 50 ? rand(30, 100) : rand($player->level, ($player->level * 2));

	$player->mana -= $mana;

	if (($player->hp + $curar) > $player->maxhp){
		$player->hp = $player->maxhp;
		array_unshift($_SESSION['battlelog'], "3, Você fez um feitiço e recuperou toda sua vida.");
	}else{
		$player->hp += $curar;
		array_unshift($_SESSION['battlelog'], "3, Você fez um feitiço e recuperou " . $curar . " pontos de vida.");
	}
?>
