<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Batalhar");
$player = check_user($db);

// Temporary variables created to facilitate adjustments in this current file.
// These values ($rate_xp and $rate_gold) are placeholders and will be replaced 
// in the future by a dedicated function to handle these adjustments more coherently.
$rate_xp = 21;
$rate_gold = 10;

$verificaLuta = $db->execute("select `id` from `duels` where `status`='s' and (`p_id`=? or `e_id`=?)", [$player->id, $player->id]);
if ($verificaLuta->recordcount() > 0) {
	header("Location: duel.php?luta=true");
	exit;
}

$selectbixo = $db->execute("select * from `bixos` where `player_id`=? and `type`=98", [$player->id]);
if ($selectbixo->recordcount() != 1 && !($_GET['noreturn'] ?? false)) {
	include(__DIR__ . "/checkhp.php");
}

include(__DIR__ . "/checkwork.php");

header("Content-Type: text/html; charset=utf-8", true);

if (!isset($_SESSION['battlelog']) || !is_array($_SESSION['battlelog'])) {
	$_SESSION['battlelog'] = [];
}

$morreu = 0;
$matou = 0;
$fugiu = 0;
$fastturno = 0;
$fastmagia = 0;

switch ($_GET['act']) {
	case "attack":

		$selectbixo = $db->execute("select * from `bixos` where `player_id`=?", [$player->id]);
		if ($selectbixo->recordcount() == 0) {

			if (!$_GET['id']) {
				header("Location: monster.php");
				break;
			} else {
				$mhpp = $db->execute("select SQL_CACHE `hp`, `mana` from `monsters` where `id`=?", [$_GET['id'] / $player->id]);
				if ($mhpp->recordcount() < 1) {
					header("Location: monster.php");
					break;
				} else {
					$surprisequest1 = random_int(1, 400);
					$surprisequest2 = random_int(1, 800);
					$mhpp = $mhpp->fetchrow();

					if ($surprisequest1 == 1 && $player->level < 40) {
						$insert['player_id'] = $player->id;
						$insert['id'] = $_GET['id'];
						$insert['hp'] = 1;
						$insert['quest'] = 't';
						$query = $db->autoexecute('bixos', $insert, 'INSERT');
						$quest = 1;
					} elseif ($surprisequest2 == 1 && $player->level < 50) {
						$insert['player_id'] = $player->id;
						$insert['id'] = $_GET['id'];
						$insert['hp'] = 2;
						$insert['quest'] = 't';
						$query = $db->autoexecute('bixos', $insert, 'INSERT');
						$quest = 1;
					} else {

						if ($_GET['times']) {
							$vezes = floor(intval($_GET['times']));
							if ($vezes > 1 && $player->energy >= ($vezes * 10)) {
								$insert['player_id'] = $player->id;
								$insert['id'] = ($_GET['id'] / $player->id);
								$insert['hp'] = ($mhpp['hp'] * $vezes);
								$insert['mana'] = ($mhpp['mana'] * $vezes);
								$insert['type'] = 95;
								$insert['mul'] = $vezes;
								$db->autoexecute('bixos', $insert, 'INSERT');
							} else {
								$db->execute("delete from `bixos` where `player_id`=?", [$player->id]);
								unset($_SESSION['battlelog']);
								if (!($_GET['nolayout'] ?? false)) {
									include(__DIR__ . "/templates/private_header.php");
								}

								echo "Você não possui tanta energia para descarregar neste monstro!</b></font> <a href=\"monster.php\">Voltar</a>.";
								if (!($_GET['nolayout'] ?? false)) {
									include(__DIR__ . "/templates/private_footer.php");
								}

								exit;
							}
						} else {
							$modefastbattle = $db->execute("select * from `other` where `value`=? and `player_id`=?", ["fastbattle", $player->id]);
							if ($modefastbattle->recordcount() > 0) {
								$insert['player_id'] = $player->id;
								$insert['id'] = ($_GET['id'] / $player->id);
								$insert['hp'] = $mhpp['hp'];
								$insert['mana'] = $mhpp['mana'];
								$insert['type'] = 95;
								$db->autoexecute('bixos', $insert, 'INSERT');
							} else {
								$insert['player_id'] = $player->id;
								$insert['id'] = ($_GET['id'] / $player->id);
								$insert['hp'] = $mhpp['hp'];
								$insert['mana'] = $mhpp['mana'];
								$db->autoexecute('bixos', $insert, 'INSERT');
							}
						}

						$quest = 0;
					}

					if ($quest == 1) {
						header("Location: esquest.php");
						exit;
					}

					header("Location: monster.php?act=attack");
					break;
				}
			}
		} else {
			$bixo1 = $selectbixo->fetchrow();
			$bixo = new stdClass();
			foreach ($bixo1 as $key => $value) {
				$bixo->$key = $value;
			}

			if ($bixo->quest == 't') {
				header("Location: esquest.php");
				exit;
			}

			if (($bixo->hp <= 0 || $bixo->type == 98) && !($_GET['noreturn'] ?? false)) {
				unset($_SESSION['statuslog']);
				unset($_SESSION['battlelog']);
				$db->execute("delete from `bixos` where `player_id`=?", [$player->id]);
				header("Location: monster.php?act=attack&id=" . $_GET['id'] . "&times=" . $_GET['times'] . "");
				exit;
			}
		}

		$query1 = $db->execute("select * from `monsters` where `id`=?", [$bixo->id]);
		$enemy1 = $query1->fetchrow(); //Get monster info
		$enemy = new stdClass();
		foreach ($enemy1 as $key => $value) {
			$enemy->$key = $value;
		}

				// Here, the XP gain rate after a kill is adjusted
		if ($setting->eventoexp > time()) {
			$expdomonstro = ceil($enemy->mtexp * ($rate_xp * 2));
		} elseif ($player->level <= 20) {
			$expdomonstro = ceil($enemy->mtexp * ($rate_xp + 1));
		} elseif ($player->level < 35) {
			$expdomonstro = ceil($enemy->mtexp * ($rate_xp + 0.5));
		} elseif ($player->vip > time()) {
			$expdomonstro = ceil($enemy->mtexp * ($rate_xp + 0.1));
		} else {
			$expdomonstro = ceil($enemy->mtexp * $rate_xp);
		}
		$expdomonstro *= $bixo->mul;


		//verifica se o monstro é de arena
		$monstroDeArena = false;
		$checkdDungeon = $db->getone("select `dungeon_id` from `dungeon_status` where `status`<90 and `fail`=0 and `player_id`=?", [$player->id]);
		if ($checkdDungeon != null && $checkdDungeon != 0) {
			$getDungeonMonsters = $db->execute("select `monsters` from `dungeon` where `id`=?", [$checkdDungeon]);
			if ($getDungeonMonsters->recordcount() > 0) {
				$splitDungeonMosters = explode(", ", (string) $getDungeonMonsters);
				$dungeonSaveStatus = $db->getone("select `status` from `dungeon_status` where `status`<90 and `fail`=0 and `player_id`=?", [$player->id]);
				$dungeonMonsterId = $splitDungeonMosters[$dungeonSaveStatus];
				if (preg_replace('/\D+/', '', $splitDungeonMosters[$dungeonSaveStatus]) == $enemy->id) {
					$monstroDeArena = true;
				}
			}
		}


		if ($bixo->type != 98 && $bixo->type != 99 && !$monstroDeArena) {
			//checa os niveis
			$tolevelttyy = round($player->level * 1.8);
			if ($tolevelttyy < $enemy->level && $enemy->id != 49) {
				$db->execute("delete from `bixos` where `player_id`=?", [$player->id]);
				unset($_SESSION['battlelog']);
				if (!($_GET['nolayout'] ?? false)) {
					include(__DIR__ . "/templates/private_header.php");
				}

				echo "Você não pode atacar este monstro!</b></font> <a href=\"monster.php\">Voltar</a>.";
				if (!($_GET['nolayout'] ?? false)) {
					include(__DIR__ . "/templates/private_footer.php");
				}

				break;
			}

			if ($enemy->evento == 'n') {
				$bixoexpec1 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=? and `quest_status`=3", [$player->id, 18]);

				if ($enemy->id != 49) {
					$db->execute("delete from `bixos` where `player_id`=?", [$player->id]);
					unset($_SESSION['battlelog']);
					if (!($_GET['nolayout'] ?? false)) {
						include(__DIR__ . "/templates/private_header.php");
					}

					echo "Este monstro não existe! <a href=\"monster.php\">Voltar</a>.";
					if (!($_GET['nolayout'] ?? false)) {
						include(__DIR__ . "/templates/private_footer.php");
					}

					break;
				} elseif ($enemy->id == 49 && $bixoexpec1->recordcount() < 1) {
					$db->execute("delete from `bixos` where `player_id`=?", [$player->id]);
					unset($_SESSION['battlelog']);
					if (!($_GET['nolayout'] ?? false)) {
						include(__DIR__ . "/templates/private_header.php");
					}

					echo "Este monstro não existe! <a href=\"monster.php\">Voltar</a>.";
					if (!($_GET['nolayout'] ?? false)) {
						include(__DIR__ . "/templates/private_footer.php");
					}

					break;
				}
			}

			if ($enemy->evento == 't') {
				$gates = $db->GetOne("select `gates` from `reinos` where `id`=?", [$player->reino]);

				if ($gates < time()) {
					$db->execute("delete from `bixos` where `player_id`=?", [$player->id]);
					unset($_SESSION['battlelog']);
					if (!($_GET['nolayout'] ?? false)) {
						include(__DIR__ . "/templates/private_header.php");
					}

					echo "Os portões do reino estáo fechados! <a href=\"monster.php\">Voltar</a>.";
					if (!($_GET['nolayout'] ?? false)) {
						include(__DIR__ . "/templates/private_footer.php");
					}

					break;
				}
			}

			//Player cannot attack anymore
			if ($player->energy < 10) {
				$query = $db->execute("delete from `bixos` where `player_id`=?", [$player->id]);
				unset($_SESSION['battlelog']);
				if (!($_GET['nolayout'] ?? false)) {
					include(__DIR__ . "/templates/private_header.php");
				}

				echo "<fieldset>";
				echo "<legend><b>Você está sem energia</b></legend>\n";
				echo "Você está exausto. A cada minuto que se passa você adquire <b>10 pontos de energia</b>.<br/><br/>";
				echo '<div id="counter" align="center"></div><br/>';

				$gettime = $db->GetOne("select `value` from `cron` where `name`='reset_last'");

				echo '<script type="text/javascript">';
				echo "javascript_countdown.init(" . ceil($gettime - (time() - 60)) . ", 'counter');";
				echo "</script>";

				echo "</fieldset><br/>";

				$query = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=136 and `mark`='f' order by rand()", [$player->id]);
				$numerodepocoes = $query->recordcount();

				$query2 = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=137 and `mark`='f' order by rand()", [$player->id]);
				$numerodepocoes2 = $query2->recordcount();

				$query3 = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=148 and `mark`='f' order by rand()", [$player->id]);
				$numerodepocoes3 = $query3->recordcount();

				$query4 = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=150 and `mark`='f' order by rand()", [$player->id]);
				$numerodepocoes4 = $query4->recordcount();

				echo "<fieldset>";
				echo "<legend><b>Poções</b></legend>";
				echo "<table width=\"100%\"><tr><td><table width=\"80px\"><tr><td><div title=\"header=[Health Potion] body=[Recupera até 5 mil de vida.]\"><img src=\"static/images/itens/healthpotion.gif\"></div></td><td><b>x" . $numerodepocoes . "</b>";
				if ($numerodepocoes > 0) {
					$item = $query->fetchrow();
					echo '<br/><a href="hospt.php?act=potion&pid=' . $item['id'] . '">Usar</a>';
				}

				echo "</td></tr></table></td>";
				echo "<td><table width=\"80px\"><tr><td><div title=\"header=[Big Health Potion] body=[Recupera até 10 mil de vida.]\"><img src=\"static/images/itens/bighealthpotion.gif\"></div></td><td><b>x" . $numerodepocoes3 . "</b>";
				if ($numerodepocoes3 > 0) {
					$item3 = $query3->fetchrow();
					echo '<br/><a href="hospt.php?act=potion&pid=' . $item3['id'] . '">Usar</a>';
				}

				echo "</td></tr></table></td>";
				echo "<td><table width=\"80px\"><tr><td><div title=\"header=[Mana Potion] body=[Recupera até 500 de mana.]\"><img src=\"static/images/itens/manapotion.gif\"></div></td><td><b>x" . $numerodepocoes4 . "</b>";
				if ($numerodepocoes4 > 0) {
					$item4 = $query4->fetchrow();
					echo '<br/><a href="hospt.php?act=potion&pid=' . $item4['id'] . '">Usar</a>';
				}

				echo "</td></tr></table></td>";
				echo "<td><table width=\"80px\"><tr><td><div title=\"header=[Energy Potion] body=[Recupera até 50 de energia.]\"><img src=\"static/images/itens/energypotion.gif\"></div></td><td><b>x" . $numerodepocoes2 . "</b>";
				if ($numerodepocoes2 > 0) {
					$item2 = $query2->fetchrow();
					echo '<br/><a href="hospt.php?act=potion&pid=' . $item2['id'] . '">Usar</a>';
				}

				echo "</td></tr></table></td><td><font size=\"1\"><a href=\"hospt.php?act=sell\">Vender Poções</a><br/><a href=\"inventory.php?transpotion=true\">Transferir Poções</a></font></td></tr></table>";
				echo "</fieldset>";

				echo '<a href="monster.php">Voltar</a>';

				if (!($_GET['nolayout'] ?? false)) {
					include(__DIR__ . "/templates/private_footer.php");
				}

				break;
			}

			// if ($player->monsterkill >= $setting->securyty_capcha)
			// {
			// 	require_once('recaptchalib.php');
			// 	$publickey = "6Ldm1zIpAAAAAJynGMOaMybgnv3XdrGRVP5WxRM-";
			// 	$privatekey = "6Ldm1zIpAAAAAEEZlEPLlMFU3nQxQLbmICK9XE95";

			// 	$resp = null;
			// 	$error = null;

			// 	# was there a reCAPTCHA response?
			// 	if ($_POST["recaptcha_response_field"]) {
			// 			$resp = recaptcha_check_answer ($privatekey,
			// 						$_SERVER["REMOTE_ADDR"],
			// 						$_POST["recaptcha_challenge_field"],
			// 						$_POST["recaptcha_response_field"]);

			// 		if ($resp->is_valid) {
			//         			$query = $db->execute("update `players` set `monsterkill`=0 where `id`=?", array($player->id));
			// 			header("Location: monster.php?act=attack&id=" . $_GET['id'] . "");
			// 			} else {
			// 			$error = $resp->error;
			// 			}
			// 	}

			// 	if (!($_GET['nolayout'] ?? false)){ include("templates/private_header.php"); }
			// 	echo "<fieldset>";
			// 	echo "<legend><b>Antes de atacar, digite o código abaixo:</b></legend>";
			// 	echo "<form action=\"\" method=\"post\"><center>";
			// 	echo recaptcha_get_html($publickey, $error);
			// 	echo "</center>";
			// 	echo "</fieldset>";
			// 	echo "<input type=\"submit\" value=\"Atacar " . $enemy->prepo . " " . $enemy->username . "\" /> <a href=\"monster.php\">Voltar</a>.";
			// 	echo "</form>";
			// 	if (!($_GET['nolayout'] ?? false)){ include("templates/private_footer.php"); }
			// 	break;
			// }
		}


		//Get player's bonuses from equipment
		$query = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='weapon' and items.status='equipped'", [$player->id]);
		$player->atkbonus = ($query->recordcount() == 1) ? $query->fetchrow() : 0;
		$query50 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='armor' and items.status='equipped'", [$player->id]);
		$player->defbonus1 = ($query50->recordcount() == 1) ? $query50->fetchrow() : 0;
		$query51 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='helmet' and items.status='equipped'", [$player->id]);
		$player->defbonus2 = ($query51->recordcount() == 1) ? $query51->fetchrow() : 0;
		$query52 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='legs' and items.status='equipped'", [$player->id]);
		$player->defbonus3 = ($query52->recordcount() == 1) ? $query52->fetchrow() : 0;
		$query54 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='shield' and items.status='equipped'", [$player->id]);
		$player->defbonus5 = ($query54->recordcount() == 1) ? $query54->fetchrow() : 0;
		$query55 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='boots' and items.status='equipped'", [$player->id]);
		$player->agibonus6 = ($query55->recordcount() == 1) ? $query55->fetchrow() : 0;
		$query56 = $db->query("select blueprint_items.effectiveness, blueprint_items.name, items.item_bonus from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='quiver' and items.status='equipped'", [$player->id]);
		$player->agibonus7 = ($query56->recordcount() == 1) ? $query56->fetchrow() : 0;

		$pbonusfor = 0;
		$pbonusagi = 0;
		$pbonusres = 0;
		$countstats = $db->query("select `for`, `vit`, `agi`, `res` from `items` where `player_id`=? and `status`='equipped'", [$player->id]);
		while ($count = $countstats->fetchrow()) {
			$pbonusfor += $count['for'];
			$pbonusagi += $count['agi'];
			$pbonusres += $count['res'];
		}

		$verificpotion = $db->execute("select * from `in_use` where `player_id`=? and `time`>?", [$player->id, time()]);
		if ($verificpotion->recordcount() > 0) {
			$selct = $verificpotion->fetchrow();
			$getpotion = $db->execute("select * from `for_use` where `item_id`=?", [$selct['item_id']]);
			$potbonus = $getpotion->fetchrow();
			$player->strength = ceil($player->strength + (($player->strength / 100) * ($potbonus['for'])));
			$player->vitality = ceil($player->vitality + (($player->vitality / 100) * ($potbonus['vit'])));
			$player->agility = ceil($player->agility + (($player->agility / 100) * ($potbonus['agi'])));
			$player->resistance = ceil($player->resistance + (($player->resistance / 100) * ($potbonus['res'])));
		}

		if ($player->voc == 'archer') {
			$varataque = 0.29;
			$vardefesa = 0.14;
			$vardivide = 0.14;
		} elseif ($player->voc == 'mage') {
			$varataque = 0.245;
			$vardefesa = 0.14;
			$vardivide = 0.13;
		} elseif ($player->voc == 'knight') {
			$varataque = 0.20;
			$vardefesa = 0.16;
			$vardivide = 0.14;
		}

		if ($player->promoted == 'f') {
			$multipleatk = 1 + ($varataque * 1.6);
			$multipledef = 1 + ($vardefesa * 1.6);
			$divideres = 2.3 - ($vardivide * 1.6);
		} elseif ($player->promoted == 't') {

			if ($player->level > 149) {
				$multipleatk = 1 + ($varataque * 3.8);
				$multipledef = 1 + ($vardefesa * 3.8);
				$divideres = 2.3 - ($vardivide * 3.8);
			} elseif ($player->level > 129) {
				$multipleatk = 1 + ($varataque * 3.6);
				$multipledef = 1 + ($vardefesa * 3.6);
				$divideres = 2.3 - ($vardivide * 3.6);
			} elseif ($player->level > 119) {
				$multipleatk = 1 + ($varataque * 3.3);
				$multipledef = 1 + ($vardefesa * 3.3);
				$divideres = 2.3 - ($vardivide * 3.3);
			} elseif ($player->level > 99) {
				$multipleatk = 1 + ($varataque * 3);
				$multipledef = 1 + ($vardefesa * 3);
				$divideres = 2.3 - ($vardivide * 3);
			} elseif ($player->level > 89) {
				$multipleatk = 1 + ($varataque * 2.7);
				$multipledef = 1 + ($vardefesa * 2.7);
				$divideres = 2.3 - ($vardivide * 2.7);
			} else {
				$multipleatk = 1 + ($varataque * 2.4);
				$multipledef = 1 + ($vardefesa * 2.4);
				$divideres = 2.3 - ($vardivide * 2.4);
			}
		} elseif ($player->promoted == 'p') {
			$multipleatk = 1 + ($varataque * 4.5);
			$multipledef = 1 + ($vardefesa * 4.5);
			$divideres = 2.3 - ($vardivide * 4.5);
		}



		//Calculate some variables that will be used
		$forcadoplayer = ceil((($player->strength + ($player->atkbonus['effectiveness'] ?? 0) + (($player->atkbonus['item_bonus'] ?? 0) * 2) + $pbonusfor) * $multipleatk) * 1.5);
		$agilidadedoplayer = ceil($player->agility + ($player->agibonus6['effectiveness'] ?? 0) + ($player->agibonus7['effectiveness'] ?? 0) + (($player->agibonus6['item_bonus'] ?? 0) * 2) + $pbonusagi);
		$resistenciadoplayer = ceil((($player->resistance + (($player->defbonus1['effectiveness'] ?? 0) + ($player->defbonus2['effectiveness'] ?? 0) + ($player->defbonus3['effectiveness'] ?? 0) + ($player->defbonus5['effectiveness'] ?? 0)) + ((($player->defbonus1['item_bonus'] ?? 0) * 2) + (($player->defbonus2['item_bonus'] ?? 0) * 2) + (($player->defbonus3['item_bonus'] ?? 0) * 2) + (($player->defbonus5['item_bonus'] ?? 0) * 2)) + $pbonusres) * $multipledef) / 0.85);

		$forcadomonstro = ($enemy->strength * 1.68);
		$agilidadedomonstro = ($enemy->agility / 1.15);
		$resistenciadomonstro = ($enemy->vitality * 1.9);

		$especagi = ceil($agilidadedoplayer * 2.3);

		$enemy->strdiff = (($forcadomonstro - $forcadoplayer) > 0) ? ($forcadomonstro - $forcadoplayer) : 0;
		$enemy->resdiff = (($resistenciadomonstro - ($resistenciadoplayer * 1.5)) > 0) ? ($resistenciadomonstro - $resistenciadoplayer) : 0;
		$enemy->agidiff = (($agilidadedomonstro - $especagi) > 0) ? ($agilidadedomonstro - $especagi) : 0;
		$enemy->leveldiff = (($enemy->level - $player->level) > 0) ? ($enemy->level - $player->level) : 0;
		$player->strdiff = (($forcadoplayer - $forcadomonstro) > 0) ? ($forcadoplayer - $forcadomonstro) : 0;
		$player->resdiff = (($resistenciadoplayer - $resistenciadomonstro) > 0) ? ($resistenciadoplayer - $resistenciadomonstro) : 0;
		$player->agidiff = (($especagi - $agilidadedomonstro) > 0) ? ($especagi - $agilidadedomonstro) : 0;
		$player->leveldiff = (($player->level - $enemy->level) > 0) ? ($player->level - $enemy->level) : 0;
		$totalstr = $forcadomonstro + $forcadoplayer;
		$totalres = $resistenciadomonstro + $resistenciadoplayer;
		$totalagi = $agilidadedomonstro + $especagi;
		$totallevel = $enemy->level + $player->level;

		//Calculate the damage to be dealt by each player (dependent on strength and level)
		$enemy->maxdmg = ($forcadomonstro - ($resistenciadoplayer / $divideres));
		$enemy->maxdmg -= intval($enemy->maxdmg * ($player->leveldiff / $totallevel));
		$enemy->maxdmg = ($enemy->maxdmg <= 2) ? 2 : $enemy->maxdmg; //Set 2 as the minimum damage
		$enemy->mindmg = (($enemy->maxdmg - 4) < 1) ? 1 : ($enemy->maxdmg - 4); //Set a minimum damage range of maxdmg-4

		$player->maxdmg = ($forcadoplayer - ($resistenciadomonstro / 1.20));
		$player->maxdmg -= intval($player->maxdmg * ($enemy->leveldiff / $totallevel));
		$player->maxdmg = ($player->maxdmg <= 2) ? 2 : $player->maxdmg; //Set 2 as the minimum damage
		$player->mindmg = (($player->maxdmg - 4) < 1) ? 1 : ($player->maxdmg - 4); //Set a minimum damage range of maxdmg-4


		//Calculate the chance to miss opposing player
		$enemy->miss = intval(($player->agidiff / $totalagi) * 100);
		$enemy->miss = ($enemy->miss > 20) ? 20 : $enemy->miss; //Maximum miss chance of 20% (possible to change in admin panel?)
		$enemy->miss = max(8, $enemy->miss); //Minimum miss chance of 5%
		$player->miss = intval(($enemy->agidiff / $totalagi) * 100);
		$player->miss = ($player->miss > 20) ? 20 : $player->miss; //Maximum miss chance of 20%
		$player->miss = max(8, $player->miss); //Minimum miss chance of 5%


		if ($bixo->hp > 0 && $player->hp > 0) {

			if ($bixo->type == 0 && $bixo->type != 95) {
				$otroatak = 5;
			} elseif ($bixo->type == 97 && $bixo->vez == 'p') {
				include(__DIR__ . "/battle/atacahit.php");
				$db->execute("update `bixos` set `vez`='e' where `player_id`=?", [$player->id]);
			} elseif ($bixo->type == 96 && $bixo->vez == 'p') {
				include(__DIR__ . "/battle/fugir.php");
			} elseif ($bixo->type == 1 && $bixo->vez == 'p') {
				$checamagicum = $db->execute("select * from `magias` where `magia_id`=1 and `player_id`=?", [$player->id]);
				if ($checamagicum->recordcount() > 0) {
					include(__DIR__ . "/battle/reforco.php");
				} else {
					include(__DIR__ . "/battle/atacahit.php");
				}
			} elseif ($bixo->type == 2 && $bixo->vez == 'p') {
				$checamagicdois = $db->execute("select * from `magias` where `magia_id`=2 and `player_id`=?", [$player->id]);
				if ($checamagicdois->recordcount() > 0) {
					include(__DIR__ . "/battle/agressivo.php");
				} else {
					include(__DIR__ . "/battle/atacahit.php");
				}
			} elseif ($bixo->type == 3 && $bixo->vez == 'p') {
				$checamagitrei = $db->execute("select * from `magias` where `magia_id`=3 and `player_id`=?", [$player->id]);
				if ($checamagitrei->recordcount() > 0) {
					include(__DIR__ . "/battle/triplohit.php");
				} else {
					include(__DIR__ . "/battle/atacahit.php");
				}
			} elseif ($bixo->type == 4 && $bixo->vez == 'p') {
				$checamagcuato = $db->execute("select * from `magias` where `magia_id`=4 and `player_id`=?", [$player->id]);
				if ($checamagcuato->recordcount() > 0) {
					include(__DIR__ . "/battle/curar.php");
				} else {
					include(__DIR__ . "/battle/atacahit.php");
				}
			} elseif ($bixo->type == 6 && $bixo->vez == 'p') {
				$checamagiccivo = $db->execute("select * from `magias` where `magia_id`=6 and `player_id`=?", [$player->id]);
				if ($checamagiccivo->recordcount() > 0) {
					include(__DIR__ . "/battle/defesatripla.php");
				} else {
					include(__DIR__ . "/battle/atacahit.php");
				}
			} elseif ($bixo->type == 7 && $bixo->vez == 'p') {
				$checamagicsies = $db->execute("select * from `magias` where `magia_id`=7 and `player_id`=?", [$player->id]);
				if ($checamagicsies->recordcount() > 0) {
					include(__DIR__ . "/battle/resistencia.php");
				} else {
					include(__DIR__ . "/battle/atacahit.php");
				}
			} elseif ($bixo->type == 8 && $bixo->vez == 'p') {
				$checamagicsete = $db->execute("select * from `magias` where `magia_id`=8 and `player_id`=?", [$player->id]);
				if ($checamagicsete->recordcount() > 0) {
					include(__DIR__ . "/battle/quintohit.php");
				} else {
					include(__DIR__ . "/battle/atacahit.php");
				}
			} elseif ($bixo->type == 9 && $bixo->vez == 'p') {
				$checamagicotho = $db->execute("select * from `magias` where `magia_id`=9 and `player_id`=?", [$player->id]);
				if ($checamagicotho->recordcount() > 0) {
					include(__DIR__ . "/battle/defesaquinta.php");
				} else {
					include(__DIR__ . "/battle/atacahit.php");
				}
			} elseif ($bixo->type == 10 && $bixo->vez == 'p') {
				$checamagicumueve = $db->execute("select * from `magias` where `magia_id`=10 and `player_id`=?", [$player->id]);
				if ($checamagicumueve->recordcount() > 0) {
					include(__DIR__ . "/battle/escudo.php");
				} else {
					include(__DIR__ . "/battle/atacahit.php");
				}
			} elseif ($bixo->type == 11 && $bixo->vez == 'p') {
				$checamagidiez = $db->execute("select * from `magias` where `magia_id`=11 and `player_id`=?", [$player->id]);
				if ($checamagidiez->recordcount() > 0) {
					include(__DIR__ . "/battle/tontura.php");
				} else {
					include(__DIR__ . "/battle/atacahit.php");
				}
			} elseif ($bixo->type == 12 && $bixo->vez == 'p') {
				$checamagiconze = $db->execute("select * from `magias` where `magia_id`=12 and `player_id`=?", [$player->id]);
				if ($checamagiconze->recordcount() > 0) {
					include(__DIR__ . "/battle/subita.php");
				} else {
					include(__DIR__ . "/battle/atacahit.php");
				}
			} elseif ($bixo->type == 95) {
				while ($bixo->hp > 0 && $player->hp > 0) {
					if ($player->hp > 0) {

						$misschance = intval(random_int(0, 100));
						if ($misschance <= $player->miss) {
							array_unshift($_SESSION['battlelog'], "5, Você tentou atacar " . $enemy->prepo . " " . $enemy->username . " mas errou!");
						} else {

							$playerdamage = random_int(intval($player->mindmg), intval($player->maxdmg));
							$monsterdamage = random_int(intval($enemy->mindmg), intval($enemy->maxdmg));

							$playerhp = $player->hp / $player->maxhp;
							$playerhp = ceil($playerhp * 100);
							$monsterhp = $bixo->hp / $enemy->hp;
							$monsterhp = ceil($monsterhp * 100);

							$healexists = $db->execute("select * from `magias` where `magia_id`=4 and `player_id`=? and `used`='t'", [$player->id]);
							$defesatriplaexists = $db->execute("select * from `magias` where `magia_id`=6 and `player_id`=? and `used`='t'", [$player->id]);
							$defesaquintaexists = $db->execute("select * from `magias` where `magia_id`=9 and `player_id`=? and `used`='t'", [$player->id]);
							$ataquetriplaexists = $db->execute("select * from `magias` where `magia_id`=3 and `player_id`=? and `used`='t'", [$player->id]);
							$ataquequintaexists = $db->execute("select * from `magias` where `magia_id`=8 and `player_id`=? and `used`='t'", [$player->id]);
							$ataqueescudomistico = $db->execute("select * from `magias` where `magia_id`=10 and `player_id`=? and `used`='t'", [$player->id]);

							$chancemagia = random_int(1, 10);
							if ($monsterdamage > $playerdamage && $player->mana >= 15 && $chancemagia > 6 && $playerhp < 45 && $monsterhp > $playerhp && $healexists->recordcount() > 0) {
								include(__DIR__ . "/battle/fastbattle/curar.php");
							} elseif ($monsterdamage > ($playerdamage / 1.3) && $ataqueescudomistico->recordcount() > 0 && $player->mana >= 75 && $fastturno === 0) {
								include(__DIR__ . "/battle/fastbattle/escudo.php");
							} elseif ($monsterdamage > $playerdamage && $player->mana >= 30 && $player->mana < 65 && $chancemagia <= 3 && $monsterhp > $playerhp && $ataquetriplaexists->recordcount() > 0) {
								include(__DIR__ . "/battle/fastbattle/triplohit.php");
							} elseif ($monsterdamage > $playerdamage && $player->mana >= 65 && $chancemagia <= 3 && $monsterhp > $playerhp && $ataquequintaexists->recordcount() > 0) {
								include(__DIR__ . "/battle/fastbattle/quintohit.php");
							} elseif ($monsterdamage > $playerdamage && $player->mana >= 30 && $player->mana < 65 && $chancemagia <= 6 && $chancemagia > 3 && $monsterhp > $playerhp && $defesatriplaexists->recordcount() > 0) {
								include(__DIR__ . "/battle/fastbattle/defesatripla.php");
							} elseif ($monsterdamage > $playerdamage && $player->mana >= 65 && $chancemagia <= 6 && $chancemagia > 3 && $monsterhp > $playerhp && $defesaquintaexists->recordcount() > 0) {
								include(__DIR__ . "/battle/fastbattle/defesaquinta.php");
							} else {
								$bixo->hp -= $playerdamage;
								array_unshift($_SESSION['battlelog'], "1, Você atacou " . $enemy->prepo . " " . $enemy->username . " e tirou " . $playerdamage . " de vida.");
							}
						}


						if ($bixo->hp <= 0) {
							$matou = 5;
						}
					} else {
						$matou = 5;
					}

					if ($bixo->hp > 0) {
						$misschance = intval(random_int(0, 100));
						if ($misschance <= $enemy->miss || $fastmagia == 6 && $fastturno > 0) {
							array_unshift($_SESSION['battlelog'], "6, " . $enemy->username . " tentou te atacar mas errou!");
						} else {
							$damage = random_int(intval($enemy->mindmg), intval($enemy->maxdmg)); //Calculate random damage
							if ($fastmagia == 10 && $fastturno > 0) {
								$bixo->hp -= $damage;
								array_unshift($_SESSION['battlelog'], "2, " . ucfirst($enemy->prepo) . " " . $enemy->username . " tentou te atacar mas seu ataque voltou e ele perdeu " . $damage . " de vida.");
							} else {
								$player->hp -= $damage;
								array_unshift($_SESSION['battlelog'], "2, " . ucfirst($enemy->prepo) . " " . $enemy->username . " te atacou e você perdeu " . $damage . " de vida.");
							}

							if ($player->hp <= 0) {
								$morreu = 5;
							}
						}
					} else {
						$matou = 5;
					}

					if ($fastturno > 0) {
						$fastturno -= 1;
					} elseif ($fastturno === 0) {
						$fastmagia = 0;
					}
				}
			}

			if ($morreu != 5 && $matou != 5 && $otroatak != 5 && $bixo->vez == 'e') {
				include(__DIR__ . "/battle/levahit.php");
				include(__DIR__ . "/battle/menosturno.php");
				// $db->execute("update `bixos` set `vez`='p' where `player_id`=?", [$player->id]);
				$db->execute("update `bixos` set `vez`='p', `type`= 0 where `player_id`=?", array($player->id)); // Adjusted query by setting type to "0", which cancels the battle loop and disables skills after an attack.

			}
		}

		if ($bixo->hp < 1 || $matou == 5) {
			if ($bixo->type != 98 && $bixo->type != 99) {

				include(__DIR__ . "/battle/loot.php");

				$checktasks = $db->execute("select * from `tasks` where `needlvl`<=? and `obj_type`='monster' and `obj_value`=?", [$player->level, $enemy->id]);
				if ($checktasks->recordcount() > 0) {
					while ($task = $checktasks->fetchrow()) {
						$checkstatus = $db->execute("select * from `completed_tasks` where `player_id`=? and `task_id`=?", [$player->id, $task['id']]);
						if ($checkstatus->recordcount() == 0) {

							$addtaskkill = $db->execute("select * from `monster_tasks` where `player_id`=? and `task_id`=?", [$player->id, $task['id']]);
							if ($addtaskkill->recordcount() == 0) {
								$insert['player_id'] = $player->id;
								$insert['task_id'] = $task['id'];
								$insert['value'] = $bixo->mul;
								$query = $db->autoexecute('monster_tasks', $insert, 'INSERT');
							} else {
								$db->execute("update `monster_tasks` set `value`=`value`+? where `player_id`=? and `task_id`=?", [$bixo->mul, $player->id, $task['id']]);
							}
						}
					}
				}



				$expwin1 = $enemy->level * 6;
				$expwin2 = (($player->level - $enemy->level) > 0) ? $expwin1 - (($player->level - $enemy->level) * 3) : $expwin1 + (($player->level - $enemy->level) * 3);
				$expwin2 = ($expwin2 <= 0) ? 1 : $expwin2;
				$expwin3 = round(0.5 * $expwin2);
				$expwin = random_int(intval($expwin3), intval($expwin2));
				$goldwin = round(0.9 * $expwin);
				if ($setting->eventoouro > time()) {
					$goldwin = round($goldwin * 4);
				}
        
				// Here, the gold gain rate after a kill is adjusted
				$goldwin = round($goldwin * $rate_gold);
				$goldwin *= $bixo->mul;

				$expgroup1 = $db->execute("select `id` from `groups` where `player_id`=?", [$player->id]);
				if ($expgroup1->recordcount() > 0) {
					$goupid = $expgroup1->fetchrow();
					$expfull = 1;
				} else {
					$expfull = 5;
				}

				if ($expfull == 1) {
					$expgroup2 = $db->execute("select * from `groups` where `id`=?", [$goupid['id']]);
					$expfull = $expgroup2->recordcount() > 1 ? 1 : 5;
				}


				if ($expfull == 1) {
					$totalgrupoquery = $db->execute("select * from `groups` where `id`=?", [$goupid['id']]);
					if ($totalgrupoquery->recordcount() > 0) {
						while ($gbbbonus = $totalgrupoquery->fetchrow()) {
							$grupototalbonus += $gbbbonus['kills'] * $bixo->mul;
						}

						if ($grupototalbonus > 4999 && $grupototalbonus < 15000) {
							$cacagrupbbonus = 5;
						} elseif ($grupototalbonus > 14999 && $grupototalbonus < 30000) {
							$cacagrupbbonus = 10;
						} elseif ($grupototalbonus > 29999 && $grupototalbonus < 50000) {
							$cacagrupbbonus = 15;
						} elseif ($grupototalbonus > 49999) {
							$cacagrupbbonus = 20;
						} else {
							$cacagrupbbonus = 0;
						}
					}

					if ($cacagrupbbonus > 0) {
						$newexppart1 = ceil($expdomonstro / 100);
						$expdomonstro = ceil($expdomonstro + ($newexppart1 * $cacagrupbbonus));
					}

					$query = $db->execute("update `groups` set `exp`=`exp`+?, `kills`=`kills`+? where `player_id`=?", [$expdomonstro, $bixo->mul, $player->id]);
					$expdomonstro = ceil($expdomonstro / $expgroup2->recordcount());
					while ($pexp = $expgroup2->fetchrow()) {
						$pinfoquery = $db->execute("select * from `players` where `id`=?", [$pexp['player_id']]);
						$pinfo = $pinfoquery->fetchrow();

						if ($expdomonstro + $pinfo['exp'] >= maxExp($pinfo['level'])) //Player gained a level!
						{
							$newexp = $expdomonstro + $pinfo['exp'] - maxExp($pinfo['level']);

							$db->execute("update `players` set `mana`=?, `maxmana`=? where `id`=?", [maxMana($pinfo['level'], $pinfo['extramana']), maxMana($pinfo['level'], $pinfo['extramana']), $pinfo['id']]);
							$db->execute("update `players` set `maxenergy`=? where `id`=? and `maxenergy`<200", [maxEnergy($pinfo['level'], $pinfo['vip']), $pinfo['id']]);

							$svexp = "difficulty_" . $player->serv . "";

							$db->execute("update `players` set `stat_points`=`stat_points`+3, `level`=`level`+1, `hp`=?, `maxhp`=?, `exp`=?, `magic_points`=`magic_points`+1, `groupmonsterkilled`=`groupmonsterkilled`+? where `id`=?", [maxHp($db, $pinfo['id'], $pinfo['level'], $pinfo['reino'], $pinfo['vip']), maxHp($db, $pinfo['id'], $pinfo['level'], $pinfo['reino'], $pinfo['vip']), $newexp, $bixo->mul, $pinfo['id']]);

							if ($pinfo['id'] != $player->id) {
								$logwinlvlmsg = "Você avançou um nível enquanto <a href=\"profile.php?id=" . $player->username . '">' . $player->username . "</a> matava monstros.";
								addlog($pinfo['id'], $logwinlvlmsg, $db);
							}

							if ($pinfo['id'] == $player->id) {
								$newlevell = 5;
							}
						} else {
							//Update player
							$query = $db->execute("update `players` set `exp`=`exp`+?, `groupmonsterkilled`=`groupmonsterkilled`+? where `id`=?", [$expdomonstro, $bixo->mul, $pinfo['id']]);
						}
					}

					$query = $db->execute("update `players` set `gold`=`gold`+?, `hp`=?, `mana`=?, `energy`=`energy`-?, `monsterkill`=`monsterkill`+1 where `id`=?", [$goldwin, $player->hp, $player->mana, (10 * $bixo->mul), $player->id]);
				} elseif ($expdomonstro + $player->exp >= maxExp($player->level)) {
					//Player gained a level!
					//Update player, gained a level
					$newlevell = 5;
					$newexp = $expdomonstro + $player->exp - maxExp($player->level);
					$db->execute("update `players` set `mana`=?, `maxmana`=? where `id`=?", [maxMana($player->level, $player->extramana), maxMana($player->level, $player->extramana), $player->id]);
					$db->execute("update `players` set `maxenergy`=? where `id`=? and `maxenergy`<200", [maxEnergy($player->level, $player->vip), $player->id]);
					$db->execute("update `players` set `stat_points`=`stat_points`+3, `level`=`level`+1, `hp`=?, `maxhp`=?, `exp`=?, `magic_points`=`magic_points`+1, `energy`=`energy`-?, `gold`=?, `monsterkill`=`monsterkill`+1, `monsterkilled`=`monsterkilled`+? where `id`=?", [maxHp($db, $player->id, $player->level, $player->reino, $player->vip), maxHp($db, $player->id, $player->level, $player->reino, $player->vip), $newexp, (10 * $bixo->mul), $player->gold + $goldwin, $bixo->mul, $player->id]);
				} else {
					//Update player
					$query = $db->execute("update `players` set `exp`=`exp`+?, `gold`=`gold`+?, `hp`=?, `mana`=?, `energy`=`energy`-?, `monsterkill`=`monsterkill`+1, `monsterkilled`=`monsterkilled`+? where `id`=?", [$expdomonstro, $goldwin, $player->hp, $player->mana, (10 * $bixo->mul), $bixo->mul, $player->id]);
				}

				if ($lootstatus == 5) {
					$insert['player_id'] = $player->id;
					$insert['item_id'] = $loot_id;
					$addlootitemwin = $db->autoexecute('items', $insert, 'INSERT');
					$id = $db->Insert_ID();
					$status = $db->execute("update `items` set `for`=`for`+?, `vit`=`vit`+?, `agi`=`agi`+?, `res`=`res`+? where `id`=?", [$lootbonus1, $lootbonus2, $lootbonus3, $lootbonus4, $id]);
				}


				if ($enemy->id == 49) {
					$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [80, $player->id, 18]);

					$insert['player_id'] = $player->id;
					$insert['item_id'] = 160;
					$addlootitemwin = $db->autoexecute('items', $insert, 'INSERT');
				}


				/* $output .= "verdungeon";
			$checkdDungeon = $db->getone("select `dungeon_id` from `dungeon_status` where `status`<90 and `fail`=0 and `player_id`=?", array($player->id));
			if (($checkdDungeon != null) and ($checkdDungeon != 0)){
			$output .= "if1";
				$getDungeonMonsters = $db->execute("select `monsters` from `dungeon` where `id`=?", array($checkdDungeon));
				if ($getDungeonMonsters->recordcount() > 0)
				{
				$output .= "if2";
						$splitDungeonMosters = explode(", ", $getDungeonMonsters);
						$dungeonSaveStatus = $db->getone("select `status` from `dungeon_status` where `status`<90 and `fail`=0 and `player_id`=?", array($player->id));
						
						$output .= "<br/>" . substr($splitDungeonMosters[$dungeonSaveStatus], 11) . " / " . $enemy->id . "";
						if (substr($splitDungeonMosters[$dungeonSaveStatus], 11) == $enemy->id)
						{
							$db->execute("update `dungeon_status` set `status`=`status`+1 where `status`<90 and `fail`=0 and `player_id`=?", array($player->id));
							$output .= "ok";
						}
				}
			} */

				if ($enemy->username == 'Zeus') {
					$medalha10 = $db->execute("select * from `medalhas` where `player_id`=? and `medalha`=?", [$player->id, 'Lendário']);
					if ($medalha10->recordcount() < 1) {
						$medalha = 10;
						$medalhamsg = "Você matou Zeus e uma medalha foi adicionada ao seu perfil por este motivo.";
						$insert['player_id'] = $player->id;
						$insert['medalha'] = "Lendário";
						$insert['motivo'] = "Matou o poderoso Zeus.";
						$query = $db->autoexecute('medalhas', $insert, 'INSERT');

						$insert['fname'] = $player->username;
						$insert['log'] = '<a href="profile.php?id=' . $player->username . '">' . $player->username . "</a> ganhou uma medalha por matar o poderoso Zeus.";
						$insert['time'] = time();
						$query = $db->autoexecute('log_friends', $insert, 'INSERT');
					}
				}

				$matou = 5;
				if ($bixo->mul > 1) {
					$output .= showAlert("<b>Você matou " . $bixo->mul . "x o monstro " . $enemy->username . "!</b><br/>Você ganhou " . number_format($expdomonstro) . " de experiência e " . number_format($goldwin) . " de ouro.", "green");
				} else {
					$output .= showAlert("<b>Você matou " . $enemy->prepo . " " . $enemy->username . "!</b><br/>Você ganhou " . number_format($expdomonstro) . " de experiência e " . number_format($goldwin) . " de ouro.", "green");
				}

				//verifica dungeon
				if ($monstroDeArena) {
					$output .= showAlert("Você matou um monstro da arena, <a href=\"dungeon.php\">clique aqui</a> e veja seu próximo oponente.", "green");
					$db->execute("update `dungeon_status` set `status`=`status`+1 where `status`<90 and `fail`=0 and `player_id`=?", [$player->id]);
				}
			}

			if ($newlevell == 5) {
				$output .= showAlert("<u><b>Você passou de nível!</b></u>", "green");
			}

			if ($lootstatus == 5) {
				$output .= showAlert($mensagem);
			}

			if ($medalha == 10) {
				$output .= showAlert($medalhamsg);
			}

			$db->execute("update `bixos` set `hp`=0, `type`=? where `player_id`=?", [99, $player->id]);
		}

		if (($player->hp < 1 || $morreu == 5) && ($bixo->type != 98 && $bixo->type != 99)) {
			$exploss1 = $player->level * 7 * ($bixo->mul / 2);
			$exploss2 = (($player->level - $enemy->level) > 0) ? ($enemy->level - $player->level) * 4 : 0;
			$exploss = $exploss1 + $exploss2;
			$goldloss = max(1, intval(0.4 * $player->gold));
			$goldloss = random_int(1, $goldloss);
			$exploss3 = min($player->exp, $exploss);
			$goldloss2 = min($player->gold, $goldloss);
			$output .= showAlert("<b>Você morreu!</b><br/>Você perdeu " . number_format($exploss3) . " de experiência e " . number_format($goldloss2) . " de ouro.", "red");
			//Update player (the loser)
			$query = $db->execute("update `players` set `energy`=`energy`-?, `exp`=`exp`-?, `gold`=`gold`-?, `deaths`=`deaths`+1, `hp`=0, `mana`=0, `deadtime`=? where `id`=?", [(10 * $bixo->mul), $exploss3, $goldloss2, time() + $setting->dead_time, $player->id]);
			//verifica dungeon
			if ($monstroDeArena) {
				$output .= showAlert("Você foi morto por um monstro da arena e foi desclassificado.", "red");
				$db->execute("update `dungeon_status` set `fail`=1, `status`=? where `status`<90 and `fail`=0 and `player_id`=?", [(time() + 86400), $player->id]);
			}

			$morreu = 5;
			$db->execute("update `bixos` set `type`=? where `player_id`=?", [98, $player->id]);
		}

		if ($fugiu == 5) {
			$db->execute("delete from `bixos` where `player_id`=?", [$player->id]);
			unset($_SESSION['battlelog']);
			header("Location: monster.php?run=success");
			exit;
		}

		if (!$_GET['nolayout']) {
			$player = check_user($db);
			include(__DIR__ . "/templates/private_header.php");
		}

		echo '<script type="text/javascript">';
		echo "setTimeout(function() { Ajax('monster.php?act=attack&nolayout=true&noreturn=true&hit=Atacar', 'battle'); }, 1500);";
		echo "</script>";

		echo '<div id="swap"></div><div id="battle">';

		$player = check_user($db);
		$verificpotion = $db->execute("select * from `in_use` where `player_id`=? and `time`>?", [$player->id, time()]);
		if ($verificpotion->recordcount() > 0) {
			$selct = $verificpotion->fetchrow();
			$valortempo = $selct['time'] - time();
			if ($valortempo < 60) {
				$auxiliar = "segundo(s)";
			} elseif ($valortempo < 3600) {
				$valortempo = ceil($valortempo / 60);
				$auxiliar = "minuto(s)";
			} elseif ($valortempo < 86400) {
				$valortempo = ceil($valortempo / 3600);
				$auxiliar = "hora(s)";
			}

			$potname = $db->GetOne("select `name` from `blueprint_items` where `id`=?", [$selct['item_id']]);
			$potdesc = $db->GetOne("select `description` from `blueprint_items` where `id`=?", [$selct['item_id']]);
			$output .= '<div style="background-color:#FFFDE0; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px"><center><b>' . $potname . ":</b> " . $valortempo . " " . $auxiliar . " restante(s).<br/>" . $potdesc . "</center></div>";
		}

		$magiaatual = $db->execute("select `magia`, `turnos` from `bixos` where `player_id`=?", [$player->id]);
		$magiaatual2 = $magiaatual->fetchrow();

		if ($player->hp > 0 && $bixo->hp > 0 && $matou != 5 && $morreu != 5 && $bixo->type != 98 && $bixo->type != 99) {

			echo '<table width="100%">';
			echo "<tr>";
			echo '<td width="8%">';
			echo '<center><img src="' . getAvatarPath($player->avatar) . '" width="42px" height="42px" alt="' . $player->username . '" border="1px"></center>';
			echo "</td>";

			echo '<td width="26%">';
			echo "<font size=\"1px\"><b>Usuário:</b> " . $player->username . "</font><br />";
			echo show_prog_bar(155, ceil(($player->hp / $player->maxhp) * 100), strval($player->hp), 'red', '#FFF');
			echo "<br />";
			echo show_prog_bar(155, ceil(($player->mana / $player->maxmana) * 100), strval($player->mana), 'blue', '#FFF');
			echo "</td>";

			echo '<th width="30%">';
			echo "<center>VS</center>";
			echo "</th>";

			echo '<td width="36%" style="text-align: right;">';
			echo '<font size="1px"><b>Inimigo:</b> ' . $enemy->username . "</font><br />";
			echo '<div style="float: right;">';
			echo show_prog_bar(155, ceil(($bixo->hp / $enemy->hp) * 100), strval($bixo->hp), 'red', '#FFF');
			echo "<br />";
			echo show_prog_bar(155, ceil(($bixo->mana / $enemy->mana) * 100), strval($bixo->mana), 'blue', '#FFF');
			echo "<div>";
			echo "</td>";

			echo "</table>";

			if ($magiaatual2['magia'] != 0) {
				if ($magiaatual2['magia'] == 1) {
					echo '<div style="background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';
					echo "<center>Ataque 15% mais forte por " . $magiaatual2['turnos'] . " turno(s).</center>";
					echo "</div>";
				} elseif ($magiaatual2['magia'] == 2) {
					echo '<div style="background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';
					echo "<center>Ataque 45% mais forte por " . $magiaatual2['turnos'] . " turno(s).<br/>Resistencia 15% mais baixa por " . $magiaatual2['turnos'] . " turno(s).</center>";
					echo "</div>";
				} elseif ($magiaatual2['magia'] == 6 || $magiaatual2['magia'] == 9) {
					echo '<div style="background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';
					echo "<center>Feitiço de defesa por " . $magiaatual2['turnos'] . " turno(s).</center>";
					echo "</div>";
				} elseif ($magiaatual2['magia'] == 7) {
					echo '<div style="background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';
					echo "<center>Defesa 20% mais alta por " . $magiaatual2['turnos'] . " turno(s).</center>";
					echo "</div>";
				} elseif ($magiaatual2['magia'] == 10) {
					echo '<div style="background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';
					echo "<center>Seu escudo místico está ativo por " . $magiaatual2['turnos'] . " turno(s).</center>";
					echo "</div>";
				} elseif ($magiaatual2['magia'] == 11) {
					echo '<div style="background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';
					echo "<center>O monstro está tonto por " . $magiaatual2['turnos'] . " turno(s).</center>";
					echo "</div>";
				} elseif ($magiaatual2['magia'] == 12) {
					echo '<div style="background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';
					echo "<center>Ataque 35% mais forte por " . $magiaatual2['turnos'] . " turno(s).</center>";
					echo "</div>";
				}
			}
		}

		$tutorial = $db->execute("select * from `pending` where `pending_id`=2 and `pending_status`=6 and `player_id`=?", [$player->id]);
		if ($tutorial->recordcount() > 0 && $player->exp > 0) {
			echo showAlert("ótimo, <a href=\"start.php?act=7\">clique aqui</a> para continuar seu tutorial.", "green");
		}


		if ($output) {
			$_SESSION['statuslog'] = $output;
		}

		echo $_SESSION['statuslog'];

		echo '<div id="logdebatalha" class="scroll" style="background-color:#FFFDE0; overflow: auto; height:220px; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';

		if (is_array($_SESSION['battlelog'])) {
			foreach ($_SESSION['battlelog'] as $log) {
				if (is_string($log)) {
					$log_parts = explode(", ", $log);
					if (count($log_parts) >= 2) {
						$alignment = in_array($log_parts[0], ['1', '3', '5']) ? 'left' : 'right';
						$color = ['1' => 'green', '2' => 'red', '3' => 'blue', '4' => 'purple'][$log_parts[0]] ?? 'black';

						echo sprintf('<div style="text-align: %s">', $alignment);
						echo sprintf('<font color="%s">%s</font>', $color, $log_parts[1]);
						echo "</div>";
					}
				}
			}
		}

		echo "</div>";


		include(__DIR__ . "/healcost.php");
		if ($bixo->hp <= 0 || $matou == 5) {

			echo '<div style="background-color:#FFFDE0; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';
			echo '<table width="100%"><tr><td width="75%">';
			echo "<b>Opções:</b> <a href=\"monster.php?act=attack&id=" . ($bixo->id * $player->id) . '">Atacar outr' . $enemy->prepo . " " . $enemy->username . "</a> | ";
			if ($heal > 0 && $player->gold > $cost) {
				echo "<a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('heal.php', 'swap')\">Recuperar vida</a> <font size=\"1\">(" . number_format($cost) . " de ouro)</font> | ";
			} elseif ($heal > 0 && $player->gold > 0) {
				echo "<a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('heal.php', 'swap')\">Recuperar vida</a> <font size=\"1\">(" . number_format($cost2) . " de ouro)</font> | ";
			}

			echo '<a href="monster.php">Voltar</a>';

			echo '</td><td width="25%">';
			$modefastbattle = $db->execute("select * from `other` where `value`=? and `player_id`=?", ['fastbattle', $player->id]);
			if ($modefastbattle->recordcount() > 0) {
				echo "<center><font size=\"1px\"><b><a href=\"swap_type.php?alterar=true\">Desativar Luta Rápida</a></b></font></center>";
			}

			echo "</tr></table>";
			echo "</div>";
		} elseif (($bixo->type == 98 || $bixo->type == 99) && $bixo->hp > 0 || $player->hp <= 0 || $morreu == 5) {
			echo showAlert("<a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('heal.php', 'swap')\">Clique aqui</a> para recuperar toda sua vida por <b>" . number_format($cost) . '</b> de ouro. | <a href="monster.php">Voltar</a>', "white", "left");
		} else {
			echo '<table width="100%" height="43px" border="0px"><tr><td width="85%" bgcolor="#E1CBA4">';
			echo "<a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('swap_type.php?type=97', 'swap')\"><img src=\"static/images/magias/hit.png\" style=\"border: 0px; padding-top: 3px; padding-left: 5px; z-index: 3;\" border=\"0\" /></a>";


			$vermagia = $db->execute("select magias.magia_id, blueprint_magias.nome, blueprint_magias.descri, blueprint_magias.mana from `magias`, `blueprint_magias` where magias.magia_id=blueprint_magias.id and magias.used=? and magias.magia_id!=5 and magias.player_id=?", ['t', $player->id]);
			while ($result = $vermagia->fetchrow()) {

				echo "<a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('swap_type.php?type=" . $result['magia_id'] . "', 'swap')\">";

				if ($bixo->type != $result['magia_id']) {
					echo '<img src="static/images/magias/black.png" style="border: 0px; padding-top: 3px; padding-left: 5px; position: absolute; z-index: 3;" title="header=[' . $result['nome'] . "] body=[" . $result['descri'] . " <b>Mana:</b> " . $result['mana'] . ']"/>';
					echo '<img src="static/images/magias/' . $result['magia_id'] . '.png" style="border: 0px; padding-top: 3px; padding-left: 5px; z-index: 2;"/>';
				} else {
					echo '<img src="static/images/magias/' . $result['magia_id'] . '.png" style="border: 0px; padding-top: 3px; padding-left: 5px; z-index: 2;" title="header=[' . $result['nome'] . "] body=[" . $result['descri'] . " <b>Mana:</b> " . $result['mana'] . ']"/>';
				}

				echo "</a>";
			}

			echo '</td><td width="15%" bgcolor="#E1CBA4">';
			echo "<center><font size=\"1px\"><b><a href=\"swap_type.php?alterar=true\">Luta Rápida</a></b><br/><a href=\"swap_type.php?type=96\">Fugir</a></font></center>";
			echo "</td></tr></table>";
		}

		if (floor($player->energy / 10) > 1) {
			$modefastbattle = $db->execute("select * from `other` where `value`=? and `player_id`=?", ['fastbattle', $player->id]);
			if ($modefastbattle->recordcount() > 0) {
				echo "<div style='text-align:center' id='des_battle'><i><a href=\"monster.php?act=attack&id=" . ($bixo->id * $player->id) . "&times=" . floor($player->energy / 10) . '">Clique aqui</a> para descarregar toda sua energia no monstro ' . $enemy->username . ".</i><img src=\"static/images/help.gif\" title=\"header=[Descarregar Energia] body=[<font size='1px'>Você possui " . $player->energy . " pontos de energia, e pode matar " . floor($player->energy / 10) . " monstros. Esta opção faz com que você ataque " . floor($player->energy / 10) . "x o monstro " . $enemy->username . " de uma só vez.</font>]\"></div>";
			} else {
				echo "<div style='text-align:center' id='des_battle'><i><a href=\"swap_type.php?descarregar=true&times=" . floor($player->energy / 10) . '">Clique aqui</a> para descarregar toda sua energia no monstro ' . $enemy->username . ".</i><img src=\"static/images/help.gif\" title=\"header=[Descarregar Energia] body=[<font size='1px'>Você possui " . $player->energy . " pontos de energia, e pode matar " . floor($player->energy / 10) . " monstros. Esta opção faz com que você ataque " . floor($player->energy / 10) . "x o monstro " . $enemy->username . " de uma só vez.</font>]\"></div>";
			}
		}

		echo "</div>";
		if (!$_GET['nolayout']) {
			include(__DIR__ . "/templates/private_footer.php");
		}

		break;


	default:

		$tolevel = round($player->level * 1.8);
		($sql = $db->execute(sprintf("select * from monsters where level>=1 and level<='%s' and evento!='n' and evento!='t' order by level asc", $tolevel))) || die($db->errormsg());

		if (!$_GET['nolayout']) {
			include(__DIR__ . "/templates/private_header.php");
		}


		$tutorial = $db->execute("select * from `pending` where `pending_id`=2 and `pending_status`=6 and `player_id`=?", [$player->id]);
		if ($tutorial->recordcount() > 0) {
			if ($player->exp > 0) {
				echo showAlert("ótimo, <a href=\"start.php?act=7\">clique aqui</a> para continuar seu tutorial.", "green");
			} else {
				echo showAlert("<table width=\"100%\"><tr><td width=\"90%\">Existem 3 maneiras de se conseguir experiência: Lutando contra outros <u>jogadores</u>, contra <u>monstros</u> ou <u>caçando</u>.<br/><font size=\"1px\">O <u>mais recomendado</u> para você, novato, é a luta contra monstros.</font><br/><br/>Escolha um monstro de nível inferior e divirta-se!</td><th><font size=\"1px\"><a href=\"start.php?act=7\">Próximo</a></font></th></tr></table>", "white", "left");
			}
		}

		if ($_GET['run'] == 'success') {
			echo showAlert("Você fugiu de sua luta com sucesso!");
		}


		$verificpotion = $db->execute("select * from `in_use` where `player_id`=? and `time`>?", [$player->id, time()]);
		if ($verificpotion->recordcount() > 0) {
			$selct = $verificpotion->fetchrow();
			$valortempo = $selct['time'] - time();
			if ($valortempo < 60) {
				$auxiliar = "segundo(s)";
			} elseif ($valortempo < 3600) {
				$valortempo = ceil($valortempo / 60);
				$auxiliar = "minuto(s)";
			} elseif ($valortempo < 86400) {
				$valortempo = ceil($valortempo / 3600);
				$auxiliar = "hora(s)";
			}

			$potname = $db->GetOne("select `name` from `blueprint_items` where `id`=?", [$selct['item_id']]);
			$potdesc = $db->GetOne("select `description` from `blueprint_items` where `id`=?", [$selct['item_id']]);
			echo '<div style="background-color:#FFFDE0; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px"><center><b>' . $potname . ":</b> " . $valortempo . " " . $auxiliar . " restante(s).<br/>" . $potdesc . "</center></div>";
		}

		if ($setting->eventoouro > time()) {
			$end = $setting->eventoouro - time();
			$days = floor($end / 60 / 60 / 24);
			$hours = $end / 60 / 60 % 24;
			$minutes = $end / 60 % 60;
			$acaba = sprintf('%s dia(s) %d hora(s) %d minuto(s)', $days, $hours, $minutes);
			echo '<div style="background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px"><b>Evento Surpresa!</b> Ouro em dobro.<br>Tempo restante: ' . $acaba . "</div>";
		}

		if ($setting->eventoexp > time()) {
			$end = $setting->eventoexp - time();
			$days = floor($end / 60 / 60 / 24);
			$hours = $end / 60 / 60 % 24;
			$minutes = $end / 60 % 60;
			$acaba = sprintf('%s dia(s) %d hora(s) %d minuto(s)', $days, $hours, $minutes);
			echo '<div style="background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px"><b>Evento Surpresa!</b> Experiência em dobro.<br>Tempo restante: ' . $acaba . "</div>";
		}

		if ($player->level <= 20) {
			echo "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">Bônus de experiência em dobro para usuários de nível 20 ou menos.</div>";
		} elseif ($player->level < 35) {
			echo "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">Bônus de experiência de 50% para usuários de nível inferior a 35.</div>";
		} elseif ($player->vip > time()) {
			echo "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">Bônus de experiência de 10% para usuários VIP.</div>";
		}


		$veriddoseugrupo = $db->execute("select `id` from `groups` where `player_id`=?", [$player->id]);
		if ($veriddoseugrupo->recordcount() > 0) {

			$seugidd = $db->GetOne("select `id` from `groups` where `player_id`=?", [$player->id]);
			$grupototalbonus = 0;
			$totalgrupoquery = $db->execute("select * from `groups` where `id`=?", [$seugidd]);
			if ($totalgrupoquery->recordcount() > 0) {
				while ($gbbbonus = $totalgrupoquery->fetchrow()) {
					$grupototalbonus += $gbbbonus['kills'];
				}

				if ($grupototalbonus > 4999 && $grupototalbonus < 15000) {
					echo "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\"><center><b>Bônus de Experiência:</b> 5%<br/>Mais de 5000 monstros mortos pelo grupo de caça.</center></div>";
				} elseif ($grupototalbonus > 14999 && $grupototalbonus < 30000) {
					echo "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\"><center><b>Bônus de Experiência:</b> 10%<br/>Mais de 15000 monstros mortos pelo grupo de caça.</center></div>";
				} elseif ($grupototalbonus > 29999 && $grupototalbonus < 50000) {
					echo "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\"><center><b>Bônus de Experiência:</b> 15%<br/>Mais de 30000 monstros mortos pelo grupo de caça.</center></div>";
				} elseif ($grupototalbonus > 49999) {
					echo "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\"><center><b>Bônus de Experiência:</b> 20%<br/>Mais de 50000 monstros mortos pelo grupo de caça.</center></div>";
				}
			}
		}

		$gates = $db->GetOne("select `gates` from `reinos` where `id`=?", [$player->reino]);

		if ($gates > time()) {
			$end = $gates - time();
			$days = floor($end / 60 / 60 / 24);
			$hours = $end / 60 / 60 % 24;
			$minutes = $end / 60 % 60;
			$acaba = sprintf('%d hora(s) %d minuto(s)', $hours, $minutes);

			echo showAlert("<b>Portões do reino abertos!</b> Você pode lutar contra monstros especiais.<br>Tempo restante: " . $acaba . ".", "white", "left");
			$bosses = $db->execute("select * from `monsters` where `evento`='t' order by `level` asc");

			echo "<table width=\"100%\">\n";
			echo "<tr><th width=\"50%\">Nome</th><th width=\"20%\">Nível</th><th width=\"30%\">Batalha</a></th></tr>\n";
			$bool = 1;
			while ($result = $bosses->fetchrow()) {
				echo '<tr class="row' . $bool . "\">\n";
				echo '<td width="50%">' . $result['username'] . "</td>\n";
				echo '<td width="20%">' . $result['level'] . "</td>\n";
				echo '<td width="30%"><a href="monster.php?act=attack&id=' . ($result['id'] * $player->id) . "\">Atacar</a></td>\n";
				echo "</tr>\n";
				$bool = ($bool == 1) ? 2 : 1;
			}

			echo "</table><br/><br/>\n";
		}


		echo showAlert("<i>Você pode enfrentar monstros do nível 1 á " . $tolevel . ".</i>");
		echo "<table width=\"100%\">\n";
		echo "<tr><th width=\"50%\">Nome</th><th width=\"20%\">Nível</th><th width=\"30%\">Batalha</a></th></tr>\n";
		$bool = 1;
		while ($result = $sql->fetchrow()) {
			echo '<tr class="row' . $bool . "\">\n";
			echo '<td width="50%">' . $result['username'] . "</td>\n";
			echo '<td width="20%">' . $result['level'] . "</td>\n";
			echo '<td width="30%"><a href="monster.php?act=attack&id=' . ($result['id'] * $player->id) . "\">Atacar</a></td>\n";
			echo "</tr>\n";
			$bool = ($bool == 1) ? 2 : 1;
		}

		echo "</table>\n";
		if (!$_GET['nolayout']) {
			include(__DIR__ . "/templates/private_footer.php");
		}

		break;
}
