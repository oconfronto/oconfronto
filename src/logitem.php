<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
$player = check_user($db);
?>
<html>

<head>
	<title>O Confronto :: Logs de Itens</title>
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
	<link rel="stylesheet" type="text/css" href="static/css/style-a.css" />
	<link rel="stylesheet" type="text/css" href="static/css/boxover.css" />
	<script type="text/javascript" src="static/js/boxover.js"></script>
</head>

<body>


	<?php
	$read0 = $db->execute("update `user_log` set `status`='read' where `player_id`=? and `status`='unread'", [$player->id]);

	echo '<table width="100%">';
	echo '<tr><td align="center" bgcolor="#E1CBA4"><b>Logs de Itens</b></td></tr>';
	$query0 = $db->execute("select * from `log_item` where `player_id`=? order by `time` desc", [$player->id]);
	if ($query0->recordcount() > 0) {
		while ($trans = $query0->fetchrow()) {
			$read1 = $db->execute("update `log_item` set `status`='read' where `player_id`=? and `status`='unread' and `id`=?", [$player->id, $trans['id'] ?? null]);

			echo "<tr>";


			$auxiliar = ($trans['action'] ?? null) == "enviou" ? "para" : "de";

			$valortempo = time() -  $trans['time'];
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

			echo "<td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\"><div title=\"header=[Log] body=[" . $valortempo2 . " " . $auxiliar2 . ']">';
			if (($trans['action'] ?? null) == "devolveu") {
				echo '<font size="1">O administrador devolveu seu/sua <b>' . $trans['value'] . '</b> para <b><a href="profile.php?id=' . $trans['name2'] . '">' . $trans['name2'] . "</a></b></font></div></td>";
			} elseif (($trans['action'] ?? null) == "recuperou") {
				echo '<font size="1">O administrador recuperou seu/sua <b>' . $trans['value'] . '</b> que estava com <b><a href="profile.php?id=' . $trans['name2'] . '">' . $trans['name2'] . "</a></b></font></div></td>";
			} else {
				echo "<font size=\"1\">Você " . $trans['action'] . " " . $trans['value'] . " " . $auxiliar . ' <b><a href="profile.php?id=' . $trans['name2'] . '">' . $trans['name2'] . "</a></b>" . $trans['aditional'] . "</font></div></td>";
			}

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