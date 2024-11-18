<?php

declare(strict_types=1);

/*************************************/
/*           ezRPG script            */
/*         Written by Khashul        */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.bbgamezone.com/     */
/*************************************/

include(__DIR__ . "/lib.php");
define("PAGENAME", "Administração do Clã");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkguild.php");

$error = 0;
$username = ($_POST['username']);
$amount = floor($_POST['amount']);

//Populates $guild variable
$query = $db->execute("select * from `guilds` where `id`=?", [$player->guild]);

if ($query->recordcount() == 0) {
	header("Location: home.php");
} else {
	$guild = $query->fetchrow();
}

include(__DIR__ . "/templates/private_header.php");

//Guild Leader Admin check
if ($player->username != $guild['leader'] && $player->username != $guild['vice']) {
	echo "<p />Você não pode acessar esta página.<p />";
	echo '<a href="home.php">Principal</a><p />';
} else {

	if (isset($_POST['username']) && ($_POST['amount']) && ($_POST['submit'])) {

		$query = $db->execute("select * from `players` where `username`=?", [$username]);

		if ($query->recordcount() == 0) {
			$errmsg .= "Este usuário não existe!<p />";
			$error = 1;
		} elseif ($amount < 1) {
			$errmsg .= "Você não pode enviar esta quantia de dinheiro!<p />";
			$error = 1;
		} elseif (!is_numeric($amount)) {
			$errmsg .= "Você não pode enviar esta quantia de dinheiro!<p />";
			$error = 1;
		} elseif ($amount > $guild['gold']) {
			$errmsg .= "Seu clã não possui esta quantia de dinheiro!<p />";
			$error = 1;
		} else {
			$member = $query->fetchrow();
			if ($member['guild'] != $guild['id']) {
				$errmsg .= sprintf('O usuário %s não é membro do clã ', $username) . $guild['name'] . "!<p />";
				$error = 1;
			} else {
				$query = $db->execute("update `guilds` set `gold`=? where `id`=?", [$guild['gold'] - $amount, $player->guild]);
				$query1 = $db->execute("update `players` set `gold`=? where `username`=?", [$member['gold'] + $amount, $member['username']]);
				$logmsg = sprintf('Você recebeu <b>%s</b> de ouro do clã: <b>', $amount) . $guild['name'] . "</b>.";
				addlog($member['id'], $logmsg, $db);

				$insert['player_id'] = $member['id'];
				$insert['name1'] = $player->username;
				$insert['name2'] = $guild['name'];
				$insert['action'] = "ganhou";
				$insert['value'] = $amount;
				$insert['aditional'] = "gangue";
				$insert['time'] = time();
				$query = $db->autoexecute('log_gold', $insert, 'INSERT');

				$msg .= sprintf('Você transferiu <b>%s</b> de ouro para: <b>%s</b>.<p />', $amount, $username);
			}
		}
	}

?>

	<fieldset>
		<legend><b><?= $guild['name'] ?> :: Transferir Ouro</b></legend>
		<form method="POST" action="guild_admin_treasury.php">
			<table>
				<tr>
					<td><b>Usuário:</b></td>
					<td><input type="text" name="username" size="20" /></td>
				</tr>
				<td><b>Quantia:</b></td>
				<td><input name="amount" size="20" type="text"> <input type="submit" name="submit" value="Enviar"></td>
				</tr>
			</table>
		</form>
	</fieldset>
	<p /><?= $msg ?>
	<p />
	<p />
	<font color=red><?= $errmsg ?></font>
	<p />
	<fieldset>
		<legend><b><?= $guild['name'] ?> :: Saldo</b></legend>
		Existe <b><?= $guild['gold'] ?> de ouro</b> no tesouro do clã.
	</fieldset>
	<a href="guild_admin.php">Voltar</a>.

<?php
}

include(__DIR__ . "/templates/private_footer.php");
?>