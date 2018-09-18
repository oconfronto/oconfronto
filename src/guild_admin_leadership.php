<?php

/*************************************/
/*           ezRPG script            */
/*         Written by Khashul        */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.bbgamezone.com/     */
/*************************************/

include("lib.php");
define("PAGENAME", "Administração do Clã");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkguild.php");

$error = 0;

//Populates $guild variable
$guildquery = $db->execute("select * from `guilds` where `id`=?", array($player->guild));

if ($guildquery->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $guildquery->fetchrow();
}

include("templates/private_header.php");

//Guild Leader Admin check
if ($player->username != $guild['leader']) {
    echo "<p />Você não pode acessar esta página.<p />";
    echo "<a href=\"home.php\">Principal</a><p />";
} else {

if (isset($_POST['username']) && ($_POST['submit'])) {
	$username = $_POST['username'];
	$query = $db->execute("select `id`, `username`, `guild` from `players` where `username`=?", array($username));

    if ($query->recordcount() == 0) {
    	$errmsg .= "Este usuário não existe!<p />";
    	$error = 1;
   	} else if ($username == $guild['leader']) {
   		$errmsg .= "Este usuário já é o lider do clã!<p />";
   		$error = 1;
    } else {
   		$member = $query->fetchrow();
	   		if ($member['guild'] != $guild['id']) {
    			$errmsg .= "O usuário $username não faz parte do clã: " . $member['guild'] ."!<p />";
    			$error = 1;
    		} else {
			if ($username == $guild['vice']){
    			$query = $db->execute("update `guilds` set `leader`=?, `vice`='' where `id`=?", array($username, $guild['id']));
			}else{
    			$query = $db->execute("update `guilds` set `leader`=? where `id`=?", array($username, $guild['id']));
			}

    			$logmsg = "Você foi nomeado lider do clã: ". $guild['name'] .".";
				addlog($member['id'], $logmsg, $db);
    			$msg .= "Você nomeou $username como lider do clã. <a href=\"home.php\">Clique aqui</a> para voltar a página inicial.<p />";
    		}
    	}
	}

?>

<fieldset>
<legend><b><?=$guild['name']?> :: Passar Liderança</b></legend>
<p><form method="POST" action="guild_admin_leadership.php">
<b>Usuário:</b> <?php $query = $db->execute("select `id`, `username` from `players` where `guild`=?", array($guild['id']));
echo "<select name=\"username\"><option value=''>Selecione</option>";
while($result = $query->fetchrow()){
echo "<option value=\"$result[username]\">$result[username]</option>";
}
echo "</select>"; ?> <input type="submit" name="submit" value="Passar liderança"><p />
</form>
<b>ATENÇÃO:</b> Nomeando um novo lider, você perderá todas as funções da administração!
<p /><?=$msg?><p />
<p /><font color=red><?=$errmsg?></font><p />
</fieldset>
<a href="guild_admin.php">Voltar</a>.

<?php
}
include("templates/private_footer.php");
?>