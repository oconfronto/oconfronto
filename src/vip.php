<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Loja VIP");
$acc = check_acc($db);
$player = check_user($db);
include(__DIR__ . "/templates/private_header.php");
echo "<i><center>A loja VIP ainda está fechada.<br/>Tente novamente amanhã.</center></i>\n";
include(__DIR__ . "/templates/private_footer.php");
exit;
