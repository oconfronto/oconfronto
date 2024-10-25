<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Pontos de status");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkhp.php");
include(__DIR__ . "/checkwork.php");

if ($player->voc == 'archer') {
	$antigaforca = "Pontaria";
} elseif ($player->voc == 'knight') {
	$antigaforca = "Força";
} elseif ($player->voc == 'mage') {
	$antigaforca = "Magia";
}

if ($player->level < 50) {
	$cost = 5000;
} elseif ($player->level < 100) {
	$cost = 25000; //25000
} elseif ($player->level < 150) {
	$cost = 75000; //75000
} else {
	$cost = 150000; //150000
}

if ($player->maxhp != maxHp($db, $player->id, ($player->level - 1), $player->reino, $player->vip)) {
	$cost = 0;
}

if ($_GET['act'] == "reset") {
	if ($player->gold < $cost) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Treinador</b></legend>\n";
		echo "<i><font color=\"red\">Voc&ecirc; não tem ouro!</i><br>\n";
		echo '<a href="home.php">Voltar.</a>';
		echo "</fieldset>\n";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	$points = (5 + (($player->level - 1) * 3) + ($player->buystats * 2));
	/* $totalvit = 0;
     $queryBonuz = $db->execute("select `item_id`, `vit`, `item_bonus` from `items` where `player_id`=? and `status`='equipped'", array($player->id));
     while($itemBonus = $queryBonuz->fetchrow())
     {
         if ($itemBonus['vit'] > 0) {
             $totalvit += $itemBonus['vit'];
         } else {
             $itemBonusType = $db->GetOne("select `type` from `blueprint_items` where `id`=?", array($itemBonus['item_id']));
             if ($itemBonusType == 'amulet')
             {
                 $itemBonusValue = $db->GetOne("select `effectiveness` from `blueprint_items` where `id`=?", array($itemBonus['item_id']));
                 $totalvit += ($itemBonus['item_bonus'] * 2);
             }
         }
     } */
	$extramana = ($player->extramana - (($player->vitality - 1) * 5));
	// vitalidade dos itens equipados
	//$db->execute("update items set `status`='unequipped' where `player_id`=?", array($player->id));
	$db->execute("update `players` set `strength`=1, `vitality`=1, `agility`=1, `resistance`=1, `gold`=`gold`-?, `stat_points`=? where `id`=?", [$cost, $points, $player->id]);
	$player = check_user($db);
	$maxhp = maxHp($db, $player->id, ($player->level - 1), $player->reino, $player->vip);
	$maxmana = maxMana(($player->level - 1), $extramana);
	$db->execute("update `players` set `hp`=?, `maxhp`=?, `mana`=?, `maxmana`=?, `extramana`=? where `id`=?", [$maxhp, $maxhp, $maxmana, $maxmana, $extramana, $player->id]);
	$player = check_user($db);
	//Get new stats
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Treinador</b></legend>\n";
	echo "<i>Pronto, seus pontos foram resetados e voc&ecirc; ganhou " . $points . " pontos de status.<br/></i>\n";
	echo '<a href="home.php">Voltar.</a>';
	echo "</fieldset>\n";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if ($_GET['act'] == "magiasreset") {
	$magiaspreco = $db->execute("select `cost` from `blueprint_magias`");
	$totalprecomagias = 0;
	while ($mmagia = $magiaspreco->fetchrow()) {
		$totalprecomagias += $mmagia['cost'];
	}

	if ($player->level > $totalprecomagias) {
		$manacomprado = abs($player->magic_points - ($player->level - $totalprecomagias));
	} else {
		$manacomprado = 0;
	}

	$db->execute("delete from `magias` where `player_id`=?", [$player->id]);
	$db->execute("update `players` set `magic_points`=`level`, `mana`=`mana`-?, `maxmana`=`maxmana`-? where `id`=?", [($manacomprado * 2), ($manacomprado * 2), $player->id]);

	$player = check_user($db); //Get new stats
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Treinador</b></legend>\n";
	echo "<i>Pronto, suas magias foram resetadas e voc&ecirc; ganhou " . $player->level . " pontos místicos!.<br/></i>\n";
	echo '<a href="home.php">Voltar.</a>';
	echo "</fieldset>\n";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}


if ($_GET['add']) {
	$error = 0;

	if ($player->stat_points == 0) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<b>Treinador:</b><br />";
		echo "<i>Desculpe, mas voc&ecirc; não tem nenhum ponto de status para utilizar.<br />";
		echo "Por favor, volte quando voc&ecirc; passar de nível.</i><br/><a href=\"home.php\">Voltar</a>.";
		include(__DIR__ . "/templates/private_footer.php");
		$error = 1;
		exit;
	}

	if (!is_numeric($_GET['for'])) {
		include(__DIR__ . "/templates/private_header.php");
		echo "O valor " . $_GET['for'] . " não é válido! <a href=\"stat_points.php\">Voltar</a>.";
		include(__DIR__ . "/templates/private_footer.php");
		$error = 1;
		exit;
	}

	if (!is_numeric($_GET['vit'])) {
		include(__DIR__ . "/templates/private_header.php");
		echo "O valor " . $_GET['vit'] . " não é válido! <a href=\"stat_points.php\">Voltar</a>.";
		include(__DIR__ . "/templates/private_footer.php");
		$error = 1;
		exit;
	}

	if (!is_numeric($_GET['agi'])) {
		include(__DIR__ . "/templates/private_header.php");
		echo "O valor " . $_GET['agi'] . " não é válido! <a href=\"stat_points.php\">Voltar</a>.";
		include(__DIR__ . "/templates/private_footer.php");
		$error = 1;
		exit;
	}

	if (!is_numeric($_GET['res'])) {
		include(__DIR__ . "/templates/private_header.php");
		echo "O valor " . $_GET['res'] . " não é válido! <a href=\"stat_points.php\">.";
		include(__DIR__ . "/templates/private_footer.php");
		$error = 1;
		exit;
	}

	if ($_GET['for'] < 0) {
		include(__DIR__ . "/templates/private_header.php");
		echo 'Voc&ecirc; precisa adicionar quantias maiores que 0! <a href="stat_points.php">Voltar</a>.';
		include(__DIR__ . "/templates/private_footer.php");
		$error = 1;
		exit;
	}

	if ($_GET['vit'] < 0) {
		include(__DIR__ . "/templates/private_header.php");
		echo 'Voc&ecirc; precisa adicionar quantias maiores que 0! <a href="stat_points.php">Voltar</a>.';
		include(__DIR__ . "/templates/private_footer.php");
		$error = 1;
		exit;
	}


	if ($_GET['agi'] < 0) {
		include(__DIR__ . "/templates/private_header.php");
		echo 'Voc&ecirc; precisa adicionar quantias maiores que 0! <a href="stat_points.php">Voltar</a>.';
		include(__DIR__ . "/templates/private_footer.php");
		$error = 1;
		exit;
	}

	if ($_GET['res'] < 0) {
		include(__DIR__ . "/templates/private_header.php");
		echo 'Voc&ecirc; precisa adicionar quantias maiores que 0! <a href="stat_points.php">Voltar</a>.';
		include(__DIR__ . "/templates/private_footer.php");
		$error = 1;
		exit;
	}



	if ($_GET['for'] <= 0 && $_GET['vit'] <= 0 && $_GET['agi'] <= 0 && $_GET['res'] <= 0) {
		include(__DIR__ . "/templates/private_header.php");
		echo 'Voc&ecirc; precisa adicionar quantias maiores que 0! <a href="stat_points.php">Voltar</a>.';
		include(__DIR__ . "/templates/private_footer.php");
		$error = 1;
		exit;
	}



	if ($total > $player->stat_points) {
		include(__DIR__ . "/templates/private_header.php");
		echo "Voc&ecirc; não possui pontos de status suficientes! <a href=\"stat_points.php\">Voltar</a>.";
		include(__DIR__ . "/templates/private_footer.php");
		$error = 1;
		exit;
	}

	$total1 = ceil($_GET['for']);
	$total2 = ceil($_GET['vit']);
	$total3 = ceil($_GET['agi']);
	$total4 = ceil($_GET['res']);

	$total = ceil($total1 + $total2 + $total3 + $total4);

	if ($total > $player->stat_points) {
		include(__DIR__ . "/templates/private_header.php");
		echo "Voc&ecirc; não possui pontos de status suficientes! <a href=\"stat_points.php\">Voltar</a>.";
		include(__DIR__ . "/templates/private_footer.php");
		$error = 1;
		exit;
	}

	if ($_GET['for'] > 0) {
		$db->execute("update `players` set `stat_points`=?, `strength`=? where `id`=?", [$player->stat_points - ceil($_GET['for']), $player->strength + ceil($_GET['for']), $player->id]);
		$player = check_user($db); //Get new stats
		$msg1 = "Voc&ecirc; aumentou " . $_GET['for'] . " ponto(s) de " . $antigaforca . "!";
	}

	if ($_GET['vit'] > 0) {
		$addinghp = ceil($_GET['vit'] * 20);
		$addingmana = ceil($_GET['vit'] * 5);
		$db->execute("update `players` set `stat_points`=?, `vitality`=?, `hp`=?, `maxhp`=?, `mana`=?, `maxmana`=?, `extramana`=? where `id`=?", [$player->stat_points - ceil($_GET['vit']), $player->vitality + ceil($_GET['vit']), $player->hp + $addinghp, $player->maxhp + $addinghp, $player->mana + $addingmana, $player->maxmana + $addingmana, $player->extramana + $addingmana, $player->id]);
		$player = check_user($db); //Get new stats
		$msg2 = "Voc&ecirc; aumentou " . $_GET['vit'] . " ponto(s) de vitalidade!";
	}

	if ($_GET['agi'] > 0) {
		$db->execute("update `players` set `stat_points`=?, `agility`=? where `id`=?", [$player->stat_points - ceil($_GET['agi']), $player->agility + ceil($_GET['agi']), $player->id]);
		$player = check_user($db); //Get new stats
		$msg3 = "Voc&ecirc; aumentou " . $_GET['agi'] . " ponto(s) de agilidade!";
	}

	if ($_GET['res'] > 0) {
		$db->execute("update `players` set `stat_points`=?, `resistance`=? where `id`=?", [$player->stat_points - ceil($_GET['res']), $player->resistance + ceil($_GET['res']), $player->id]);
		$player = check_user($db); //Get new stats
		$msg4 = "Voc&ecirc; aumentou " . $_GET['res'] . " ponto(s) de resist&ecirc;ncia!";
	}
}

if ($_GET['add'] == 'Home') {
	header("Location: showskills.php?voltar=true");
	exit;
}

include(__DIR__ . "/templates/private_header.php");

$tutorial = $db->execute("select * from `pending` where `pending_id`=2 and `pending_status`=3 and `player_id`=?", [$player->id]);
if ($tutorial->recordcount() > 0) {
	if ($player->stat_points > 0) {
		echo showAlert("<table width=\"100%\"><tr><td width=\"90%\">Antes de começar a lutar, voc&ecirc; deve distribuir seus pontos de status.<br/><font size=\"1px\">Voc&ecirc; começa com 5 pontos, e ganhará mais 3 pontos de status a cada nível que obter.</font><br/><br/> Comece focando seus pontos em força e resist&ecirc;ncia, mas não esqueça que sua agilidade e vitalidade também são importantes.</td><th><font size=\"1px\"><a href=\"start.php?act=4\">Próximo</a></font></th></tr></table>", "white", "left");
	} else {
		echo showAlert("ótimo, <a href=\"start.php?act=4\">clique aqui</a> para continuar seu tutorial.", "green");
	}
}

if ($player->stat_points > 0) {
	echo showAlert("Voc&ecirc; tem " . $player->stat_points . " ponto de status dispon&iacute;veis.", "green", "center", NULL, "pontos");
} else {
	echo showAlert("Voc&ecirc; não possui mais pontos de status dispon&iacute;veis.", "yellow", "center", NULL, "pontos");
}
?>

<form method="GET" action="stat_points.php">
	<fieldset>
		<legend><b>Status</b></legend>
		<table>
			<tr>
				<td style="vertical-align:middle"><b><?= $antigaforca ?>:</b> <?= $player->strength ?></td>
				<td style="vertical-align:middle">+ <input type="text" id="checkStatus1" name="for" value="0" size="4" maxlength="4" /><input id="buttonAdd1" value="+" type="button"> <input id="buttonSub1" value="-" type="button" disabled="true"><img src="static/images/help.gif" title="header=[<?= $antigaforca ?>] body=[Aumenta seu poder de ataque.]"> <?php echo $msg1; ?></td>
			</tr>
			<tr>
				<td style="vertical-align:middle"><b>Vitalidade:</b> <?= $player->vitality ?></td>
				<td style="vertical-align:middle">+ <input type="text" id="checkStatus2" name="vit" value="0" size="4" maxlength="4" /><input id="buttonAdd2" value="+" type="button"> <input id="buttonSub2" value="-" type="button" disabled="true"><img src="static/images/help.gif" title="header=[Vitalidade] body=[Adiciona +20 á sua vida e +5 pontos á sua mana total.]"> <?php echo $msg2; ?></td>
			</tr>
			<tr>
				<td style="vertical-align:middle"><b>Agilidade:</b> <?= $player->agility ?></td>
				<td style="vertical-align:middle">+ <input type="text" id="checkStatus3" name="agi" value="0" size="4" maxlength="4" /><input id="buttonAdd3" value="+" type="button"> <input id="buttonSub3" value="-" type="button" disabled="true"><img src="static/images/help.gif" title="header=[Agilidade] body=[Desvia de ataques de inimigos e da ataques multiplos com mais facilidade.]"> <?php echo $msg3; ?></td>
			</tr>
			<tr>
				<td style="vertical-align:middle"><b>Resist&ecirc;ncia:</b> <?= $player->resistance ?></td>
				<td style="vertical-align:middle">+ <input type="text" id="checkStatus4" name="res" value="0" size="4" maxlength="4" /><input id="buttonAdd4" value="+" type="button"> <input id="buttonSub4" value="-" type="button" disabled="true"><img src="static/images/help.gif" title="header=[Resist&ecirc;ncia] body=[Aumenta sua defesa.]"> <?php echo $msg4; ?></td>
			</tr>
		</table>
	</fieldset>
	<p>
		<center><input type="submit" name="add" value="Adicionar Pontos de Status"></center>
	</p>
</form>
<br />
<?php
if ($tutorial->recordcount() == 0) {
	echo "<fieldset><legend><b>Treinador</b></legend>";
	echo "<i>Voc&ecirc; gostaria de redistribuir todos seus pontos de status e pontos místicos por " . $cost . " moedas de ouro?<br/>";
	echo '<table width="100%" border="0"><tr>';
	echo '<td width="50%"><a href="home.php">Voltar</a></td>';
	echo '<td width="50%" align="right"><a href="stat_points.php?act=reset">Sim</a></td>';
	echo "</tr></table>";
}

include(__DIR__ . "/templates/private_footer.php");
?>