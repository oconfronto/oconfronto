<?php
$selectmana = $db->GetOne("select `mana` from `blueprint_magias` where `id`=6");
if (($player->reino == '1') or ($player->vip > time())) {
	$mana = ($selectmana - 5);
} else {
	$mana = $selectmana;
}

$fastmagia = 6;
$fastturno = 3;

	$player->mana -= $mana;
	array_unshift($_SESSION['battlelog'], "3, Você lançou o feitiço defesa dupla.");
?>