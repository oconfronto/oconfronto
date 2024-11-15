<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Personagens");
$acc = check_acc($db);

include(__DIR__ . "/templates/acc-header.php");

$aviso = 0;
//verifica se pediu pra transferir personagem
$playerstrans = $db->execute("select * from `pending` where `pending_id`=4 and `pending_other`=?", [$acc->id]);
if ($playerstrans->recordcount() > 0) {
    $change = $playerstrans->fetchrow();
    $coconta = $db->GetOne("select `conta` from `accounts` where `id`=?", [$change['player_id']]);

    if ($change['pending_time'] < time()) {
        $trocaperso = $db->execute("update `players` set `acc_id`=?, `transpass`='f' where `username`=?", [$change['player_id'], $change['pending_status']]);
        $query = $db->execute("delete from `pending` where `id`=?", [$change['id']]);
        echo '<span id="aviso-v">O personagem <b>' . $change['pending_status'] . "</b> foi transferido para a conta <b>" . $coconta . "</b>.</span>";
        $insert['player_id'] = $acc->id;
        $insert['msg'] = "O personagem <b>" . $change['pending_status'] . "</b> foi transferido para a conta <b>" . $coconta . "</b>.";
        $insert['time'] = time();
        $query = $db->autoexecute('account_log', $insert, 'INSERT');

        $insert['player_id'] = $change['player_id'];
        $insert['msg'] = "O personagem <b>" . $change['pending_status'] . "</b> foi transferido para sua conta.";
        $insert['time'] = time();
        $query = $db->autoexecute('account_log', $insert, 'INSERT');
        $aviso = 1;
    } else {
        $valortempo = $change['pending_time'] - time();
        if ($valortempo < 60) {
            $valortempo2 = $valortempo;
            $auxiliar2 = "segundo(s)";
        } elseif ($valortempo < 3600) {
            $valortempo2 = floor($valortempo / 60);
            $auxiliar2 = "minuto(s)";
        } elseif ($valortempo < 86400) {
            $valortempo2 = floor($valortempo / 3600);
            $auxiliar2 = "hora(s)";
        } elseif ($valortempo > 86400) {
            $valortempo2 = floor($valortempo / 86400);
            $auxiliar2 = "dia(s)";
        }

        echo '<span id="aviso-a"><font size="1px"><b>' . $change['pending_status'] . "</b> será transferido para a conta: <b>" . $coconta . "</b>.<br/>Ele será transferido em " . $valortempo2 . " " . $auxiliar2 . ', para cancelar o envio, <a href="transferchar.php?cancel=true">clique aqui</a>.</font></span>';
        $aviso = 1;
    }
}

//verifica se ja pode transferir o personagem
$playerstrans2 = $db->execute("select * from `pending` where `pending_id`=4 and `player_id`=?", [$acc->id]);
if ($playerstrans2->recordcount() > 0) {
    $change2 = $playerstrans2->fetchrow();
    $coconta = $db->GetOne("select `conta` from `accounts` where `id`=?", [$change2['player_id']]);

    if ($change2['pending_time'] < time()) {
        $trocachare = $db->execute("update `players` set `acc_id`=?, `transpass`='f' where `username`=?", [$change2['player_id'], $change2['pending_status']]);
        $query = $db->execute("delete from `pending` where `id`=?", [$change2['id']]);
        echo '<span id="aviso-v">O personagem <b>' . $change2['pending_status'] . "</b> foi transferido para sua conta.</span>";

        $insert['player_id'] = $acc->id;
        $insert['msg'] = "O personagem <b>" . $change2['pending_status'] . "</b> foi transferido para sua conta.";
        $insert['time'] = time();
        $query = $db->autoexecute('account_log', $insert, 'INSERT');

        $insert['player_id'] = $change2['pending_other'];
        $insert['msg'] = "O personagem <b>" . $change2['pending_status'] . "</b> foi transferido para a conta <b>" . $coconta . "</b>.";
        $insert['time'] = time();
        $query = $db->autoexecute('account_log', $insert, 'INSERT');
        $aviso = 1;
    } else {
        $valortempo = $change2['pending_time'] - time();
        if ($valortempo < 60) {
            $valortempo2 = $valortempo;
            $auxiliar2 = "segundo(s)";
        } elseif ($valortempo < 3600) {
            $valortempo2 = floor($valortempo / 60);
            $auxiliar2 = "minuto(s)";
        } elseif ($valortempo < 86400) {
            $valortempo2 = floor($valortempo / 3600);
            $auxiliar2 = "hora(s)";
        } elseif ($valortempo > 86400) {
            $valortempo2 = floor($valortempo / 86400);
            $auxiliar2 = "dia(s)";
        }

        echo '<span id="aviso-a"><font size="1px"><b>' . $change2['pending_status'] . "</b> será transferido para sua conta em " . $valortempo2 . " " . $auxiliar2 . ".</font></span>";
        $aviso = 1;
    }
}


//verificar mudança de email
$query04876 = $db->execute("select * from `pending` where `pending_id`=1 and `player_id`=?", [$acc->id]);
if ($query04876->recordcount() > 0) {
    $change = $query04876->fetchrow();
    if ($change['pending_time'] < time()) {
        $trocaemail = $db->execute("update `accounts` set `email`=? where `id`=?", [$change['pending_status'], $acc->id]);
        $query = $db->execute("delete from `pending` where `pending_id`=1 and `player_id`=?", [$acc->id]);
        echo '<span id="aviso-v">Seu e-mail foi alterado para: <b>' . $change['pending_status'] . "</b>.</span>";
        $insert['player_id'] = $acc->id;
        $insert['msg'] = "Seu e-mail foi alterado para: <b>" . $change['pending_status'] . "</b>.";
        $insert['time'] = time();
        $query = $db->autoexecute('account_log', $insert, 'INSERT');
        $aviso = 1;
    } else {
        $valortempo = $change['pending_time'] - time();
        if ($valortempo < 60) {
            $valortempo2 = $valortempo;
            $auxiliar2 = "segundo(s)";
        } elseif ($valortempo < 3600) {
            $valortempo2 = floor($valortempo / 60);
            $auxiliar2 = "minuto(s)";
        } elseif ($valortempo < 86400) {
            $valortempo2 = floor($valortempo / 3600);
            $auxiliar2 = "hora(s)";
        } elseif ($valortempo > 86400) {
            $valortempo2 = floor($valortempo / 86400);
            $auxiliar2 = "dia(s)";
        }

        echo "<span id=\"aviso-a\"><font size=\"1px\">Foi solicitada a mudança de seu e-mail para: <b>" . $change['pending_status'] . "</b><br/>Seu e-mail será alterado em " . $valortempo2 . " " . $auxiliar2 . ".<br/>Se não quiser mais mudar de e-mail <a href=\"changemail.php?act=cancel\">clique aqui</a>.</font></span>";
        $aviso = 1;
    }
}


//d'a ouro pro cara q te convidou
$queryactivate = $db->execute("select `id` from `players` where `acc_id`=? and `level`>=?", [$acc->id, $setting->activate_level]);
if ($acc->ref != "t" && $queryactivate->recordcount() > 0) {
    $query7 = $db->execute("update `players_ref` set `session_id`=? where `id_p_c`=?", [1, $acc->id]);

    if ($setting->promo == 't') {
        $query6 = $db->execute("update `promo` set `refs`=`refs`+1 where `player_id`=?", [$acc->ref]);
    }

    //INSERINDO ITENS OU GOLD COM BASE QUE O EVENTO ESTEJA OU NÃO ATIVADO, CONFORME A LISTA PRÉ-DEFINIDA BANCO DE DADOS.
    //PESQUISANDO SE PLAYER FOI REALMENTE CONVIDADO
    if ($queryactivate1 = $db->execute("select * from `players_ref` where `id_p_c`=? ", [$acc->id])) {;



        $query2 = $db->execute(sprintf('select * from `players_ref` where `id_p_c` = %s and session_id = 1', $acc->id));
        while ($row = $query2->fetchrow()) {
            $qt_ref = $row['id_p_ref'];
        }



        $query3 = $db->execute(sprintf('select * from `players_ref` where `id_p_ref` = %s and session_id = 1', $qt_ref));

        //SELECIONANDO TABELA DE PREMIOS
        $variavel = $query3->recordcount();
        $query2 = $db->execute(sprintf('select * from `ref_list_prem` where `qt` = %s and `event` = %s order by rand()', $variavel, $setting->event_convidados));
        while ($row = $query2->fetchrow()) {
            $type_qt = $row['qt'];
            $type_item = $row['item_id'];
            $type_gold = $row['gold'];
            $type_event = $row['event'];
            $item_bonus = $row['bonus'];
        }

        if ($query2->recordcount()) {
            // INSERINDO ITEM		
            if ($type_item > 0) {

                $insert_item['player_id'] = $qt_ref;
                $insert_item['item_id'] = $type_item;
                $insert_item_for = random_int(1, 5);
                $insert_item_vit = random_int(1, 5);
                $insert_item_agi = random_int(1, 5);
                $insert_item_res = random_int(1, 5);
                $insert_item['item_bonus'] = $item_bonus;
                $insert_item['status'] = 'unequipped';
                $insert_item['tile'] = '1';
                $insert_item['mark'] = 'f';
                $insert_item['item_event'] = '1';
                $db->autoexecute('items', $insert_item, 'INSERT');
                $id = $db->Insert_ID();
                $status = $db->execute("update `items` set `for`=`for`+?, `vit`=`vit`+?, `agi`=`agi`+?, `res`=`res`+? where `id`=?", [$insert_item_for, $insert_item_vit, $insert_item_agi, $insert_item_res, $id]);
            }

            // INSERINDO GOLD
            if ($type_gold > 0) {
                $query7 = $db->execute("update `players` set `gold`=`gold`+?, `ref`=`ref`+1 where `id`=?", [$type_gold, $id_p_ref]);
            }

            // RETORNANDO INSERÃÃO NA TABELA PLAYERS_REF
            $status = $db->execute("update `players_ref` set `date_end`=? where `id_p_c`=?", [time(), $acc->id]);
        }
    }

    $query7 = $db->execute("update `players` set `gold`=`gold`+?, `ref`=`ref`+1 where `id`=?", [$setting->earn, $acc->ref]);
    $validaconta = $db->execute("update `accounts` set `ref`='t' where `id`=?", [$acc->id]);
    $validaconta = $db->execute("update `player_ref` set `session_id`='2' where `id`=?", [$acc->id]);
}

if ($aviso != 1) {
    echo '<span id="aviso-a"></span>';
}

$query = $db->execute("select `id`, `username`, `level`, `avatar`, `ban`, `serv` from `players` where `acc_id`=? order by `level` desc", [$acc->id]);
if ($query->recordcount() == 0) {
    echo "<br/><p><center><b>Você ainda não possui nenhum personagem.</b></center></p><br/>";
} elseif ($query->recordcount() <= 3) {
    echo '<p><table align="center" width="95%"><tr>';
    while ($member = $query->fetchrow()) {
        $dire = ($member['avatar'] == "anonimo.gif") ? "static/" : "";
        echo "<td><table align=\"center\" style=\"height:132px; border:1px solid #444; padding:3px;\" onmouseover=\"this.bgColor='#cccccc';\" onmouseout=\"this.bgColor='#000000';\" onclick='window.location=\"login.php?id=" . $member['id'] . "\"'>";
        echo "<tr><td>";
        echo '<center><a href="login.php?id=' . $member['id'] . '"><img src="' . $dire . '' . $member['avatar'] . '" alt="' . $member['username'] . '" width="85px" height="80px"/></a></center>';
        echo "</td></tr>";

        if (strlen((string) $member['username']) < 8) {
            echo '<tr><td><center><b><font size="3px">' . $member['username'] . "</font></b></center></td></tr>";
        } elseif (strlen((string) $member['username']) < 12) {
            echo '<tr><td><center><b><font size="2px">' . $member['username'] . "</font></b></center></td></tr>";
        } else {
            echo '<tr><td><center><b><font size="1px">' . $member['username'] . "</font></b></center></td></tr>";
        }

        if ($member['ban'] > time()) {
            echo '<tr><td><center><font size="1px" color="red"><b>Banido</b></font></center></td></tr>';
        } else {
            echo "<tr><td><center><font size=\"1px\">nível " . $member['level'] . "</font></center></td></tr>";
        }

        echo "</table></td>";
    }

    echo "</tr></table></p>";
} else {
    echo '<p><div id="jMyCarousel" class="jMyCarousel"><ul>';
    while ($member = $query->fetchrow()) {
        $dire = ($member['avatar'] == "anonimo.gif") ? "static/" : "";
        echo "<li><table align=\"center\" style=\"height:132px; border:1px solid #444; padding:3px;\" onmouseover=\"this.bgColor='#cccccc';\" onmouseout=\"this.bgColor='#000000';\" onclick='window.location=\"login.php?id=" . $member['id'] . "\"'>";
        echo "<tr><td>";
        echo '<center><a href="login.php?id=' . $member['id'] . '"><img src="' . $dire . '' . $member['avatar'] . '" alt="' . $member['username'] . '" width="85px" height="80px"/></a></center>';
        echo "</td></tr>";

        if (strlen((string) $member['username']) < 8) {
            echo '<tr><td><center><b><font size="3px">' . $member['username'] . "</font></b></center></td></tr>";
        } elseif (strlen((string) $member['username']) < 12) {
            echo '<tr><td><center><b><font size="2px">' . $member['username'] . "</font></b></center></td></tr>";
        } else {
            echo '<tr><td><center><b><font size="1px">' . $member['username'] . "</font></b></center></td></tr>";
        }

        if ($member['ban'] > time()) {
            echo '<tr><td><center><font size="1px" color="red"><b>Banido</b></font></center></td></tr>';
        } else {
            echo "<tr><td><center><font size=\"1px\">nível " . $member['level'] . "</font></center></td></tr>";
        }

        echo "</table></li>";
    }

    echo "</ul></div></p>";
}

echo '<span id="aviso-v"><table width="95%" align="center"><tr>';
echo '<td width="15%"><font size="1px"><a href="logout.php">Sair</a></font></td>';
echo '<td width="30%" align="center"><font size="1px"><a href="newchar.php"><b>Criar novo Personagem</b></a></font></td>';
echo '<td width="30%" align="center"><font size="1px"><a href="deletechar.php"><b>Excluir Personagem</b></a></font></td>';
echo '<td width="25%" align="right"><font size="1px"><a href="acc_options.php">Editar Conta</a></font></td>';
echo "</tr></table></span>";
include(__DIR__ . "/templates/footer.php");
exit;
