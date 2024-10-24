<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Treinar");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkhp.php");
include(__DIR__ . "/checkwork.php");

if ($player->buystats >= 15)
{
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Treinador</b></legend>\n";
	echo "<i>Você já comprou muitos pontos de status!</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo '</fieldset>';
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

$heal = $player->buystats + 1;
$cost = $heal * 1500;
if ($_GET['act'])
	{
		if ($player->gold < $cost)
		{
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Treinador</b></legend>\n";
			echo "<i>Você não tem ouro suficiente!</i><br/>\n";
			echo '<a href="home.php">Voltar</a>.';
	                echo '</fieldset>';
	 		include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

 $query = $db->execute("update `players` set `gold`=`gold`-?, `stat_points`=`stat_points`+2, `buystats`=`buystats`+1 where `id`=?", [$cost, $player->id]);
 $player = check_user($db);
 //Get new stats
 include(__DIR__ . "/templates/private_header.php");
 echo "<i>Você ganhou 2 ponto(s) de status!</i>\n";
 echo '<a href="home.php">Voltar</a>.';
 include(__DIR__ . "/templates/private_footer.php");
 exit;
	}

include(__DIR__ . "/templates/private_header.php");
echo "<fieldset>";
echo "<legend><b>Treinador</b></legend>";
echo "<i>Você gostaria de treinar por apenas <b>" . $cost . '</b> de ouro?</i> <a href="buystats.php?act=buy">Treinar!</a><br/>';
echo "Treinando você ganhará mais 2 pontos de status.";
include(__DIR__ . "/templates/private_footer.php");
exit;
?>
