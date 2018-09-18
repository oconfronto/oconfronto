<?php
	include("lib.php");
	$player = check_user($secret_key, $db);
?>
<html>
<head>
<title>O Confronto :: Logs de Tarefas</title>
<link rel="stylesheet" type="text/css" href="css/style-a.css" />
<link rel="stylesheet" type="text/css" href="css/boxover.css" />
<script type="text/javascript" src="js/boxover.js"></script>
</head>

<body>


<?php
echo "<table width=\"100%\">";
echo "<tr><td align=\"center\" bgcolor=\"#E1CBA4\"><b>Tarefas Concluídas</b></td></tr>";
$query0 = $db->execute("select * from `completed_tasks` where `player_id`=? order by `time` desc", array($player->id));
if ($query0->recordcount() > 0)
{
	while ($gettsk = $query0->fetchrow())
	{

		echo "<tr>";
		$valortempo = time() -  $gettsk['time'];
		if ($valortempo < 60){
		$valortempo2 = $valortempo;
		$auxiliar2 = "segundo(s) atrás.";
		}else if($valortempo < 3600){
		$valortempo2 = floor($valortempo / 60);
		$auxiliar2 = "minuto(s) atrás.";
		}else if($valortempo < 86400){
		$valortempo2 = floor($valortempo / 3600);
		$auxiliar2 = "hora(s) atrás.";
		}else if($valortempo > 86400){
		$valortempo2 = floor($valortempo / 86400);
		$auxiliar2 = "dia(s) atrás.";
		}

			$gettasks = $db->execute("select * from `tasks` where `id`=?", array($gettsk['task_id']));
			$task = $gettasks->fetchrow();

						if (($task['obj_type'] == 'monster') and ($task['obj_extra'] > 0)){
							$mname = $db->GetOne("select `username` from `monsters` where `id`=?", array($task['obj_value']));
							$pcento = $db->GetOne("select `value` from `monster_tasks` where `player_id`=? and `task_id`=?", array($player->id, $task['id']));
							$pcento = ceil(($pcento / $task['obj_extra']) * 100);
							$msg = "Matar " . $task['obj_extra'] . "x o monstro " . $mname . ".<br/>";
						}elseif (($task['obj_type'] == 'monster') and ($task['obj_extra'] == 0)){
							$pcento = ceil(($player->monsterkilled / $task['obj_value']) * 100);
							$msg = "Matar " . $task['obj_value'] . " monstros.<br/>";
						}elseif (($task['obj_type'] == 'pvp') and ($task['obj_extra'] == 0)){
							$pcento = ceil(($player->kills / $task['obj_value']) * 100);
							$msg = "Matar " . $task['obj_value'] . " usuários.<br/>";
						}elseif ($task['obj_type'] == 'level'){
							$pcento = ceil(($player->level / $task['obj_value']) * 100);
							$msg = "Alcançar o nível " . $task['obj_value'] . ".<br/>";
						}


						if ($task['win_type'] == 'gold'){
							$win = "<b>Recompensa:</b> " . $task['win_value'] . " moedas de ouro.<br/>";
						}elseif ($task['win_type'] == 'exp'){
							$win = "<b>Recompensa:</b> " . $task['win_value'] . " pontos de experiência.<br/>";
						}elseif ($task['win_type'] == 'item'){
							$itname = $db->GetOne("select `name` from `blueprint_items` where `id`=?", array($task['win_value']));
							$win = "<b>Recompensa:</b> " . $itname . ".<br/>";
						}


		echo "<td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\"><div title=\"header=[Log] body=[" . $valortempo2 . " " . $auxiliar2 . "]\">";
		echo "<font size=\"1\">" . $msg . "" . $win . "</font></div></td>";
		echo "</tr>";
	}
}
else
{
	echo "<tr>";
	echo "<td class=\"off\"><font size=\"1\">Nenhum registro encontrado!</font></td>";
	echo "</tr>";
}
echo "</table>";
echo "</body>";
echo "</html>";
?>