<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Administração do Clã");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkguild.php");

$error = 0;
$username = ($_POST['username']);

$guildquery = $db->execute("select * from `guilds` where `id`=?", [$player->guild]);

if ($guildquery->recordcount() == 0) {
	header("Location: home.php");
} else {
	$guild = $guildquery->fetchrow();
}

include(__DIR__ . "/templates/private_header.php");
//Guild Leader Admin check
if ($player->username != ($guild['leader'] ?? null) && $player->username != ($guild['vice'] ?? null)) {
	echo "Você não pode acessar esta página. <a href=\"home.php\">Voltar</a>.";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

//Guild Leader Admin check
if (($guild['msgs'] ?? null) > 3) {
	echo "Seu clã já enviou mensagens demais hoje.<br>Máximo de 3 mensagens por dia. <a href=\"home.php\">Voltar</a>.";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if ($_POST['submit'] ?? null) {
	if (!($_POST['subject'] ?? null)) {
		$errmsg .= "<font color=red>Você precisa adicionar um titulo para sua mensagem.</font>";
		$error = 1;
	}

	if (!($_POST['body'] ?? null)) {
		$errmsg .= "<font color=red>Você precisa escrever uma mensagem.</font>";
		$error = 1;
	}

	if (strlen((string) ($_POST['body'] ?? null)) > 5000) {
		$errmsg .= "<font color=red>Sua mensagem deve ter menos que 5000 caracteres.</font>";
		$error = 1;
	}


	if ($error == 0) {
		$mensagem = "<div style='width:100%; background-color:#EEA2A2; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px' align='center'><font size=1><b>Esta mensagem foi enviada para todos os membros do clã: " . $guild['name'] . ".</b></font></div><br/>" . $_POST['body'] . "";

		$database = $db->execute("select `id` from `players` where `guild`=?", [$guild['id'] ?? null]);
		while ($member = $database->fetchrow()) {
			$query = $db->execute("insert into `mail` (`to`, `from`, `body`, `subject`, `time`) values (?, ?, ?, ?, ?)", [$member['id'] ?? null, $player->id, $mensagem, $_POST['subject'] ?? null, time()]);
		}

		$query = $db->execute("update `guilds` set `msgs`=? where `id`=?", [$guild['msgs'] + 1, $player->guild]);
		$errmsg .= "Mensagem enviada com sucesso.";
	}
}


?>

<fieldset>
	<legend><b><?= $guild['name'] ?? null ?> :: Enviar mensagem</b></legend>
	<form method="POST" action="guild_admin_msg.php">
		<table width="100%" border="0">
			<tr>
				<td width="20%"><b>Para:</b></td>
				<td width="80%">Membros do Clã <?= $guild['name'] ?? null ?></td>
			</tr>
			<tr>
				<td width="20%"><b>Assunto:</b></td>
				<td width="80%"><input type="text" name="subject" /></td>
			</tr>
			<tr>
				<td width="20%"><b>Mensagem:</b></td>
				<td width="80%"><textarea name="body" rows="15" cols="50"></textarea></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="Enviar" name="submit" /></td>
			</tr>
		</table>
	</form>
	<br><?= $errmsg ?>
</fieldset>
<a href="guild_admin.php">Voltar</a>.

<?php
include(__DIR__ . "/templates/private_footer.php");
?>