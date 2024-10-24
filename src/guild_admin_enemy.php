<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Administração do Clã");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkguild.php");

$error = 0;

//Populates $guild variable
$guildquery = $db->execute("select `id`, `name`, `leader`, `vice`, `members` from `guilds` where `id`=?", [$player->guild]);

if ($guildquery->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $guildquery->fetchrow();
}

include(__DIR__ . "/templates/private_header.php");

//Guild Leader Admin check
if ($player->username != $guild['leader'] && $player->username != $guild['vice']) {
    echo "Você não pode acessar esta página.";
    echo '<br/><a href="home.php">Voltar</a>.';
} else {
    if ($_GET['cancel']) {
        $gwar = $db->execute("select * from `pwar` where `id`=? and `status`='p' and `guild_id`=?", [$_GET['cancel'], $player->guild]);
        if ($gwar->recordcount() != 1){
       		echo "Pedido de guerra não encontrado.";
       		echo '<br/><a href="guild_admin_enemy.php">Voltar</a>.';
       		include(__DIR__ . "/templates/private_footer.php");
       		exit;
       	}
        
        $war = $gwar->fetchrow();
        $db->execute("update `guilds` set `blocked`=`blocked`-?, `gold`=`gold`+? where `id`=?", [$war['bet'], $war['bet'], $player->guild]);
        $db->execute("delete from `pwar` where `id`=?", [$_GET['cancel']]);
        $warguild = $db->execute("select * from `guilds` where `id`=?", [$war['enemy_id']]);
        $warguild = $warguild->fetchrow();
        $lider = $db->GetOne("select `id` from `players` where `username`=?", [$warguild['leader']]);
        $logmsg = "O pedido de guerra contra o clã <b>" . $guild['name'] . "</b> foi retirado.";
        addlog($lider, $logmsg, $db);
        if ($warguild['vice'] != NULL){
     				$vice = $db->GetOne("select `id` from `players` where `username`=?", [$warguild['vice']]);
     				addlog($vice, $logmsg, $db);
     			}
        
        $array1 = explode(", ",$war['players_guild']);
        foreach ($array1 as $gplayer) {
         				$logmsg = "O pedido de guerra contra o clã <b>" . $warguild['name'] . "</b> foi retirado.";
     				addlog($gplayer, $logmsg, $db);
     			}
        
        echo "Pedido de guerra cancelado.";
        echo '<br/><a href="guild_admin_enemy.php">Voltar</a>.';
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }
    
    if ($_GET['unenemy'] && $_GET['enemy_na']) {
        $acheckcla = $db->execute("select `id` from `guilds` where `id`=?", [$_GET['enemy_na']]);
        $ccheckjaaly = $db->execute("select `id` from `guild_enemy` where `guild_na`=? and `enemy_na`=?", [$guild['id'], $_GET['enemy_na']]);
        if ($acheckcla->recordcount() != 1) {
           		$errmsg .= "Este clã não existe!";
          		$errorb = 1;
       	}elseif ($ccheckjaaly->recordcount() < 1) {
           		$errmsg .= "Este clã não é um clã inimigo!";
          		$errorb = 1;
       	} elseif ($errorb == 0) {
            $log1 = $db->execute("select `id` from `players` where `guild`=?", [$_GET['enemy_na']]);
            while($p1 = $log1->fetchrow())
         			{
             			$logmsg1 = "O clã <a href=\"guild_profile.php?id=". $guild['id'] .'">'. $guild['name'] ."</a> não é mais um clã inimigo.";
         			addlog($p1['id'], $logmsg1, $db);
         			}
            
            $msglog2guild = $db->GetOne("select `name` from `guilds` where `id`=?", [$_GET['enemy_na']]);
            $log2 = $db->execute("select `id` from `players` where `guild`=?", [$guild['id']]);
            while($p2 = $log2->fetchrow())
         			{
             			$logmsg2 = "O clã <a href=\"guild_profile.php?id=". $_GET['enemy_na'] .'">'. $msglog2guild ."</a> não é mais um clã inimigo.";
         			addlog($p2['id'], $logmsg2, $db);
         			}
            
            $query = $db->execute("delete from `guild_enemy` where `guild_na`=? and `enemy_na`=?", [$guild['id'], $_GET['enemy_na']]);
            $query = $db->execute("delete from `guild_enemy` where `guild_na`=? and `enemy_na`=?", [$_GET['enemy_na'], $guild['id']]);
            $msg .= "O clã " . $msglog2guild . " foi removido da lista de inimigos.";
        }
    } elseif (isset($_POST['gname']) && ($_POST['submit'])) {
        
    	$checkcla = $db->execute("select `id`, `leader`, `vice`, `name` from `guilds` where `id`=?", [$_POST['gname']]);
    	$checkjaeny0 = $db->execute("select `id` from `guild_enemy` where `guild_na`=? and `enemy_na`=?", [$guild['id'], $_POST['gname']]);
    	$checkjaeny1 = $db->execute("select `id` from `guild_aliance` where `guild_na`=?", [$guild['id']]);
    
        if ($checkcla->recordcount() == 0) {
            $errmsg .= "Este clã não existe!";
            $error = 1;
        } elseif ($checkjaeny0->recordcount() > 0) {
            $errmsg .= "Este clã já está marcado como inimigo!";
            $error = 1;
        } elseif ($checkjaeny1->recordcount() > 0) {
            $errmsg .= "Este clã é um clã aliado!";
            $error = 1;
        } elseif ($error === 0) {
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
            $log1 = $db->execute("select `id` from `players` where `guild`=?", [$guild['id']]);
            while($p1 = $log1->fetchrow())
         			{
             			$logmsg1 = "O clã <a href=\"guild_profile.php?id=". $enyguild['id'] .'">'. $enyguild['name'] ."</a> foi marcado como clã inimigo.";
         			addlog($p1['id'], $logmsg1, $db);
         			}
            
            $log2 = $db->execute("select `id` from `players` where `guild`=?", [$enyguild['id']]);
            while($p2 = $log2->fetchrow())
         			{
             			$logmsg2 = "O clã <a href=\"guild_profile.php?id=". $guild['id'] .'">'. $guild['name'] ."</a> foi marcado como clã inimigo.";
         			addlog($p2['id'], $logmsg2, $db);
         			}
        } else{
         		$errmsg .= "Um erro desconhecido ocorreu.";
         		$error = 1;
      		}
    }
    ?>

<fieldset>
<legend><b><?php echo $guild['name']; ?> :: Clãs Inimigos</b></legend>
<form method="POST" action="guild_admin_enemy.php">
<b>Adicionar o clã:</b> <?php 
    $query = $db->execute("select `id`, `name` from `guilds` where `id`!=?", [$player->guild]);
    echo "<select name=\"gname\"><option value=''>Selecione</option>";
    while($result = $query->fetchrow()){
    echo sprintf('<option value="%s">%s</option>', $result["id"], $result["name"]);
    }

    echo "</select>";
    ?> <input type="submit" name="submit" value="Adicionar Inimigo">
</form>
</fieldset>
<center><p /><font color=green><?php 
    echo $msg;
?></font><p /></center>
<center><p /><font color=red><?php 
    echo $errmsg;
?></font><p /></center>
<br/>
<fieldset>
<legend><b>Gerenciar Inimigos</b></legend>
<?php 
    $query0000 = $db->execute("select `enemy_na` from `guild_enemy` where `guild_na`=? order by `enemy_na` asc", [$guild['id']]);
    if ($query0000->recordcount() < 1) {
    echo "<p /><center>Seu clã não possui inimigos.</center><p />";
    }else{
    
    	echo '<table width="100%" border="0">';
    	echo "<tr>";
    	echo "<th width=\"30%\"><b>Clã</b></td>";
    	echo '<th width="12%"><b>Membros</b></td>';
    	echo '<th width="38%"><b>Status</b></td>';
    	echo "<th width=\"20%\"><b>Opções</b></td>";
    	echo "</tr>";
    
    
    	while($ali = $query0000->fetchrow()){
    	$whileechoname = $db->GetOne("select `name` from `guilds` where `id`=?", [$ali["enemy_na"]]);
    	$whileechomembers = $db->GetOne("select `members` from `guilds` where `id`=?", [$ali["enemy_na"]]);
    
    		echo "<tr>\n";
    			echo '<td><a href="guild_profile.php?id=' . $ali["enemy_na"] . '"><b>' . $whileechoname . "</b></a></td>";
    			echo "<td>" . $whileechomembers . "</td>";
    
    			$gwar = $db->execute("select * from `pwar` where (((`guild_id`=?) and (`enemy_id`=?)) or ((`guild_id`=?) and (`enemy_id`=?))) order by `time` desc limit 5", [$player->guild, $ali["enemy_na"], $ali["enemy_na"], $player->guild]);
    			if ($gwar->recordcount() > 0){
    				echo '<td><font size="1px">';
    				while($war = $gwar->fetchrow()){
    					if ($war['status'] == 'g'){
    						echo "Derrotado pelo seu clã na guerra.<br/>";
    					} elseif ($war['status'] == 'e'){
    						echo "Derrotou seu clã na guerra.<br/>";
    					} elseif ($war['status'] == 'p'){
    						echo 'Pedido de guerra pendente. <a href="guild_admin_enemy.php?cancel=' . $war['id'] . '">Cancelar</a>.<br/>';
    					}
    				}
        
    				echo "</font></td>";
    			}else{
    				echo "<td>Nenhum conflito.</td>";
    			}
    
    
    			echo '<td><font size="1px"><a href="guild_admin_enemy.php?unenemy=true&enemy_na=' . $ali["enemy_na"] . '">Promover Paz</a><br/><a href="guild_admin_war.php?id=' . $ali["enemy_na"] . '">Proclamar Guerra</a></font></td>';
    		echo "</tr>\n";
    	}
     
    	echo "</table>";
    
    }
    ?>
</fieldset>
<a href="guild_admin.php">Voltar</a>.

<?php 
}

include(__DIR__ . "/templates/private_footer.php");
?>
