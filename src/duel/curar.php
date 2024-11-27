<?php

declare(strict_types=1);

$selectmana = $db->GetOne("select `mana` from `blueprint_magias` where `id`=4");
$mana = $player->reino == '1' || $player->vip > time() ? $selectmana - 5 : $selectmana;

$log = (($duellog[0] ?? null) && is_string($duellog[0])) ? explode(", ", $duellog[0]) : [];

if ($player->mana < $mana) {
    if ($log === [] || ($log[0] ?? null) != 6) {
        $duellog = ["6, " . $player->username . ""];
    }

    $otroatak = 5;
} else {
    $curar = $player->level < 50 ? random_int(30, 100) : random_int(intval($player->level), intval($player->level * 2));
    if (($player->hp + $curar) > $player->maxhp) {
        $db->execute("update `players` set `hp`=`maxhp` where `id`=?", [$player->id]);
        array_unshift($duellog, "3, " . $player->username . ", cura e recuperou toda sua vida.");
    } else {
        $db->execute("update `players` set `hp`=`hp`+? where `id`=?", [$curar, $player->id]);
        array_unshift($duellog, "3, " . $player->username . ", cura e recuperou " . $curar . " pontos de vida.");
    }

    $db->execute("update `players` set `mana`=`mana`-? where `id`=?", [$mana, $player->id]);
}
