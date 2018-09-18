<?php
define("PAGENAME", "Erro");
$player = check_user($secret_key, $db);

    if ($player->maxhp != maxHp($db, $player->id, ($player->level - 1), $player->reino, $player->vip)) {
        include("templates/private_header.php");
        echo "<center>OPS! Parece que há um problema no HP do seu personagem.</center>";
        echo "<br/><br/>Por favor <a href=\"bugs.php\">clique aqui</a> e nos informe das sias últimas ações no jogo que você acredite que tenham levado ao erro.<br/>Para normalizar sua conta <a href=\"stat_points.php?act=reset\">clique aqui</a>.";
        include("templates/private_footer.php");
        exit;
    }
?>