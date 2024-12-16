<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
$acc = check_acc($db);
?>
<html>

<head>
	<title>O Confronto :: Logs da Conta</title>
	<link rel="stylesheet" type="text/css" href="static/css/style-a.css" />
	<link rel="stylesheet" type="text/css" href="static/css/boxover.css" />
	<script type="text/javascript" src="static/js/boxover.js"></script>
	<link rel="icon" type="image/x-icon" href="static/favicon.ico">

	<style>
		@font-face {
			font-family: 'Pixelify Sans Without Digits';
			src: url('static/fonts/PixelifySans.ttf') format('truetype');
			/* Include all characters except digits */
			unicode-range: U+0000-002F, U+003A-FFFF;
		}

		* {
			font-family: 'Pixelify Sans Without Digits', monospace, sans-serif !important;
		}
	</style>
</head>

<body>


	<?php
	$read0 = $db->execute("update `account_log` set `status`='read' where `player_id`=? and `status`='unread'", [$acc->id]);

	echo '<table width="100%">';
	echo '<tr><td align="center" bgcolor="#E1CBA4"><b>Logs da Conta</b></td></tr>';
	$query0 = $db->execute("select `msg`, `status`, `time` from `account_log` where `player_id`=? order by `time` desc", [$acc->id]);
	if ($query0->recordcount() > 0) {
		while ($log0 = $query0->fetchrow()) {

			$valortempo = time() - $log0['time'];
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

			echo "<tr>";
			echo "<td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\"><div title=\"header=[Log] body=[" . $valortempo2 . " " . $auxiliar2 . ']"><font size="1">' . $log0['msg'] . "</font></div></td>";
			echo "</tr>";
		}
	} else {
		echo "<tr>";
		echo '<td class="off"><font size="1">Nenhum registro encontrado!</font></td>';
		echo "</tr>";
	}

	echo "</table>";
	echo "<center><font size=\"1\">Exibindo todos os logs dos últimos 14 dias.</font></center>";
	echo "</body>";
	echo "</html>";
	?>