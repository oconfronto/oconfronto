<?php
define("PAGENAME", "Erro");
$player = check_user($secret_key, $db);

    if ($player->maxhp != maxHp($db, $player->id, ($player->level - 1), $player->reino, $player->vip)) {
        include("templates/private_header.php");
        echo "<center>OPS! Parece que h um problema no HP do seu personagem.</center>";
        echo "<br/><br/>Por favor <a href=\"bugs.php\">clique aqui</a> e nos informe das sias ltimas aes no jogo que voc acredite que tenham levado ao erro.<br/>Para normalizar sua conta <a href=\"stat_points.php?act=reset\">clique aqui</a>.";
        include("templates/private_footer.php");
        exit;
    }
?>