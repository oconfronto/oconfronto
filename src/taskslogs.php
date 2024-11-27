<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
$player = check_user($db);
?>
<html>

<head>
	<title>O Confronto :: Logs de Tarefas</title>
	<link rel="icon" type="image/x-icon" href="static/favicon.ico">
	<link rel="stylesheet" type="text/css" href="static/css/style-a.css" />
	<link rel="stylesheet" type="text/css" href="static/css/boxover.css" />
	<script type="text/javascript" src="static/js/boxover.js"></script>
</head>

<body>


	<?php
	echo '<table width="100%">';
	echo "<tr><td align=\"center\" bgcolor=\"#E1CBA4\"><b>Tarefas Concluídas</b></td></tr>";
	$query0 = $db->execute("select * from `completed_tasks` where `player_id`=? order by `time` desc", [$player->id]);
	if ($query0->recordcount() > 0) {
		while ($gettsk = $query0->fetchrow()) {

			echo "<tr>";
			$valortempo = time() -  $gettsk['time'];
			if ($valortempo < 60) {
				$valortempo2 = $valortempo;
				$auxiliar2 = "segundo(s) atrás.";
			} elseif ($valortempo < 3600) {
				$valortempo2 = floor($valortempo / 60);
				$auxiliar2 = "minuto(s) atrás.";
			} elseif ($valortempo < 86400) {
				$valortempo2 = floor($valortempo / 3600);
				$auxiliar2 = "hora(s) atrás.";
			} elseif ($valortempo > 86400) {
				$valortempo2 = floor($valortempo / 86400);
				$auxiliar2 = "dia(s) atrás.";
			}

			$gettasks = $db->execute("select * from `tasks` where `id`=?", [$gettsk['task_id'] ?? null]);
			$task = $gettasks->fetchrow();

			if (($task['obj_type'] ?? null) == 'monster' && ($task['obj_extra'] ?? null) > 0) {
				$mname = $db->GetOne("select `username` from `monsters` where `id`=?", [$task['obj_value'] ?? null]);
				$pcento = $db->GetOne("select `value` from `monster_tasks` where `player_id`=? and `task_id`=?", [$player->id, $task['id'] ?? null]);
				$pcento = ceil(($pcento / $task['obj_extra']) * 100);
				$msg = "Matar " . $task['obj_extra'] . "x o monstro " . $mname . ".<br/>";
			} elseif (($task['obj_type'] ?? null) == 'monster' && ($task['obj_extra'] ?? null) == 0) {
				$pcento = ceil(($player->monsterkilled / $task['obj_value']) * 100);
				$msg = "Matar " . $task['obj_value'] . " monstros.<br/>";
			} elseif (($task['obj_type'] ?? null) == 'pvp' && ($task['obj_extra'] ?? null) == 0) {
				$pcento = ceil(($player->kills / $task['obj_value']) * 100);
				$msg = "Matar " . $task['obj_value'] . " usuários.<br/>";
			} elseif (($task['obj_type'] ?? null) == 'level') {
				$pcento = ceil(($player->level / $task['obj_value']) * 100);
				$msg = "Alcançar o nível " . $task['obj_value'] . ".<br/>";
			}


			if (($task['win_type'] ?? null) == 'gold') {
				$win = "<b>Recompensa:</b> " . $task['win_value'] . " moedas de ouro.<br/>";
			} elseif (($task['win_type'] ?? null) == 'exp') {
				$win = "<b>Recompensa:</b> " . $task['win_value'] . " pontos de experiência.<br/>";
			} elseif (($task['win_type'] ?? null) == 'item') {
				$itname = $db->GetOne("select `name` from `blueprint_items` where `id`=?", [$task['win_value'] ?? null]);
				$win = "<b>Recompensa:</b> " . $itname . ".<br/>";
			}


			echo "<td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\"><div title=\"header=[Log] body=[" . $valortempo2 . " " . $auxiliar2 . ']">';
			echo '<font size="1">' . $msg . "" . $win . "</font></div></td>";
			echo "</tr>";
		}
	} else {
		echo "<tr>";
		echo '<td class="off"><font size="1">Nenhum registro encontrado!</font></td>';
		echo "</tr>";
	}

	echo "</table>";
	echo "</body>";
	echo "</html>";
	?>