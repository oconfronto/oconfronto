<?php

declare(strict_types=1);

$checabattalha = $db->execute("select `hp` from `bixos` where `player_id`=? and `type`!=98 and `type`!=99", [$player->id]);
$verificaLuta = $db->execute("select `id` from `duels` where `status`='s' and (`p_id`=? or `e_id`=?)", [$player->id, $player->id]);
if ($checabattalha->recordcount() > 0) {
    header("Location: monster.php?act=attack");
    exit;
}

if ($verificaLuta->recordcount() > 0) {
    header("Location: duel.php?luta=true");
    exit;
}
