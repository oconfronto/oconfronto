<?php
// Player Friends Template

function formatTimeAgo($timestamp) {
    $valortempo = time() - $timestamp;
    if ($valortempo < 60) {
        return $valortempo . " segundo(s) atrás.";
    } elseif ($valortempo < 3600) {
        return ceil($valortempo / 60) . " minuto(s) atrás.";
    } elseif ($valortempo < 86400) {
        return ceil($valortempo / 3600) . " hora(s) atrás.";
    } else {
        return ceil($valortempo / 86400) . " dia(s) atrás.";
    }
}
?>

<table width="100%">
    <tr>
        <td class="brown" width="100%">
            <center>
                <b>Amigos</b>
                <img src="static/images/help.gif" title="header=[Amigos] body=[<font size='1px'>Seus amigos são importantes no jogo. Além de poder caçar com eles você sempre ficará informado do que seu amigo está fazendo no jogo, portanto, vá logo para o chat ou o fórum do jogo e comece novas amizades!</font>]">
            </center>
        </td>
    </tr>

    <?php
    $countfriends = $db->execute("select * from `friends` where `uid`=?", [$player->acc_id]);
    
    if ($countfriends->recordcount() == 0): ?>
        <tr>
            <td class="off" onmouseover="this.className='on'" onmouseout="this.className='off'" width="100%">
                <center><font size="1px">Você não tem amigos.</font></center>
            </td>
        </tr>
    <?php else:
        $getflogs = $db->execute("select log_friends.log, log_friends.time from `log_friends`, `friends` 
            where friends.uid=? and log_friends.fname=friends.fname 
            order by log_friends.time desc limit 5", [$player->acc_id]);
        
        if ($getflogs->recordcount() < 1): ?>
            <tr>
                <td class="off" onmouseover="this.className='on'" onmouseout="this.className='off'" width="100%">
                    <center><font size="1px">Nenhum registro recente.</font></center>
                </td>
            </tr>
        <?php else:
            while ($pfriend = $getflogs->fetchrow()): 
                $timeAgo = formatTimeAgo($pfriend['time']);
            ?>
                <tr>
                    <td class="off" onmouseover="this.className='on'" onmouseout="this.className='off'" width="100%">
                        <div title="header=[Log] body=[<?= $timeAgo ?>]">
                            <font size="1px"><?= $pfriend['log'] ?></font>
                        </div>
                    </td>
                </tr>
            <?php endwhile;
        endif;
        
        // Show "View more logs" link if there are more than 5 logs
        $countgetflogs = $db->execute("select log_friends.log from `log_friends`, `friends` 
            where friends.uid=? and log_friends.fname=friends.fname", [$player->acc_id]);
        
        if ($countgetflogs->recordcount() > 5): ?>
            <center>
                <font size="1">
                    <a href="#" onclick="window.open('friendslogs.php', '_blank', 'width=520,height=350,top=100,left=100,status=no,menubar=no,resizable=no,scrollbars=yes,toolbar=no,location=no,directories=no');">
                        Exibir mais logs de amigos
                    </a>
                </font>
            </center>
        <?php endif;
    endif; ?>
</table>
