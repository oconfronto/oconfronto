<?php
$selectmana = $db->GetOne("select `mana` from `blueprint_magias` where `id`=8");
if (($player->reino == '1') or ($player->vip > time())) {
    $mana = ($selectmana - 5);
} else {
    $mana = $selectmana;
}

$log = explode(", ", $duellog[0]);
if ($player->mana < $mana){
    if ($log[0] != 6) {
        array_unshift($duellog, "6, " . $player->username . "");
    }
    $otroatak = 5;
}else{
    
    if ($player->id == $luta['p_id']) {
        $magia = $luta['p_magia'];
        $emagia = $luta['e_magia'];
    } else {
        $magia = $luta['e_magia'];
        $emagia = $luta['p_magia'];
    }
    
    $pak0 = rand($player->mindmg, $player->maxdmg);
    $pak1 = rand($player->mindmg, $player->maxdmg);
    $pak2 = rand($player->mindmg, $player->maxdmg);
    $pak3 = rand($player->mindmg, $player->maxdmg);
    $totalpak = ceil($pak0 + $pak1 + $pak2 + $pak3);
    
    if ($magia == 1){
        $porcento = $totalpak / 100;
        $porcento = ceil($porcento * 15);
        $totalpak = $totalpak + $porcento;
    }else if($magia == 2){
        $porcento = $totalpak / 100;
        $porcento = ceil($porcento * 45);
        $totalpak = $totalpak + $porcento;
    }else if($magia == 12){
        $porcento = $totalpak / 100;
        $porcento = ceil($porcento * 35);
        $totalpak = $totalpak + $porcento;
    }
    
    if ($emagia == 2){
        $porcento = $totalpak / 100;
        $porcento = ceil($porcento * 15);
        $totalpak = $totalpak + $porcento;
    }else if ($emagia == 7){
        $porcento = $totalpak / 100;
        $porcento = ceil($porcento * 20);
        $totalpak = $totalpak - $porcento;
    }else if ($emagia == 11){
        $totalpak = ceil($totalpak / 2);
    }
    
    $misschance = intval(rand(0, 100));
    if (($misschance <= $player->miss) or ($emagia == 6))
    {
        $db->execute("update `players` set `mana`=`mana`-? where `id`=?", array($mana, $player->id));
        array_unshift($duellog, "8, " . $player->username . ", " . $enemy->username . "");
    }else{
        if ($emagia == 10){
            if (($player->hp - $totalpak) < 1){
                $db->execute("update `players` set `hp`='0', `deadtime`=? where `id`=?", array(time() + $setting->dead_time, $player->id));
                $morreu = 5;
            }else{
                $db->execute("update `players` set `hp`=`hp`-? where `id`=?", array($totalpak, $player->id));
            }
            array_unshift($duellog, "10, " . $player->username . ", " . $enemy->username . ", " . $totalpak . "");
        } else {
            if (($enemy->hp - $totalpak) < 1){
                $db->execute("update `players` set `hp`='0', `deadtime`=? where `id`=?", array(time() + $setting->dead_time, $enemy->id));
                $matou = 5;
            }else{
                $db->execute("update `players` set `hp`=`hp`-? where `id`=?", array($totalpak, $enemy->id));
            }
            array_unshift($duellog, "1, " . $player->username . ", " . $enemy->username . ", " . $totalpak . ", ataque quádruplo");
        }
        
        $db->execute("update `players` set `mana`=`mana`-? where `id`=?", array($mana, $player->id));
    }
}
?>