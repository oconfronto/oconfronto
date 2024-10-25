<?php

declare(strict_types=1);

if ($player->alerts > 998 && $player->alerts != 'forever' && $player->alerts < time()) {
    $unban = $db->execute("update `players` set `alerts`=0 where `id`=?", [$player->id]);
    include(__DIR__ . "/templates/private_header.php");
    echo "Seu banimento no fórum acabou!<br/><a href=\"select_forum.php\">Visitar o fórum</a>.";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}

if ($player->alerts > 99 && $player->alerts < time() || $player->alerts == 'forever' || $player->alerts > time()) {
    if ($player->alerts > 99 && $player->alerts < time()) {
        include(__DIR__ . "/templates/private_header.php");
        echo "Seu alerta chegou a " . $player->alerts . "% e você não poderá visitar o fórum até que seu alerta baixe. Seu alerta cai 1% ao dia.<br/><a href=\"home.php\">Voltar</a>.";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }

    if ($player->alerts == 'forever') {
        include(__DIR__ . "/templates/private_header.php");
        echo "Você foi banido do fórum permanentemente.<br/><a href=\"home.php\">Voltar</a>.";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }

    if ($player->alerts > time()) {
        $tempo0 = $player->alerts - time();
        $tempo = ceil($tempo0 / 60 / 60 / 24);
        include(__DIR__ . "/templates/private_header.php");
        echo "Você foi banido do fórum. Seu banimento irá acabar em " . $tempo . ' dia(s).<br/><a href="home.php">Voltar</a>.';
        include(__DIR__ . "/templates/private_footer.php");
        exit;
    }
}
