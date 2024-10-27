<?php

declare(strict_types=1);

/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.ezrpgproject.com/   */
/*************************************/

include(__DIR__ . "/lib.php");
define("PAGENAME", "Hospital");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");

include(__DIR__ . "/checkwork.php");

if (isset($_POST['submit'])) {

	if ($player->level < 20) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Vender poções</b></legend>\n";
		echo "<i>Você só pode vender poções a partir do nível 20.<br/></i>";
		echo "</fieldset>\n";
		echo '<a href="inventory.php">Voltar ao inventário.</a>';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	if (!is_numeric($_POST['sellhp'])) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Vender poções</b></legend>\n";
		echo "<i>O valor que você inseriu não é valido.<br/></i>";
		echo "</fieldset>\n";
		echo '<a href="inventory.php">Voltar ao inventário.</a>';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}


	if (!is_numeric($_POST['sellbhp'])) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Vender poções</b></legend>\n";
		echo "<i>O valor que você inseriu não é valido.<br/></i>";
		echo "</fieldset>\n";
		echo '<a href="inventory.php">Voltar ao inventário.</a>';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	if (!is_numeric($_POST['sellep'])) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Vender poções</b></legend>\n";
		echo "<i>O valor que você inseriu não é valido.<br/></i>";
		echo "</fieldset>\n";
		echo '<a href="inventory.php">Voltar ao inventário.</a>';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	if (!is_numeric($_POST['sellmp'])) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Vender poções</b></legend>\n";
		echo "<i>O valor que você inseriu não é valido.<br/></i>";
		echo "</fieldset>\n";
		echo '<a href="inventory.php">Voltar ao inventário.</a>';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	$query = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=136 and `mark`='f' order by rand()", [$player->id]);
	$numerodepocoes = $query->recordcount();

	$query2 = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=137 and `mark`='f' order by rand()", [$player->id]);
	$numerodepocoes2 = $query2->recordcount();

	$query3 = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=148 and `mark`='f' order by rand()", [$player->id]);
	$numerodepocoes3 = $query3->recordcount();

	$query4 = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=150 and `mark`='f' order by rand()", [$player->id]);
	$numerodepocoes4 = $query4->recordcount();

	$pocoesdevida = floor($_POST['sellhp']);
	if ($pocoesdevida > $numerodepocoes) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Vender poções</b></legend>\n";
		echo "<i>Você não possui " . $pocoesdevida . " poções de vida.<br/></i>";
		echo "</fieldset>\n";
		echo '<a href="inventory.php">Voltar ao inventário.</a>';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	$bigpocoesdevida = floor($_POST['sellbhp']);
	if ($bigpocoesdevida > $numerodepocoes3) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Vender poções</b></legend>\n";
		echo "<i>Você não possui " . $bigpocoesdevida . " poções grandes de vida.<br/></i>";
		echo "</fieldset>\n";
		echo '<a href="inventory.php">Voltar ao inventário.</a>';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	$pocoesdeenergia = floor($_POST['sellep']);
	if ($pocoesdeenergia > $numerodepocoes2) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Vender poções</b></legend>\n";
		echo "<i>Você não possui " . $pocoesdeenergia . " poções de energia.<br/></i>";
		echo "</fieldset>\n";
		echo '<a href="inventory.php">Voltar ao inventário.</a>';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	$pocoesdemana = floor($_POST['sellmp']);
	if ($pocoesdemana > $numerodepocoes4) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Vender poções</b></legend>\n";
		echo "<i>Você não possui " . $pocoesdeenergia . " poções de mana.<br/></i>";
		echo "</fieldset>\n";
		echo '<a href="inventory.php">Voltar ao inventário.</a>';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	$ganha = ($pocoesdevida * 1250);
	$ganha2 = ($bigpocoesdevida * 2000);
	$ganha22 = ($pocoesdeenergia * 2000);
	$ganha222 = ($pocoesdemana * 1000);
	$ganha3 = $ganha + $ganha2 + $ganha22 + $ganha222;

	$numerohp = $pocoesdevida;
	$numerobhp = $bigpocoesdevida;
	$numeroep = $pocoesdeenergia;
	$numeromp = $pocoesdemana;

	$query = $db->execute('delete from `items` where `item_id`=? and `player_id`=? limit ' . $numerohp, [136, $player->id]);
	$query = $db->execute('delete from `items` where `item_id`=? and `player_id`=? limit ' . $numeroep, [137, $player->id]);
	$query = $db->execute('delete from `items` where `item_id`=? and `player_id`=? limit ' . $numerobhp, [148, $player->id]);
	$query = $db->execute('delete from `items` where `item_id`=? and `player_id`=? limit ' . $numeromp, [150, $player->id]);
	$query = $db->execute("update `players` set `gold`=? where `id`=?", [$player->gold + $ganha3, $player->id]);

	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Vender poções</b></legend>\n";
	echo "<i>Você vendeu " . $pocoesdevida . " poções de vida por " . number_format($ganha) . " de ouro.<br/></i>";
	echo "<i>Você vendeu " . $bigpocoesdevida . " poções grandes de vida por " . number_format($ganha2) . " de ouro.<br/></i>";
	echo "<i>Você vendeu " . $pocoesdeenergia . " poções de energia por " . number_format($ganha22) . " de ouro.<br/></i>";
	echo "<i>Você vendeu " . $pocoesdemana . " poções de mana por " . number_format($ganha222) . " de ouro.<br/></i>";
	echo "<i>Você faturou: " . number_format($ganha3) . " de ouro.<br/></i>";
	echo "</fieldset>\n";
	echo '<a href="inventory.php">Voltar ao inventário.</a>';
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}


$heal = $player->maxhp - $player->hp;

if ($player->level < 36) {
	$cost = ceil($heal * 1);
	$cost2 = floor($player->gold / 1);
} elseif ($player->level > 35 && $player->level < 90) {
	$cost = ceil($heal * 1.45);
	$cost2 = floor($player->gold / 1.45);
} else {
	$cost = ceil($heal * 1.8);
	$cost2 = floor($player->gold / 1.8);
}

if (isset($_GET['act'])) {

	if ($_GET['act'] == 'sell') {
		if ($player->level < 20) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Vender poções</b></legend>\n";
			echo "<i>Você só pode vender poções a partir do nível 20.<br/></i>";
			echo "</fieldset>\n";
			echo '<a href="inventory.php">Voltar ao inventário.</a>';
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		$query = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=136 and `mark`='f'", [$player->id]);
		$numerodepocoes = $query->recordcount();

		$query2 = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=137 and `mark`='f'", [$player->id]);
		$numerodepocoes2 = $query2->recordcount();

		$query3 = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=148 and `mark`='f'", [$player->id]);
		$numerodepocoes3 = $query3->recordcount();

		$query4 = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=150 and `mark`='f'", [$player->id]);
		$numerodepocoes4 = $query4->recordcount();

		$total = $numerodepocoes + $numerodepocoes2 + $numerodepocoes3 + $numerodepocoes4;
		if ($total < 1) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Vender poções</b></legend>\n";
			echo "<i>Você não possui poções para vender.<br/></i>";
			echo "</fieldset>\n";
			echo '<a href="inventory.php">Voltar ao inventário.</a>';
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Vender poções</b></legend>\n";
		echo "<i>Você possui <b>" . $numerodepocoes . " poções de vida</b> e <b>" . $numerodepocoes2 . " poções de energia</b>.<br/></i>";
		echo '<form method="POST" action="hospt.php">';
		echo "Quero vender: <input type=\"text\" name=\"sellhp\" size=\"3\" value=\"0\"> poções de vida. (1,250 de ouro cada)<br/>";
		echo "Quero vender: <input type=\"text\" name=\"sellbhp\" size=\"3\" value=\"0\"> poções grandes de vida. (2,000 de ouro cada)<br/>";
		echo "Quero vender: <input type=\"text\" name=\"sellep\" size=\"3\" value=\"0\"> poções de energia. (2,000 de ouro cada)<br/>";
		echo "Quero vender: <input type=\"text\" name=\"sellmp\" size=\"3\" value=\"0\"> poções de mana. (1,000 de ouro cada)";
		echo '<br/><br/><input type="submit" name="submit" value="Vender">';
		echo "</form></fieldset>\n";
		echo '<a href="inventory.php">Voltar ao inventário.</a>';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	if ($_GET['act'] == 'heal') {
		if ($player->hp == $player->maxhp) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset style='padding:0px;border:1px solid #b9892f;'>";
			echo "<fieldset style='margin-bottom:5px;border:0px;text-align:center;'><b>Hospital</b></fieldset>";
			echo '<div style="float:left;width:80px;"></div>';
			echo '<div style="padding-left:25px;"><b>Bem vindo ao Hospital!</b><p>';
			echo "<i>Você esta com a vida cheia! Você não precisa ser curado.</i><br/>\n";
			echo "</p></div></fieldset>";

			echo "<table style='border:1px solid #b9892f;margin-left:2px;width:99.4%;' border=\"0\"><tr>";
			echo "<td width=\"50%\"><a href=\"hospt.php\"  id=\"link\" style='color:#fff;text-align:center;' class=\"normal\"><b>Voltar</b>.</a></td>";
			echo '<td width="50%" align="right"></td>';
			echo "</tr></table>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		if ($player->gold < $cost && $player->gold < 1) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Hospital</b></legend>\n";
			echo "<i>Você não possui ouro suficiente!</i><br>\n";
			echo "</fieldset>\n";
			echo '<a href="hospt.php">Retornar ao Hospital.</a>';
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		if ($player->gold < $cost) {
			$query = $db->execute("update `players` set `gold`=0, `hp`=? where `id`=?", [$player->hp + $cost2, $player->id]);
			$player = check_user($db); //Get new stats
		} else {
			$query = $db->execute("update `players` set `gold`=?, `hp`=? where `id`=?", [$player->gold - $cost, $player->maxhp, $player->id]);
			$player = check_user($db); //Get new stats
		}

		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Hospital</b></legend>\n";
		echo "<i>Você acaba de ser curado!<br/></i>\n";
		echo "</fieldset>\n";
		echo '<a href="hospt.php">Retornar ao Hospital.</a>';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}


	if ($_GET['act'] == 'potion') {
		if (!$_GET['pid']) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Erro</b></legend>\n";
			echo "<i>Um erro desconhecido ocorreu. Contate o administrador.<br/></i>\n";
			echo "</fieldset>\n";
			echo '<a href="hospt.php">Retornar ao Hospital.</a>';
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		$query = $db->execute("select * from `items` where `id`=? and `player_id`=?", [$_GET['pid'], $player->id]);
		if ($query->recordcount() == 0) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Erro</b></legend>\n";
			echo "<i>Você não pode usar esta poção.<br/></i>\n";
			echo "</fieldset>\n";
			echo '<a href="hospt.php">Retornar ao Hospital.</a>';
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		$potion = $query->fetchrow();
		if ($potion['mark'] == 't') {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Erro</b></legend>\n";
			echo "<i>Você não pode usar um item que está a venda no mercado.<br/></i>\n";
			echo "</fieldset>\n";
			echo '<a href="hospt.php">Retornar ao Hospital.</a>';
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		if ($potion['item_id'] != 136 && $potion['item_id'] != 137 && $potion['item_id'] != 148 && $potion['item_id'] != 150) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Erro</b></legend>\n";
			echo "<i>Este item não é uma poção.<br/></i>\n";
			echo "</fieldset>\n";
			echo '<a href="hospt.php">Retornar ao Hospital.</a>';
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		if ($potion['item_id'] == 136) {
			if ($player->hp == $player->maxhp) {
				include(__DIR__ . "/templates/private_header.php");
				echo "<fieldset style='padding:0px;border:1px solid #b9892f;'>";
				echo "<fieldset style='margin-bottom:5px;border:0px;text-align:center;'><b>Hospital</b></fieldset>";
				echo '<div style="float:left;width:80px;"></div>';
				echo '<div style="padding-left:25px;"><b>Bem vindo ao Hospital!</b><p>';
				echo "<i>Você esta com a vida cheia! Você não precisa ser curado.</i><br/>\n";
				echo "</p></div></fieldset>";

				echo "<table style='border:1px solid #b9892f;margin-left:2px;width:99.4%;' border=\"0\"><tr>";
				echo "<td width=\"50%\"><a href=\"hospt.php\"  id=\"link\" style='color:#fff;text-align:center;' class=\"normal\"><b>Voltar</b>.</a></td>";
				echo '<td width="50%" align="right"></td>';
				echo "</tr></table>";
				include(__DIR__ . "/templates/private_footer.php");
				exit;
			}

			$pocaoajuda = $player->hp + 5000;
			if ($pocaoajuda < $player->maxhp) {
				$query = $db->execute("update `players` set `hp`=? where `id`=?", [$player->hp + 5000, $player->id]);
				$palavra = "parte de";
			} else {
				$query = $db->execute("update `players` set `hp`=? where `id`=?", [$player->maxhp, $player->id]);
				$palavra = "toda";
			}

			$query = $db->execute("delete from `items` where `id`=?", [$potion['id']]);
			$player = check_user($db); //Get new stats
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Hospital</b></legend>\n";
			echo "<i>Você usou sua poção e recuperou " . $palavra . " sua vida.<br/></i>\n";
			echo "</fieldset>\n";
			echo '<a href="hospt.php">Retornar ao Hospital.</a>';
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		if ($potion['item_id'] == 148) {
			if ($player->hp == $player->maxhp) {
				include(__DIR__ . "/templates/private_header.php");
				echo "<fieldset style='padding:0px;border:1px solid #b9892f;'>";
				echo "<fieldset style='margin-bottom:5px;border:0px;text-align:center;'><b>Hospital</b></fieldset>";
				echo '<div style="float:left;width:80px;"></div>';
				echo '<div style="padding-left:25px;"><b>Bem vindo ao Hospital!</b><p>';
				echo "<i>Você esta com a vida cheia! Você não precisa ser curado.</i><br/>\n";
				echo "</p></div></fieldset>";

				echo "<table style='border:1px solid #b9892f;margin-left:2px;width:99.4%;' border=\"0\"><tr>";
				echo "<td width=\"50%\"><a href=\"hospt.php\"  id=\"link\" style='color:#fff;text-align:center;' class=\"normal\"><b>Voltar</b>.</a></td>";
				echo '<td width="50%" align="right"></td>';
				echo "</tr></table>";





				include(__DIR__ . "/templates/private_footer.php");
				exit;
			}

			$pocaoajuda = $player->hp + 10000;
			if ($pocaoajuda < $player->maxhp) {
				$query = $db->execute("update `players` set `hp`=? where `id`=?", [$player->hp + 10000, $player->id]);
				$palavra = "parte de";
			} else {
				$query = $db->execute("update `players` set `hp`=? where `id`=?", [$player->maxhp, $player->id]);
				$palavra = "toda";
			}

			$query = $db->execute("delete from `items` where `id`=?", [$potion['id']]);
			$player = check_user($db); //Get new stats
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Hospital</b></legend>\n";
			echo "<i>Você usou sua poção e recuperou " . $palavra . " sua vida.<br/></i>\n";
			echo "</fieldset>\n";
			echo '<a href="hospt.php">Retornar ao Hospital.</a>';
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		if ($potion['item_id'] == 137) {
			if ($player->energy == $player->maxenergy) {
				include(__DIR__ . "/templates/private_header.php");
				echo "<fieldset><legend><b>Hospital</b></legend>\n";
				echo "<i>Você esta com a energia máxima! Você não precisa desta poção.</i><br/>\n";
				echo "</fieldset>\n";
				echo '<a href="hospt.php">Retornar ao Hospital.</a>';
				include(__DIR__ . "/templates/private_footer.php");
				exit;
			}

			if (($player->energy + $setting->energy_potion) > $player->maxenergy) {
				$query = $db->execute("update `players` set `energy`=? where `id`=?", [$player->maxenergy, $player->id]);
				$palavra = "parte de";
			} else {
				$query = $db->execute("update `players` set `energy`=? where `id`=?", [$player->energy + $setting->energy_potion, $player->id]);
				$palavra = "toda";
			}

			$query = $db->execute("delete from `items` where `id`=?", [$potion['id']]);
			$player = check_user($db); //Get new stats
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Hospital</b></legend>\n";
			echo "<i>Você usou sua poção e recuperou " . $palavra . " sua energia.<br/></i>\n";
			echo "</fieldset>\n";
			echo '<a href="hospt.php">Retornar ao Hospital.</a>';
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		if ($potion['item_id'] == 150) {
			if ($player->mana == $player->maxmana) {
				include(__DIR__ . "/templates/private_header.php");
				echo "<fieldset><legend><b>Hospital</b></legend>\n";
				echo "<i>Você esta com a mana ao máximo! Você não precisa desta poção.</i><br/>\n";
				echo "</fieldset>\n";
				echo '<a href="hospt.php">Retornar ao Hospital.</a>';
				include(__DIR__ . "/templates/private_footer.php");
				exit;
			}

			if (($player->mana + 500) > $player->maxmana) {
				$query = $db->execute("update `players` set `mana`=`maxmana` where `id`=?", [$player->id]);
				$palavra = "parte de";
			} else {
				$query = $db->execute("update `players` set `mana`=`mana`+500 where `id`=?", [$player->id]);
				$palavra = "toda";
			}

			$query = $db->execute("delete from `items` where `id`=?", [$potion['id']]);
			$player = check_user($db); //Get new stats
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Hospital</b></legend>\n";
			echo "<i>Você usou sua poção e recuperou " . $palavra . " sua mana.<br/></i>\n";
			echo "</fieldset>\n";
			echo '<a href="hospt.php">Retornar ao Hospital.</a>';
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}
	} else {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Erro</b></legend>\n";
		echo "<i>Um erro desconhecido ocorreu. Contate o administrador.<br/></i>\n";
		echo "</fieldset>\n";
		echo '<a href="hospt.php">Retornar ao Hospital.</a>';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}
}


include(__DIR__ . "/templates/private_header.php");
//Add option to change price of hospital (life to heal * set number chosen by GM in admin panel)
$player = check_user($db);

echo "<fieldset style='padding:0px;border:1px solid #b9892f;'>";
echo "<fieldset style='margin-bottom:5px;border:0px;text-align:center;'><b>Hospital</b></fieldset>";
echo '<div style="float:left;width:80px;"></div>';
echo '<div style="padding-left:25px;"><b>Bem vindo ao Hospital!</b><p>';

if ($player->gold < $cost && $player->gold != 0) {
	echo "<i>Você não possui dinheiro suficiente para recuperar toda sua vida.<br/>Podemos ajuda-lo recuperando <b>" . number_format($cost2) . "</b> pontos de vida por <b>" . number_format($player->gold) . "</b> moedas de ouro.</i><br />";
} elseif ($player->hp == $player->maxhp) {
	echo "<i>Sua vida está completa, Você não necessita de tratamento no momento.<br />";
} elseif ($player->hp <= 0) {
	echo "<i>Você está morto, para ressucitar recuperando toda sua vida irá lhe custar <b>" . number_format($cost) . "</b> moedas de ouro.</i><br />";
} else {
	echo "<i>Recuperar toda sua vida irá lhe custar <b>" . number_format($cost) . "</b> moedas de ouro.</i><br />";
}

echo "</p></div></fieldset>";

echo "<table style='border:1px solid #b9892f;margin-left:2px;width:99.4%;' border=\"0\"><tr>";
echo "<td width=\"50%\"><a href=\"home.php\"  id=\"link\" style='color:#fff;text-align:center;' class=\"normal\"><b>Voltar</b>.</a></td>";
echo "<td width=\"50%\" align=\"right\"><a href=\"hospt.php?act=heal\" style='color:#fff;text-align:center;'  id=\"link\" class=\"neg\"><b>Curar</b>!</a></td>";
echo "</tr></table>";

include(__DIR__ . "/templates/private_footer.php");
