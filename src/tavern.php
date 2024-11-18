<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Taverna");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkhp.php");
include(__DIR__ . "/checkwork.php");

switch ($_GET['p']) {
    case "quests":
        if ($_GET['start']) {
            $query = $db->execute("select * from `allquests` where `id`=?", [$_GET['start']]);
            if ($query->recordcount() != 1) {
                header("Location: tavern.php?p=quests");
                exit;
            }

            $quest = $query->fetchrow();

            //verifica se a missão está disponível ou se foi completa
            $qStatus = $db->GetOne("select `quest_status` from `quests` where `player_id`=? and `quest_id`=?", [$player->id, $quest['id']]);
            if ($qStatus == 90) {
                $a = "Você já concluiu esta missão!";
                $b = '<center><a href="tavern.php?p=quests">Voltar</a></center>';
            } elseif ($quest['lvl'] > $player->level || $quest['to_lvl'] < $player->level && $quest['to_lvl'] > 0) {
                $a = "Você não possui o nível necessário para esta missão!";
                $b = '<center><a href="tavern.php?p=quests">Voltar</a></center>';
            } elseif ($qStatus > 0) {
                $query = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, $quest['id']]);
                $missao = $query->fetchrow();
                if ($_GET['pay']) {
                    if ($player->gold - $quest['cost'] < 0) {
                        $a = "Você não possui esta quantia de ouro!";
                        $b = '<a href="tavern.php?p=quests&start=' . $quest['id'] . '">Voltar</a>';
                    } else {
                        $db->execute("update `players` set `gold`=`gold`-? where `id`=?", [$quest['cost'], $player->id]);
                        $db->execute("update `quests` set `pago`='t' where `id`=?", [$missao['id']]);
                        $a = "Você pagou " . $quest['cost'] . " moedas de ouro.";
                        $b = '<a href="tavern.php?p=quests&start=' . $quest['id'] . '">Continuar</a>';
                    }
                } else {
                    include("quests/" . $quest['id'] . ".php");
                }
            } elseif (!$_GET['confirm']) {
                $a = "Você realmente deseja iniciar essa missão?";
                $b = '<center><a href="tavern.php?p=quests&start=' . $quest['id'] . '&confirm=true">Continuar</a><br /><a href="tavern.php?p=quests">Voltar</a></center>';
            } else {
                //inicia missão
                $insert['player_id'] = $player->id;
                $insert['quest_id'] = $quest['id'];
                $insert['quest_status'] = 1;
                $query = $db->autoexecute('quests', $insert, 'INSERT');

                $a = "Você agora está participando da missão: <b>" . $quest['name'] . "</b>.";
                $b = '<center><a href="tavern.php?p=quests&start=' . $quest['id'] . '">Continuar</a></center>';
            }


            include(__DIR__ . "/templates/private_header.php");

            echo showAlert('<table width="100%" border="0"><tr><td width="80%">' . $a . '</td><td width="20%" align="right">' . $b . "</td></tr></table>", "white", "left");
            include(__DIR__ . "/templates/private_footer.php");
            break;
        }

        include(__DIR__ . "/templates/private_header.php");
        echo "<p><center>";
        if (($_GET['p'] != "bar") && ($_GET['p'] != "tasks")) {
            echo '<a href="tavern.php?p=quests"><b>Quests</b></a> | ';
        } else {
            echo '<a href="tavern.php?p=quests">Quests</a> | ';
        }

        if ($_GET['p'] == "tasks") {
            echo '<a href="tavern.php?p=tasks"><b>Tarefas</b></a> | ';
        } else {
            echo '<a href="tavern.php?p=tasks">Tarefas</a> | ';
        }

        if ($_GET['p'] == "bar") {
            echo '<a href="tavern.php?p=bar"><b>Bar</b></a>';
        } else {
            echo '<a href="tavern.php?p=bar">Bar</a>';
        }

        echo "</p></center>";

        echo "<center><i>A taverna e um ótimo local para encontrar pessoas e aceitar missões.</i></center><br />\n";

        $query = $db->execute("select * from `allquests`");
        while ($quest = $query->fetchrow()) {
            $q .= '<table width="100%" border="0px"><tr>';
            $q .= '<td width="70%"><b>' . $quest['name'] . "</b><br/><i>" . $quest['desc'] . "</i><br/><br/></td>";

            //verifica se a missão está disponível ou se foi completa
            $qStatus = $db->GetOne("select `quest_status` from `quests` where `player_id`=? and `quest_id`=?", [$player->id, $quest['id']]);
            if ($qStatus == 90) {
                $q .= "<td width=\"30%\" align=\"right\"><p><b>Concluída</b></p></td></tr>";
            } elseif ($quest['lvl'] > $player->level || $quest['to_lvl'] < $player->level && $quest['to_lvl'] > 0) {
                $q .= '<td width="30%" align="right"><p><s>Participar</s></p></td></tr>';
            } elseif ($qStatus > 0) {
                $q .= '<td width="30%" align="right"><p><a href="tavern.php?p=quests&start=' . $quest['id'] . '">Continuar</a></p></td></tr>';
            } else {
                $q .= '<td width="30%" align="right"><p><a href="tavern.php?p=quests&start=' . $quest['id'] . '">Participar</a></p></td></tr>';
            }

            $q .= '<tr><td width="70%"><b>Recompensa:</b> ' . $quest['prize'] . ".</td>";

            //verifica se user tem o nível minimo
            $q .= '<td width="30%" align="right">';
            if ($quest['lvl'] > $player->level || $quest['to_lvl'] < $player->level && $quest['to_lvl'] > 0) {
                $q .= '<font color="red">';
                $closered = true;
            }

            if ($quest['to_lvl'] > 0) {
                $q .= "Disponível entre o nível " . $quest['lvl'] . " e " . $quest['to_lvl'] . ".";
            } else {
                $q .= "Disponível a partir do nível " . $quest['lvl'] . ".";
            }

            if ($closered) {
                $q .= "</font>";
            }

            $q .= "</td></tr></table>";

            echo showAlert($q, "white", "left");
            $q = null;
        }

        break;

    case "tasks":
        include(__DIR__ . "/templates/private_header.php");
        echo "<p><center>";
        if (($_GET['p'] != "bar") && ($_GET['p'] != "tasks")) {
            echo '<a href="tavern.php?p=quests"><b>Quests</b></a> | ';
        } else {
            echo '<a href="tavern.php?p=quests">Quests</a> | ';
        }

        if ($_GET['p'] == "tasks") {
            echo '<a href="tavern.php?p=tasks"><b>Tarefas</b></a> | ';
        } else {
            echo '<a href="tavern.php?p=tasks">Tarefas</a> | ';
        }

        if ($_GET['p'] == "bar") {
            echo '<a href="tavern.php?p=bar"><b>Bar</b></a>';
        } else {
            echo '<a href="tavern.php?p=bar">Bar</a>';
        }

        echo "</p></center>";

        echo "<center><i>As tarefas são ativadas automaticamente, basta fazer seu objetivos após atiginir o nível mínimo necessário.</i></center><br />\n";

        echo '<table width="100%">';
        echo "<tr><td class=\"brown\" width=\"100%\"><center><b>Lista de Tarefas</b><img src=\"static/images/help.gif\" title=\"header=[Tarefas] body=[<font size='1px'>Tarefas são maneiras divertidas de se beneficiar no jogo. Apenas siga alguma das tarefas abaixo e seja recompensado com ouro, itens ou até mesmo ponto de experiência!</font>]\"></center></td></tr>";
        $gettasks = $db->execute("select * from `tasks` order by `needlvl` asc");
        if ($gettasks->recordcount() < 1) {
            echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" width=\"100%\"><center><font size=\"1px\">Nenhuma tarefa disponível.</font></center></td></tr>";
        } else {
            while ($task = $gettasks->fetchrow()) {
                $checkcompleted = $db->execute("select * from `completed_tasks` where `player_id`=? and `task_id`=?", [$player->id, $task['id']]);
                if ($checkcompleted->recordcount() == 0) {
                    if ($task['obj_type'] == 'monster' && $task['obj_extra'] > 0) {
                        $mname = $db->GetOne("select `username` from `monsters` where `id`=?", [$task['obj_value']]);
                        $pcento = $db->GetOne("select `value` from `monster_tasks` where `player_id`=? and `task_id`=?", [$player->id, $task['id']]);
                        $pcento = ceil(($pcento / $task['obj_extra']) * 100);
                        $msg = "Matar " . $task['obj_extra'] . "x o monstro " . $mname . ".<br/>";
                    } elseif ($task['obj_type'] == 'monster' && $task['obj_extra'] == 0) {
                        $pcento = ceil(($player->monsterkilled / $task['obj_value']) * 100);
                        $msg = "Matar " . $task['obj_value'] . " monstros.<br/>";
                    } elseif ($task['obj_type'] == 'pvp' && $task['obj_extra'] == 0) {
                        $pcento = ceil(($player->kills / $task['obj_value']) * 100);
                        $msg = "Matar " . $task['obj_value'] . " usuários.<br/>";
                    } elseif ($task['obj_type'] == 'level') {
                        $pcento = ceil(($player->level / $task['obj_value']) * 100);
                        $msg = "Alcançar o nível " . $task['obj_value'] . ".<br/>";
                    }


                    if ($task['win_type'] == 'gold') {
                        $win = "<b>Recompensa:</b> " . $task['win_value'] . " moedas de ouro.<br/>";
                    } elseif ($task['win_type'] == 'exp') {
                        $win = "<b>Recompensa:</b> " . $task['win_value'] . " pontos de experiência.<br/>";
                    } elseif ($task['win_type'] == 'item') {
                        $itname = $db->GetOne("select `name` from `blueprint_items` where `id`=?", [$task['win_value']]);
                        $win = "<b>Recompensa:</b> " . $itname . ".<br/>";
                    }

                    if ($task['needlvl'] > $player->level) {
                        echo '<tr><td class="red" width="100%"><table width="100%" border="0"><tr><td width="80%"><font size="1px">' . $msg . "" . $win . "</font></td><th width=\"20%\" align=\"right\"><font size=\"1px\">A partir do nível " . $task['needlvl'] . "</font></th></tr></table></td></tr>";
                    } elseif ($pcento >= 100) {
                        echo '<tr><td class="off" style="background-color: #DBD5D7;" width="100%"><table width="100%" border="0"><tr><td width="80%"><font size="1px">' . $msg . "" . $win . "</font></td><th width=\"20%\" align=\"right\"><font size=\"1px\">Concluída</font></th></tr></table></td></tr>";
                    } else {
                        echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" width=\"100%\"><table width=\"100%\" border=\"0\"><tr><td width=\"80%\"><font size=\"1px\">" . $msg . "" . $win . '</font></td><th width="20%" align="right"><font size="1px">' . $pcento . "% concluída</font></th></tr></table></td></tr>";
                    }
                }
            }

            $countcompleted = $db->execute("select `id` from `completed_tasks` where `player_id`=?", [$player->id]);
            if ($gettasks->recordcount() == $countcompleted->recordcount()) {
                echo "<tr><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" width=\"100%\"><center><font size=\"1px\">Nenhuma tarefa disponível.</font></center></td></tr>";
            }
        }

        echo "</table>";
        include(__DIR__ . "/templates/private_footer.php");
        break;

    case "bar":
        if ($_GET['act'] == 'buy') {
            if (!$_GET['id']) {
                include(__DIR__ . "/templates/private_header.php");
                echo "<b>Taverna:</b><br />\n";
                echo "<i>Este item não está a venda.</i><br /><br />\n";
                echo '<a href="tavern.php?p=bar">Voltar</a>.';
                include(__DIR__ . "/templates/private_footer.php");
                break;
            }

            $bebid = $db->execute("select `id`, `name`, `price`, `effectiveness` from `blueprint_items` where `id`=? and `type`='potion' and `effectiveness`>0", [$_GET['id']]);
            if ($bebid->recordcount() != 1) {
                include(__DIR__ . "/templates/private_header.php");
                echo "<b>Taverna:</b><br />\n";
                echo "<i>Este item não está a venda.</i><br /><br />\n";
                echo '<a href="tavern.php?p=bar">Voltar</a>.';
                include(__DIR__ . "/templates/private_footer.php");
                break;
            }

            $buy = $bebid->fetchrow();
            $bebado = $db->execute("select `item_id` from `in_use` where `player_id`=?", [$player->id]);
            if ($buy['id'] == 182 && $bebado->recordcount() == 0) {
                include(__DIR__ . "/templates/private_header.php");
                echo "<b>Taverna:</b><br />\n";
                echo "<i>Você não está sob efeito de nenhuma bebida para tomar um Glass of Water.</i><br /><br />\n";
                echo '<a href="tavern.php?p=bar">Voltar</a>.';
                include(__DIR__ . "/templates/private_footer.php");
                break;
            }

            $itemprice = $player->reino == '1' || $player->vip > time() ? ceil($buy['price'] * 0.9) : $buy['price'];

            if ($itemprice > $player->gold) {
                include(__DIR__ . "/templates/private_header.php");
                echo "<b>Taverna:</b><br />\n";
                echo "<i>Desculpe, mas você não pode pagar por isto!</i><br /><br />\n";
                echo '<a href="tavern.php?p=bar">Voltar</a>.';
                include(__DIR__ . "/templates/private_footer.php");
                break;
            }


            $db->execute("update `players` set `gold`=`gold`-? where `id`=?", [$itemprice, $player->id]);
            $db->execute("delete from `in_use` where `player_id`=?", [$player->id]);

            if ($buy['id'] != 182) {
                $insert['player_id'] = $player->id;
                $insert['item_id'] = $buy['id'];
                $insert['time'] = ceil(time() + ($buy['effectiveness'] * 60));
                $db->autoexecute('in_use', $insert, 'INSERT');
            }

            include(__DIR__ . "/templates/private_header.php");
            echo "<b>Taverna:</b><br />\n";
            echo "<i>Obrigado, aproveite o efeito de sua " . $buy['name'] . "!</i><br /><br />\n";
            echo '<a href="tavern.php?p=bar">Voltar</a>.';
            include(__DIR__ . "/templates/private_footer.php");
            break;
        }

        $lista = $db->execute("select `id`, `name`, `description`, `price`, `effectiveness`, `img`, `voc`, `needlvl`, `needpromo` from `blueprint_items` where `type`='potion' and `effectiveness`>0 order by `price` asc");
        include(__DIR__ . "/templates/private_header.php");

        echo "<p><center>";
        if (($_GET['p'] != "bar") && ($_GET['p'] != "tasks")) {
            echo '<a href="tavern.php?p=quests"><b>Quests</b></a> | ';
        } else {
            echo '<a href="tavern.php?p=quests">Quests</a> | ';
        }

        if ($_GET['p'] == "tasks") {
            echo '<a href="tavern.php?p=tasks"><b>Tarefas</b></a> | ';
        } else {
            echo '<a href="tavern.php?p=tasks">Tarefas</a> | ';
        }

        if ($_GET['p'] == "bar") {
            echo '<a href="tavern.php?p=bar"><b>Bar</b></a>';
        } else {
            echo '<a href="tavern.php?p=bar">Bar</a>';
        }

        echo "</p></center>";

        echo "<center><i>Bem-Vindo a Taverna. Tome uma bebida e sinta-se á vontade.</i></center><br />";
        $verificpotion = $db->execute("select * from `in_use` where `player_id`=? and `time`>?", [$player->id, time()]);
        if ($verificpotion->recordcount() > 0) {
            $selct = $verificpotion->fetchrow();
            $potname = $db->GetOne("select `name` from `blueprint_items` where `id`=?", [$selct['item_id']]);
            echo "<div style=\"background-color:#EEA2A2; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\"><center>Se você tomar outra bebida o efeito do/da <b>" . $potname . "</b> irá acabar.</center></div>";
        }

        if ($player->reino == '1') {
            echo showAlert("<i>Você tem 10% de desconto nas bebidas, pelo fato de ser um membro do reino Cathal.</i>");
        } elseif ($player->vip > time()) {
            echo showAlert("<i>Você tem 10% de desconto nas bebidas, pelo fato de ser um membro VIP.</i>");
        }

        while ($item = $lista->fetchrow()) {
            echo "<fieldset>\n";
            echo "<legend><b>" . $item['name'] . "</b></legend>\n";
            echo "<table width=\"100%\">\n";
            echo '<tr><td width="6%">';
            echo '<img src="static/images/itens/' . $item['img'] . '"/>';
            echo '</td><td width="74%">';
            echo $item['description'] . "";
            if ($item['effectiveness'] >= 60) {
                echo " <b>Duração:</b> " . ($item['effectiveness'] / 60) . " hora(s).";
            } elseif ($item['effectiveness'] > 1) {
                echo " <b>Duração:</b> " . $item['effectiveness'] . " minuto(s).";
            }

            echo '</td><td width="20%">';
            echo "<b>Preço:</b> ";
            if ($player->reino == '1' || $player->vip > time()) {
                echo ceil($item['price'] * 0.9);
            } else {
                echo $item['price'];
            }

            echo '<br/><a href="tavern.php?p=bar&act=buy&id=' . $item['id'] . '">Comprar</a><br />';
            echo "</td></tr>\n";
            if ($item['needlvl'] > 1) {
                if ($player->level < $item['needlvl']) {
                    echo "<table style=\"width:100%; background-color:#EEA2A2;\"><tr><td><center><b>Você precisa ter nível " . $item['needlvl'] . " ou mais para usar este item.</b></center></td></tr>\n";
                } else {
                    echo "<table style=\"width:100%; background-color:#BDF0A6;\"><tr><td><center><b>Você precisa ter nível " . $item['needlvl'] . " ou mais para usar este item.</b></center></td></tr>\n";
                }
            }

            if ($item['needpromo'] == "t") {
                if ($player->promoted != "f") {
                    echo "<table style=\"width:100%; background-color:#BDF0A6;\"><tr><td><center><b>Você precisa ter uma vocação superior para usar este item.</b></center></td></tr>\n";
                } else {
                    echo "<table style=\"width:100%; background-color:#EEA2A2;\"><tr><td><center><b>Você precisa ter uma vocação superior para usar este item.</b></center></td></tr>\n";
                }
            }

            echo "</table>";
            echo "</fieldset><br/>";
        }

        include(__DIR__ . "/templates/private_footer.php");
        break;

    default:
        header("Location: tavern.php?p=quests");
        break;
}
