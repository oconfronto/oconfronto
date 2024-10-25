<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
$player = check_user($db);
?>
<html>

<head>
	<title>O Confronto :: Logs de Amigos</title>
	<link rel="icon" type="image/x-icon" href="static/favicon.ico">
	<?php
	$checknocur = $db->execute("select * from `other` where `value`=? and `player_id`=?", ["cursor", $player->id]);
	if ($checknocur->recordcount() > 0) {
		echo '<link rel="stylesheet" type="text/css" href="css/private_style_2.css" />';
	} else {
		echo '<link rel="stylesheet" type="text/css" href="css/private_style_1.css" />';
	}
	?>
	<link rel="stylesheet" type="text/css" href="static/css/boxover.css" />
	<script type="text/javascript" src="static/js/boxover.js"></script>
</head>

<body>


	<?php
	echo '<table width="100%">';
	echo '<tr><td align="center" bgcolor="#E1CBA4"><b>Logs de Amigos</b></td></tr>';
	$query0 = $db->execute("select log_friends.log, log_friends.time from `log_friends`, `friends` where friends.uid=? and log_friends.fname=friends.fname order by log_friends.time desc", [$player->id]);
	if ($query0->recordcount() > 0) {
		while ($trans = $query0->fetchrow()) {

			echo "<tr>";
			$valortempo = time() -  $trans['time'];
			if ($valortempo < 60) {
				$valortempo2 = $valortempo;
				$auxiliar2 = "segundo(s) atrás.";
			} elseif ($valortempo < 3600) {
				$valortempo2 = ceil($valortempo / 60);
				$auxiliar2 = "minuto(s) atrás.";
			} elseif ($valortempo < 86400) {
				$valortempo2 = ceil($valortempo / 3600);
				$auxiliar2 = "hora(s) atrás.";
			} elseif ($valortempo > 86400) {
				$valortempo2 = ceil($valortempo / 86400);
				$auxiliar2 = "dia(s) atrás.";
			}

			echo "<td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\"><div title=\"header=[Log] body=[" . $valortempo2 . " " . $auxiliar2 . ']">';
			echo '<font size="1">' . $trans['log'] . "</font></div></td>";
			echo "</tr>";
		}
	} else {
		echo "<tr>";
		echo '<td class="off"><font size="1">Nenhum registro encontrado!</font></td>';
		echo "</tr>";
	}

	echo "</table>";
	echo "<center><font size=\"1\">Exibindo todos os logs dos últimos 7 dias.</font></center>";
	echo "</body>";
	echo "</html>";
	?>