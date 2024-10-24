<?php
include("lib.php");
define("PAGENAME", "Arena");
$player = check_user($secret_key, $db);

$dungeonPoints = $db->execute("select `dungeon_id` from `dungeon_status` where `player_id`=? and `status`=90 and `fail`=0", array($player->id));
$dungeonPoints = $dungeonPoints->recordcount();
    
$dungeonVerificaPremiacoes = $db->execute("select * from `dungeon_status` where `player_id`=? and `status`<90 and `fail`=0", array($player->id));
while($dungeonInfo = $dungeonVerificaPremiacoes->fetchrow())
{    
	$getAllDungeonInfo = $db->execute("select * from `dungeon` where `id`=?", array($dungeonInfo['dungeon_id']));
	$AllDungeonInfo = $getAllDungeonInfo->fetchrow();
	
	$divideDungeonMosters = explode (", ", $AllDungeonInfo['monsters']);

	if (($dungeonInfo['start'] + $AllDungeonInfo['time']) < time())
	{
		$db->execute("update `dungeon_status` set `fail`='2', `status`=? where `dungeon_id`=? and `player_id`=?", array((time() + 86400),$dungeonInfo['dungeon_id'], $player->id));
		include("templates/private_header.php");
		echo "O tempo para a dungeon " . $AllDungeonInfo['name'] . " se esgotou!<br/>Você deverá esperar 1 dia para participar dela novamente.";
        echo "<br/><a href=\"dungeon.php\">Continuar</a>.";
		include("templates/private_footer.php");
		exit;
	}
	else if (count($divideDungeonMosters) == $dungeonInfo['status']){

        $db->execute("update `dungeon_status` set `status`=90, `finish`=? where `dungeon_id`=? and `player_id`=?", array(time(), $dungeonInfo['dungeon_id'], $player->id));
        
		include("templates/private_header.php");
		echo "<b>Você completou a dungeon " . $AllDungeonInfo['name'] . ", parabéns guerreiro!</b><br/>";
        echo "Você adiquiriu 1 dungeon point por completar esta arena.<br/>";
		echo "<br/><i>Os seguintes prêmios foram adicionados ao seu inventário:</i><br/>";
		
		echo "<ul>";

			$itid = explode (", ", $AllDungeonInfo['prize']);
            foreach ($itid as $key_value)
            {
                if ($key_value > 999) {
                    $db->execute("update `players` set `gold`=`gold`+? where `id`=?", array($key_value, $player->id));
                    echo "<li>" . $key_value . " moedas de ouro.</li>";
                } else {
                    echo "<li>";
                        $itinfo = $db->execute("select * from `blueprint_items` where `id`=?", array($key_value));
                        $item = $itinfo->fetchrow();
                        
                        $insert['player_id'] = $player->id;
                        $insert['item_id'] = $item['id'];
                        $query = $db->autoexecute('items', $insert, 'INSERT');
                        
                        if ($item['type'] == 'amulet'){
                            echo "" . $item['name'] . " <font size=\"1px\">(Vitalidade: " . $item['effectiveness'] . ")</font><br/ >";
                        }elseif ($item['type'] == 'boots') {
                            echo $itnames .= "" . $item['name'] . " <font size=\"1px\">(Agilidade: " . $item['effectiveness'] . ")</font><br/ >";
                        }elseif ($item['type'] == 'weapon') {
                            echo "" . $item['name'] . " <font size=\"1px\">(Ataque: " . $item['effectiveness'] . ")</font><br/ >";
                        }elseif ($item['type'] == 'shield') {
                            echo "" . $item['name'] . " <font size=\"1px\">(Defesa: " . $item['effectiveness'] . ")<br/ ><i>Arqueiros não podem usar escudos</i>.</font><br/ >";
                        }else{
                            echo "" . $item['name'] . " <font size=\"1px\">(Defesa: " . $item['effectiveness'] . ")</font><br/ >";
                        }
                    echo "</li>";
                }
            }

        echo "</ul>";
        echo "<a href=\"dungeon.php\">Continuar</a>.";
		include("templates/private_footer.php");
		exit;
	}
}

if ($_GET['id']) {
    $checkverid = $db->execute("select * from `dungeon` where `id`=?", array($_GET['id'])); 
    if ($checkverid->recordcount() > 0) {
        
        $checkverid2 = $db->execute("select * from `dungeon_status` where `player_id`=? and `status`<90 and `fail`=0", array($player->id)); 
        if ($checkverid2->recordcount() == 0) {
            $datta = $checkverid->fetchrow();
            
            $checkverid3 = $db->execute("select * from `dungeon_status` where `dungeon_id`=? and `player_id`=? and `status`<=90 and `fail`=0", array($_GET['id'], $player->id)); 
            if ($checkverid3->recordcount() == 0) {
                
                $checkverid4 = $db->execute("select * from `dungeon_status` where `dungeon_id`=? and `player_id`=? and `status`>?", array($_GET['id'], $player->id, time())); 
                if ($checkverid4->recordcount() == 0) {
                
                    if ($dungeonPoints >= $datta['level']) {
                        if (!$_GET['comfirm']) {
                            include("templates/private_header.php");
                            echo showAlert("<table width=\"100%\"><tr><td>Você tem certeza que deseja participar da arena <b>" . $datta['name'] . "</b>?<br/>O guerreiro que for derrotado em uma da batalhas da arena será eliminado<br/>e não poderá repeti-la nas próximas 24 horas.<br/>Você terá de completa-la em no máximo " . ($datta['time'] / 60) . " minutos para receber seu prêmio.</td><td><center><p><a href=\"dungeon.php?id=" . $_GET['id'] . "&comfirm=true\">Participar</a><br/><a href=\"dungeon.php\"><font size=\"1px\">Voltar</font></a></p></center></td></tr></table>", "", "left");
                            include("templates/private_footer.php");
                            exit;
                        } else {
                            $insert['player_id'] = $player->id;
                            $insert['dungeon_id'] = $_GET['id'];
                            $insert['start'] = time();
                            $query = $db->autoexecute('dungeon_status', $insert, 'INSERT');
                        
                            include("templates/private_header.php");
                            echo showAlert("<table width=\"100%\"><tr><td>Você acaba de se inscrever na arena <b>" . $datta['name'] . "</b>.<br/>Boa sorte guerreiro.</td><td><center><p><a href=\"dungeon.php\">Continuar</a></p></center></td></tr></table>", "", "left");
                            include("templates/private_footer.php");
                            exit;
                        }
                    } else {
                        include("templates/private_header.php");
                        echo "Você não possui dungeon points suficientes para participar desta arena.";
                        echo "<br/><a href=\"dungeon.php\">Voltar.</a>";
                        include("templates/private_footer.php");
                        exit;   
                    }
                    
                } else {
                    include("templates/private_header.php");
                    echo "Você deve aguardar para participar novamente desta arena.";
                    echo "<br/><a href=\"dungeon.php\">Voltar.</a>";
                    include("templates/private_footer.php");
                    exit;  
                }
            } else {
                include("templates/private_header.php");
                echo "Você já participou desta arena.";
                echo "<br/><a href=\"dungeon.php\">Voltar.</a>";
                include("templates/private_footer.php");
                exit;
            }
        } else {
            include("templates/private_header.php");
            echo "Você já está participando de uma arena!";
            echo "<br/><a href=\"dungeon.php\">Voltar.</a>";
            include("templates/private_footer.php");
            exit;
        }
        
    } else {
        include("templates/private_header.php");
        echo "Esta arena não está dispon&ecirc;vel no momento.";
        echo "<br/><a href=\"dungeon.php\">Voltar.</a>";
        include("templates/private_footer.php");
        exit;
    }
}
	
	include("templates/private_header.php");
    
        $getitems = $db->execute("select * from `dungeon` order by `level` asc");
		if ($getitems->recordcount() == 0)
		{
			echo "<center><i>Nenhuma arena está dispon&ecirc;vel no momento.</i></center>";
		}
		else
		{
            echo showAlert("Você já conquistou " . $dungeonPoints . " dungeon points.");
			while ($vipti = $getitems->fetchrow())
			{
                echo "<table width=\"100%\">";
                echo "<tr>";
                echo "<td width=\"30%\" class=\"brown\"><b><center>" . $vipti['name'] . "</center></b></td>";
                echo "<td width=\"35%\" class=\"brown\"><b>Monstros</b></td>";
                echo "<td width=\"35%\" class=\"brown\"><b>Prêmio</b></td>";
                echo "</tr>";
                echo "<tr><td style=\"background-color: #FFFDE0;\">";
                echo "<table width=\"100%\"><tr>";
                echo "<td width=\"20%\"><center><img src=\"static/images/itens/medalha.gif\" style=\"padding-top: 5px;\" border=\"0px\"/></center></td>";
                    $verstatus = $db->getone("select `status` from `dungeon_status` where `dungeon_id`=? and `player_id`=?", array($vipti['id'], $player->id));
                    $verstart = $db->getone("select `start` from `dungeon_status` where `dungeon_id`=? and `player_id`=?", array($vipti['id'], $player->id));
                    if ($verstatus > 90) {
                        if (time() > $verstatus) {
                            $db->execute("delete from `dungeon_status` where `dungeon_id`=? and `player_id`=?", array($vipti['id'], $player->id));
                            echo "<td width=\"80%\"><center><a href=\"dungeon.php?id=" . $vipti['id'] . "\">Participar</a><br/><font size=\"1px\">Min. " . $vipti['level'] . " dungeon points.<br/>Tempo max. " . ($vipti['time'] / 60) . " minutos.</font></center></td>";
                        } else {
                            $horas = ceil(($verstatus - time()) / 3600);
                            echo "<td width=\"80%\"><p><center><s>Participar</s><br/>";
                            echo "<font size=\"1px\">Aguarde " . $horas . " hora(s).</font></center></p></td>";
                        }
                    } else if (($verstatus != null) and ($verstatus < 90)) {
                    	$timeleftforwork = (($verstart + $vipti['time']) - time());
                    	$time_remaining = ceil($timeleftforwork / 60);
                        echo "<td width=\"80%\"><center><b>Participando</b><br/><div id=\"counter\" align=\"center\"></div></center></td>";
                        
                        echo "<script type=\"text/javascript\">";
                        echo "javascript_countdown.init(" . $timeleftforwork . ", 'counter');";
                        echo "</script>";
                        
                    } else if ($verstatus == 90) {
                        echo "<td width=\"80%\"><p><center><b>Completa</b></center></p></td>";
                    } else {
                        echo "<td width=\"80%\"><center><a href=\"dungeon.php?id=" . $vipti['id'] . "\">Participar</a><br/><font size=\"1px\">Min. " . $vipti['level'] . " dungeon points.<br/>Tempo max. " . ($vipti['time'] / 60) . " minutos.</font></center></td>";
                    }
                echo "</tr></table>";
                echo "</td><td style=\"background-color: #FFFDE0;\">";
                $itid = explode (", ", $vipti['monsters']);
                $itcount = 1;
                foreach ($itid as $key_value)
                {
                    $itinfo = $db->execute("select * from `monsters` where `id`=?", array($key_value));
                    $item = $itinfo->fetchrow();
                    
                    $dungeoncomfirma = $db->execute("select * from `dungeon_status` where `status`<=90 and `dungeon_id`=? and `player_id`=?", array($vipti['id'], $player->id)); 
                    if ($dungeoncomfirma->recordcount() > 0) {
                        $info = $dungeoncomfirma->fetchrow();
                        
                        if (($info['status'] >= $itcount) or ($checkverstatus['status'] == 90)) {
                            echo "" . $itcount . "º <s>" . $item['username'] . " <font size=\"1px\">(N&ecirc;vel: " . $item['level'] . ")</font></s><br/ >";
                        } elseif (($info['status'] + 1) < $itcount) {
                            echo "<font color=\"gray\">" . $itcount . "º " . $item['username'] . " <font size=\"1px\">(N&ecirc;vel: " . $item['level'] . ")</font></font><br/ >";
                        } else {
                            echo "" . $itcount . "º <a href=\"monster.php?act=attack&id=" . (((int)$item['id']) * $player->id) . "\">" . $item['username'] . "</a> <font size=\"1px\">(N&ecirc;vel: " . $item['level'] . ")</font><br/ >";
                        }
                        
                    } else {
                        echo "" . $itcount . "º " . $item['username'] . " <font size=\"1px\">(N&ecirc;vel: " . $item['level'] . ")</font><br/ >";
                    }
                    
                    $itcount = $itcount + 1;
                }
                
                echo "</td><td style=\"background-color: #FFFDE0;\">";

					$itid = explode (", ", $vipti['prize']);
                    foreach ($itid as $key_value)
                    {
                        if ($key_value > 999) {
                            echo "" . $key_value . " moedas de ouro.<br/>";
                        } else {
                            $itinfo = $db->execute("select * from `blueprint_items` where `id`=?", array($key_value));
                            $item = $itinfo->fetchrow();
                            
                            if ($item['type'] == 'amulet'){
                                echo "" . $item['name'] . " <font size=\"1px\">(Vitalidade: " . $item['effectiveness'] . ")</font><br/ >";
                            }elseif ($item['type'] == 'boots') {
                                echo $itnames .= "" . $item['name'] . " <font size=\"1px\">(Agilidade: " . $item['effectiveness'] . ")</font><br/ >";
                            }elseif ($item['type'] == 'weapon') {
                                echo "" . $item['name'] . " <font size=\"1px\">(Ataque: " . $item['effectiveness'] . ")</font><br/ >";
                            }elseif ($item['type'] == 'shield') {
                                echo "" . $item['name'] . " <font size=\"1px\">(Defesa: " . $item['effectiveness'] . ")<br/ ><i>Arqueiros não podem usar escudos</i>.</font><br/ >";
                            }else{
                                echo "" . $item['name'] . " <font size=\"1px\">(Defesa: " . $item['effectiveness'] . ")</font><br/ >";
                            }
                        }
                    }
                
                echo "</td></tr>";
                echo "</table><br/>";
            }
        }

echo "<table width=\"100%\">";
echo "<tr><td align=\"center\" bgcolor=\"#E1CBA4\"><b>Dungeons Recentes</b></td></tr>";
$query1 = $db->execute("select * from `dungeon_status` where `player_id`=? and (`status`='90' or `fail`!='0') order by `start` desc limit 10", array($player->id));
if ($query1->recordcount() > 0)
{
	while ($log1 = $query1->fetchrow())
	{
		$valortempo = time() - $log1['start'];
		if ($valortempo < 60){
		$valortempo2 = $valortempo;
		$auxiliar2 = "segundo(s) atrás.";
		}else if($valortempo < 3600){
		$valortempo2 = floor($valortempo / 60);
		$auxiliar2 = "minuto(s) atrás.";
		}else if($valortempo < 86400){
		$valortempo2 = floor($valortempo / 3600);
		$auxiliar2 = "hora(s) atrás.";
		}else if($valortempo > 86400){
		$valortempo2 = floor($valortempo / 86400);
		$auxiliar2 = "dia(s) atrás.";
		}
        
        $DungeonLogName = $db->getone("select `name` from `dungeon` where `id`=?", array($log1['dungeon_id']));

		echo "<tr>";
		if ($log1['fail'] == '2'){
		echo "<td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\"><div title=\"header=[" . $valortempo2 . " " . $auxiliar2 . "] body=[]\"><font size=\"1\">Você começou a dungeon: " . $DungeonLogName . ", porém não a completou em tempo suficiente.</font></div></td>";
		} elseif ($log1['fail'] == '1'){
		echo "<td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\"><div title=\"header=[" . $valortempo2 . " " . $auxiliar2 . "] body=[]\"><font size=\"1\">Você morreu enquanto participava da " . $DungeonLogName . " e falhou na conquista pelo prêmio.</font></div></td>";
		} else {
		echo "<td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\"><div title=\"header=[" . $valortempo2 . " " . $auxiliar2 . "] body=[]\"><font size=\"1\">Você finalizou a dungeon \"" . $DungeonLogName . "\" em " . ceil(($log1['finish'] - $log1['start']) / 60) . " minuto(s).</font></div></td>";
		}
		echo "</tr>";
	}
}
else
{
	echo "<tr>";
	echo "<td class=\"off\"><font size=\"1\">Você ainda não participou de nenhuma dungeon.</font></td>";
	echo "</tr>";
}
echo "</table>";

/*$checkdDungeon = $db->getone("select `dungeon_id` from `dungeon_status` where `status`<90 and `fail`=0 and `player_id`=?", array($player->id));
if (($checkdDungeon != null) and ($checkdDungeon != 0)){
	$getDungeonMonsters = $db->execute("select `monsters` from `dungeon` where `id`=?", array($checkdDungeon));
	if ($getDungeonMonsters->recordcount() > 0)
	{
			$splitDungeonMosters = explode (", ", $getDungeonMonsters);
			$dungeonMonsterId = $splitDungeonMosters[$db->getone("select `status` from `dungeon_status` where `status`<90 and `fail`=0 and `player_id`=?", array($player->id))];
			echo (int)$dungeonMonsterId;
	}
}*/

include("templates/private_footer.php");
exit;
?>
