<?php
declare(strict_types=1);

$selectmana = $db->GetOne("select `mana` from `blueprint_magias` where `id`=7");
$mana = $player->reino == '1' || $player->vip > time() ? $selectmana - 5 : $selectmana;

$magiaatual = $player->id == $luta['p_id'] ? $luta['p_turnos'] : $luta['e_turnos'];

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
        $db->execute("update `duels` set `p_magia`='7', `p_turnos`='6' where `id`=?", [$luta['id']]);
    } else {
        $db->execute("update `duels` set `e_magia`='7', `e_turnos`='6' where `id`=?", [$luta['id']]);
    }
    
    $db->execute("update `players` set `mana`=`mana`-? where `id`=?", [$mana, $player->id]);
    array_unshift($duellog, "3, " . $player->username . ", resistência");
}
?>
