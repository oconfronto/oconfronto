<?php
declare(strict_types=1);

$selectmana = $db->GetOne("select `mana` from `blueprint_magias` where `id`=3");
$mana = $player->reino == '1' || $player->vip > time() ? $selectmana - 5 : $selectmana;

$log = explode(", ", $_SESSION['battlelog'][0]);

$pak0 = random_int(intval($player->mindmg), intval($player->maxdmg));
$pak1 = random_int(intval($player->mindmg), intval($player->maxdmg));
$totalpak = ceil($pak0 + $pak1);
		
$magiaatual = $db->execute("select `magia`, `turnos` from `bixos` where `player_id`=?", [$player->id]);
$magiaatual2 = $magiaatual->fetchrow();

	if ($magiaatual2['magia'] == 1) {
     $porcento = $totalpak / 100;
     $porcento = ceil($porcento * 15);
     $totalpak += $porcento;
 } elseif ($magiaatual2['magia'] == 2) {
     $porcento = $totalpak / 100;
     $porcento = ceil($porcento * 45);
     $totalpak += $porcento;
 } elseif ($magiaatual2['magia'] == 12) {
     $porcento = $totalpak / 100;
     $porcento = ceil($porcento * 35);
     $totalpak += $porcento;
 }

		if ($player->mana < $mana){
			if ($log[1] !== "Você tentou lançar um feitiço mas está sem mana sufuciente.") {
				array_unshift($_SESSION['battlelog'], "5, Você tentou lançar um feitiço mas está sem mana sufuciente.");
			}
   
			$otroatak = 5;
		}else{

			$misschance = intval(random_int(0, 100));
			if ($misschance <= $player->miss)
			{
					array_unshift($_SESSION['battlelog'], "5, Você tentou lançar um feitiço n" . $enemy->prepo . " " . $enemy->username . " mas errou!");
					$db->execute("update `bixos` set `vez`='e' where `player_id`=?", [$player->id]);
			}else{
					if (($bixo->hp - $totalpak) < 1){
						$db->execute("update `bixos` set `hp`=0 where `player_id`=?", [$player->id]);
						$matou = 5;
					}else{
						$db->execute("update `bixos` set `hp`=`hp`-? where `player_id`=?", [$totalpak, $player->id]);
					}

				$db->execute("update `players` set `mana`=`mana`-? where `id`=?", [$mana, $player->id]);
      				array_unshift($_SESSION['battlelog'], "3, Você deu um ataque duplo n" . $enemy->prepo . " " . $enemy->username . " e tirou " . $totalpak . " pontos de vida.");
				$db->execute("update `bixos` set `vez`='e' where `player_id`=?", [$player->id]);
			}
		}
?>
