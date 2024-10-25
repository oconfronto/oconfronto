<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");

//Select all members ordered by level (highest first, members table also doubles as rankings table)
$query = $db->execute("select `email` from `accounts` where ((`email` LIKE '%gmail.com%') or (`email` LIKE '%yahoo.com%') or (`email` LIKE '%hotmail.com%') or (`email` LIKE '%terra.com%') or (`email` LIKE '%bol.com%') or (`email` LIKE '%live.com%') or (`email` LIKE '%ig.com%'))");

    $numero = 0;
    $tire = 1;
while($member = $query->fetchrow())
{
    if ($numero > 29) {
        echo ", ju.rotta@gmail.com<br/><br/><b>" . $tire . "</b><br/>";
        $tire += 1;
        $numero = 0;
    } else {
        if ($numero >= 0 ) {    
	echo ", ";
        }
        
    $numero += 1;
    }
    
	echo strtolower((string) $member['email']);
}

    echo $query->recordcount();

?>
