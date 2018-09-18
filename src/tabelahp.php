<?php
include("lib.php");
define("PAGENAME", "Membros");
$player = check_user($secret_key, $db);

include("templates/private_header.php");

    echo "sem bonus<br/>";
    for ($i = 1; $i <= 100; $i++) {
        echo $i;
        echo " - ";
        echo maxHp($db, $player->id, ($i - 2), 1, 0);
        echo "<br/>";
    }

    
    echo "<br><br/>com bonus<br/>";
    for ($i = 1; $i <= 100; $i++) {
        echo $i;
        echo " - ";
        echo maxHp($db, $player->id, ($i - 2), 3, 0);
        echo "<br/>";
    }
    
include("templates/private_footer.php");
?>