<?php
if ($_GET['header']) {
    include("lib.php");
    header("Content-Type: text/html; charset=utf-8",true);
    $player = check_user($secret_key, $db);
}

echo "<table width=\"95%\" align=\"center\">";
echo "<tr><td align=\"center\" bgcolor=\"#E1CBA4\" colspan=\"3\"><b>Desafios</b></td></tr>";
$showDuel = $db->execute("select * from `duels` where (`status`='w' or `status`=?) and (`p_id`=? or `e_id`=?)", array($player->id, $player->id, $player->id));
if ($showDuel->recordcount() > 0){
    while($duel = $showDuel->fetchrow())
    {
        echo "<tr>";
        if ($duel['p_id'] == $player->id)
        {
            $getUsername = $db->GetOne("select `username` from `players` where `id`=?", array($duel['e_id']));
            $getLevel = $db->GetOne("select `level` from `players` where `id`=?", array($duel['e_id']));
            echo "<td class=\"off\" style=\"width: 75%; vertical-align: middle;\">Você desafiou <a href=\"profile.php?id=" . $getUsername . "\">" . $getUsername . "</a><font size=\"1px\">(n&ecirc;vel " . $getLevel . ")</font> para um duelo.</td>";
            
            $duelCheckOnline = $db->execute("select `id` from `user_online` where `player_id`=?", array($duel['e_id']));
            if ($duelCheckOnline->recordcount() > 0) {
                echo "<td class=\"off\" style=\"width: 20%; vertical-align: middle;\"><center>Online</center></td>";
            } else {
                echo "<td class=\"off\" style=\"width: 20%; vertical-align: middle;\"><center>Offline</center></td>";
            }
            
            echo "<td class=\"off\" style=\"width: 5%; vertical-align: middle;\"><center><a href=\"duel.php?remove=" . $duel['id'] . "\"><b>X</b></a></center></td>";
        } else {
            $getUsername = $db->GetOne("select `username` from `players` where `id`=?", array($duel['p_id']));
            $getLevel = $db->GetOne("select `level` from `players` where `id`=?", array($duel['p_id']));
            echo "<td class=\"off\" style=\"vertical-align: middle;\"><a href=\"profile.php?id=" . $getUsername . "\">" . $getUsername . "</a><font size=\"1px\">(n&ecirc;vel " . $getLevel . ")</font> te desafiou para um duelo.</td>";
            
            $duelCheckOnline = $db->execute("select `id` from `user_online` where `player_id`=?", array($duel['p_id']));
            if ($duelCheckOnline->recordcount() > 0) {
                echo "<td class=\"off\" style=\"width: 20%; vertical-align: middle;\"><center><a href=\"duel.php?start=" . $duel['id'] . "\">Aceitar</a></center></td>";
            } else {
                echo "<td class=\"off\" style=\"width: 20%; vertical-align: middle;\"><center>Offline</center></td>";
            }
            
            echo "<td class=\"off\" style=\"width: 5%; vertical-align: middle;\"><center><a href=\"duel.php?remove=" . $duel['id'] . "\"><b>X</b></a></center></td>";
        }
        echo "</tr>";
    }
} else {
    echo "<tr><td class=\"off\"><center>Você ainda não tem desafios para duelos.</center></td></tr>";
}
echo "</table>";
?>