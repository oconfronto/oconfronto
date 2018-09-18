<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Jrotta         */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.bbgamezone.com/     */
/*************************************/

include("lib.php");
define("PAGENAME", "Batalhar");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkhp.php");
include("checkwork.php");

	if ($_GET['act'])
	{
		if ($player->died >= 3)
		{
			$query = $db->execute("update `players` set `died`=? where `id`=?", array(0, $player->id));
			include("templates/private_header.php");
			echo "<i>Sua imunidade foi removida!</i><br/>";
			echo "Agora você pode lutar contra os outros <a href=\"battle.php\">jogadores</a>.";
			include("templates/private_footer.php");
			exit;
		}
		else
		{
			include("templates/private_header.php");
			echo "<i><font color=\"red\">Parece que você não está protegido!</i> <a href=\"home.php\">Voltar</a>.";
	 		include("templates/private_footer.php");
			exit;
		}
	}
	include("templates/private_header.php");
?>
<i>Você tem certeza que quer remover sua imunidade contra ataques de outros jogadores?</i><br />
<a href="nobless.php?act=nobless">Sim</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="home.php">Não</a>
<?php
	include("templates/private_footer.php");
?>