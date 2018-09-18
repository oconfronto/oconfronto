<?php 
include("lib.php");
define("PAGENAME", "Abandonar Clã");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkguild.php");

//Populates $guild variable
$query = $db->execute("select * from `guilds` where `id`=?", array($player->guild));
if ($query->recordcount() == 0) {
      header("Location: home.php");
}else{ 
$guild = $query->fetchrow();
}

include("templates/private_header.php");
  
if ($_GET['act'] == "go") {
	$leader = $db->GetOne("select `leader` from `guilds` where `id`=?", array($player->guild));
	$vice = $db->GetOne("select `vice` from `guilds` where `id`=?", array($player->guild));
      
    if (($player->username != $leader) and ($player->username != $vice)) {
    	$query = $db->execute("update `guilds` set `members`=? where `id`=?", array($guild['members'] - 1, $player->guild));
        $query = $db->execute("update `players` set `guild`=? where `username`=?", array(NULL, $player->username));
	echo "<fieldset>";
	echo "<legend><b>" . $guild['name'] . " :: Abandonar Clã</b></legend>";
        echo "Você abandonou seu clã com sucesso.<br />";
	echo "</fieldset>";
        echo "<a href=\"home.php\">Principal</a>";
    } else {
	echo "<fieldset>";
	echo "<legend><b>" . $guild['name'] . " :: Abandonar Clã</b></legend>";
      	echo "Você não pode abandonar este clã. Se você for o lider dele, deverá desfaze-lo primeiro. Se for o vice-lider, abandone seu cargo primeiro.<br />";
	echo "</fieldset>";
        echo "<a href=\"guild_home.php\">Voltar</a>";
    }
} else {
	echo "<fieldset>";
	echo "<legend><b>" . $guild['name'] . " :: Abandonar Clã</b></legend>";
	echo "Você tem certeza que quer abandonar seu clã?<br />";
	echo "<table width=\"100%\" border=\"0\"><tr>";
	echo "<td width=\"50%\"><a href=\"guild_home.php\">Voltar</a></td>";
	echo "<td width=\"50%\" align=\"right\"><a href=\"guild_leave.php?act=go\">Abandonar</a></td>";
	echo "</tr></table>";
	echo "</fieldset>";
}

include("templates/private_footer.php");
?>