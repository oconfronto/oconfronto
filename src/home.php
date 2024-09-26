<?php
include("lib.php");
define("PAGENAME", "Principal");
$player = check_user($secret_key, $db);

$tutorial = $db->execute("select * from `pending` where `pending_id`=2 and `pending_status`=90 and `player_id`=?", array($player->id));
if ($tutorial->recordcount() == 0) {
    $checatutoriallido = $db->execute("select * from `pending` where `pending_id`=2 and `player_id`=?", array($player->id));
    if ($checatutoriallido->recordcount() == 0) {
        $insert['player_id'] = $player->id;
        $insert['pending_id'] = 2;
        $insert['pending_status'] = 1;
        $insert['pending_time'] = time();
        $query = $db->autoexecute('pending', $insert, 'INSERT');
        header("Location: start.php");
        exit;
    }
}


include("checkbattle.php");
include("checktasks.php");

include("templates/private_header.php");
include("checkmedals.php");

$tutorial = $db->execute("select * from `pending` where `pending_id`=2 and `pending_status`=5 and `player_id`=?", array($player->id));
if ($tutorial->recordcount() > 0) {
    $tutorial = $db->execute("select * from `magias` where `magia_id`=? and `player_id`=?", array(4, $player->id));
    if ($tutorial->recordcount() == 0) {
        echo showAlert("<table width=\"100%\"><tr><td width=\"90%\">A cada nível que voc&ecirc; passa, voc&ecirc; ganha 1 <u>ponto místico</u>.<br/><font size=\"1px\">Com os pontos místicos voc&ecirc; pode treinar <u>novos feitiços</u>.</font><br/><br/>Agora, treine o feitiço <b>Cura</b> para continuar.</td><th><font size=\"1px\"><a href=\"start.php?act=6\">Próximo</a></font></th></tr></table>", "white", "left");
    } else {
        echo showAlert("ótimo, <a href=\"start.php?act=6\">clique aqui</a> para continuar seu tutorial.", "green");
    }
}

include("checkquest.php");

//VERIFICANDO ULTIMO ITEM RECEBIDO E NOTIFICANDO //
if ($query2 = mysql_query("select * from `items` where `player_id`= $player->id and `item_event` = 1")) {
    while ($row = mysql_fetch_array($query2)) {
        $id = $row['id'];
        $item_id = $row['item_id'];
        $item_bonus = $row['item_bonus'];
        if ($query3 = mysql_query("select * from `blueprint_items` where `id`= $item_id")) {
            while ($row2 = mysql_fetch_array($query3)) {
                $item_name = $row2['name'];
            }
            echo showAlert("Você acaba de ganhar o Item <u>" . $item_name . " +" . $item_bonus . "</u> do Evento Convide Amigos, Parabéns !", "green");
            $db->execute("update `items` set `item_event`=? where `id`=? ", array('0', $id));
        }
    }
}
// FIM DO MEU CODE LINDO //	


echo "<table width=\"100%\">";
echo "<tr><td width=\"60%\">";
echo "<table width=\"100%\">";
echo "<tr><td class=\"brown\" width=\"100%\"><center><b>" . $player->username . "</b></center></td></tr>";

echo "<tr><td class=\"salmon\" height=\"80px\">";
echo "<table style='padding:14px;'  width=\"100%\">";

echo "<tr><td width=\"20%\"><b>Vocação:</b></td><td width=\"55%\">";

if ($player->voc == 'archer' and $player->promoted == 'f') {
    echo "Caçador";
} else if ($player->voc == 'knight' and $player->promoted == 'f') {
    echo "Espadachim";
} else if ($player->voc == 'mage' and $player->promoted == 'f') {
    echo "Bruxo";
} else if (($player->voc == 'archer') and ($player->promoted == 't' or $player->promoted == 's' or $player->promoted == 'r')) {
    echo "Arqueiro";
} else if (($player->voc == 'knight') and ($player->promoted == 't' or $player->promoted == 's' or $player->promoted == 'r')) {
    echo "Guerreiro";
} else if (($player->voc == 'mage') and ($player->promoted == 't' or $player->promoted == 's' or $player->promoted == 'r')) {
    echo "Mago";
} else if ($player->voc == 'archer' and $player->promoted == 'p') {
    echo "Arqueiro Royal";
} else if ($player->voc == 'knight' and $player->promoted == 'p') {
    echo "Cavaleiro";
} else if ($player->voc == 'mage' and $player->promoted == 'p') {
    echo "Arquimago";
}

echo "</td><th rowspan=\"4\" width=\"25%\">";
echo "<center><font size=\"1px\">Ranking</font><br/>";
$sql = "select id from players where gm_rank<10 and serv=" . $player->serv . " order by level desc, exp desc";
$dados = mysql_query($sql);
$i = 1;
while ($linha = mysql_fetch_array($dados)) {
    if ($linha['id'] == $player->id)
        echo "$i";
    $i++;
}
echo "º";
echo "</center>";
echo "</th></tr>";

echo "<tr><td><b>Reino:</b></td><td>";
if ($player->reino == 1) {
    echo "Cathal";
} else if ($player->reino == 2) {
    echo "Eroda";
} else if ($player->reino == 3) {
    echo "Turkic";
} else {
    echo "Nenhum";
}
echo "</td></tr>";

$nomecla = $db->GetOne("select `name` from `guilds` where `id`=?", array($player->guild));
echo "<tr><td><b>Clã:</b></td><td>";
if ($nomecla != NULL) {
    echo "<a href=\"guild_home.php\">" . $nomecla . "</a>";
} else {
    echo "Nenhum";
}
echo "</td></tr>";

$mes = date("M", $player->registered);
$mes_ano["Jan"] = "Janeiro";
$mes_ano["Feb"] = "Fevereiro";
$mes_ano["Mar"] = "Março";
$mes_ano["Apr"] = "Abril";
$mes_ano["May"] = "Maio";
$mes_ano["Jun"] = "Junho";
$mes_ano["Jul"] = "Julho";
$mes_ano["Aug"] = "Agosto";
$mes_ano["Sep"] = "Setembro";
$mes_ano["Oct"] = "Outubro";
$mes_ano["Nov"] = "Novembro";
$mes_ano["Dec"] = "Dezembro";

echo "<tr><td><b>Registrado:</b></td><td>" . date("d", $player->registered) . " de " . $mes_ano[$mes] . " de " . date("Y, g:i A", $player->registered) . ".</td></tr>";
echo "</table>";
echo "</td></tr>";
if ($player->magic_points > 29) {
    echo "<tr><td class=\"red\">";
} else {
    echo "<tr><td class=\"on\">";
}
echo "<center id=\"vl_pontosMisticos\"><font size=\"1px\"><b>Pontos místicos:</b> " . $player->magic_points . "</font></center>";
echo "</td></tr>";
echo "<tr><td>";
echo "<br/><table width=\"100%\">";
echo "<tr><td class=\"brown\" width=\"80%\"><center><b>Magias</b></center></td><td class=\"brown\" width=\"20%\"><center><font size=\"1px\"><a href=\"stat_points.php?act=magiasreset\">Reorganizar</a></font></center></td></tr>";
echo "<tr><td colspan=\"2\">";
echo "<div id=\"comfirm\" style=\"background-color: #FFFDE0; padding: 5px; text-align: center;\" height=\"100px\">";
include("showspells.php");
echo "</div>";
echo "</td></tr>";
echo "</table>";
echo "</td></tr>";
echo "</table>";
echo "</td>";
echo "<td width=\"40%\">";
echo "<table width=\"100%\">";
echo "<tr><td class=\"brown\" width=\"100%\" colspan=\"2\"><center><b>Pontos de Status</b><img src=\"images/help.gif\" title=\"header=[Pontos de Status] body=[<font size='1px'>São utilizados para aumentar sua agilidade, vitalidade, etc. A cada nível que voc&ecirc; passar voc&ecirc; ganha 3 pontos de status. Quando isso ocorrer não se esqueça de utiliza-los!</font>]\"></center></td></tr>";
echo "<tr><td class=\"salmon\" height=\"80px\" colspan=\"2\"><div id=\"skills\">";
include("showskills.php");
echo "</div></td></tr>";
echo "<tr><td ";
if ($player->stat_points > 8) {
    echo "class=\"red\"";
} else {
    echo "class=\"on\"";
}
echo "><center><font size=\"1px\"><b><a href=\"stat_points.php\">Distribuir pontos</a></b></font></center>";
echo "</td>";
if (($player->level > 79) and ($player->buystats == 0)) {
    echo "<td class=\"red\">";
} else {
    echo "<td class=\"on\">";
}
echo "<center><font size=\"1px\"><b><a href=\"buystats.php\">Treinar</a></b></font></center>";
echo "</td></tr>";
echo "<tr><td  colspan=\"2\">";
echo "<br/><table width=\"100%\">";
echo "<tr><td class=\"brown\" width=\"100%\"><center><b>Estender Mana</b></center></td></tr>";
echo "<tr>";
echo "<td class=\"salmon\" height=\"100px\"><div id=\"maxmana\">";
$magiascount = $db->execute("select * from `magias` where `player_id`=?", array($player->id));
if ($magiascount->recordcount() < 11) {
    echo "<br/><br/><center>Apenas jogadores que possuem todas as magias liberadas podem estender sua mana.</center><br/><br/>";
} else {
    echo "<br/><center><img src=\"images/man.png\"><img src=\"bargen.php?man\">";
    if ($player->magic_points > 0) {
        echo "<a href=\"javascript:void(0)\" onclick=\"javascript:LoadPage('swap_spells.php?estender=true', 'maxmana')\"><img src=\"images/addstat.png\" border=\"0px\"></a>";
    } else {
        echo "<img src=\"images/none.png\" border=\"0px\">";
    }
    echo "</center>";
    echo "<center><font size=\"1px\">Estenda 2 pontos da sua mana<br/>máxima por 1 ponto místico.<br/><br/><b>Voc&ecirc; " . $player->magic_points . " tem ponto(s) místico(s).</b></font></center>";
}
echo "</div></td>";
echo "</tr>";
echo "</table>";
echo "</td></tr>";
echo "</table>";
echo "</td></tr>";
echo "</table>";
/*	echo "</td></tr>";
echo "</table>"; */


echo "<br/>";

echo "<table width=\"100%\">";
echo "<tr><td width=\"50%\">";
echo "<table width=\"100%\">";
echo "<tr><td class=\"brown\" width=\"100%\"><center><b>Tarefas e Missões</b><img src=\"images/help.gif\" title=\"header=[Tarefas] body=[<font size='1px'>Tarefas são maneiras divertidas de se beneficiar no jogo. Apenas siga alguma das tarefas abaixo e seja recompensado com ouro, itens ou até mesmo ponto de experi&ecirc;ncia!</font>]\"></center></td></tr>";
$gettasks = $db->execute("select * from `tasks` where `needlvl`<=? order by `needlvl` asc", array($player->level));
if ($gettasks->recordcount() < 1) {
    echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" width=\"100%\"><center><font size=\"1px\">Nenhuma tarefa disponível.</font></center></td></tr>";
} else {
    $query = $db->execute("select * from `allquests`");
    while ($quest = $query->fetchrow()) {
        $q .= "<table width=\"100%\" border=\"0px\"><tr>";
        $q .= "<td width=\"70%\"><b>" . $quest['name'] . "</b><br/><i>" . $quest['desc'] . "</i><br/><br/></td>";

        //verifica se a missão está disponível ou se foi completa
        $qStatus = $db->GetOne("select `quest_status` from `quests` where `player_id`=? and `quest_id`=?", array($player->id, $quest['id']));
        if (($qStatus != 90) and ($quest['lvl'] <= $player->level)) {
            $mostra = true;
            if (($quest['to_lvl'] < $player->level) and ($quest['to_lvl'] > 0)) {
                $mostra = false;
            }

            if ($mostra) {
                if ($qStatus > 0) {
                    echo "<tr><th class=\"red\" width=\"100%\"><table width=\"100%\" border=\"0\"><tr><td width=\"80%\"><font size=\"1px\">" . $quest['name'] . "</font></td><th width=\"20%\" align=\"right\"><font size=\"1px\"><a href=\"tavern.php?p=quests&start=" . $quest['id'] . "\">Continuar</a></font></th></tr></table></th></tr>";
                } else {
                    echo "<tr><th class=\"red\" width=\"100%\"><table width=\"100%\" border=\"0\"><tr><td width=\"80%\"><font size=\"1px\">" . $quest['name'] . "</font></td><th width=\"20%\" align=\"right\"><font size=\"1px\"><a href=\"tavern.php?p=quests&start=" . $quest['id'] . "\">Participar</a></font></th></tr></table></th></tr>";
                }
            }
        }
    }

    while ($task = $gettasks->fetchrow()) {
        $checkcompleted = $db->execute("select * from `completed_tasks` where `player_id`=? and `task_id`=?", array($player->id, $task['id']));
        if ($checkcompleted->recordcount() == 0) {
            if (($task['obj_type'] == 'monster') and ($task['obj_extra'] > 0)) {
                $mname = $db->GetOne("select `username` from `monsters` where `id`=?", array($task['obj_value']));
                $pcento = $db->GetOne("select `value` from `monster_tasks` where `player_id`=? and `task_id`=?", array($player->id, $task['id']));
                $pcento = ceil(($pcento / $task['obj_extra']) * 100);
                $msg = "Matar " . $task['obj_extra'] . "x o monstro " . $mname . ".<br/>";
            } elseif (($task['obj_type'] == 'monster') and ($task['obj_extra'] == 0)) {
                $pcento = ceil(($player->monsterkilled / $task['obj_value']) * 100);
                $msg = "Matar " . $task['obj_value'] . " monstros.<br/>";
            } elseif (($task['obj_type'] == 'pvp') and ($task['obj_extra'] == 0)) {
                $pcento = ceil(($player->kills / $task['obj_value']) * 100);
                $msg = "Matar " . $task['obj_value'] . " usuários.<br/>";
            } elseif ($task['obj_type'] == 'level') {
                $pcento = ceil(($player->level / $task['obj_value']) * 100);
                $msg = "Alcançar o nível " . $task['obj_value'] . ".<br/>";
            }


            if ($task['win_type'] == 'gold') {
                $win = "<b>Recompensa:</b> " . $task['win_value'] . " moedas de ouro.<br/>";
            } elseif ($task['win_type'] == 'exp') {
                $win = "<b>Recompensa:</b> " . $task['win_value'] . " pontos de experi&ecirc;ncia.<br/>";
            } elseif ($task['win_type'] == 'item') {
                $itname = $db->GetOne("select `name` from `blueprint_items` where `id`=?", array($task['win_value']));
                $win = "<b>Recompensa:</b> " . $itname . ".<br/>";
            }

            echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" width=\"100%\"><div title=\"header=[Tarefa] body=[" . $pcento . "% concluida.]\"><font size=\"1px\">" . $msg . "" . $win . "</font></div></td></tr>";
        }
    }

    $countcompleted = $db->execute("select `id` from `completed_tasks` where `player_id`=?", array($player->id));
    if ($gettasks->recordcount() == $countcompleted->recordcount()) {
        echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" width=\"100%\"><center><font size=\"1px\">Nenhuma tarefa disponível.</font></center></td></tr>";
    }
}
echo "</table>";
echo "<center><font size=\"1\"><a href=\"tavern.php?p=tasks\">Exibir todas as tarefas</a></font></center>";
echo "</td>";
echo "<td width=\"50%\">";
echo "<table width=\"100%\">";
echo "<tr><td class=\"brown\" width=\"100%\"><center><b>Amigos</b><img src=\"images/help.gif\" title=\"header=[Amigos] body=[<font size='1px'>Seus amigos são importantes no jogo. Além de poder caçar com eles voc&ecirc; sempre ficará informado do que seu amigo está fazendo no jogo, portanto, vá logo para o chat ou o fórum do jogo e comece novas amizades!</font>]\"></center></td></tr>";

$countfriends = $db->execute("select * from `friends` where `uid`=?", array($player->acc_id));
if ($countfriends->recordcount() == 0) {
    echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" width=\"100%\"><center><font size=\"1px\">Você não tem amigos.</font></center></td></tr>";
} else {

    $getflogs = $db->execute("select log_friends.log, log_friends.time from `log_friends`, `friends` where friends.uid=? and log_friends.fname=friends.fname order by log_friends.time desc limit 5", array($player->acc_id));
    if ($getflogs->recordcount() < 1) {
        echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" width=\"100%\"><center><font size=\"1px\">Nenhum registro recente.</font></center></td></tr>";
    } else {
        while ($pfriend = $getflogs->fetchrow()) {

            $valortempo = time() -  $pfriend['time'];
            if ($valortempo < 60) {
                $valortempo2 = $valortempo;
                $auxiliar2 = "segundo(s) atrás.";
            } else if ($valortempo < 3600) {
                $valortempo2 = ceil($valortempo / 60);
                $auxiliar2 = "minuto(s) atrás.";
            } else if ($valortempo < 86400) {
                $valortempo2 = ceil($valortempo / 3600);
                $auxiliar2 = "hora(s) atrás.";
            } else if ($valortempo > 86400) {
                $valortempo2 = ceil($valortempo / 86400);
                $auxiliar2 = "dia(s) atrás.";
            }

            echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" width=\"100%\"><div title=\"header=[Log] body=[" . $valortempo2 . " " . $auxiliar2 . "]\"><font size=\"1px\">" . $pfriend['log'] . "</font></div></td></tr>";
        }
    }
}
echo "</table>";
if ($countfriends->recordcount() > 0) {
    $countgetflogs = $db->execute("select log_friends.log from `log_friends`, `friends` where friends.uid=? and log_friends.fname=friends.fname", array($player->acc_id));
    if ($countgetflogs->recordcount() > 5) {
        echo "<center><font size=\"1\"><a href=\"#\" onclick=\"javascript:window.open('friendslogs.php', '_blank','top=100, left=100, height=350, width=520, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');\">Exibir mais logs de amigos</a></font></center>";
    }
}

echo "</td></tr>";
echo "</table>";

$totalon = $db->execute("select `player_id` from `user_online`");
if ($totalon->recordcount() > $setting->user_record) {
    $query = $db->execute("update `settings` set `value`=? where `name`='user_record'", array($totalon->recordcount()));
}

include("templates/private_footer.php");
exit;
