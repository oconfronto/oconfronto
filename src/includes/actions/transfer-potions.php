<?php
if (($_GET['transpotion']) and (!$_POST['mandap']))
{
include("templates/private_header.php");
echo "<fieldset><legend><b>Enviar Poções</b></legend>\n";
echo "<form method=\"post\" action=\"inventory.php?transpotion=true\"><table><tr><td><b>Desejo enviar:</b></td><td><select name=\"potion\"><option value=\"none\" selected=\"selected\">Selecione</option><option value=\"hp\">Health Potions</option><option value=\"bhp\">Big Health Potions</option><option value=\"mana\">Mana Potions</option><option value=\"energy\">Energy Potions</option></select></td></tr>";
echo "<tr><td><b>Quantia:</b></td><td><input type=\"text\" name=\"quantia\" size=\"4\"/></td></tr>";
echo "<tr><td><b>Senha de Transferência:</b></td><td><input type=\"password\" name=\"passcode\" size=\"20\"/></td></tr>";
echo "<tr><td><b>Para:</b></td><td><input type=\"text\" name=\"to\"/> <input type=\"submit\" name=\"mandap\" value=\"Enviar\" /></td></tr></table>";
echo "</form></fieldset><a href=\"inventory.php\">Voltar</a>.";
include("templates/private_footer.php");
exit;
}elseif (($_GET['transpotion']) and ($_POST['mandap'])){
	 if (!$_POST['potion']){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Erro</b></legend>\n";
        	echo "Você precisa preencher todos os campos!<br />";
        	echo "<a href=\"inventory.php\">Voltar</a>.";
		echo "</fieldset>";
        	include("templates/private_footer.php");
        	exit;
	}
	 if (!$_POST['quantia']){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Erro</b></legend>\n";
        	echo "Você precisa preencher todos os campos!<br />";
        	echo "<a href=\"inventory.php\">Voltar</a>.";
		echo "</fieldset>";
        	include("templates/private_footer.php");
        	exit;
	}
	 if (!$_POST['passcode']){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Erro</b></legend>\n";
        	echo "Você precisa preencher todos os campos!<br />";
        	echo "<a href=\"inventory.php\">Voltar</a>.";
		echo "</fieldset>";
        	include("templates/private_footer.php");
        	exit;
	}
	 if (!$_POST['to']){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Erro</b></legend>\n";
        	echo "Você precisa preencher todos os campos!<br />";
        	echo "<a href=\"inventory.php\">Voltar</a>.";
		echo "</fieldset>";
        	include("templates/private_footer.php");
        	exit;
	}
	 if ($_POST['passcode'] != $player->transpass){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Erro</b></legend>\n";
        	echo "Sua senha de transferência está incorreta.<br />";
        	echo "<a href=\"inventory.php\">Voltar</a>.";
		echo "</fieldset>";
        	include("templates/private_footer.php");
        	exit;
	}
	if ((!is_numeric($_POST['quantia'])) or ($_POST['quantia'] < 1)){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Erro</b></legend>\n";
        	echo "A quantia de poções digitada não é uma quantia válida.<br />";
        	echo "<a href=\"inventory.php\">Voltar</a>.";
		echo "</fieldset>";
        	include("templates/private_footer.php");
        	exit;
	}
	if (($_POST['potion'] != hp) and ($_POST['potion'] != bhp) and ($_POST['potion'] != mana) and ($_POST['potion'] != energy)){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Erro</b></legend>\n";
        	echo "Selecione um tipo de poção para enviar.<br />";
        	echo "<a href=\"inventory.php\">Voltar</a>.";
		echo "</fieldset>";
        	include("templates/private_footer.php");
        	exit;
	}
	$veruser = $db->execute("select `id`, `username`, `serv` from `players` where `username`=?", array($_POST['to']));
   	if ($veruser->recordcount() == 0) {
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Erro</b></legend>\n";
        	echo "O usuário " . $_POST['to'] . " não existe.<br />";
        	echo "<a href=\"inventory.php\">Voltar</a>.";
		echo "</fieldset>";
        	include("templates/private_footer.php");
        	exit;
	}
		$memberto = $veruser->fetchrow();

   	if ($player->serv != $memberto['serv']) {
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Erro</b></legend>\n";
        	echo "Este usuário pertence a outro servidor.<br />";
        	echo "<a href=\"inventory.php\">Voltar</a>.";
		echo "</fieldset>";
        	include("templates/private_footer.php");
        	exit;
	}


		if ($_POST['potion'] == hp){
		$pid = 136;
		$tipo = "Health Potion";
		}elseif ($_POST['potion'] == bhp){
		$pid = 148;
		$tipo = "Big Health Potion";
		}elseif ($_POST['potion'] == mana){
		$pid = 150;
		$tipo = "Mana Potion";
		}elseif ($_POST['potion'] == energy){
		$pid = 137;
		$tipo = "Energy Potion";
		}

	$quantia = floor($_POST['quantia']);

	$numpotio = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=? and `mark`='f'", array($player->id, $pid));
	if ($numpotio->recordcount() < $quantia){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Erro</b></legend>\n";
        	echo "Você não possui " . $quantia . " " . $tipo . "s para enviar.<br />";
        	echo "<a href=\"inventory.php\">Voltar</a>.";
		echo "</fieldset>";
        	include("templates/private_footer.php");
        	exit;
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

		$mandapocoes = $db->execute("update `items` set `player_id`=? where `player_id`=? and `item_id`=? and `mark`='f' LIMIT ?", array($memberto['id'], $player->id, $pid, $quantia));
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Sucesso</b></legend>\n";
        	echo "Você acaba de enviar " . $quantia . " " . $tipo . "s para " . $memberto['username'] . ".<br />";
        	echo "<a href=\"inventory.php\">Voltar</a>.";
		echo "</fieldset>";
        	include("templates/private_footer.php");
        	exit;
		
}
?>