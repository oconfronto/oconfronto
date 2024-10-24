<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Administração do Clã");
$player = check_user($secret_key, $db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkguild.php");

$error = 0;

$guildquery = $db->execute("select * from `guilds` where `id`=?", array($player->guild));
if ($guildquery->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $guildquery->fetchrow();
}

include(__DIR__ . "/templates/private_header.php");

//Guild Leader Admin check
if ($player->username != $guild['leader'] && $player->username != $guild['vice']) {
    echo "Você não pode acessar esta página.<br/>";
    echo '<a href="home.php">Principal</a>.';
} else {

if ($_GET['remove']) {
	if ($guild['vice'] == NULL || $guild['vice'] == ''){
		$msg .= "Seu clã não possui um vice lider.";
	}else{
	$db->execute("update `guilds` set `vice`=NULL where `id`=?", array($guild['id']));

		if ($player->username == $guild['leader']){
			$msg .= "Você removeu os privilégios de vice-lider de " . $guild['vice'] . ".";
		}else{
    			echo "Você abandonou seu cargo de vice-liderança no clã: " . $guild['name'] ."<br/>";
    			echo '<a href="home.php">Principal</a>.';
			include(__DIR__ . "/templates/private_footer.php");
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
    } elseif ($username == $guild['leader']) {
        $errmsg .= "Este usuário é o lider do clã!";
        $error = 1;
    } elseif ($username == $guild['vice']) {
        $errmsg .= "Este usuário já é o vice-lider do clã!";
        $error = 1;
    } else {
   		$member = $query->fetchrow();
	   		if ($member['guild'] != $guild['id']) {
    			$errmsg .= sprintf('O usuário %s não faz parte do clã: ', $username) . $member['guild'] ."!<p />";
    			$error = 1;
    		} else {
			if ($guild['vice'] == NULL || $guild['vice'] == ''){
    			$msg .= sprintf('Você nomeou %s como vice-lider do clã.', $username);
			}else{
    			$msg .= sprintf('Você nomeou %s como vice-lider do clã.<br/>O antigo vice-lider, ', $username) . $guild['vice'] . "  agora é um membro comum.";
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

if ($guild['vice'] == NULL || $guild['vice'] == ''){
$viceatual1 = "Ninguém.";
}else{
$viceatual1 = $guild['vice'];
$viceatual2 = '<br/><a href="guild_admin_vice.php?remove=' . $guild['vice'] . "\">Remover Vice-Liderança de " . $guild['vice'] . "</a>.";
}

	if ($player->username == $guild['leader']){
		echo "<fieldset>";
		echo "<legend><b>" . $guild['name'] . " :: Nomear Vice-Lider</b></legend>";
		echo '<form method="POST" action="guild_admin_vice.php">';
		echo '<table width="100%" border="0"><tr>';
			echo '<td width="60%">';
			echo "<b>Usuário:</b>";
				$query = $db->execute("select `id`, `username` from `players` where `guild`=?", array($guild['id']));
				echo "<select name=\"username\"><option value=''>Selecione</option>";
				while($result = $query->fetchrow()){
					echo sprintf('<option value="%s">%s</option>', $result[username], $result[username]);
				}
    
				echo "</select>";
			echo '<input type="submit" name="submit" value="Nomear Vice-Lider">';
			echo "</td>";
			echo '<td width="40%" align="right"><b>Vice-Lider atual:</b> ' . $viceatual1 . " " . $viceatual2 . "</td>";
		echo "</tr></table>";
		echo "</form>";
			echo "<br/><b>ATENÃÃO:</b> Um vice-lider tem todas as funções do administrador do clã, porem não pode desfazer o mesmo e nem nomear novos vice lideres.";
			echo "<p><center>" . $msg . '<font color="red">' . $errmsg . "</font></center></p>";
		echo "</fieldset>";
		echo '<a href="guild_admin.php">Voltar</a>.';
	}elseif ($player->username == $guild['vice']){
		echo "<fieldset>";
		echo "<legend><b>" . $guild['name'] . " :: Vice-Lider</b></legend>";
		echo "<br/><center><input type=\"button\" value=\"Abandonar cargo de Vice-Liderança.\" onclick=\"window.location.href='guild_admin_vice.php?remove=" . $guild['vice'] . "'\"></center><br/>";
		echo "</fieldset>";
		echo '<a href="guild_admin.php">Voltar</a>.';
	}else{
   		echo "Você não pode acessar esta página.<br/>";
    		echo '<a href="home.php">Principal</a>.';
	}

}

include(__DIR__ . "/templates/private_footer.php");
?>
