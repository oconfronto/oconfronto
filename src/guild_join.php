<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Entrar no Clã");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkguild.php");

//Check for user ID
if (!($_GET['id'] ?? null)) {
	header("Location: guild_listing.php");
} else {
	//Populates $guild variable
	$query = $db->execute("select * from  guilds  where  id =?", [$_GET['id'] ?? null]);
	if ($query->recordcount() == 0) {
		header("Location: guild_listing.php");
	} else {
		$guild = $query->fetchrow();
	}

	if (!($_GET['confirm'] ?? null)) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset>";
		echo "<legend><b>" . $guild['name'] . " ::Entrar</b></legend>";
		echo "Deseja fazer parte do clã <b>" . $guild['name'] . "</b> por <b>" . $guild['price'] . "</b> moedas de ouro?<br/><br/>";
		echo '<table width="100%" border="0"><tr>';
		echo "<td width=\"50%\"><a href=\"home.php\">Não</a></td>";
		echo '<td width="50%" align="right"><a href="guild_join.php?id=' . $_GET['id'] . '&confirm=true">Sim</a></td>';
		echo "</tr></table>";
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	include(__DIR__ . "/templates/private_header.php");
	//Checks if player is in a guild or cannot afford the guild price
	if ($player->guild != NULL) {
		echo "<fieldset>";
		echo "<legend><b>" . $guild['name'] . " :: Entrar</b></legend>";
		echo "Você já está em um clã!<br/>";
		echo "</fieldset>";
		echo '<a href="home.php">Principal</a>';
	} elseif ($player->gold < ($guild['price'] ?? null)) {
		echo "<fieldset>";
		echo "<legend><b>" . $guild['name'] . " :: Entrar</b></legend>";
		echo "Você não tem dinheiro para entar no clã. Custa " . $guild['price'] . " de ouro.<br/>";
		echo "</fieldset>";
		echo '<a href="home.php">Principal</a>';
	} else {
		$mayjoin = true;
		if ($db->execute("show tables like 'guild_invites'")->recordcount() > 0) { // if guild invites mod is installed ...
			$checkquery = $db->execute("select count(*) inv_count from guild_invites where player_id =? and guild_id =?", [$player->id, $guild['id'] ?? null]);
			$check = $checkquery->fetchrow();
			if (($check['inv_count'] ?? null) > 0) {
				$db->execute("delete from guild_invites where guild_id=? and player_id=?", [$guild['id'] ?? null, $player->id]);
			} else {
				echo "<fieldset>";
				echo "<legend><b>" . $guild['name'] . " :: Entrar</b></legend>";
				echo "Você não foi convidado por este clã.<br/>";
				echo "</fieldset>";
				echo '<a href="home.php">Principal</a>';
				$mayjoin = false;
			}
		}

		if ($mayjoin == true) {
			$db->execute("update players set  gold=?, guild=? where id=?", [$player->gold - $guild['price'], $guild['id'] ?? null, $player->id]);
			$db->execute("update guilds set members=?, gold=? where id=?", [$guild['members'] + 1, $guild['gold'] + $guild['price'], $guild['id'] ?? null]);
			echo "<fieldset>";
			echo "<legend><b>" . $guild['name'] . " :: Entrar</b></legend>";
			echo "Obrigado por participar do clã: <b>" . $guild['name'] . "</b>!<br/>";
			echo "</fieldset>";
			echo '<table width="100%" border="0"><tr>';
			echo '<td width="50%"><a href="home.php">Voltar</a></td>';
			echo "<td width=\"50%\" align=\"right\"><a href=\"guild_home.php\">Página do Clã</a></td>";
			echo "</tr></table>";
		}
	}

	include(__DIR__ . "/templates/private_footer.php");
}
