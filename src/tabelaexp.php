<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Membros");
$player = check_user($db);

include(__DIR__ . "/templates/private_header.php");


    for ($i = 1; $i <= 800; ++$i) {
        echo $i;
        echo " - ";
        echo maxExp($i);
        echo "<br/>";
    }

include(__DIR__ . "/templates/private_footer.php");
?>
