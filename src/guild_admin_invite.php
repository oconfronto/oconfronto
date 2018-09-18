<?php
include("lib.php");
define("PAGENAME", "Administração do Clã");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkguild.php");

$error = 0;
$username = ($_GET['username']);

//Populates $guild variable
$guildquery = $db->execute("select * from `guilds` where `id`=?", array($player->guild));

if ($guildquery->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $guildquery->fetchrow();
}

include("templates/private_header.php");

//Guild Leader Admin check
if (($player->username != $guild['leader']) and ($player->username != $guild['vice'])) {
    echo "Você não pode acessar esta página. <a href=\"home.php\">Voltar</a>.";
} elseif ($guild['members'] >= ($guild['maxmembers'])) {
    echo "Seu clã já está grande demais! (max. " . $guild['maxmembers'] . " membros).<br/><a href=\"guild_admin.php\">Voltar</a>.";
} else {
//If username is set
if (isset($_GET['username']) && ($_GET['submit'])) {
    //Checks if player exists
	$query = $db->execute("select `id`, `guild`, `serv`, `reino` from `players` where `username`='$username'");
	$member = $query->fetchrow();
	
    if ($query->recordcount() == 0) {
    	$errmsg .= "<center><b>Este usuário não existe!</b></center>";
    	$error = 1;
   	} else if ($member['serv'] != $guild['serv']) {
   		$errmsg .= "<center><b>Este usuário pertence a outro servidor.</b></center>";
   		$error = 1;
   	} else if ($member['reino'] != $guild['reino']) {
   		$errmsg .= "<center><b>Este usuário pertence a outro reino.</b></center>";
   		$error = 1;
   	} else if ($member['guild'] != NULL) {
   		$errmsg .= "<center><b>Você não pode convidar um usuário que está em outro clã!</b></center>";
   		$error = 1;
    } else {	//Insert user invite into guild_invites table
    			$insert['player_id'] = $member['id'];
    			$insert['guild_id'] = $guild['id'];
    			$query = $db->autoexecute('guild_invites', $insert, 'INSERT');
    			
    			if (!$query) {
    				$errmsg .= "<center><b>Não foi possivel convidar o usuário! Provavelmete ele já está convidado.</b></center>";
    			}
    			else {
    				$logmsg = "Estão te convidando para participar do clã: <b><a href=\"guild_profile.php?id=" . $guild['id'] . "\">" . $guild['name'] . "</a></b>. <b><a href=\"guild_join.php?id=" . $guild['id'] . "\">Participar</a>.<br/>O custo para participar deste clã é de " . $guild['price'] . " de ouro.</a></b>";
					addlog($member['id'], $logmsg, $db);
    				$msg .= "<center><b>Você convidou $username para o clã.</b></center>";
    			}
    	   }
	}

?>

<fieldset>
<legend><b><?=$guild['name']?> :: Convidar usuários</b></legend>
<form method="GET" action="guild_admin_invite.php">
<b>Usuário:</b> <input type="text" name="username" size="20"/> <input  type="submit" name="submit" value="Convidar">
</form></fieldset>
<a href="guild_admin.php">Voltar</a>.

<p /><?=$msg?><p />
<p /><font color=red><?=$errmsg?></font><p />
</fieldset>
<?php
}
include("templates/private_footer.php");
?>
