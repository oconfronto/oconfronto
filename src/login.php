<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Login");
$acc = check_acc($db);

if (!$_GET['id']) {
    header("Location: characters.php");
    exit;
}

$loginban = $db->GetOne("select `ban` from `players` where `id`=?", [$_GET['id']]);
$youracc = $db->execute("select * from `players` where `id`=? and `acc_id`=?", [$_GET['id'], $acc->id]);
if ($loginban > time()) {
    include(__DIR__ . "/templates/acc_header.php");
    $time = $loginban - time();
    $days_remaining = $time / 86400;
    echo "<br/><br/><br/><center>" . $loginto . " foi banido do jogo. O banimento acabará em " . round($days_remaining) . ' dia(s). <a href="characters.php">Voltar</a>.</center><br/>';
    include(__DIR__ . "/templates/acc_footer.php");
    exit;
}

if ($youracc->recordcount() != 1) {
    include(__DIR__ . "/templates/acc_header.php");
    echo "<br/><br/><br/><center>Este usuário não pertence a sua conta ou não foi encontrado. <a href=\"characters.php\">Voltar</a>.</center><br/>";
    include(__DIR__ . "/templates/acc_footer.php");
    exit;
}

$_SESSION['Login']['player_id'] = $_GET['id'];
header("Location: home.php");
exit;
