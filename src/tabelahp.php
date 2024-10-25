<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Membros");
$player = check_user($db);

include(__DIR__ . "/templates/private_header.php");

echo "sem bonus<br/>";
for ($i = 1; $i <= 100; ++$i) {
    echo $i;
    echo " - ";
    echo maxHp($db, $player->id, ($i - 2), 1, 0);
    echo "<br/>";
}


echo "<br><br/>com bonus<br/>";
for ($i = 1; $i <= 100; ++$i) {
    echo $i;
    echo " - ";
    echo maxHp($db, $player->id, ($i - 2), 3, 0);
    echo "<br/>";
}

include(__DIR__ . "/templates/private_footer.php");
