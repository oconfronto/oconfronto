<?php
if ($player->id == $luta['p_id']) {
    $magia = $luta['p_magia'];
    $emagia = $luta['e_magia'];
} else {
    $magia = $luta['e_magia'];
    $emagia = $luta['p_magia'];
}

$misschance = intval(rand(0, 100));
if (($misschance <= $player->miss) or ($emagia == 6))
{
    array_unshift($duellog, "5, " . $player->username . ", " . $enemy->username . "");
}else{
    $totalpak = rand($player->mindmg, $player->maxdmg);
    
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
        array_unshift($duellog, "1, " . $player->username . ", " . $enemy->username . ", " . $totalpak . "");
    }
}
?>