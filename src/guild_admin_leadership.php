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

//Populates $guild variable
$guildquery = $db->execute("select * from `guilds` where `id`=?", [$player->guild]);

if ($guildquery->recordcount() == 0) {
	header("Location: home.php");
} else {
	$guild = $guildquery->fetchrow();
}

include(__DIR__ . "/templates/private_header.php");

//Guild Leader Admin check
if ($player->username != ($guild['leader'] ?? null)) {
	echo "<p />Você não pode acessar esta página.<p />";
	echo '<a href="home.php">Principal</a><p />';
} else {

	if (($_POST['username'] ?? null) && ($_POST['submit'] ?? null)) {
		$username = $_POST['username'];
		$query = $db->execute("select `id`, `username`, `guild` from `players` where `username`=?", [$username]);

		if ($query->recordcount() == 0) {
			$errmsg .= "Este usuário não existe!<p />";
			$error = 1;
		} elseif ($username == ($guild['leader'] ?? null)) {
			$errmsg .= "Este usuário já é o lider do clã!<p />";
			$error = 1;
		} else {
			$member = $query->fetchrow();
			if (($member['guild'] ?? null) != ($guild['id'] ?? null)) {
				$errmsg .= sprintf('O usuário %s não faz parte do clã: ', $username) . $member['guild'] . "!<p />";
				$error = 1;
			} else {
				if ($username == ($guild['vice'] ?? null)) {
					$query = $db->execute("update `guilds` set `leader`=?, `vice`='' where `id`=?", [$username, $guild['id'] ?? null]);
				} else {
					$query = $db->execute("update `guilds` set `leader`=? where `id`=?", [$username, $guild['id'] ?? null]);
				}

				$logmsg = "Você foi nomeado lider do clã: " . $guild['name'] . ".";
				addlog($member['id'], $logmsg, $db);
				$msg .= sprintf('Você nomeou %s como lider do clã. <a href="home.php">Clique aqui</a> para voltar a página inicial.<p />', $username);
			}
		}
	}

?>

	<fieldset>
		<legend><b><?= $guild['name'] ?? null ?> :: Passar Liderança</b></legend>
		<p>
		<form method="POST" action="guild_admin_leadership.php">
			<b>Usuário:</b> <?php $query = $db->execute("select `id`, `username` from `players` where `guild`=?", [$guild['id'] ?? null]);
							echo "<select name=\"username\"><option value=''>Selecione</option>";
							while ($result = $query->fetchrow()) {
								echo sprintf('<option value="%s">%s</option>', $result["username"], $result["username"]);
							}

							echo "</select>"; ?> <input type="submit" name="submit" value="Passar liderança">
			<p />
		</form>
		<b>ATENÃÃO:</b> Nomeando um novo lider, você perderá todas as funções da administração!
		<p /><?= $msg ?>
		<p />
		<p />
		<font color=red><?= $errmsg ?></font>
		<p />
	</fieldset>
	<a href="guild_admin.php">Voltar</a>.

<?php
}

include(__DIR__ . "/templates/private_footer.php");
?>