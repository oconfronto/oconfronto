<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Trabalhar");
$player = check_user($db);

include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkhp.php");

$totaltime = 0;
$counthours = $db->execute("select `hunttime` from `hunt` where `start`>? and `player_id`=? and `status`!='a'", [time() + 604800, $player->id]);
while ($hours = $counthours->fetchrow()) {
	$totaltime += $totaltime + $hours['hunttime'];
}

if (($_GET['act'] ?? null) == "cancel") {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset>";
	echo "<legend><b>Caça</b></legend>";
	echo "Tem certeza que deseja parar de caçar agora? Se abandoná-la, não ganhará nada. ";
	echo "<a href=\"hunt.php?act=remove\">Desejo abandonar a caça</a>.";
	echo '</fieldset><br/><a href="home.php">Principal</a>.';
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}


if (($_GET['act'] ?? null) == "remove") {
	$query = $db->execute("update `hunt` set `status`='a' where `player_id`=? and `status`='t'", [$player->id]);
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset>";
	echo "<legend><b>Caça</b></legend>";
	echo "Você abandonou sua caça.";
	echo '</fieldset><br/><a href="home.php">Principal</a>.';
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}


include(__DIR__ . "/checkwork.php");


if (($_POST['cacatime'] ?? null) && ($_POST['cacastart'] ?? null)) {

	if (!is_numeric($_POST['cacatime']) || ($_POST['cacatime'] ?? null) > 12) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset>";
		echo "<legend><b>Caçar</b></legend>";
		echo "Você precisa preencher todos os campos.";
		echo '</fieldset><br /><a href="hunt.php">Voltar</a>.';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	if ($player->reino != '2' && $player->vip < time() && ($player->level < 40 && ($_POST['cacatime'] ?? null) > 2 || $player->level < 60 && ($_POST['cacatime'] ?? null) > 2.5 || $player->level < 80 && ($_POST['cacatime'] ?? null) > 3 || $player->level < 120 && ($_POST['cacatime'] ?? null) > 3.5 || $player->level < 140 && ($_POST['cacatime'] ?? null) > 4)) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset>";
		echo "<legend><b>Caçar</b></legend>";
		echo "Você não pode caçar por tanto tempo.";
		echo '</fieldset><br /><a href="hunt.php">Voltar</a>.';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	if (($player->reino == '2' || $player->vip > time()) && ($player->level < 40 && ($_POST['cacatime'] ?? null) > 2.5 || $player->level < 60 && ($_POST['cacatime'] ?? null) > 3 || $player->level < 80 && ($_POST['cacatime'] ?? null) > 3.5 || $player->level < 120 && ($_POST['cacatime'] ?? null) > 4 || $player->level < 140 && ($_POST['cacatime'] ?? null) > 5)) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset>";
		echo "<legend><b>Caçar</b></legend>";
		echo "Você não pode caçar por tanto tempo.";
		echo '</fieldset><br /><a href="hunt.php">Voltar</a>.';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	if ($player->tour == "t" && $setting->tournament != 'f') {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset>";
		echo "<legend><b>Caçar</b></legend>";
		echo "Você não pode caçar enquanto participa ou está inscrito em um torneio.";
		echo '</fieldset><br /><a href="hunt.php">Voltar</a>.';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	if (($totaltime + $_POST['cacatime']) > 36) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset>";
		echo "<legend><b>Caçar</b></legend>";
		echo "Você anda trabalhando demais. O máximo permido por semana é de 36 horas.<br/>Você ainda pode caçar por " . (36 - $totaltime) . "h esta semana.";
		echo '</fieldset><br /><a href="hunt.php">Voltar</a>.';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	$checkmonster = $db->execute("select `id`, `username`, `mtexp` from `monsters` where `level`<=? and `evento`!='n' and `evento`!='t' order by `level` desc limit 1", [$player->level]);
	if ($checkmonster->recordcount() == 0) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset>";
		echo "<legend><b>Caçar</b></legend>";
		echo "Um erro desconhecido ocorreu!";
		echo '</fieldset><br /><a href="hunt.php">Voltar</a>.';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	$monster = $checkmonster->fetchrow();

	if (($monster['level'] ?? null) >= $player->level && ($monster['level'] ?? null) != 1) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset>";
		echo "<legend><b>Caçar</b></legend>";
		echo "Você só pode caçar monstros de nível mais baixo que o seu.";
		echo '</fieldset><br /><a href="hunt.php">Voltar</a>.';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}



	$insert['player_id'] = $player->id;
	$insert['start'] = time();
	$insert['hunttype'] = $monster['id'];
	$insert['hunttime'] = $_POST['cacatime'];
	$query = $db->autoexecute('hunt', $insert, 'INSERT');

	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset>";
	echo "<legend><b>Caçar</b></legend>";
	echo "Você está caçando: <b>" . $monster['username'] . "</b>.<br/>Média de <b>" . (($monster['mtexp']) * 20) . "</b> pontos de experiência hora.<br/>Você irá caçar por <b>" . $_POST['cacatime'] . " hora(s)</b>.";
	echo '</fieldset><br /><a href="home.php">Principal</a>.';
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}


include(__DIR__ . "/templates/private_header.php");

if ($player->reino == '2') {
	echo showAlert("<i>Você pode caçar por uma hora a mais, pelo fato de ser um membro do reino Eroda.</i>");
} elseif ($player->vip > time()) {
	echo showAlert("<i>Você pode caçar por uma hora a mais, pelo fato de ser um membro VIP.</i>");
}


echo '<form method="POST" action="hunt.php">';
echo "<fieldset>";
echo "<legend><b>Caçar</b></legend>";
echo '<table width="100%" border="0">';
echo "<tr>";
echo '<td width="15%"><b>Monstro:</b></td>';

$query = $db->execute("select `id`, `username` from `monsters` where `level`<=? and `evento`!='n' and `evento`!='t' order by `level` desc limit 1", [$player->level]);
echo "<td>";
$result = $query->fetchrow();
echo $result["username"] ?? null;
echo ".</td>";

echo "</tr><tr>";

echo '<td width="15%"><b>Tempo:</b></td>';
echo '<td><select name="cacatime">';
echo '<option value="0.5" selected="selected">30 minutos</option>';
echo '<option value="1">1 hora</option>';
echo '<option value="1.5">1 hora e 30 minutos</option>';
if ($player->level >= 40 || $player->reino == '2' || $player->vip > time()) {
	echo '<option value="2">2 horas</option>';
}

if ($player->level >= 60 || $player->level >= 40 && ($player->reino == '2' || $player->vip > time())) {
	echo '<option value="2.5">2 horas e 30 minutos</option>';
}

if ($player->level >= 80 || $player->level >= 60 && ($player->reino == '2' || $player->vip > time())) {
	echo '<option value="3">3 horas</option>';
}

if ($player->level >= 120 || $player->level >= 80 && ($player->reino == '2' || $player->vip > time())) {
	echo '<option value="3.5">3 horas e 30 minutos</option>';
}

if ($player->level >= 140 || $player->level >= 120 && ($player->reino == '2' || $player->vip > time())) {
	echo '<option value="4">4 horas</option>';
}

if ($player->level >= 140 && ($player->reino == '2' || $player->vip > time())) {
	echo '<option value="5">5 horas</option>';
}

echo "</select>";

if ($player->level < 40) {
	if ($player->reino == '2' || $player->vip > time()) {
		echo " <font size=\"1\">A partir do nível 40 você poderá caçar por 2h e 30 min.</font>";
	} else {
		echo " <font size=\"1\">A partir do nível 40 você poderá caçar por 2h.</font>";
	}
} elseif ($player->level < 60) {
	if ($player->reino == '2' || $player->vip > time()) {
		echo " <font size=\"1\">A partir do nível 60 você poderá caçar por 3h.</font>";
	} else {
		echo " <font size=\"1\">A partir do nível 60 você poderá caçar por 2h e 30 min.</font>";
	}
} elseif ($player->level < 80) {
	if ($player->reino == '2' || $player->vip > time()) {
		echo " <font size=\"1\">A partir do nível 80 você poderá caçar por 3h e 30 min.</font>";
	} else {
		echo " <font size=\"1\">A partir do nível 80 você poderá caçar por 3h.</font>";
	}
} elseif ($player->level < 120) {
	if ($player->reino == '2' || $player->vip > time()) {
		echo " <font size=\"1\">A partir do nível 120 você poderá caçar por 4h.</font>";
	} else {
		echo " <font size=\"1\">A partir do nível 120 você poderá caçar por 3h e 30 min.</font>";
	}
} elseif ($player->level < 140) {
	if ($player->reino == '2' || $player->vip > time()) {
		echo " <font size=\"1\">A partir do nível 140 você poderá caçar por 4h e 30 min.</font>";
	} else {
		echo " <font size=\"1\">A partir do nível 140 você poderá caçar por 4h.</font>";
	}
}

echo "</td></tr></table>";
echo "</fieldset>";

echo '<table width="100%" border="0">';
echo "<tr><td width=\"30%\"><input type=\"submit\" name=\"cacastart\" value=\"Começar a caça\" /></td><td width=\"70%\" align=\"right\">";
echo "<font size=\"1\">Você ainda pode caçar por " . (36 - $totaltime) . " horas esta semana.</font>";
echo "</td></tr></table></form>";
echo "<br />";


echo '<table width="100%">';
echo "<tr><td align=\"center\" bgcolor=\"#E1CBA4\"><b>últimas Caças</b></td></tr>";
$query1 = $db->execute("select * from `hunt` where `player_id`=? and `status`!='t' order by `start` desc limit 10", [$player->id]);
if ($query1->recordcount() > 0) {
	while ($log1 = $query1->fetchrow()) {
		$valortempo = time() - $log1['start'];
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

		$huntmonstername = $db->GetOne("select `username` from `monsters` where `id`=?", [$log1['hunttype'] ?? null]);
		$huntmonsterlevel = $db->GetOne("select `level` from `monsters` where `id`=?", [$log1['hunttype'] ?? null]);
		$huntmonstermtexp = $db->GetOne("select `mtexp` from `monsters` where `id`=?", [$log1['hunttype'] ?? null]);


		$expwin1 = $huntmonsterlevel * 6;
		$expwin2 = (($player->level - $huntmonsterlevel) > 0) ? $expwin1 - (($player->level - $huntmonsterlevel) * 3) : $expwin1 + (($player->level - $huntmonsterlevel) * 3);
		$expwin2 = ($expwin2 <= 0) ? 1 : $expwin2;
		$expwin3 = round(0.5 * $expwin2);
		$expwin = random_int(intval($expwin3), intval($expwin2));
		$goldwin = round(0.9 * $expwin);
		if ($setting->eventoouro > time()) {
			$goldwin = round($goldwin * 2);
		}

		$huntgold = ceil(($goldwin * 7) * $log1['hunttime']);

		echo "<tr>";
		if (($log1['status'] ?? null) == 'a') {
			echo "<td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\"><div title=\"header=[" . $valortempo2 . " " . $auxiliar2 . "] body=[]\"><font size=\"1\">Você começou a caçar " . $huntmonstername . " mas abandonou sua caça.</font></div></td>";
		} else {
			echo "<td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\"><div title=\"header=[" . $valortempo2 . " " . $auxiliar2 . "] body=[]\"><font size=\"1\">Você caçou " . $huntmonstername . " por " . $log1['hunttime'] . " horas e ganhou " . ((($huntmonstermtexp) * 20) * $log1['hunttime']) . " pontos de esperiência e " . $huntgold . " moedas de ouro.</font></div></td>";
		}

		echo "</tr>";
	}
} else {
	echo "<tr>";
	echo '<td class="off"><font size="1">Nenhum registro encontrado!</font></td>';
	echo "</tr>";
}

echo "</table>";

include(__DIR__ . "/templates/private_footer.php");
