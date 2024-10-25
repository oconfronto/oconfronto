<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Reino");
$player = check_user($db);
$msg = null;

$query = $db->execute("select * from `reinos` where `id`=?", [$player->reino]);
$reino = $query->fetchrow();

if ($reino['imperador'] == $player->id) {
	if ($_POST['submit'] && ($_POST['tax'] == 0 || $_POST['tax'] == 10 || $_POST['tax'] == 15 || $_POST['tax'] == 20)) {
		if ($_POST['tax'] == 0) {
			$tax = '0';
		} elseif ($_POST['tax'] == 10) {
			$tax = '0.01';
		} elseif ($_POST['tax'] == 15) {
			$tax = '0.015';
		} elseif ($_POST['tax'] == 20) {
			$tax = '0.02';
		}

		$db->execute("update `reinos` set `tax`=? where `id`=?", [$tax, $player->reino]);
		$query = $db->execute("select `id` from `players` where `id`!=? and `reino`=?", [$player->id, $player->reino]);
		while ($member = $query->fetchrow()) {
			$logmsg = "Os impostos do reino foram alterados. A nova taxa agora é de " . $tax . "%.";
			addlog($member['id'], $logmsg, $db);
		}

		$insert['reino'] = $player->reino;
		$insert['log'] = "Os impostos do reino foram alterados.<br/>A nova taxa agora é de " . $tax . "%.";
		$insert['time'] = time();
		$db->autoexecute('log_reino', $insert, 'INSERT');
		$query = $db->execute("select * from `reinos` where `id`=?", [$player->reino]);
		$reino = $query->fetchrow();
		$msg = "Taxas atualizadas com sucesso!";
	}

	include(__DIR__ . "/templates/private_header.php");
	if ($msg != null) {
		echo showAlert($msg, "green");
	}

	echo '<table width="100%" align="center">';
	echo '<tr><td width="35%">';

	echo '<table width="100%" style="text-align: center;">';
	echo '<tr><td class="brown" width="100%"><center><b>Imposto Atual</b></center></td></tr>';
	echo '<tr><td class="off">';

	echo '<font size="1px"><b>Taxa Atual:</b> ' . $reino['tax'] . "%</font>";

	echo '<table width="100%">';
	$query = $db->execute("select `id` from `players` where `reino`=?", [$player->reino]);
	echo "<tr><td>1000</td><td>equivale à</td><td>" . ceil(1000 * (0.1 * $reino['tax'])) . "</td></tr>";
	echo "<tr><td>10000</td><td>equivale à</td><td>" . ceil(10000 * (0.1 * $reino['tax'])) . "</td></tr>";
	echo "<tr><td>100000</td><td>equivale à</td><td>" . ceil(100000 * (0.1 * $reino['tax'])) . "</td></tr>";
	echo "<tr><td>1000000</td><td>equivale à</td><td>" . ceil(1000000 * (0.1 * $reino['tax'])) . "</td></tr>";
	echo "</table>";

	echo "<font size=\"1px\">Usuário com 1000000 moedas de ouro no banco terá de pagar <b>" . ceil(1000000 * (0.1 * $reino['tax'])) . " de ouro por dia</b>.</font>";

	echo "</td></tr>";
	echo "</table>";

	echo "</td>";
	echo '<td width="65%">';

	echo '<table width="100%" style="text-align: center;">';
	echo '<tr><td class="brown" width="100%"><center><b>Ajustar Impostos</b></center></td></tr>';
	echo '<tr><td class="salmon">';

	echo "<font size=\"1px\">Impostos <b>muito altos</b> podem trazer opiniões negativas dos membros, porém impostos <b>muito baixos</b> podem levar os cofres do reino à falência!</font>";

	echo '<p><form method="POST" action="reino_tax.php">';
	echo "<b>Nova Taxa:</b> ";

	echo '<select name="tax">';
	if ($reino['tax'] == '0') {
		echo '<option value="0" selected="selected">0%</option>';
		echo '<option value="10">0.010%</option>';
		echo '<option value="15">0.015%</option>';
		echo '<option value="20">0.020%</option>';
	} elseif ($reino['tax'] == '0.01') {
		echo '<option value="0">0%</option>';
		echo '<option value="10" selected="selected">0.010%</option>';
		echo '<option value="15">0.015%</option>';
		echo '<option value="20">0.020%</option>';
	} elseif ($reino['tax'] == '0.015') {
		echo '<option value="0">0%</option>';
		echo '<option value="10">0.010%</option>';
		echo '<option value="15" selected="selected">0.015%</option>';
		echo '<option value="20">0.020%</option>';
	} elseif ($reino['tax'] == '0.02') {
		echo '<option value="0">0%</option>';
		echo '<option value="10">0.010%</option>';
		echo '<option value="15">0.015%</option>';
		echo '<option value="20" selected="selected">0.020%</option>';
	}

	echo "</select>";

	echo '<input type="submit" name="submit" value="Atualizar">';
	echo "</form></p>";

	echo "</td></tr>";
	echo "</table>";

	echo "</td></tr>";
	echo "</table>";
	echo '<a href="reino.php">Voltar</a>.';
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

header("Location: home.php");
