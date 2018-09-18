<?php
include("lib.php");
define("PAGENAME", "Administração do Clã");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkguild.php");

$error = 0;

$guildquery = $db->execute("select * from `guilds` where `id`=?", array($player->guild));
if ($guildquery->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $guildquery->fetchrow();
}

include("templates/private_header.php");

//Guild Leader Admin check
if (($player->username != $guild['leader']) and ($player->username != $guild['vice'])) {
    echo "Você não pode acessar esta página.<br/>";
    echo "<a href=\"home.php\">Principal</a>.";
} else {

if ($_GET['remove']) {
	if (($guild['vice'] == NULL) or ($guild['vice'] == '')){
		$msg .= "Seu clã não possui um vice lider.";
	}else{
	$db->execute("update `guilds` set `vice`=NULL where `id`=?", array($guild['id']));

		if ($player->username == $guild['leader']){
			$msg .= "Você removeu os privilégios de vice-lider de " . $guild['vice'] . ".";
		}else{
    			echo "Você abandonou seu cargo de vice-liderança no clã: " . $guild['name'] ."<br/>";
    			echo "<a href=\"home.php\">Principal</a>.";
			include("templates/private_footer.php");
			exit;
		}
	}
}


if (isset($_POST['username']) && ($_POST['submit'])) {

	$username = $_POST['username'];
	$query = $db->execute("select `id`, `username`, `guild` from `players` where `username`=? and `serv`=?", array($username, $guild['serv']));

    if ($query->recordcount() == 0) {
    	$errmsg .= "Este usuário não existe!<p />";
    	$error = 1;
   	} else if ($username == $guild['leader']) {
   		$errmsg .= "Este usuário é o lider do clã!";
   		$error = 1;
    } else if ($username == $guild['vice']) {
   		$errmsg .= "Este usuário já é o vice-lider do clã!";
   		$error = 1;
    } else {
   		$member = $query->fetchrow();
	   		if ($member['guild'] != $guild['id']) {
    			$errmsg .= "O usuário $username não faz parte do clã: " . $member['guild'] ."!<p />";
    			$error = 1;
    		} else {
			if (($guild['vice'] == NULL) or ($guild['vice'] == '')){
    			$msg .= "Você nomeou $username como vice-lider do clã.";
			}else{
    			$msg .= "Você nomeou $username como vice-lider do clã.<br/>O antigo vice-lider, " . $guild['vice'] . "  agora é um membro comum.";
			}

    			$query = $db->execute("update `guilds` set `vice`=? where `id`=?", array($username, $guild['id']));
    			$logmsg = "Você foi nomeado vice-lider do clã: ". $guild['name'] .".";
				addlog($member['id'], $logmsg, $db);
    		}
    	}
	}


$guildquery = $db->execute("select * from `guilds` where `id`=?", array($player->guild));
if ($guildquery->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $guildquery->fetchrow();
}

if (($guild['vice'] == NULL) or ($guild['vice'] == '')){
$viceatual1 = "Ninguém.";
}else{
$viceatual1 = $guild['vice'];
$viceatual2 = "<br/><a href=\"guild_admin_vice.php?remove=" . $guild['vice'] . "\">Remover Vice-Liderança de " . $guild['vice'] . "</a>.";
}

	if ($player->username == $guild['leader']){
		echo "<fieldset>";
		echo "<legend><b>" . $guild['name'] . " :: Nomear Vice-Lider</b></legend>";
		echo "<form method=\"POST\" action=\"guild_admin_vice.php\">";
		echo "<table width=\"100%\" border=\"0\"><tr>";
			echo "<td width=\"60%\">";
			echo "<b>Usuário:</b>";
				$query = $db->execute("select `id`, `username` from `players` where `guild`=?", array($guild['id']));
				echo "<select name=\"username\"><option value=''>Selecione</option>";
				while($result = $query->fetchrow()){
					echo "<option value=\"$result[username]\">$result[username]</option>";
				}
				echo "</select>";
			echo "<input type=\"submit\" name=\"submit\" value=\"Nomear Vice-Lider\">";
			echo "</td>";
			echo "<td width=\"40%\" align=\"right\"><b>Vice-Lider atual:</b> " . $viceatual1 . " " . $viceatual2 . "</td>";
		echo "</tr></table>";
		echo "</form>";
			echo "<br/><b>ATENÇÃO:</b> Um vice-lider tem todas as funções do administrador do clã, porem não pode desfazer o mesmo e nem nomear novos vice lideres.";
			echo "<p><center>" . $msg . "<font color=\"red\">" . $errmsg . "</font></center></p>";
		echo "</fieldset>";
		echo "<a href=\"guild_admin.php\">Voltar</a>.";
	}elseif ($player->username == $guild['vice']){
		echo "<fieldset>";
		echo "<legend><b>" . $guild['name'] . " :: Vice-Lider</b></legend>";
		echo "<br/><center><input type=\"button\" value=\"Abandonar cargo de Vice-Liderança.\" onclick=\"window.location.href='guild_admin_vice.php?remove=" . $guild['vice'] . "'\"></center><br/>";
		echo "</fieldset>";
		echo "<a href=\"guild_admin.php\">Voltar</a>.";
	}else{
   		echo "Você não pode acessar esta página.<br/>";
    		echo "<a href=\"home.php\">Principal</a>.";
	}

}
include("templates/private_footer.php");
?>