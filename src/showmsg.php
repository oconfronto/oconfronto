<?php

declare(strict_types=1);

$messaged = 0;
$msgtype = random_int(1, 5);

$gwar = $db->execute("select * from `pwar` where `time`>? and (`status`='t' or `status`='g' or `status`='e') and (`guild_id`=? or `enemy_id`=?)", [time() - 172800, $player->guild, $player->guild]);
$otherwar = $db->execute("select * from `pwar` where `time`>? and (`status`='t' or `status`='g' or `status`='e') order by rand() limit 1", [time() - 43200]);

if ($player->stat_points > 0 && $msgtype == 1) {
	echo "Voc&ecirc; tem " . $player->stat_points . " pontos de status disponíveis! <a href=\"stat_points.php\">Clique aqui para utiliza-los</a>!";
	$messaged = 1;
} elseif ($msgtype == 2 && $messaged === 0) {
	$lembrete = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=? and `quest_status`<90", [$player->id, 1]);
	if ($lembrete->recordcount() > 0 && $messaged === 0) {
		echo "<a href=\"promote.php\">Clique aqui</a> para continuar sua missão!";
		$messaged = 1;
	}

	$lembrete2 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=? and `quest_status`<90", [$player->id, 2]);
	if ($lembrete2->recordcount() > 0 && $messaged == 0) {
		echo "<a href=\"quest1.php\">Clique aqui</a> para continuar sua missão!";
		$messaged = 1;
	}

	$lembrete4 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=? and `quest_status`!=90", [$player->id, 4]);
	if ($lembrete4->recordcount() > 0 && $messaged == 0) {
		echo "<a href=\"tavern.php?p=quests&start=4\">Clique aqui</a> para continuar sua missão!";
		$messaged = 1;
	}

	$lembrete5 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=? and `quest_status`!=90", [$player->id, 5]);
	if ($lembrete5->recordcount() > 0 && $messaged == 0) {
		echo "<a href=\"tavern.php?p=quests&start=5\">Clique aqui</a> para continuar sua missão!";
		$messaged = 1;
	}

	$lembrete7 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=? and `quest_status`!=90", [$player->id, 7]);
	if ($lembrete7->recordcount() > 0 && $messaged == 0) {
		echo "<a href=\"quest4.php\">Clique aqui</a> para continuar sua missão!";
		$messaged = 1;
	}

	$lembrete8 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=? and `quest_status`!=90", [$player->id, 9]);
	if ($lembrete8->recordcount() > 0 && $messaged == 0) {
		echo "<a href=\"quest5.php\">Clique aqui</a> para continuar sua missão!";
		$messaged = 1;
	}

	$lembrete9 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=? and `quest_status`!=90", [$player->id, 12]);
	if ($lembrete9->recordcount() > 0 && $messaged == 0) {
		echo "<a href=\"promo1.php\">Clique aqui</a> para continuar sua missão!";
		$messaged = 1;
	}

	$lembrete102 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=? and `quest_status`!=90", [$player->id, 13]);
	if ($lembrete102->recordcount() > 0 && $messaged == 0) {
		echo "<a href=\"quest6.php\">Clique aqui</a> para continuar sua missão!";
		$messaged = 1;
	}

	$lembrete10 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=? and `quest_status`!=90 and `quest_status`!=89", [$player->id, 14]);
	if ($lembrete10->recordcount() > 0 && $messaged == 0) {
		echo "<a href=\"quest6.php\">Clique aqui</a> para continuar sua missão!";
		$messaged = 1;
	}

	$lembrete11 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=? and `quest_status`!=90", [$player->id, 15]);
	if ($lembrete11->recordcount() > 0 && $messaged == 0) {
		echo "<a href=\"quest7.php\">Clique aqui</a> para continuar sua missão!";
		$messaged = 1;
	}

	$lembrete12 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=? and `quest_status`!=90", [$player->id, 17]);
	if ($lembrete12->recordcount() > 0 && $messaged == 0) {
		echo "<a href=\"quest8.php\">Clique aqui</a> para continuar sua missão!";
		$messaged = 1;
	}

	$lembrete13 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=? and `quest_status`!=90", [$player->id, 18]);
	if ($lembrete13->recordcount() > 0 && $messaged == 0) {
		echo "<a href=\"quest9.php\">Clique aqui</a> para continuar sua missão!";
		$messaged = 1;
	}
} elseif ($msgtype == 3 && $messaged === 0) {

	if ($player->level < 100) {
		$tier = 1;
	} elseif ($player->level > 99 && $player->level < 200) {
		$tier = 2;
	} elseif ($player->level > 199 && $player->level < 300) {
		$tier = 3;
	} elseif ($player->level > 299 && $player->level < 400) {
		$tier = 4;
	} elseif ($player->level > 399 && $player->level < 1000) {
		$tier = 5;
	}

	$torneiovarificapelotier = "tournament_" . $tier . "_" . $player->serv . "";
	$lottoavisoheader = "lottery_" . $player->serv . "";

	if ($setting->$torneiovarificapelotier == "y") {
		echo "O <a href=\"tournament.php\">Torneio</a> começou!";
		$messaged = 1;
	} else {
		$sorteia = random_int(1, 7);
		if ($setting->$torneiovarificapelotier == 't' && $sorteia == 1) {
			echo 'Inscreva-se no torneio! <a href="tournament.php">Clique aqui</a>.';
			$messaged = 1;
		}

		if ($setting->$lottoavisoheader == 't' && $sorteia == 2) {
			echo 'Aposte na loteria! <a href="lottery.php">Clique aqui</a>.';
			$messaged = 1;
		}

		if ($setting->eventoouro > time() && $sorteia == 3) {
			echo "Evento surpresa! Monstros com ouro em dobro!.";
			$messaged = 1;
		}

		if ($setting->eventoexp > time() && $sorteia == 4) {
			echo "Evento surpresa! Monstros com experi&ecirc;ncia em dobro!.";
			$messaged = 1;
		}


		$query = $db->execute("select * from `reinos` where `id`=?", [$player->reino]);
		$reino = $query->fetchrow();

		if ($reino['worktime'] > time() && $sorteia == 5) {
			$valortempo = $reino['worktime'] - time();
			if ($valortempo < 60) {
				$valortempo2 = $valortempo;
				$auxiliar2 = "segundo(s)";
			} elseif ($valortempo < 3600) {
				$valortempo2 = floor($valortempo / 60);
				$auxiliar2 = "minuto(s)";
			} elseif ($valortempo < 86400) {
				$valortempo2 = floor($valortempo / 3600);
				$auxiliar2 = "hora(s)";
			} elseif ($valortempo > 86400) {
				$valortempo2 = floor($valortempo / 86400);
				$auxiliar2 = "dia(s)";
			}

			if ($player->vip > time() && $reino['work'] > '0.15' || $player->vip < time()) {
				echo "Bônus salarial de " . ceil($reino['work'] * 100) . "% por " . $valortempo2 . " " . $auxiliar2 . ".";
				$messaged = 1;
			}
		}

		if ($reino['gates'] > time() && $sorteia == 6) {
			$valortempo = $reino['worktime'] - time();
			if ($valortempo < 60) {
				$valortempo2 = $valortempo;
				$auxiliar2 = "segundo(s)";
			} elseif ($valortempo < 3600) {
				$valortempo2 = floor($valortempo / 60);
				$auxiliar2 = "minuto(s)";
			} elseif ($valortempo < 86400) {
				$valortempo2 = floor($valortempo / 3600);
				$auxiliar2 = "hora(s)";
			} elseif ($valortempo > 86400) {
				$valortempo2 = floor($valortempo / 86400);
				$auxiliar2 = "dia(s)";
			}

			if ($player->vip > time() && $reino['work'] > '0.15' || $player->vip < time()) {
				echo "Os portões do reino estão abertos! <a href=\"monster.php\">Clique aqui</a> para lutar contra monstros especiais.";
				$messaged = 1;
			}
		}

		if (strstr((string) $_SERVER["HTTP_USER_AGENT"], "MSIE") && $sorteia == 7) {
			echo "Seu navegador pode não suportar o jogo. Experimente Firefox ou Chrome.";
			$messaged = 1;
		}
	}
} elseif ($msgtype == 4 && $messaged === 0) {

	$imperador = $db->GetOne("select `imperador` from `reinos` where `id`=?", [$player->reino]);
	$time = $db->GetOne("select `poll` from `reinos` where `id`=?", [$player->reino]);
	if ($time > time() && $imperador == 0) {
		echo "Participe das eleições para novo Imperador. <a href=\"reino.php\">Clique aqui</a>.";
		$messaged = 1;
	}
} elseif ($msgtype == 5 && $gwar->recordcount() > 0 && $messaged === 0) {
	$war = $gwar->fetchrow();

	if ($war['guild_id'] == $player->guild) {
		$guildname = $db->GetOne("select `name` from `guilds` where `id`=?", [$war['enemy_id']]);
	} else {
		$guildname = $db->GetOne("select `name` from `guilds` where `id`=?", [$war['guild_id']]);
	}

	$valortempo = $war['time'] - time();
	if ($valortempo < 60) {
		$auxiliar = "segundo(s)";
	} elseif ($valortempo < 3600) {
		$valortempo = ceil($valortempo / 60);
		$auxiliar = "minuto(s)";
	} elseif ($valortempo < 86400) {
		$valortempo = ceil($valortempo / 3600);
		$auxiliar = "hora(s)";
	}

	if ($war['time'] > time()) {
		echo "O seu clã atacará o clã " . $guildname . " em " . $valortempo . " " . $auxiliar . '. <a href="view_war.php?id=' . $war['id'] . '">Clique aqui</a> para ver a guerra.';
		$messaged = 1;
	} elseif ($war['time'] < time()) {
		echo '<a href="view_war.php?id=' . $war['id'] . "\">Clique aqui</a> e veja como seu clã se saiu na guerra contra o clã " . $guildname . ".";
		$messaged = 1;
	} elseif ($otherwar->recordcount() > 0) {
		$war = $otherwar->fetchrow();
		$guildname = $db->GetOne("select `name` from `guilds` where `id`=?", [$war['guild_id']]);
		$enemyname = $db->GetOne("select `name` from `guilds` where `id`=?", [$war['enemy_id']]);
		echo "O clã " . $guildname . " irá atacar o clã " . $enemyname . " em " . $valortempo . " " . $auxiliar . '. <a href="view_war.php?id=' . $war['id'] . '">Clique aqui</a> para ver a guerra.';
		$messaged = 1;
	}
} elseif ($msgtype == 5 && $otherwar->recordcount() > 0 && $messaged === 0) {
	$war = $otherwar->fetchrow();
	$guildname = $db->GetOne("select `name` from `guilds` where `id`=?", [$war['guild_id']]);
	$enemyname = $db->GetOne("select `name` from `guilds` where `id`=?", [$war['enemy_id']]);

	if ($war['time'] > time()) {
		$valortempo = $war['time'] - time();
		if ($valortempo < 60) {
			$auxiliar = "segundo(s)";
		} elseif ($valortempo < 3600) {
			$valortempo = ceil($valortempo / 60);
			$auxiliar = "minuto(s)";
		} elseif ($valortempo < 86400) {
			$valortempo = ceil($valortempo / 3600);
			$auxiliar = "hora(s)";
		}

		echo "O clã " . $guildname . " irá atacar o clã " . $enemyname . " em " . $valortempo . " " . $auxiliar . '. <a href="view_war.php?id=' . $war['id'] . '">Clique aqui</a> para ver a guerra.';
		$messaged = 1;
	} else {
		echo "O clã " . $guildname . " atacou o clã " . $enemyname . '. <a href="view_war.php?id=' . $war['id'] . '">Clique aqui</a> e veja como foi a batalha.';
		$messaged = 1;
	}
}


if ($messaged == 0) {
	$mensagemespecial = random_int(1, 3);
	if ($mensagemespecial == 1) {
		// echo "Adicione nosso aplicativo no Orkut! <a href=\"view_topic.php?id=1430\">Leia mais</a>.";
		echo "Curta nossa página no facebook! <a href=\"http://facebook.com/ocrpg\" target=\"_blank\">Clique aqui</a>.";
	} elseif ($mensagemespecial == 2) {
		// echo "Torneios e Loterias serão abertos semanalmente. <a href=\"view_topic.php?id=1215\">Leia mais</a>.";
		echo "<a href=\"view_topic.php?id=1\">Clique aqui</a> e fique por dentro de todas as novidades da nova versão.";
	} else {
		// echo "Ganhe " . $setting->earn . " moedas de ouro a cada amigo convidado. <a href=\"earn.php\">Clique aqui</a>.";
		echo 'Algum problema com o jogo? <a href="view_topic.php?id=2">Clique aqui</a>.';
	}
}
