<?php

declare(strict_types=1);

if (($_GET['voltar'] ?? null) == true) {
	include(__DIR__ . "/lib.php");
	header("Content-Type: text/html; charset=utf-8", true);
}

$player = check_user($db);

echo '<div id="spells">';

$magiasdisponiveis = $db->execute("select * from `blueprint_magias`");
while ($spell = $magiasdisponiveis->fetchrow()) {
	$magia1 = $db->execute("select * from `magias` where `magia_id`=? and `player_id`=?", [$spell['id'] ?? null, $player->id]);

	if (($spell['mana'] ?? null) > 0) {
		$mana = $player->reino == '1' ? "<b>Mana:</b> " . ($spell['mana'] - 5) . "" : "<b>Mana:</b> " . $spell['mana'] . "";
	} else {
		$mana = "<b>Magia Passiva</b>";
	}

	if (($spell['id'] ?? null) == 1) {
		$top = 2;
		$left = 55;
	} elseif (($spell['id'] ?? null) == 2) {
		$top = 2;
		$left = 146;
	} elseif (($spell['id'] ?? null) == 3) {
		$top = 2;
		$left = 91;
	} elseif (($spell['id'] ?? null) == 4) {
		$top = 30;
		$left = 0;
	} elseif (($spell['id'] ?? null) == 6) {
		$top = 57;
		$left = 91;
	} elseif (($spell['id'] ?? null) == 7) {
		$top = 57;
		$left = 55;
	} elseif (($spell['id'] ?? null) == 8) {
		$top = 2;
		$left = 237;
	} elseif (($spell['id'] ?? null) == 9) {
		$top = 57;
		$left = 201;
	} elseif (($spell['id'] ?? null) == 10) {
		$top = 57;
		$left = 256;
	} elseif (($spell['id'] ?? null) == 11) {
		$top = 57;
		$left = 146;
	} elseif (($spell['id'] ?? null) == 12) {
		$top = 2;
		$left = 182;
	}

	if ($magia1->recordcount() > 0) {
		$usado = $magia1->fetchrow();
		echo '<img src="static/images/magias/' . $spell['id'] . '.jpg" id="magia' . $spell['id'] . '" border="0"/>';
		if (($usado['used'] ?? null) == 't') {
			echo "<a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('swap_spells.php?use=magia&spell=" . $usado['id'] . "', 'comfirm')\"><div title=\"header=[" . $spell['nome'] . "] body=[" . $spell['descri'] . " " . $mana . "<br/><center><font size='1px'><b>Clique para desativar.</b></font></center>]\"><img src=\"static/images/magias/border.gif\" id=\"block" . $spell['id'] . '" border="0"/></div></a>';
		} else {
			echo "<a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('swap_spells.php?use=magia&spell=" . $usado['id'] . "', 'comfirm')\"><div title=\"header=[" . $spell['nome'] . "] body=[" . $spell['descri'] . " " . $mana . "<br/><center><font size='1px'><b>Clique para ativar.</b></font></center>]\"><img src=\"static/images/magias/black.png\" id=\"block" . $spell['id'] . '" border="0"/></div></a>';
		}
	} else {
		echo '<div title="header=[' . $spell['nome'] . "] body=[" . $spell['descri'] . "<br/><b>Custo:</b> " . $spell['cost'] . " <b>|</b> " . $mana . "]\"><a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('swap_spells.php?act=buy&spell=" . $spell['id'] . "', 'comfirm')\"><img src=\"static/images/magias/" . $spell['id'] . '.jpg" id="magia' . $spell['id'] . '" border="0"/><img src="static/images/magias/none.png" id="block' . $spell['id'] . '" border="0"/>';


		$tutorial = $db->execute("select * from `pending` where `pending_id`=2 and `pending_status`=5 and `player_id`=?", [$player->id]);
		if (($spell['id'] ?? null) == 4 && $tutorial->recordcount() > 0) {
			echo '<img src="static/images/itens/show.gif" id="tutorial" border="0"/>';
		}

		echo "</div></a>";
	}
}


echo "</div>";
