<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
$player = check_user($db);

$verificaLuta = $db->execute("select * from `duels` where `status`!='w' and (`p_id`=? or `e_id`=?) order by `status` asc, `id` desc limit 1", [$player->id, $player->id]);
$luta = $verificaLuta->fetchrow();


if ($player->id == ($luta['p_id'] ?? null)) {
    if (($_GET['type'] ?? null) != 96 && ($_GET['type'] ?? null) < 98 && ($_GET['type'] ?? null) > 0) {
        $db->execute("update `duels` set `p_type`=? where `id`=?", [$_GET['type'] ?? null, $luta['id'] ?? null]);
    } elseif (($_GET['type'] ?? null) == 96) {
        $db->execute("update `duels` set `p_type`=? where `id`=?", [$_GET['type'] ?? null, $luta['id'] ?? null]);
        header("Location: duel.php?luta=true");
        exit;
    }
} elseif (($_GET['type'] ?? null) != 96 && ($_GET['type'] ?? null) < 98 && ($_GET['type'] ?? null) > 0) {
    $db->execute("update `duels` set `e_type`=? where `id`=?", [$_GET['type'] ?? null, $luta['id'] ?? null]);
} elseif (($_GET['type'] ?? null) == 96) {
    $db->execute("update `duels` set `e_type`=? where `id`=?", [$_GET['type'] ?? null, $luta['id'] ?? null]);
    header("Location: duel.php?luta=true");
    exit;
}
