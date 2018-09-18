<?php
$selectmana = $db->GetOne("select `mana` from `blueprint_magias` where `id`=3");
if (($player->reino == '1') or ($player->vip > time())) {
	$mana = ($selectmana - 5);
} else {
	$mana = $selectmana;
}

$pak0 = rand($player->mindmg, $player->maxdmg);
$pak1 = rand($player->mindmg, $player->maxdmg);
$totalpak = ceil($pak0 + $pak1);

if ($fastmagia == 1){
	$porcento = $totalpak / 100;
	$porcento = ceil($porcento * 15);
	$totalpak = $totalpak + $porcento;
}else if($fastmagia == 2){
	$porcento = $totalpak / 100;
	$porcento = ceil($porcento * 45);
	$totalpak = $totalpak + $porcento;
}else if($fastmagia == 12){
	$porcento = $totalpak / 100;
	$porcento = ceil($porcento * 35);
	$totalpak = $totalpak + $porcento;
}

	if (($bixo->hp - $totalpak) < 1){
		$bixo->hp = 0;
		$matou = 5;
	}else{
		$bixo->hp -= $totalpak;
	}

		$player->mana -= $mana;
		array_unshift($_SESSION['battlelog'], "3, Você deu um ataque duplo n" . $enemy->prepo . " " . $enemy->username . " e tirou " . $totalpak . " de vida.");
?>