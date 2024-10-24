<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Tesouro do Clã");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkguild.php");

$error1 = 0;
$error2 = 0;

//Populates $guild variable
$query = $db->execute("select * from `guilds` where `id`=?", [$player->guild]);
if ($query->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $query->fetchrow();
}

if ($_POST['deposit']) {
    if (!$_POST['amount']) {
        $msg1 .= "Você precisa preencher todos os campos.";
        $error1 = 1;
    } elseif (floor($_POST['amount']) < 1) {
        $msg1 .= "Você não pode enviar esta quantia de ouro!";
        $error1 = 1;
    } elseif (!is_numeric(floor($_POST['amount']))) {
        $msg1 .= "Você não pode enviar esta quantia de ouro!";
        $error1 = 1;
    } elseif (floor($_POST['amount']) > $player->gold) {
        $msg1 .= "Você não possui esta quantia de ouro!";
        $error1 = 1;
    } else {
	$db->execute("update `guilds` set `gold`=`gold`+? where `id`=?", [floor($_POST['amount']), $player->guild]);
	$db->execute("update `players` set `gold`=`gold`-? where `id`=?", [floor($_POST['amount']), $player->id]);

		$insert['player_id'] = $player->id;
		$insert['name1'] = $player->username;
		$insert['name2'] = $guild['name'];
		$insert['action'] = "doou";
		$insert['value'] = floor($_POST['amount']);
		$insert['aditional'] = "gangue";
		$insert['time'] = time();
		$query = $db->autoexecute('log_gold', $insert, 'INSERT');

		$lider = $db->GetOne("select `id` from `players` where `username`=?", [$guild['leader']]);
		$vice = $db->GetOne("select `id` from `players` where `username`=?", [$guild['vice']]);
    		$logmsg = sprintf('<b>%s</b> transferiu <b>', $player->username) . floor($_POST['amount']) . " de gold</b> para o clã.";
		addlog($lider, $logmsg, $db);
		addlog($vice, $logmsg, $db);

            	$msg1 .= "Você tranferiu <b>" . floor($_POST['amount']) . "</b> de ouro para seu clã.";
    	}
}

elseif ($_POST['transfer']) {
$query = $db->execute("select * from `players` where `username`=?", [$_POST['username']]);

    if ($query->recordcount() == 0) {
        $msg2 .= "Este usuário não existe!";
        $error2 = 1;
    } elseif (!$_POST['username'] || !$_POST['amount']) {
        $msg2 .= "Você precisa preencher todos os campos.";
        $error2 = 1;
    } elseif (floor($_POST['amount']) < 1) {
        $msg2 .= "Você não pode enviar esta quantia de dinheiro!";
        $error2 = 1;
    } elseif (!is_numeric(floor($_POST['amount']))) {
        $msg2 .= "Você não pode enviar esta quantia de dinheiro!";
        $error2 = 1;
    } elseif (floor($_POST['amount']) > $guild['gold']) {
        $msg2 .= "Seu clã não possui esta quantia de dinheiro!";
        $error2 = 1;
    } else {
        $member = $query->fetchrow();

            		$db->execute("update `guilds` set `gold`=? where `id`=?", [$guild['gold'] - floor($_POST['amount']), $player->guild]);
            		$db->execute("update `players` set `gold`=? where `username`=?", [$member['gold'] + floor($_POST['amount']), $member['username']]);
            		$logmsg = "Você recebeu <b>" . floor($_POST['amount']) . "</b> de ouro do clã: <b>". $guild['name'] ."</b>.";
			addlog($member['id'], $logmsg, $db);

		$insert['player_id'] = $member['id'];
		$insert['name1'] = $player->username;
		$insert['name2'] = $guild['name'];
		$insert['action'] = "ganhou";
		$insert['value'] = floor($_POST['amount']);
		$insert['aditional'] = "gangue";
		$insert['time'] = time();
		$query = $db->autoexecute('log_gold', $insert, 'INSERT');

            	$msg2 .= "Você tranferiu <b>" . floor($_POST['amount']) . "</b> de ouro para: <b>" . $_POST['username'] . "</b>.";
    	}
}

$player = check_user($db);
$query = $db->execute("select * from `guilds` where `id`=?", [$player->guild]);
$guild = $query->fetchrow();

include(__DIR__ . "/templates/private_header.php");

echo "<fieldset>";
echo "<legend><b>" . $guild['name'] . " :: Depositar Ouro</b></legend>";
echo '<form method="POST" action="guild_treasury.php">';
echo "<table>";
echo "<tr>";
echo '<td><b>Quantia:</b></td><td><input name="amount" size="20" type="text"> <input type="submit" name="deposit" value="Depositar"> ' . $msg1 . "</td></tr>";
echo "</table>";
echo "</form>";
echo "</fieldset>";
echo "<br/><br/>";

if ($player->username == $guild['leader'] || $player->username == $guild['vice']) {
	echo "<fieldset>";
	echo "<legend><b>" . $guild['name'] . " :: Tranferir Ouro</b></legend>";
	echo '<form method="POST" action="guild_treasury.php">';
	echo "<table>";
	echo "<tr>";
	echo "<td><b>Usuário:</b></td><td><input type=\"text\" name=\"username\" size=\"20\"/></td></tr>";
	echo '<td><b>Quantia:</b></td><td><input name="amount" size="20" type="text"> <input type="submit" name="transfer" value="Transferir"> ' . $msg2 . "</td></tr>";
	echo "</table>";
	echo "</form>";
	echo "</fieldset>";
	echo "<br/><br/>";
}

echo "<fieldset>";
echo "<legend><b>" . $guild['name'] . " :: Tesouro</b></legend>";
echo "<table>";
echo "<tr><td><b>Saldo livre:</b> " . $guild['gold'] . " moeda(s) de ouro.</td></tr>";
echo "<tr><td><b>Saldo bloqueado:</b> " . $guild['blocked'] . " moeda(s) de ouro.</td></tr>";
echo "<tr><td><b>Total:</b> " . ($guild['gold'] + $guild['blocked']) . " moeda(s) de ouro.</td></tr>";
echo "</table>";

echo "</fieldset>";
echo '<a href="guild_home.php">Voltar</a>.';

include(__DIR__ . "/templates/private_footer.php");
?>
