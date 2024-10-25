<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Administração do Clã");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkguild.php");

$error = 0;
$errorb = 0;

//Populates $guild variable
$guildquery = $db->execute("select `id`, `name`, `leader`, `vice`, `members`, `serv` from `guilds` where `id`=?", [$player->guild]);

if ($guildquery->recordcount() == 0) {
	header("Location: home.php");
} else {
	$guild = $guildquery->fetchrow();
}

include(__DIR__ . "/templates/private_header.php");

//Guild Leader Admin check
if ($player->username != $guild['leader'] && $player->username != $guild['vice']) {
	echo "Você não pode acessar esta página.";
	echo '<br/><a href="home.php">Voltar</a>.';
} else {

	if ($_GET['unaliance'] && $_GET['aled_na']) {

		$alynamme = $_GET['aled_na'];

		$acheckcla = $db->execute("select `name` from `guilds` where `id`=?", [$alynamme]);
		$bcheckjaaly = $db->execute("select `id` from `guild_paliance` where (`guild_na`=? and `aled_na`=?) or (`guild_na`=? and `aled_na`=?)", [$guild['id'], $alynamme, $alynamme, $guild['id']]);
		$ccheckjaaly = $db->execute("select `id` from `guild_aliance` where `guild_na`=? and `aled_na`=?", [$guild['id'], $alynamme]);

		if ($acheckcla->recordcount() != 1) {
			$errmsg .= "Este clã não existe!";
			$errorb = 1;
		} elseif ($bcheckjaaly->recordcount() < 1 && $ccheckjaaly->recordcount() < 1) {
			$errmsg .= "Este clã não é um clã aliado!";
			$errorb = 1;
		} elseif ($errorb === 0) {
			$deletaaliancagname = $db->GetOne("select `name` from `guilds` where `id`=?", [$_GET['aled_na']]);
			$log1 = $db->execute("select `id` from `players` where `guild`=?", [$alynamme]);
			while ($p1 = $log1->fetchrow()) {
				$logmsg1 = "O clã <a href=\"guild_profile.php?id=" . $guild['id'] . '">' . $guild['name'] . "</a> desfez as aliança que tinha com seu clã.";
				addlog($p1['id'], $logmsg1, $db);
			}

			$msgallyname = $db->GetOne("select `name` from `guilds` where `id`=?", [$alynamme]);
			$log2 = $db->execute("select `id` from `players` where `guild`=?", [$guild['id']]);
			while ($p2 = $log2->fetchrow()) {
				$logmsg2 = "Seu clã desfez as alianças que tinha com o clã <a href=\"guild_profile.php?id=" . $alynamme . '">' . $msgallyname . "</a>.";
				addlog($p2['id'], $logmsg2, $db);
			}

			$query = $db->execute("delete from `guild_aliance` where `guild_na`=? and `aled_na`=?", [$guild['id'], $alynamme]);
			$query = $db->execute("delete from `guild_aliance` where `guild_na`=? and `aled_na`=?", [$alynamme, $guild['id']]);
			$query = $db->execute("delete from `guild_paliance` where `guild_na`=? and `aled_na`=?", [$guild['id'], $alynamme]);
			$query = $db->execute("delete from `guild_paliance` where `guild_na`=? and `aled_na`=?", [$alynamme, $guild['id']]);
			$msg .= "As ligações com o clã " . $deletaaliancagname . " foram removidas com sucesso.";
		}
	} elseif (isset($_POST['gname']) && ($_POST['submit'])) {

		$checkcla = $db->execute("select `id`, `leader`, `vice`, `name`, `serv` from `guilds` where `id`=?", [$_POST['gname']]);
		$checkjaaly0 = $db->execute("select `id` from `guild_paliance` where (`guild_na`=? and `aled_na`=?) or (`guild_na`=? and `aled_na`=?)", [$guild['id'], $_POST['gname'], $_POST['gname'], $guild['id']]);
		$checkjaaly1 = $db->execute("select `id` from `guild_aliance` where `guild_na`=? and `aled_na`=?", [$guild['id'], $_POST['gname']]);
		$checkjaaly2 = $db->execute("select `id` from `guild_enemy` where `guild_na`=? and `enemy_na`=?", [$guild['id'], $_POST['gname']]);
		$aliancaguildname = $db->GetOne("select `name` from `guilds` where `id`=?", [$_POST['gname']]);

		if ($checkcla->recordcount() != 1) {
			$errmsg .= "Este clã não existe!";
			$error = 1;
		} elseif ($checkjaaly0->recordcount() > 0) {
			$errmsg .= "Uma solicitação de aliança entre o seu clã e o clã " . $aliancaguildname . " já está pendente.";
			$error = 1;
		} elseif ($checkjaaly1->recordcount() > 0) {
			$errmsg .= "Este clã já é um aliado!";
			$error = 1;
		} elseif ($checkjaaly2->recordcount() > 0) {
			$errmsg .= "Este clã é um clã inimigo!";
			$error = 1;
		} elseif ($error === 0) {
			$enyguild = $checkcla->fetchrow();
			if ($guild['serv'] != $enyguild['serv']) {
				echo "Este clã pertence a outro servidor.";
				echo '<br/><a href="guild_admin_aliado.php">Voltar</a>.';
				include(__DIR__ . "/templates/private_footer.php");
				exit;
			}

			$to1 = $db->GetOne("select `id` from `players` where `username`=?", [$enyguild['leader']]);
			$to2 = $db->GetOne("select `id` from `players` where `username`=?", [$enyguild['vice']]);
			$insert['guild_na'] = $guild['id'];
			$insert['aled_na'] = $enyguild['id'];
			$insert['time'] = time();
			$acpt = $db->autoexecute('guild_paliance', $insert, 'INSERT');
			$acptid = $db->Insert_ID();
			$logmsg = "O clã <a href=\"guild_profile.php?id=" . $guild['name'] . '">' . $guild['name'] . "</a> está solicitando uma aliança com seu clã. <a href=\"guild_admin_accept.php?id=" . $acptid . '">Clique aqui</a> para aceitar.';
			addlog($to1, $logmsg, $db);
			$logmsg2 = "O clã <a href=\"guild_profile.php?id=" . $guild['name'] . '">' . $guild['name'] . "</a> está solicitando uma aliança com seu clã. <a href=\"guild_admin_accept.php?id=" . $acptid . '">Clique aqui</a> para aceitar.';
			addlog($to2, $logmsg2, $db);
			$msg .= "Você solicitou uma aliança com o clã " . $enyguild['name'] . ". Se ela for aceita, você será informado.";
		} else {
			$errmsg .= "Um erro desconhecido ocorreu.";
			$error = 1;
		}
	}
?>

	<fieldset>
		<legend><b><?= $guild['name'] ?> :: Clãs Aliados</b></legend>
		<form method="POST" action="guild_admin_aliado.php">
			<b>Solicitar aliança com o clã:</b> <?php $query = $db->execute("select `id`, `name` from `guilds` where `name`!=? and `serv`=?", [$guild['name'], $guild['serv']]);
												echo "<select name=\"gname\"><option value=''>Selecione</option>";
												while ($result = $query->fetchrow()) {
													echo sprintf('<option value="%s">%s</option>', $result["id"], $result["name"]);
												}

												echo "</select>"; ?> <input type="submit" name="submit" value="Solicitar Aliança">
		</form>
	</fieldset>
	<center>
		<p />
		<font color=green><?= $msg ?></font>
		<p />
	</center>
	<center>
		<p />
		<font color=red><?= $errmsg ?></font>
		<p />
	</center>
	<br />
	<fieldset>
		<legend><b>Gerenciar Alianças</b></legend>
		<?php
		$query0000 = $db->execute("select `aled_na` from `guild_aliance` where `guild_na`=? order by `aled_na` asc", [$guild['id']]);
		$query0001 = $db->execute("select `aled_na` from `guild_paliance` where `guild_na`=? order by `aled_na` asc", [$guild['id']]);
		$query0002 = $db->execute("select `id`, `guild_na` from `guild_paliance` where `aled_na`=? order by `aled_na` asc", [$guild['id']]);

		if ($query0000->recordcount() < 1 && $query0001->recordcount() < 1 && $query0002->recordcount() < 1) {
			echo "<p /><center>Seu clã não possui alianças.</center><p />";
		} else {
			echo '<table width="100%" border="0">';
			echo "<tr>";
			echo "<th width=\"30%\"><b>Clã</b></td>";
			echo '<th width="15%"><b>Membros</b></td>';
			echo '<th width="25%"><b>Status</b></td>';
			echo "<th width=\"30%\"><b>Opções</b></td>";
			echo "</tr>";

			while ($ali = $query0000->fetchrow()) {
				$postgname = $db->GetOne("select `name` from `guilds` where `id`=?", [$ali["aled_na"]]);
				$postgmembers = $db->GetOne("select `members` from `guilds` where `id`=?", [$ali["aled_na"]]);

				echo "<tr>\n";
				echo '<td><a href="guild_profile.php?id=' . $ali["aled_na"] . '"><b>' . $postgname . "</b></a></td>";
				echo "<td>" . $postgmembers . "</td>";
				echo "<td>Estado de paz.</td>";
				echo '<td><font size="1px"><a href="guild_admin_aliado.php?unaliance=true&aled_na=' . $ali["aled_na"] . "\">Desfazer Aliança</a></font></td>";
				echo "</tr>";
			}

			while ($ali = $query0001->fetchrow()) {
				$postgname = $db->GetOne("select `name` from `guilds` where `id`=?", [$ali["aled_na"]]);
				$postgmembers = $db->GetOne("select `members` from `guilds` where `id`=?", [$ali["aled_na"]]);

				echo "<tr>\n";
				echo '<td><a href="guild_profile.php?id=' . $ali["aled_na"] . '"><b>' . $postgname . "</b></a></td>";
				echo "<td>" . $postgmembers . "</td>";
				echo "<td>Estado de paz.</td>";
				echo '<td><font size="1px"><a href="guild_admin_aliado.php?unaliance=true&aled_na=' . $ali["aled_na"] . "\">Remover solicitação de aliança</a></font></td>";
				echo "</tr>";
			}

			while ($ali = $query0002->fetchrow()) {
				$postgname = $db->GetOne("select `name` from `guilds` where `id`=?", [$ali["aled_na"]]);
				$postgmembers = $db->GetOne("select `members` from `guilds` where `id`=?", [$ali["aled_na"]]);

				echo "<tr>\n";
				echo '<td><a href="guild_profile.php?id=' . $ali["aled_na"] . '"><b>' . $postgname . "</b></a></td>";
				echo "<td>" . $postgmembers . "</td>";
				echo "<td>Estado de paz.</td>";
				echo '<td><font size="1px"><a href="guild_admin_accept.php?id=' . $ali["id"] . "\">Aceitar Aliança</a></font></td>";
				echo "</tr>";
			}

			echo "</table>";
		}
		?>
	</fieldset>
	<a href="guild_admin.php">Voltar</a>.

<?php
}

include(__DIR__ . "/templates/private_footer.php");
?>