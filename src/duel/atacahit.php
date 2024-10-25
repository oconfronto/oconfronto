<?php

declare(strict_types=1);

if ($player->id == $luta['p_id']) {
    $magia = $luta['p_magia'];
    $emagia = $luta['e_magia'];
} else {
    $magia = $luta['e_magia'];
    $emagia = $luta['p_magia'];
}

$misschance = intval(random_int(0, 100));
if ($misschance <= $player->miss || $emagia == 6) {
    array_unshift($duellog, "5, " . $player->username . ", " . $enemy->username . "");
} else {
    $totalpak = random_int(intval($player->mindmg), intval($player->maxdmg));

    if ($magia == 1) {
        $porcento = $totalpak / 100;
        $porcento = ceil($porcento * 15);
        $totalpak += $porcento;
    } elseif ($magia == 2) {
        $porcento = $totalpak / 100;
        $porcento = ceil($porcento * 45);
        $totalpak += $porcento;
    } elseif ($magia == 12) {
        $porcento = $totalpak / 100;
        $porcento = ceil($porcento * 35);
        $totalpak += $porcento;
    }

    if ($emagia == 2) {
        $porcento = $totalpak / 100;
        $porcento = ceil($porcento * 15);
        $totalpak += $porcento;
    } elseif ($emagia == 7) {
        $porcento = $totalpak / 100;
        $porcento = ceil($porcento * 20);
        $totalpak -= $porcento;
    } elseif ($emagia == 11) {
        $totalpak = ceil($totalpak / 2);
    }

    if ($emagia == 10) {
        if (($player->hp - $totalpak) < 1) {
            $db->execute("update `players` set `hp`='0', `deadtime`=? where `id`=?", [time() + $setting->dead_time, $player->id]);
            $morreu = 5;
        } else {
            $db->execute("update `players` set `hp`=`hp`-? where `id`=?", [$totalpak, $player->id]);
        }

        array_unshift($duellog, "10, " . $player->username . ", " . $enemy->username . ", " . $totalpak . "");
    } else {
        if (($enemy->hp - $totalpak) < 1) {
            $db->execute("update `players` set `hp`='0', `deadtime`=? where `id`=?", [time() + $setting->dead_time, $enemy->id]);
            $matou = 5;
        } else {
            $db->execute("update `players` set `hp`=`hp`-? where `id`=?", [$totalpak, $enemy->id]);
        }

        array_unshift($duellog, "1, " . $player->username . ", " . $enemy->username . ", " . $totalpak . "");
    }
}
