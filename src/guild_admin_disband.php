<?php
include("lib.php");
define("PAGENAME", "Desfazer Clã");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkguild.php");

//Populates $guild variable
$query = $db->execute("select * from `guilds` where `id`=?", array($player->guild));

if ($query->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $query->fetchrow();
}

include("templates/private_header.php");

//Guild Leader Admin check
if ($player->username != $guild['leader']) {
	echo "<fieldset>";
	echo "<legend><b>Acesso Negado</b></legend>";
	echo "<p />Você não pode acessar esta página.<br/><br/>";
	echo "<a href=\"home.php\">Principal</a>";
	echo "</fieldset>";
} else {

if ($_GET['act'] == "go") {
		$query4 = $db->execute("select `id` from `players` where `guild`=?", array($guild['id']));
		while($member = $query4->fetchrow()) {
		$logmsg = "A gangue " . $guild['name'] . " foi deletada pelo lider do clã.";
		addlog($member['id'], $logmsg, $db);
		}

	$db->execute("update `players` set `bank`=`bank`+? where `username`=?", array($guild['gold'], $guild['leader']));
        $db->execute("delete from `guilds` where `id`=?", array($player->guild));
        $db->execute("delete from `guild_invites` where `guild_id`=?", array($player->guild));
        $db->execute("delete from `guild_chat` where `guild_id`=?", array($player->guild));
        $db->execute("delete from `guild_enemy` where (`guild_na`=? or `enemy_na`=?)", array($player->guild, $player->guild));
        $db->execute("delete from `guild_aliance` where (`guild_na`=? or `aled_na`=?)", array($player->guild, $player->guild));
        $db->execute("delete from `guild_paliance` where (`guild_na`=? or `aled_na`=?)", array($player->guild, $player->guild));

	$db->execute("update `players` set `guild`=? where `guild`=?", array(NULL, $guild['id']));

	echo "<fieldset>";
	echo "<legend><b>" . $guild['name'] . " :: Desfazer Clã</b></legend>";
        echo "Seu clã foi excluido com sucesso!<br/><br/>";
        echo "<a href=\"home.php\">Principal</a>";
	echo "</fieldset>";
} else {
echo "<fieldset>";
echo "<legend><b>" . $guild['name'] . " :: Desfazer Clã</b></legend>";
echo "Você tem certeza que quer excluir o clã: " . $guild['name'] . "?<br/><br/>";
echo "<table width=\"100%\" border=\"0\"><tr>";
echo "<td width=\"50%\"><a href=\"guild_admin.php\">Voltar</a></td>";
echo "<td width=\"50%\" align=\"right\"><a href=\"guild_admin_disband.php?act=go\">Desfazer Clã</a></td>";
echo "</tr></table>";
echo "</fieldset>";
}

}
include("templates/private_footer.php");
?>