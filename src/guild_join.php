<?php
include("lib.php");
define("PAGENAME", "Entrar no Clã");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkguild.php");

//Check for user ID
if (!$_GET['id']) {
	header("Location: guild_listing.php");
} else {
	//Populates $guild variable
	$query = $db->execute("select * from  guilds  where  id =?", array($_GET['id']));
	if ($query->recordcount() == 0) {
		header("Location: guild_listing.php");
	} else {
		$guild = $query->fetchrow();
	}

if (!$_GET['confirm']){
include("templates/private_header.php");
echo "<fieldset>";
echo "<legend><b>" . $guild['name'] . " ::Entrar</b></legend>";
echo "Deseja fazer parte do clã <b>" . $guild['name'] . "</b> por <b>" . $guild['price'] . "</b> moedas de ouro?<br/><br/>";
echo "<table width=\"100%\" border=\"0\"><tr>";
echo "<td width=\"50%\"><a href=\"home.php\">Não</a></td>";
echo "<td width=\"50%\" align=\"right\"><a href=\"guild_join.php?id=" . $_GET['id'] . "&confirm=true\">Sim</a></td>";
echo "</tr></table>";
echo "</fieldset>";
include("templates/private_footer.php");
exit;
}
	
	include("templates/private_header.php");
	//Checks if player is in a guild or cannot afford the guild price
	if ($player->guild != NULL) {
		echo "<fieldset>";
		echo "<legend><b>" . $guild['name'] . " :: Entrar</b></legend>";
		echo "Você já está em um clã!<br/>";
		echo "</fieldset>";
		echo "<a href=\"home.php\">Principal</a>";
	} elseif ($player->gold < $guild['price']) {
		echo "<fieldset>";
		echo "<legend><b>" . $guild['name'] . " :: Entrar</b></legend>";
		echo "Você não tem dinheiro para entar no clã. Custa " . $guild['price'] . " de ouro.<br/>";
		echo "</fieldset>";
		echo "<a href=\"home.php\">Principal</a>";
	} else {
		$mayjoin = true;
		if ($db->execute("show tables like 'guild_invites'")->recordcount() > 0) { // if guild invites mod is installed ...
			$checkquery = $db->execute("select count(*) inv_count from guild_invites where player_id =? and guild_id =?", array($player->id, $guild['id']));	
			$check = $checkquery->fetchrow();
			if ($check['inv_count'] > 0) {
				$db->execute("delete from guild_invites where guild_id=? and player_id=?", array($guild['id'], $player->id));	
			} else {
				echo "<fieldset>";
				echo "<legend><b>" . $guild['name'] . " :: Entrar</b></legend>";
				echo "Você não foi convidado por este clã.<br/>";
				echo "</fieldset>";
				echo "<a href=\"home.php\">Principal</a>";
				$mayjoin = false;
			}
		}
		if ($mayjoin == true) {
			$db->execute("update players set  gold=?, guild=? where id=?", array($player->gold - $guild['price'], $guild['id'], $player->id));
			$db->execute("update guilds set members=?, gold=? where id=?", array($guild['members'] + 1, $guild['gold'] + $guild['price'], $guild['id']));
			echo "<fieldset>";
			echo "<legend><b>" . $guild['name'] . " :: Entrar</b></legend>";
			echo "Obrigado por participar do clã: <b>" . $guild['name'] . "</b>!<br/>";
			echo "</fieldset>";
			echo "<table width=\"100%\" border=\"0\"><tr>";
			echo "<td width=\"50%\"><a href=\"home.php\">Voltar</a></td>";
			echo "<td width=\"50%\" align=\"right\"><a href=\"guild_home.php\">Página do Clã</a></td>";
			echo "</tr></table>";
		}
	}
	include("templates/private_footer.php");
}

?>