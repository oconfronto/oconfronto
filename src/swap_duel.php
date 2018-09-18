<?php
include("lib.php");
$player = check_user($secret_key, $db);
    
$verificaLuta = $db->execute("select * from `duels` where `status`!='w' and (`p_id`=? or `e_id`=?) order by `status` asc, `id` desc limit 1", array($player->id, $player->id));
$luta = $verificaLuta->fetchrow();
    

if ($player->id == $luta['p_id']) {
    if (($_GET['type'] != 96) and ($_GET['type'] < 98) and ($_GET['type'] > 0)) {
        $db->execute("update `duels` set `p_type`=? where `id`=?", array($_GET['type'], $luta['id']));
    } elseif ($_GET['type'] == 96) {
        $db->execute("update `duels` set `p_type`=? where `id`=?", array($_GET['type'], $luta['id']));
        header("Location: duel.php?luta=true");
        exit;
    }
} else {
    if (($_GET['type'] != 96) and ($_GET['type'] < 98) and ($_GET['type'] > 0)) {
        $db->execute("update `duels` set `e_type`=? where `id`=?", array($_GET['type'], $luta['id']));
    } elseif ($_GET['type'] == 96) {
        $db->execute("update `duels` set `e_type`=? where `id`=?", array($_GET['type'], $luta['id']));
        header("Location: duel.php?luta=true");
        exit;
    }
}

?>