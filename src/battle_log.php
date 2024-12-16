<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
$player = check_user($db);
?>
<html>

<head>
	<title>O Confronto :: Log de Batalha</title>
	<link rel="icon" type="image/x-icon" href="static/favicon.ico">
	<link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&display=swap" rel="stylesheet">
	<style>
		body {
			font-family: 'Pixelify Sans', sans-serif;
		}
	</style>
	<style type="text/css">
		body {
			margin: 0em auto;
			padding: 0em;
			font-size: 0.8em;
			background: url("../images/dot.jpg") center top repeat-x #FFFFFF;

		}

		a:link {
			color: #8C6B2F;
			text-decoration: none;
		}

		a:visited {
			color: #745927;
			text-decoration: none;
		}

		a:hover {
			color: #745927;
			text-decoration: underline;
		}
	</style>


	<script language="JavaScript">
		function divDown() {
			document.getElementById('logdebatalha').scrollTop += 1000000;
		}
	</script>

</head>

<body onload="divDown()">
	<?php

	if (!($_GET['id'] ?? null)) {
		echo "Um erro ocorreu.";
		echo "</body>";
		echo "</html>";
		exit;
	}

	$query = $db->execute("select * from `log_battle` where `id`=? and `player_id`=?", [$_GET['id'] ?? null, $player->id]);
	if ($query->recordcount() < 1) {
		echo "Log não encontrado.";
		echo "</body>";
		echo "</html>";
		exit;
	}

	$log = $query->fetchrow();
	echo '<br/><center><div id="logdebatalha" align="left" class="scroll" style="background-color:#FFFDE0; overflow: auto; width:95%; height:270px; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';
	echo $log['log'] ?? null;
	echo "</div></center>";
	echo "</body>";
	echo "</html>";
	exit;
	?>