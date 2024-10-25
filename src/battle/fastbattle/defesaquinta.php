<?php

declare(strict_types=1);

$selectmana = $db->GetOne("select `mana` from `blueprint_magias` where `id`=9");
$mana = $player->reino == '1' || $player->vip > time() ? $selectmana - 5 : $selectmana;

$fastmagia = 6;
$fastturno = 5;

$player->mana -= $mana;
array_unshift($_SESSION['battlelog'], "3, Você lançou o feitiço defesa quádrupla.");
