<?php
include("lib.php");
define("PAGENAME", "Administração do Clã");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkguild.php");

$error = 0;

//Populates $guild variable
$guildquery = $db->execute("select `id`, `name`, `leader`, `vice`, `members` from `guilds` where `id`=?", array($player->guild));

if ($guildquery->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $guildquery->fetchrow();
}

include("templates/private_header.php");

//Guild Leader Admin check
if (($player->username != $guild['leader']) and ($player->username != $guild['vice'])) {
    echo "Você não pode acessar esta página.";
    echo "<br/><a href=\"home.php\">Voltar</a>.";
} else {

if ($_GET['cancel']){
$gwar = $db->execute("select * from `pwar` where `id`=? and `status`='p' and `guild_id`=?", array($_GET['cancel'], $player->guild));
	if ($gwar->recordcount() != 1){
		echo "Pedido de guerra não encontrado.";
		echo "<br/><a href=\"guild_admin_enemy.php\">Voltar</a>.";
		include("templates/private_footer.php");
		exit;
	}else{
		$war = $gwar->fetchrow();
		$db->execute("update `guilds` set `blocked`=`blocked`-?, `gold`=`gold`+? where `id`=?", array($war['bet'], $war['bet'], $player->guild));
		$db->execute("delete from `pwar` where `id`=?", array($_GET['cancel']));

			$warguild = $db->execute("select * from `guilds` where `id`=?", array($war['enemy_id']));
			$warguild = $warguild->fetchrow();
		
			$lider = $db->GetOne("select `id` from `players` where `username`=?", array($warguild['leader']));
    			$logmsg = "O pedido de guerra contra o clã <b>" . $guild['name'] . "</b> foi retirado.";
			addlog($lider, $logmsg, $db);

			if ($warguild['vice'] != NULL){
				$vice = $db->GetOne("select `id` from `players` where `username`=?", array($warguild['vice']));
				addlog($vice, $logmsg, $db);
			}

			$array1 = explode(", ",$war['players_guild']);
			foreach ($array1 as $gplayer) {
    				$logmsg = "O pedido de guerra contra o clã <b>" . $warguild['name'] . "</b> foi retirado.";
				addlog($gplayer, $logmsg, $db);
			}

		echo "Pedido de guerra cancelado.";
		echo "<br/><a href=\"guild_admin_enemy.php\">Voltar</a>.";
		include("templates/private_footer.php");
		exit;
	}

} elseif (($_GET['unenemy']) and ($_GET['enemy_na'])){

	$acheckcla = $db->execute("select `id` from `guilds` where `id`=?", array($_GET['enemy_na']));
	$ccheckjaaly = $db->execute("select `id` from `guild_enemy` where `guild_na`=? and `enemy_na`=?", array($guild['id'], $_GET['enemy_na']));
	
	if ($acheckcla->recordcount() != 1) {
    		$errmsg .= "Este clã não existe!";
   		$errorb = 1;
	}elseif ($ccheckjaaly->recordcount() < 1) {
    		$errmsg .= "Este clã não é um clã inimigo!";
   		$errorb = 1;
	}else{
		if ($errorb == 0){

			$log1 = $db->execute("select `id` from `players` where `guild`=?", array($_GET['enemy_na']));
			while($p1 = $log1->fetchrow())
			{
    			$logmsg1 = "O clã <a href=\"guild_profile.php?id=". $guild['id'] ."\">". $guild['name'] ."</a> não é mais um clã inimigo.";
			addlog($p1['id'], $logmsg1, $db);
			}

			$msglog2guild = $db->GetOne("select `name` from `guilds` where `id`=?", array($_GET['enemy_na']));
			$log2 = $db->execute("select `id` from `players` where `guild`=?", array($guild['id']));
			while($p2 = $log2->fetchrow())
			{
    			$logmsg2 = "O clã <a href=\"guild_profile.php?id=". $_GET['enemy_na'] ."\">". $msglog2guild ."</a> não é mais um clã inimigo.";
			addlog($p2['id'], $logmsg2, $db);
			}

			$query = $db->execute("delete from `guild_enemy` where `guild_na`=? and `enemy_na`=?", array($guild['id'], $_GET['enemy_na']));
			$query = $db->execute("delete from `guild_enemy` where `guild_na`=? and `enemy_na`=?", array($_GET['enemy_na'], $guild['id']));

			$msg .= "O clã " . $msglog2guild . " foi removido da lista de inimigos.";
		}
	}

}elseif (isset($_POST['gname']) && ($_POST['submit'])) {
    
	$checkcla = $db->execute("select `id`, `leader`, `vice`, `name` from `guilds` where `id`=?", array($_POST['gname']));
	$checkjaeny0 = $db->execute("select `id` from `guild_enemy` where `guild_na`=? and `enemy_na`=?", array($guild['id'], $_POST['gname']));
	$checkjaeny1 = $db->execute("select `id` from `guild_aliance` where `guild_na`=?", array($guild['id']));

    if ($checkcla->recordcount() == 0) {
    	$errmsg .= "Este clã não existe!";
    	$error = 1;
   	} else if ($checkjaeny0->recordcount() > 0) {
   		$errmsg .= "Este clã já está marcado como inimigo!";
   		$error = 1;
   	} else if ($checkjaeny1->recordcount() > 0) {
   		$errmsg .= "Este clã é um clã aliado!";
   		$error = 1;
	} else {

		if ($error == 0){
   		$enyguild = $checkcla->fetchrow();

		$insert['guild_na'] = $guild['id'];
		$insert['enemy_na'] = $enyguild['id'];
		$insert['time'] = time();
		$query = $db->autoexecute('guild_enemy', $insert, 'INSERT');

		$insert['guild_na'] = $enyguild['id'];
		$insert['enemy_na'] = $guild['id'];
		$insert['time'] = time();
		$query = $db->autoexecute('guild_enemy', $insert, 'INSERT');

    			$msg .= "O clã " . $enyguild['name'] . " foi marcado como inimigo.";


			$log1 = $db->execute("select `id` from `players` where `guild`=?", array($guild['id']));
			while($p1 = $log1->fetchrow())
			{
    			$logmsg1 = "O clã <a href=\"guild_profile.php?id=". $enyguild['id'] ."\">". $enyguild['name'] ."</a> foi marcado como clã inimigo.";
			addlog($p1['id'], $logmsg1, $db);
			}

			$log2 = $db->execute("select `id` from `players` where `guild`=?", array($enyguild['id']));
			while($p2 = $log2->fetchrow())
			{
    			$logmsg2 = "O clã <a href=\"guild_profile.php?id=". $guild['id'] ."\">". $guild['name'] ."</a> foi marcado como clã inimigo.";
			addlog($p2['id'], $logmsg2, $db);
			}


		}else{
   		$errmsg .= "Um erro desconhecido ocorreu.";
   		$error = 1;
		}

	}
}
?>

<fieldset>
<legend><b><?=$guild['name']?> :: Clãs Inimigos</b></legend>
<form method="POST" action="guild_admin_enemy.php">
<b>Adicionar o clã:</b> <?php $query = $db->execute("select `id`, `name` from `guilds` where `id`!=?", array($player->guild));
echo "<select name=\"gname\"><option value=''>Selecione</option>";
while($result = $query->fetchrow()){
echo "<option value=\"$result[id]\">$result[name]</option>";
}
echo "</select>"; ?> <input type="submit" name="submit" value="Adicionar Inimigo">
</form>
</fieldset>
<center><p /><font color=green><?=$msg?></font><p /></center>
<center><p /><font color=red><?=$errmsg?></font><p /></center>
<br/>
<fieldset>
<legend><b>Gerenciar Inimigos</b></legend>
<?php
$query0000 = $db->execute("select `enemy_na` from `guild_enemy` where `guild_na`=? order by `enemy_na` asc", array($guild['id']));

if ($query0000->recordcount() < 1) {
echo "<p /><center>Seu clã não possui inimigos.</center><p />";
}else{

	echo "<table width=\"100%\" border=\"0\">";
	echo "<tr>";
	echo "<th width=\"30%\"><b>Clã</b></td>";
	echo "<th width=\"12%\"><b>Membros</b></td>";
	echo "<th width=\"38%\"><b>Status</b></td>";
	echo "<th width=\"20%\"><b>Opções</b></td>";
	echo "</tr>";


	while($ali = $query0000->fetchrow()){
	$whileechoname = $db->GetOne("select `name` from `guilds` where `id`=?", array($ali[enemy_na]));
	$whileechomembers = $db->GetOne("select `members` from `guilds` where `id`=?", array($ali[enemy_na]));

		echo "<tr>\n";
			echo "<td><a href=\"guild_profile.php?id=" . $ali[enemy_na] . "\"><b>" . $whileechoname . "</b></a></td>";
			echo "<td>" . $whileechomembers . "</td>";

			$gwar = $db->execute("select * from `pwar` where (((`guild_id`=?) and (`enemy_id`=?)) or ((`guild_id`=?) and (`enemy_id`=?))) order by `time` desc limit 5", array($player->guild, $ali[enemy_na], $ali[enemy_na], $player->guild));
			if ($gwar->recordcount() > 0){
				echo "<td><font size=\"1px\">";
				while($war = $gwar->fetchrow()){
					if ($war['status'] == 'g'){
						echo "Derrotado pelo seu clã na guerra.<br/>";
					} elseif ($war['status'] == 'e'){
						echo "Derrotou seu clã na guerra.<br/>";
					} elseif ($war['status'] == 'p'){
						echo "Pedido de guerra pendente. <a href=\"guild_admin_enemy.php?cancel=" . $war['id'] . "\">Cancelar</a>.<br/>";
					}
				}
				echo "</font></td>";
			}else{
				echo "<td>Nenhum conflito.</td>";
			}


			echo "<td><font size=\"1px\"><a href=\"guild_admin_enemy.php?unenemy=true&enemy_na=" . $ali[enemy_na] . "\">Promover Paz</a><br/><a href=\"guild_admin_war.php?id=" . $ali[enemy_na] . "\">Proclamar Guerra</a></font></td>";
		echo "</tr>\n";
	}
	echo "</table>";

}
?>
</fieldset>
<a href="guild_admin.php">Voltar</a>.

<?php
}

include("templates/private_footer.php");
?>