<?php
include("lib.php");
define("PAGENAME", "Treinar");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkhp.php");
include("checkwork.php");

if ($player->buystats >= 15)
{
	include("templates/private_header.php");
	echo "<fieldset><legend><b>Treinador</b></legend>\n";
	echo "<i>Você já comprou muitos pontos de status!</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo '</fieldset>';
	include("templates/private_footer.php");
	exit;
}
else
{	
	$heal = $player->buystats + 1;
	$cost = $heal * 1500;
	
	if ($_GET['act'])
	{
		if ($player->gold < $cost)
		{
			include("templates/private_header.php");
			echo "<fieldset><legend><b>Treinador</b></legend>\n";
			echo "<i>Você não tem ouro suficiente!</i><br/>\n";
			echo '<a href="home.php">Voltar</a>.';
	                echo '</fieldset>';
	 		include("templates/private_footer.php");
			exit;
		}
		else
		{
			$query = $db->execute("update `players` set `gold`=`gold`-?, `stat_points`=`stat_points`+2, `buystats`=`buystats`+1 where `id`=?", array($cost, $player->id));
			$player = check_user($secret_key, $db); //Get new stats
			include("templates/private_header.php");

			echo "<i>Você ganhou 2 ponto(s) de status!</i>\n";
			echo '<a href="home.php">Voltar</a>.';
			include("templates/private_footer.php");
			exit;
		}
	}
	include("templates/private_header.php");
	echo "<fieldset>";
	echo "<legend><b>Treinador</b></legend>";
	echo "<i>Você gostaria de treinar por apenas <b>" . $cost . "</b> de ouro?</i> <a href=\"buystats.php?act=buy\">Treinar!</a><br/>";
	echo "Treinando você ganhará mais 2 pontos de status.";
	include("templates/private_footer.php");
	exit;
}
?>