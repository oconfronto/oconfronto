<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Fórum");
$player = check_user($db);

include(__DIR__ . "/templates/private_header.php");
if (!$_GET['player']) {
    echo "Nenhum usuário foi selecionado! <a href=\"select_forum.php\">Voltar</a>.";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}

if ($player->gm_rank < 3) {
    echo "Você não pode acessar esta página! <a href=\"select_forum.php\">Voltar</a>.";
    include(__DIR__ . "/templates/private_footer.php");
    exit;
}

$user = $db->execute("select `username`, `gm_rank` from `players` where `id`=?", [$_GET['player']]);
if ($user->recordcount() == 0) {
    echo "Este usuário não existe! <a href=\"select_forum.php\">Voltar</a>.";
}

include(__DIR__ . "/templates/private_footer.php");
exit;
