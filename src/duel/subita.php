<?php
$selectmana = $db->GetOne("select `mana` from `blueprint_magias` where `id`=12");
if (($player->reino == '1') or ($player->vip > time())) {
    $mana = ($selectmana - 5);
} else {
    $mana = $selectmana;
}

if ($player->id == $luta['p_id']) {
    $magiaatual = $luta['p_turnos'];
} else {
    $magiaatual = $luta['e_turnos'];
}

$log = explode(", ", $duellog[0]);
if ($player->mana < $mana){
    if ($log[0] != 6) {
        array_unshift($duellog, "6, " . $player->username . "");
    }
    $otroatak = 5;
}elseif ($magiaatual != 0){
    if ($log[0] != 7) {
        array_unshift($duellog, "7, " . $player->username . "");
    }
    $otroatak = 5;
}else{
    if ($player->id == $luta['p_id']) {
        $db->execute("update `duels` set `p_magia`='12', `p_turnos`='6' where `id`=?", array($luta['id']));
    } else {
        $db->execute("update `duels` set `e_magia`='12', `e_turnos`='6' where `id`=?", array($luta['id']));
    }
    
    $db->execute("update `players` set `mana`=`mana`-? where `id`=?", array($mana, $player->id));
    array_unshift($duellog, "3, " . $player->username . ", força súbita");
}
?>