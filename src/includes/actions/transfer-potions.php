<?php

declare(strict_types=1);

function showError($message) {
	
	require_once(__DIR__ . "/../../templates/private_header.php");
	echo "<fieldset><legend><b>Erro</b></legend>\n";
	echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "<br />";
	echo '<a href="inventory_mobile.php">Voltar</a>.';
	echo "</fieldset>";
	require_once(__DIR__ . "/../../templates/private_footer.php");
	exit;
}

if (($_GET['transpotion'] ?? null) && !($_POST['mandap'] ?? null)) {
	require_once(__DIR__ . "/../../templates/private_header.php");
	echo "<fieldset><legend><b>Enviar Poções</b></legend>\n";
	echo '<form method="post" action="inventory_mobile.php?transpotion=true"><table><tr><td><b>Desejo enviar:</b></td><td><select name="potion"><option value="none" selected="selected">Selecione</option><option value="hp">Health Potions</option><option value="bhp">Big Health Potions</option><option value="mana">Mana Potions</option><option value="energy">Energy Potions</option></select></td></tr>';
	echo '<tr><td><b>Quantia:</b></td><td><input type="text" name="quantia" size="4"/></td></tr>';
	echo "<tr><td><b>Senha de Transferência:</b></td><td><input type=\"password\" name=\"passcode\" size=\"20\"/></td></tr>";
	echo '<tr><td><b>Para:</b></td><td><input type="text" name="to"/> <input type="submit" name="mandap" value="Enviar" /></td></tr></table>';
	echo '</form></fieldset><a href="inventory_mobile.php">Voltar</a>.';
	require_once(__DIR__ . "/../../templates/private_footer.php");
	exit;
}

if (($_GET['transpotion'] ?? null) && ($_POST['mandap'] ?? null)) {
	$required_fields = ['potion', 'quantia', 'passcode', 'to'];
	foreach ($required_fields as $field) {
		if (!($_POST[$field] ?? null) || empty($_POST[$field])) {
			showError("Você precisa preencher todos os campos!");
		}
	}

	if (!isset($player->transpass) || ($_POST['passcode'] ?? null) !== $player->transpass) {
		showError("Sua senha de transferência está incorreta.");
	}

	if (!($_POST['quantia'] ?? null) || !is_numeric($_POST['quantia']) || ($_POST['quantia'] ?? null) < 1) {
		showError("A quantia de poções digitada não é uma quantia válida.");
	}

	if (($_POST['potion'] ?? null) != "hp" && ($_POST['potion'] ?? null) != "bhp" && ($_POST['potion'] ?? null) != "mana" && ($_POST['potion'] ?? null) != "energy") {
		showError("Selecione um tipo de poção para enviar.");
	}

	$veruser = $db->execute("select `id`, `username`, `serv` from `players` where `username`=?", [$_POST['to'] ?? null]);
	if ($veruser->recordcount() == 0) {
		showError("O usuário " . $_POST['to'] . " não existe.");
	}

	$memberto = $veruser->fetchrow();
	if ($player->serv != ($memberto['serv'] ?? null)) {
		showError("Este usuário pertence a outro servidor.");
	}

	if (($_POST['potion'] ?? null) == "hp") {
		$pid = 136;
		$tipo = "Health Potion";
	} elseif (($_POST['potion'] ?? null) == "bhp") {
		$pid = 148;
		$tipo = "Big Health Potion";
	} elseif (($_POST['potion'] ?? null) == "mana") {
		$pid = 150;
		$tipo = "Mana Potion";
	} elseif (($_POST['potion'] ?? null) == "energy") {
		$pid = 137;
		$tipo = "Energy Potion";
	}

	// var_dump($_POST['quantia']);
	// exit;

	$quantia = isset($_POST['quantia']) && is_numeric($_POST['quantia']) ? (int)$_POST['quantia'] : 0;
	$numpotio = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=? and `mark`='f'", [$player->id, $pid]);
	if ($numpotio->recordcount() < $quantia) {
		showError("Você não possui " . $quantia . " " . $tipo . "s para enviar.");
	}

	$insert['player_id'] = $player->id;
	$insert['name1'] = $player->username;
	$insert['name2'] = $memberto['username'];
	$insert['action'] = "enviou";
	$insert['value'] = "<b>" . $quantia . " " . $tipo . "s</b>";
	$insert['itemid'] = 0;
	$insert['time'] = time();
	$query = $db->autoexecute('log_item', $insert, 'INSERT');
	$insert['player_id'] = $memberto['id'];
	$insert['name1'] = $memberto['username'];
	$insert['name2'] = $player->username;
	$insert['action'] = "recebeu";
	$insert['value'] = "<b>" . $quantia . " " . $tipo . "s</b>";
	$insert['itemid'] = 0;
	$insert['blue_id'] = $pid;
	$insert['time'] = time();
	$query = $db->autoexecute('log_item', $insert, 'INSERT');
	$logmsg = "O usuário <b>" . $player->username . "</b> lhe enviou <b>" . $quantia . " " . $tipo . "s</b>.";
	addlog($memberto['id'], $logmsg, $db);
	$mandapocoes = $db->execute("update `items` set `player_id`=? where `player_id`=? and `item_id`=? and `mark`='f' LIMIT ?", [$memberto['id'] ?? null, $player->id, $pid, $quantia]);
	require_once(__DIR__ . "/../../templates/private_header.php");
	echo "<fieldset><legend><b>Sucesso</b></legend>\n";
	echo "Você acaba de enviar " . $quantia . " " . $tipo . "s para " . $memberto['username'] . ".<br />";
	echo '<a href="inventory_mobile.php">Voltar</a>.';
	echo "</fieldset>";
	require_once(__DIR__ . "/../../templates/private_footer.php");
	exit;
}
