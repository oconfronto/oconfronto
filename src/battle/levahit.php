<?php
declare(strict_types=1);

$magiaatual = $db->execute("select `magia`, `turnos` from `bixos` where `player_id`=?", array($player->id));
$magiaatual2 = $magiaatual->fetchrow();

	$misschance2 = intval(rand(0, 100));
	if ($misschance2 <= $enemy->miss || $magiaatual2['magia'] == 6 || $magiaatual2['magia'] == 9)
	{
		array_unshift($_SESSION['battlelog'], "6, " . ucfirst($enemy->prepo) . " " . $enemy->username . " tentou te atacar mas errou!");
	}else{

		$playerdamage = rand($player->mindmg, $player->maxdmg);
		$monsterdamage = rand($enemy->mindmg, $enemy->maxdmg);
		$monsterdamage = rand($enemy->mindmg, $enemy->maxdmg);

		if ($magiaatual2['magia'] == 2){
			$porcento = $monsterdamage / 100;
			$porcento = ceil($porcento * 15);
			$monsterdamage += $porcento;
		}elseif ($magiaatual2['magia'] == 11){
			$monsterdamage = ceil ($monsterdamage / 2);
		}elseif ($magiaatual2['magia'] == 7){
			$porcento = $monsterdamage / 100;
			$porcento = ceil($porcento * 20);
			$monsterdamage -= $porcento;
		}

		if ($magiaatual2['magia'] == 10){
				if (($bixo->hp - $monsterdamage) < 1){
					$db->execute("update `bixos` set `hp`=0 where `player_id`=?", array($player->id));
					array_unshift($_SESSION['battlelog'], "2, " . ucfirst($enemy->prepo) . " " . $enemy->username . " tentou te atacar mas seu ataque voltou e ele perdeu " . $monsterdamage . " de vida.");
				}else{
					$db->execute("update `bixos` set `hp`=`hp`-? where `player_id`=?", array($monsterdamage, $player->id));
					array_unshift($_SESSION['battlelog'], "2, " . ucfirst($enemy->prepo) . " " . $enemy->username . " tentou te atacar mas seu ataque voltou e ele perdeu " . $monsterdamage . " de vida.");
				}
		}else{

			$monsterhp = $bixo->hp / $enemy->hp;
			$monsterhp = ceil($monsterhp * 100);

			$playerhp = $player->hp / $player->maxhp;
			$playerhp = ceil($playerhp * 100);

			$chancemagia = rand(1, 10);
				if ($playerdamage > $monsterdamage && $bixo->mana >= 15 && $chancemagia > 6 && $monsterhp < 45 && $playerhp > $monsterhp){
					$curar = rand($enemy->level, ($enemy->level * 2));
					if (($bixo->hp + $curar) > $enemy->hp){
						$db->execute("update `bixos` set `hp`=`hp`+?, `mana`=`mana`-15 where `player_id`=?", array($curar, $player->id));
						array_unshift($_SESSION['battlelog'], "4, " . ucfirst($enemy->prepo) . " " . $enemy->username . " fez um feitiço e recuperou toda sua vida.");
					}else{
						$db->execute("update `bixos` set `hp`=`hp`+?, `mana`=`mana`-15 where `player_id`=?", array($curar, $player->id));
						array_unshift($_SESSION['battlelog'], "4, " . ucfirst($enemy->prepo) . " " . $enemy->username . " fez um feitiço e recuperou " . $curar . " pontos de vida.");
					}

				} elseif ($playerdamage > $monsterdamage && $bixo->mana >= 30 && $bixo->mana < 65 && $chancemagia <= 3 && $playerhp > $monsterhp){
					if (($player->hp - ($monsterdamage * 2)) < 1){
						$db->execute("update `players` set `hp`=0 where `id`=?", array($player->id));
						$morreu = 5;
					}else{
						$db->execute("update `players` set `hp`=`hp`-? where `id`=?", array(($monsterdamage * 2), $player->id));
					}

					$db->execute("update `bixos` set `mana`=`mana`-30 where `player_id`=?", array($player->id));
					array_unshift($_SESSION['battlelog'], "4, " . ucfirst($enemy->prepo) . " " . $enemy->username . " deu um ataque duplo em você e tirou " . ($monsterdamage * 2) . " pontos de vida.");

				} elseif ($playerdamage > $monsterdamage && $bixo->mana >= 65 && $chancemagia <= 3 && $playerhp > $monsterhp){
					if (($player->hp - ($monsterdamage * 4)) < 1){
						$db->execute("update `players` set `hp`=0 where `id`=?", array($player->id));
						$morreu = 5;
					}else{
						$db->execute("update `players` set `hp`=`hp`-? where `id`=?", array(($monsterdamage * 4), $player->id));
					}

					$db->execute("update `bixos` set `mana`=`mana`-30 where `player_id`=?", array($player->id));
					array_unshift($_SESSION['battlelog'], "4, " . ucfirst($enemy->prepo) . " " . $enemy->username . " deu um ataque quádruplo em você e tirou " . ($monsterdamage * 4) . " pontos de vida.");

				} elseif (($player->hp - $monsterdamage) < 1) {
        $db->execute("update `players` set `hp`=0 where `id`=?", array($player->id));
        array_unshift($_SESSION['battlelog'], "2, " . ucfirst($enemy->prepo) . " " . $enemy->username . " te atacou e você perdeu " . $monsterdamage . " de vida.");
        $morreu = 5;
    }else{
						$db->execute("update `players` set `hp`=`hp`-? where `id`=?", array($monsterdamage, $player->id));
						array_unshift($_SESSION['battlelog'], "2, " . ucfirst($enemy->prepo) . " " . $enemy->username . " te atacou e você perdeu " . $monsterdamage . " de vida.");	
					}
		}
	}
?>
