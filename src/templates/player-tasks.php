<?php
// Player Tasks Template
?>
<table width="100%">
    <tr>
        <td class="brown" width="100%">
            <center>
                <b>Tarefas e Missões</b>
                <img src="static/images/help.gif" title="header=[Tarefas] body=[<font size='1px'>Tarefas são maneiras divertidas de se beneficiar no jogo. Apenas siga alguma das tarefas abaixo e seja recompensado com ouro, itens ou até mesmo ponto de experiência!</font>]">
            </center>
        </td>
    </tr>
    
    <?php
    $gettasks = $db->execute("select * from `tasks` where `needlvl`<=? order by `needlvl` asc", [$player->level]);
    
    if ($gettasks->recordcount() < 1): ?>
        <tr>
            <td class="off" onmouseover="this.className='on'" onmouseout="this.className='off'" width="100%">
                <center><font size="1px">Nenhuma tarefa disponível.</font></center>
            </td>
        </tr>
    <?php else:
        // Display available quests
        $query = $db->execute("select * from `allquests`");
        while ($quest = $query->fetchrow()):
            $qStatus = $db->GetOne("select `quest_status` from `quests` where `player_id`=? and `quest_id`=?", 
                [$player->id, $quest['id'] ?? null]);
            
            if ($qStatus != 90 && ($quest['lvl'] ?? null) <= $player->level):
                $mostra = true;
                if (($quest['to_lvl'] ?? null) < $player->level && ($quest['to_lvl'] ?? null) > 0) {
                    $mostra = false;
                }
                
                if ($mostra): ?>
                    <tr>
                        <th class="red" width="100%">
                            <table width="100%" border="0">
                                <tr>
                                    <td width="80%">
                                        <font size="1px"><?= $quest['name'] ?></font>
                                    </td>
                                    <th width="20%" align="right">
                                        <font size="1px">
                                            <a href="tavern.php?p=quests&start=<?= $quest['id'] ?>">
                                                <?= $qStatus > 0 ? 'Continuar' : 'Participar' ?>
                                            </a>
                                        </font>
                                    </th>
                                </tr>
                            </table>
                        </th>
                    </tr>
                <?php endif;
            endif;
        endwhile;

        // Display available tasks
        while ($task = $gettasks->fetchrow()):
            $checkcompleted = $db->execute("select * from `completed_tasks` where `player_id`=? and `task_id`=?", 
                [$player->id, $task['id'] ?? null]);
            
            if ($checkcompleted->recordcount() == 0):
                // Calculate task progress and message
                if (($task['obj_type'] ?? null) == 'monster' && ($task['obj_extra'] ?? null) > 0) {
                    $mname = $db->GetOne("select `username` from `monsters` where `id`=?", [$task['obj_value'] ?? null]);
                    $pcento = $db->GetOne("select `value` from `monster_tasks` where `player_id`=? and `task_id`=?", 
                        [$player->id, $task['id'] ?? null]);
                    $pcento = ceil(($pcento / $task['obj_extra']) * 100);
                    $msg = "Matar " . $task['obj_extra'] . "x o monstro " . $mname . ".<br/>";
                } elseif (($task['obj_type'] ?? null) == 'monster' && ($task['obj_extra'] ?? null) == 0) {
                    $pcento = ceil(($player->monsterkilled / $task['obj_value']) * 100);
                    $msg = "Matar " . $task['obj_value'] . " monstros.<br/>";
                } elseif (($task['obj_type'] ?? null) == 'pvp' && ($task['obj_extra'] ?? null) == 0) {
                    $pcento = ceil(($player->kills / $task['obj_value']) * 100);
                    $msg = "Matar " . $task['obj_value'] . " usuários.<br/>";
                } elseif (($task['obj_type'] ?? null) == 'level') {
                    $pcento = ceil(($player->level / $task['obj_value']) * 100);
                    $msg = "Alcançar o nível " . $task['obj_value'] . ".<br/>";
                }

                // Calculate reward message
                if (($task['win_type'] ?? null) == 'gold') {
                    $win = "<b>Recompensa:</b> " . $task['win_value'] . " moedas de ouro.<br/>";
                } elseif (($task['win_type'] ?? null) == 'exp') {
                    $win = "<b>Recompensa:</b> " . $task['win_value'] . " pontos de experiência.<br/>";
                } elseif (($task['win_type'] ?? null) == 'item') {
                    $itname = $db->GetOne("select `name` from `blueprint_items` where `id`=?", [$task['win_value'] ?? null]);
                    $win = "<b>Recompensa:</b> " . $itname . ".<br/>";
                }
                ?>
                <tr>
                    <td class="off" onmouseover="this.className='on'" onmouseout="this.className='off'" width="100%">
                        <div title="header=[Tarefa] body=[<?= $pcento ?>% concluida.]">
                            <font size="1px">
                                <?= $msg . $win ?>
                            </font>
                        </div>
                    </td>
                </tr>
            <?php endif;
        endwhile;

        // Check if all tasks are completed
        $countcompleted = $db->execute("select `id` from `completed_tasks` where `player_id`=?", [$player->id]);
        if ($gettasks->recordcount() == $countcompleted->recordcount()): ?>
            <tr>
                <td class="off" onmouseover="this.className='on'" onmouseout="this.className='off'" width="100%">
                    <center><font size="1px">Nenhuma tarefa disponível.</font></center>
                </td>
            </tr>
        <?php endif;
    endif; ?>
</table>
<center>
    <font size="1">
        <a href="tavern.php?p=tasks">Exibir todas as tarefas</a>
    </font>
</center>
