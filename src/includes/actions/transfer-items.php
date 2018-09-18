<?php
if ($_POST['transferitems']) {
$verifikeuser = $db->execute("select `id` from `quests` where `quest_id`=4 and `quest_status`=90 and `player_id`=?", array($player->id));
   	 if ($verifikeuser->recordcount() == 0) {
		include("templates/private_header.php");
	echo"<fieldset><legend><b>Enviar Itens</b></legend>";
	echo"Você precisa chegar ao nivel 40 e completar uma missão para utilizar esta função.";
	if ($player->level > 39) {
	echo"<br><center><a href=\"quest2.php\"><b>Clique aqui para fazer a missão.</b></a></center>";
	}
	echo"</fieldset>";
        	include("templates/private_footer.php");
        	exit;
	}

	 if (!$_POST['username']){
		$error = 1;
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Erro</b></legend>\n";
        	echo "Você precisa preencher todos os campos!<br />";
        	echo "<a href=\"inventory.php\">Voltar</a>.";
		echo "</fieldset>";
        	include("templates/private_footer.php");
        	exit;
	}

	 if (!$_POST['itselected']){
		$error = 1;
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Erro</b></legend>\n";
        	echo "Você precisa preencher todos os campos!<br />";
        	echo "<a href=\"inventory.php\">Voltar</a>.";
		echo "</fieldset>";
        	include("templates/private_footer.php");
        	exit;
	 }

	 if (!$_POST['passcode']){
		$error = 1;
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Erro</b></legend>\n";
        	echo "Você precisa preencher todos os campos!<br />";
        	echo "<a href=\"inventory.php\">Voltar</a>.";
		echo "</fieldset>";
        	include("templates/private_footer.php");
        	exit;
	 }

	 if (strtolower($_POST['passcode']) != strtolower($player->transpass)){
		$error = 1;
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Erro</b></legend>\n";
        	echo "Sua senha de transferência está incorreta.<br />";
        	echo "<a href=\"inventory.php\">Voltar</a>.";
		echo "</fieldset>";
        	include("templates/private_footer.php");
        	exit;
	 }

	 if ($_POST['username'] == $player->username){
		$error = 1;
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Erro</b></legend>\n";
        	echo "Você não pode enviar um iten para você mesmo!<br />";
        	echo "<a href=\"inventory.php\">Voltar</a>.";
		echo "</fieldset>";
        	include("templates/private_footer.php");
        	exit;
	 }


    $quhjdjn = $db->execute("select items.item_bonus, items.status, items.mark, blueprint_items.id, blueprint_items.name, blueprint_items.type from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.id=? and items.player_id=?", array($_POST['itselected'], $player->id));
    $item5 = $quhjdjn->fetchrow();


    	$checkuser = $db->execute("select `id`, `username`, `serv` from `players` where `username`=?", array($_POST['username']));
    	$destination = $checkuser->fetchrow();


    if ($quhjdjn->recordcount() == 0) {
	$error = 1;
        include("templates/private_header.php");
	echo "<fieldset><legend><b>Erro</b></legend>\n";
        echo "Você não possui este item.<br />";
        echo "<a href=\"inventory.php\">Voltar</a>.";
	echo "</fieldset>";
        include("templates/private_footer.php");
        exit;
	}

    if ($item5['status'] == 'equipped') {
	$error = 1;
        include("templates/private_header.php");
	echo "<fieldset><legend><b>Erro</b></legend>\n";
        echo "Você não pode enviar um item que está sendo usado.<br />";
        echo "<a href=\"inventory.php\">Voltar</a>.";
	echo "</fieldset>";
        include("templates/private_footer.php");
        exit;
	}

    if ($item5['mark'] == 't') {
	$error = 1;
        include("templates/private_header.php");
	echo "<fieldset><legend><b>Erro</b></legend>\n";
        echo "Você não pode enviar um item que está à venda no mercado.<br />";
        echo "<a href=\"inventory.php\">Voltar</a>.";
	echo "</fieldset>";
        include("templates/private_footer.php");
        exit;
	}

    if ($item5['type'] == 'stone') {
	$error = 1;
        include("templates/private_header.php");
	echo "<fieldset><legend><b>Erro</b></legend>\n";
        echo "Você não pode enviar pedras.<br />";
        echo "<a href=\"inventory.php\">Voltar</a>.";
	echo "</fieldset>";
        include("templates/private_footer.php");
        exit;
	}

    if ($checkuser->recordcount() == 0) {
	$error = 1;
        include("templates/private_header.php");
	echo "<fieldset><legend><b>Erro</b></legend>\n";
        echo "O usuário " . $_POST['username'] . " não existe.<br />";
        echo "<a href=\"inventory.php\">Voltar</a>.";
	echo "</fieldset>";
        include("templates/private_footer.php");
        exit;
	}

   if ($player->serv != $destination['serv']) {
	$error = 1;
	include("templates/private_header.php");
	echo "<fieldset><legend><b>Erro</b></legend>\n";
        echo "Este usuário pertence a outro servidor.<br />";
        echo "<a href=\"inventory.php\">Voltar</a>.";
	echo "</fieldset>";
        include("templates/private_footer.php");
        exit;
	}


		if ($error == 0){
            $sendit = $db->execute("update `items` set `player_id`=? where `id`=?", array($destination['id'], $_POST['itselected']));

		$insert['player_id'] = $player->id;
		$insert['name1'] = $player->username;
		$insert['name2'] = $destination['username'];
		$insert['action'] = "enviou";
		$insert['value'] = "um/uma <b>" . $item5['name'] . " +" . $item5['item_bonus'] . "</b>";
		$insert['itemid'] = $_POST['itselected'];
		$insert['blue_id'] = $item5['id'];
		$insert['time'] = time();
		$query = $db->autoexecute('log_item', $insert, 'INSERT');

		$insert['player_id'] = $destination['id'];
		$insert['name1'] = $destination['username'];
		$insert['name2'] = $player->username;
		$insert['action'] = "recebeu";
		$insert['value'] = "um/uma <b>" . $item5['name'] . " +" . $item5['item_bonus'] . "</b>";
		$insert['itemid'] = $_POST['itselected'];
		$insert['blue_id'] = $item5['id'];
		$insert['time'] = time();
		$query = $db->autoexecute('log_item', $insert, 'INSERT');

                $logmsg = "O usuário <b>" . $player->username . "</b> lhe enviou um(a) <b>" . $item5['name'] . " +" . $item5['item_bonus'] . "</b>.";
                addlog($destination['id'], $logmsg, $db);
                include("templates/private_header.php");
		echo "<fieldset><legend><b>Sucesso</b></legend>\n";
                echo "Você enviou um(a) <b>" . $item5['name'] . " +" . $item5['item_bonus'] . "</b> com sucesso para o usuário: <b>" . $_POST['username'] . "</b>.<br />";
                echo "<a href=\"inventory.php\">Voltar</a>.";
		echo "</fieldset>";
                include("templates/private_footer.php");
                exit;
		}
}
?>