<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Loteria");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkhp.php");
include(__DIR__ . "/checkwork.php");

$unc1 = "last_winner_" . $player->serv . "";
$unc2 = "win_id_" . $player->serv . "";
$unc3 = "lottery_" . $player->serv . "";
$unc4 = "lottery_price_" . $player->serv . "";
$unc5 = "end_lotto_" . $player->serv . "";
$unc6 = "lottery_tic_" . $player->serv . "";
$unc7 = "lottery_premio_" . $player->serv . "";
$unc8 = "lotto_" . $player->serv . "";

if ($setting->$unc3 == "t") {

	if (time() > $setting->$unc5) {

		$query = $db->execute(sprintf("update `settings` set `value`='f' where `name`='%s'", $unc3));

		$wpaodsla = $db->execute("select * from `lotto` where `serv`=? order by RAND() limit 1", [$player->serv]);
		$ipwpwpwpa = $wpaodsla->fetchrow();

		if ($setting->$unc2 > 1000) {
			$query = $db->execute("update `players` set `bank`=`bank`+? where `id`=?", [$setting->$unc2, $ipwpwpwpa['player_id']]);
			$logmsg = "Você ganhou na loteria e <b>" . $setting->$unc2 . " de ouro</b> foram depositados na sua conta bancária.";
			addlog($ipwpwpwpa['player_id'], $logmsg, $db);
			$premiorecebido = "" . $setting->$unc2 . " de ouro";
		} else {
			$itotuuejdb = $db->execute("select `name` from `blueprint_items` where id=?", [$setting->$unc2]);
			$ioeowkewttttee = $itotuuejdb->fetchrow();

			$insert['player_id'] = $ipwpwpwpa['player_id'];
			$insert['item_id'] = $setting->$unc2;
			$query = $db->autoexecute('items', $insert, 'INSERT');
			if ($setting->$unc2 == 172) {
				$ringid = $db->Insert_ID();
				$db->execute("update `items` set `for`=`for`+?, `vit`=`vit`+?, `agi`=`agi`+?, `res`=`res`+? where `id`=?", [40, 30, 40, 30, $ringid]);
			}

			$logmsg = "Você ganhou na loteria e recebeu um(a) <b>" . $ioeowkewttttee['name'] . "</b>.";
			addlog($ipwpwpwpa['player_id'], $logmsg, $db);
			$premiorecebido = $ioeowkewttttee['name'];
		}

		$medalha7 = $db->execute("select * from `medalhas` where `player_id`=? and `medalha`=?", [$ipwpwpwpa['player_id'], "sortudo"]);
		if ($medalha7->recordcount() < 1) {
			$insert['player_id'] = $ipwpwpwpa['player_id'];
			$insert['medalha'] = "Sortudo";
			$insert['motivo'] = "Ganhou na loteria.";
			$query = $db->autoexecute('medalhas', $insert, 'INSERT');

			$insert['fname'] = $player->username;
			$insert['log'] = '<a href="profile.php?id=' . $player->username . '">' . $player->username . "</a> ganhou na loteria!";
			$insert['time'] = time();
			$query = $db->autoexecute('log_friends', $insert, 'INSERT');
		}

		$peoeajjwwa = $db->execute("select `username` from `players` where `id`=?", [$ipwpwpwpa['player_id']]);
		$totkooowowow = $peoeajjwwa->fetchrow();


		$query = $db->execute(sprintf("update `settings` set `value`=? where `name`='%s'", $unc1), [$totkooowowow['username']]);
		$query = $db->execute(sprintf("update `settings` set `value`=? where `name`='%s'", $unc7), [$premiorecebido]);
		$query = $db->execute(sprintf("update `settings` set `value`=0 where `name`='%s'", $unc6));
		$query = $db->execute(sprintf("update `settings` set `value`=0 where `name`='%s'", $unc5));
		$query = $db->execute("delete from `lotto` where `serv`=?", [$player->serv]);

		header("Location: lottery.php");
		exit;
	}

	if ($_POST['buy']) {
		$error = 0;

		if (!is_numeric($_POST['amount'])) {
			include(__DIR__ . "/templates/private_header.php");
			echo "O valor " . $_POST['for'] . " não é válido! <a href=\"lottery.php\">Voltar</a>.";
			include(__DIR__ . "/templates/private_footer.php");
			$error = 1;
			exit;
		}

		if ($_POST['amount'] < 1) {
			include(__DIR__ . "/templates/private_header.php");
			echo "Você precisa digitar quantias maiores que 0! <a href=\"lottery.php\">Voltar</a>.";
			include(__DIR__ . "/templates/private_footer.php");
			$error = 1;
			exit;
		}

		if ($_POST['amount'] > 999) {
			include(__DIR__ . "/templates/private_header.php");
			echo "Você pode comprar até 999 tickes por vez! <a href=\"lottery.php\">Voltar</a>.";
			include(__DIR__ . "/templates/private_footer.php");
			$error = 1;
			exit;
		}

		$total = ceil($_POST['amount'] * $setting->$unc4);

		if ($total > $player->gold) {
			include(__DIR__ . "/templates/private_header.php");
			echo "Você não possui ouro sufficiente! <a href=\"lottery.php\">Voltar</a>.";
			include(__DIR__ . "/templates/private_footer.php");
			$error = 1;
			exit;
		}

		$query = $db->execute("update `players` set `gold`=? where `id`=?", [$player->gold - $total, $player->id]);
		$query = $db->execute(sprintf("update `settings` set `value`=? where `name`='%s'", $unc6), [$setting->$unc6 + $_POST['amount']]);
		$num = $_POST['amount'];
		$sql = "INSERT INTO lotto (player_id, serv) VALUES";
		for ($i = 0; $i < $num; ++$i) {
			$sql .= sprintf('(%s, %s)', $player->id, $player->serv) . (($i == $num - 1) ? "" : ", ");
		}

		$result = $db->execute($sql);
		include(__DIR__ . "/templates/private_header.php");
		echo "Você comprou " . $_POST['amount'] . " ticket(s) por " . $total . ' de ouro. <a href="lottery.php">Voltar</a>.';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	include(__DIR__ . "/templates/private_header.php");

	if ($setting->$unc2 < 1000) {
		$itcheckedcheckondb = $db->execute("select name, description, type, effectiveness, img, voc, needpromo, needring, needlvl from `blueprint_items` where id=?", [$setting->$unc2]);
		$itchecked = $itcheckedcheckondb->fetchrow();
		$premio = $itchecked['name'];
		$premiotype = 1;
	} else {
		$premio = "" . $setting->$unc2 . " de ouro";
		$premiotype = 2;
	}

	echo "<fieldset><legend><b>Loteria</b></legend>\n";
	echo "<table>";
	echo "<tr>";
	echo "<td><b>Prêmio:</b></td>";
	echo "<td>" . $premio . "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td><b>Tempo Restante:</b></td>";
	$end = $setting->$unc5 - time();
	$days = floor($end / 60 / 60 / 24);
	$hours = floor(($end / 60 / 60) % 24);  // Add floor() function
	$minutes = floor(($end / 60) % 60);     // Add floor() function
	$comecaem = sprintf('%s dia(s) %d hora(s) %d minuto(s)', $days, $hours, $minutes);
	$nova_data = date("d/m/Y G:i", (int)$setting->$unc5);  // Cast $setting->$unc5 to integer
	echo "<td>" . $comecaem . ' <a href="lottery.php">Atualizar</a><br/><b>Dia:</b> ' . $nova_data . "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td><b>Preço por Ticket:</b></td>";
	echo "<td>" . $setting->$unc4 . "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td><b>Tickets Vendidos:</b></td>";
	echo "<td>" . $setting->$unc6 . "</td>";
	echo "</tr>";

	echo "</table>";
	echo "</fieldset>";
	echo "<br/><br/>";


	echo "<i>Compre tickets de loteria.<br/>Se seu ticket for sorteado você ganhará:</i> ";

	if ($premiotype == 2) {
		echo "<b>" . $premio . "</b>.";
	} elseif ($premiotype === 1) {
		echo "<br/>";
		echo "<fieldset><legend><b>" . $itchecked['name'] . " + 0</b></legend>\n";
		if ($itchecked['optimized'] == 10) {
			echo "<table width=\"100%\" bgcolor=\"#CEBBEE\">\n";
		} else {
			echo "<table width=\"100%\">\n";
		}

		echo '<tr><td width="5%">';
		echo '<img src="static/images/itens/' . $itchecked['img'] . '"/>';
		echo '</td><td width="68%">' . $itchecked['description'] . "<br />";
		echo "<b>";
		if ($itchecked['type'] == 'weapon') {
			echo "Ataque: ";
		} elseif ($itchecked['type'] == 'amulet') {
			echo "Vitalidade: ";
		} elseif ($itchecked['type'] == 'boots') {
			echo "Agilidade: ";
		} else {
			echo "Defesa: ";
		}

		echo "</b>";
		echo $itchecked['effectiveness'];
		echo '<td width="30%">';
		echo "<b>Vocação:</b> ";
		if ($itchecked['voc'] == 1 && $itchecked['needpromo'] == 'f') {
			echo "Arqueiro";
		} elseif ($itchecked['voc'] == 2 && $itchecked['needpromo'] == 'f') {
			echo "Cavaleiro";
		} elseif ($itchecked['voc'] == 3 && $itchecked['needpromo'] == 'f') {
			echo "Mago";
		} elseif ($itchecked['voc'] == 1 && $itchecked['needpromo'] == 't') {
			echo "Paladino";
		} elseif ($itchecked['voc'] == 2 && $itchecked['needpromo'] == 't') {
			echo "Cavaleiro de Elite";
		} elseif ($itchecked['voc'] == 3 && $itchecked['needpromo'] == 't') {
			echo "Feiticeiro";
		} elseif ($itchecked['voc'] == 0 && $itchecked['needpromo'] == 't') {
			echo "Vocações superiores";
		} else {
			echo "Todas";
		}

		echo "</td>";
		echo "</tr>";
		echo "</table>";
		if ($itchecked['needlvl'] > 1) {
			echo "<center><b><font color=\"red\">Você precisa ter nível " . $itchecked['needlvl'] . " ou mais para usar este item.</font></b></center>";
		}

		if ($itchecked['needring'] == 't') {
			echo "<center><b><font color=\"red\">Para usar este item você precisa estar usando um Jeweled Ring.</font></b></center>";
		}

		echo "</fieldset>";
	}

	echo "<br/><br/>";
	echo "<fieldset><legend><b>Comprar Tickets</b></legend>\n";
	echo '<form method="POST" action="lottery.php">';
	echo '<b>Quantia:</b> <input type="text" name="amount" value="1" size="10" maxlength="2"/><input type="submit" name="buy" value="Comprar">';
	echo "</form>";
	echo "</fieldset>";

	$getlottocount = $db->execute("select `id` from `lotto` where `player_id`=?", [$player->id]);
	echo " <b>Cada ticket custa:</b> " . $setting->$unc4 . " de ouro | <b>Você já comprou:</b> " . $getlottocount->recordcount() . " tickets.";

	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

include(__DIR__ . "/templates/private_header.php");
echo "<fieldset><legend><b>A loteria está fechada</b></legend>\n";
echo "<table>";
echo "<tr>";
echo "<td><b>último ganhador:</b></td>";
echo "<td>" . $setting->$unc1 . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td><b>Prêmio recebido:</b></td>";
echo "<td>" . $setting->$unc7 . "</td>";
echo "</tr>";
echo "</table>";
echo "</fieldset>";
echo "<br/><center><i>A loteria abrirá automaticamente todas as Terças-Feiras.</i></center>";
include(__DIR__ . "/templates/private_footer.php");
exit;

