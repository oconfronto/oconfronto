<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Tutorial");
$player = check_user($secret_key, $db);

if ($_GET['skip'] == true)
{
$query = $db->execute("update `pending` set `pending_status`=90 where `pending_id`=2 and `player_id`=?", array($player->id));
header("Location: home.php");
exit;
}

include(__DIR__ . "/templates/private_header.php");

$numero = $_GET['act'] ?: 1;

echo'<br/><center><img src="static/images/tutorial/' . $numero . '.png" border="0"></center>';


	echo '<table width="100%" border="0"><tr>';
	echo '<td width="50%">';
		if ($numero == 1){
			echo'<a href="tutorial.php?skip=true"><img src="static/images/tutorial/skip.png" border="0"></a>';
		}else{
			echo'<a href="tutorial.php?act=' . ($numero - 1) . '"><img src="static/images/tutorial/previous.png" border="0"></a>';
		}
  
	echo "</td>";
	echo '<td width="50%" align="right">';
		if ($numero == 14){
			echo'<a href="tutorial.php?skip=true"><img src="static/images/tutorial/end.png" border="0"></a>';
		}else{
			echo'<a href="tutorial.php?act=' . ($numero + 1) . '"><img src="static/images/tutorial/next.png" border="0"></a>';
		}
  
	echo "</td>";
	echo "</tr></table>";

include(__DIR__ . "/templates/private_footer.php");
?>
