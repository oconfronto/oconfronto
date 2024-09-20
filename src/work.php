<?php
include("lib.php");
define("PAGENAME", "Trabalhar");
$player = check_user($secret_key, $db);

include("checkbattle.php");
include("checkhp.php");

$totaltime = 0;
$counthours = $db->execute("select `worktime` from `work` where `start`>? and `player_id`=? and `status`!='a'", array(time() + 604800, $player->id));
while($hours = $counthours->fetchrow())
{
	$totaltime += $totaltime + $hours['worktime'];
}


	$query = $db->execute("select * from `reinos` where `id`=?", array($player->reino));
	$reino = $query->fetchrow();

	$bonnus = 0;
	if ($reino['worktime'] > time()) {
		$bonnus = $reino['work'];
	}
		if (($player->vip > time()) and ($bonnus < '0.15')) {
			$bonnus = '0.15';
		}

	if ($player->level >= 180){
		$profic = "Cavaleiro";
		$ganha = 20000 * (1 + $bonnus);
	} elseif ($player->level >= 160){
		$profic = "Escudeiro";
		$nextprofic = "Cavaleiro";
		$needlvl = 180;
		$ganha = 17000 * (1 + $bonnus);
	} elseif ($player->level >= 140){
		$profic = "Guarda";
		$nextprofic = "Escudeiro";
		$needlvl = 160;
		$ganha = 14200 * (1 + $bonnus);
	} elseif ($player->level >= 120){
		$profic = "Conselheiro";
		$nextprofic = "Guarda";
		$needlvl = 140;
		$ganha = 10300 * (1 + $bonnus);
	} elseif ($player->level >= 100){
		$profic = "Mensageiro";
		$nextprofic = "Conselheiro";
		$needlvl = 120;
		$ganha = 6500 * (1 + $bonnus);
	} elseif ($player->level >= 80){
		$profic = "Ferreiro";
		$nextprofic = "Mensageiro";
		$needlvl = 100;
		$ganha = 2900 * (1 + $bonnus);
	} elseif ($player->level >= 60){
		$profic = "Artesão";
		$nextprofic = "Ferreiro";
		$needlvl = 80;
		$ganha = 1000 * (1 + $bonnus);
	} elseif ($player->level >= 40){
		$profic = "Campones";
		$nextprofic = "Artesão";
		$needlvl = 60;
		$ganha = 450 * (1 + $bonnus);
	} elseif ($player->level >= 1){
		$profic = "Lenhador";
		$nextprofic = "Campones";
		$needlvl = 40;
		$ganha = 20 * (1 + $bonnus);
	}

	if ($_GET['act'] == cancel){	
		include("templates/private_header.php");
		echo "<fieldset>";
		echo "<legend><b>Trabalho</b></legend>";
		echo "Tem certeza que deseja abandonar seu trabalho? Se abandona-lo, não ganhará nada. ";
		echo "<a href=\"work.php?act=remove\">Desejo abandonar o trabalho</a>.";
		echo "</fieldset><br/><a href=\"home.php\">Principal</a>.";
		include("templates/private_footer.php");
		exit;
	}

	elseif ($_GET['act'] == remove){
		$query = $db->execute("update `work` set `status`='a' where `player_id`=? and `status`='t'", array($player->id));
		include("templates/private_header.php");
		echo "<fieldset>";
		echo "<legend><b>Trabalho</b></legend>";
		echo "Você abandonou seu trabalho.";
		echo "</fieldset><br/><a href=\"home.php\">Principal</a>.";
		include("templates/private_footer.php");
		exit;
	}


include("checkwork.php");

if (($_POST['time']) && ($_POST['submit']))  {


if ((!is_numeric($_POST['time'])) or ($_POST['time'] > 12)) {
	include("templates/private_header.php");
	echo "<fieldset>";
	echo "<legend><b>Trabalhar</b></legend>";
	echo "Um erro desconhecido ocorreu!";
	echo "</fieldset><br /><a href=\"work.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
}

if ((($player->reino != '2') and ($player->vip < time())) and ((($player->level < 80) and ($_POST['time'] > 8)) or (($player->level < 100) and ($_POST['time'] > 9)) or (($player->level < 120) and ($_POST['time'] > 10)) or (($player->level < 140) and ($_POST['time'] > 11)))){
	include("templates/private_header.php");
	echo "<fieldset>";
	echo "<legend><b>Trabalhar</b></legend>";
	echo "Você não pode trabalhar por tanto tempo.";
	echo "</fieldset><br /><a href=\"work.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
}

if ((($player->reino == '2') or ($player->vip > time())) and ((($player->level < 80) and ($_POST['time'] > 9)) or (($player->level < 100) and ($_POST['time'] > 10)) or (($player->level < 120) and ($_POST['time'] > 11)) or (($player->level < 140) and ($_POST['time'] > 12)))){
	include("templates/private_header.php");
	echo "<fieldset>";
	echo "<legend><b>Trabalhar</b></legend>";
	echo "Você não pode trabalhar por tanto tempo.";
	echo "</fieldset><br /><a href=\"work.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
}


	if (($player->tour == t) and ($setting->tournament != 'f')) {
		include("templates/private_header.php");
		echo "<fieldset>";
		echo "<legend><b>Trabalhar</b></legend>";
		echo "Você não pode trabalhar enquanto participa ou está inscrito em um torneio.";
		echo "</fieldset><br /><a href=\"work.php\">Voltar</a>.";
		include("templates/private_footer.php");
		exit;
	}

	if (($totaltime + $_POST['time']) > 72) {
		include("templates/private_header.php");
		echo "<fieldset>";
		echo "<legend><b>Trabalhar</b></legend>";
		echo "Você anda trabalhando demais. O máximo permido por semana é de 72 horas.<br/>Você ainda pode trabalhar por " . (72 - $totaltime) . "h esta semana.";
		echo "</fieldset><br /><a href=\"work.php\">Voltar</a>.";
		include("templates/private_footer.php");
		exit;
	}


			$insert['player_id'] = $player->id;
			$insert['start'] = time();
			$insert['worktype'] = $profic;
			$insert['worktime'] = $_POST['time'];
			$insert['gold'] = $ganha;
			$query = $db->autoexecute('work', $insert, 'INSERT');

		include("templates/private_header.php");
		echo "<fieldset>";
		echo "<legend><b>Trabalhar</b></legend>";
		echo "Você começou a trabalhar como <b>" . $profic . "</b>, com o salário de <b>" . $ganha . " por hora</b>.<br/>Restam <b>" . $_POST['time'] . " hora(s)</b> para terminar seu trabalho.";
		echo "</fieldset><br /><a href=\"home.php\">Principal</a>.";
		include("templates/private_footer.php");
		exit;
}

include("templates/private_header.php");

if ($player->level < 40){
echo "<div style=\"background-color:#EEA2A2; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">";
echo "<center><font size=\"1\"><b>Personagens de nível inferior a 40 ganham salários extremamente baixos para evitar fraudes.</b></font></center>";
echo "</div>";
} else {
	if ($player->reino == '2') {
		echo showAlert("<i>Você pode trabalhar por uma hora a mais, pelo fato de ser um membro do reino Eroda.</i>");
	} elseif ($player->vip > time()) {
		echo showAlert("<i>Você pode trabalhar por uma hora a mais, pelo fato de ser um membro vip.</i>");
	}
}

	$query = $db->execute("select * from `reinos` where `id`=?", array($player->reino));
	$reino = $query->fetchrow();

	if (($reino['worktime'] > time()) or ($player->vip > time())) {
		$valortempo = $reino['worktime'] - time();
		if ($valortempo < 60){
			$valortempo2 = $valortempo;
			$auxiliar2 = "segundo(s)";
		}else if($valortempo < 3600){
			$valortempo2 = floor($valortempo / 60);
			$auxiliar2 = "minuto(s)";
		}else if($valortempo < 86400){
			$valortempo2 = floor($valortempo / 3600);
			$auxiliar2 = "hora(s)";
		}else if($valortempo > 86400){
			$valortempo2 = floor($valortempo / 86400);
			$auxiliar2 = "dia(s)";
		}

		if (($player->vip > time()) and ($reino['work'] < '0.15')) {
			echo showAlert("<i>Sendo vip você também tem 15% de bônus salárial.</i>");
		} else {
			echo showAlert("<i>Membros do seu reino ainda terão " . ceil($reino['work'] * 100) . "% de bônus salárial por " . $valortempo2 . " " . $auxiliar2 . ".</i>");
		}
	}


echo "<form method=\"POST\" action=\"work.php\">";
echo "<fieldset style='background:url(images/bg-fieldset.png);'>";
echo "<legend><b>Trabalhar</b></legend>";
echo "<table width=\"100%\" border=\"0\">";
echo "<tr>";
echo "<td width=\"15%\"><b>Profissão:</b></td>";
echo "<td>" . $profic . ".<br/>";
	if ($player->level < 180){
	echo "<font size=\"1\">Ao atingir o nível " . $needlvl . " você será promovido a " . $nextprofic . ".</font></td>";
	}
echo "</tr><tr>";
echo "<td width=\"15%\"><b>Horas:</b></td>";
echo "<td><select name=\"time\">";
echo "<option value=\"1\" selected=\"selected\">1 hora</option>";
echo "<option value=\"2\">2 horas</option>";
echo "<option value=\"3\">3 horas</option>";
echo "<option value=\"4\">4 horas</option>";
echo "<option value=\"5\">5 horas</option>";
echo "<option value=\"6\">6 horas</option>";
echo "<option value=\"7\">7 horas</option>";

	if (($player->level >= 80) or ($player->reino == '2') or ($player->vip > time())) {
		echo "<option value=\"8\">8 horas</option>";
	}
	if (($player->level >= 100) or (($player->level >= 80) and (($player->reino == '2') or ($player->vip > time())))){
		echo "<option value=\"9\">9 horas</option>";
	}
	if (($player->level >= 120) or (($player->level >= 100) and (($player->reino == '2') or ($player->vip > time())))){
		echo "<option value=\"10\">10 horas</option>";
	}
	if (($player->level >= 140) or (($player->level >= 120) and (($player->reino == '2') or ($player->vip > time())))){
		echo "<option value=\"11\">11 horas</option>";
	}
	if (($player->level >= 140) and (($player->reino == '2') or ($player->vip > time()))){
		echo "<option value=\"12\">12 horas</option>";
	}

echo "</select>";

	if ($player->level < 80){
		if (($player->reino == '2') or ($player->vip > time())) {
			echo " <font size=\"1\">Apartir do nível 80 você poderá trabalhar por 9h.</font>";
		} else {
			echo " <font size=\"1\">Apartir do nível 80 você poderá trabalhar por 8h.</font>";
		}
	} elseif ($player->level < 100){
		if (($player->reino == '2') or ($player->vip > time())) {
			echo " <font size=\"1\">Apartir do nível 100 você poderá trabalhar por 10h.</font>";
		} else {
			echo " <font size=\"1\">Apartir do nível 100 você poderá trabalhar por 9h.</font>";
		}
	} elseif ($player->level < 120){
		if (($player->reino == '2') or ($player->vip > time())) {
			echo " <font size=\"1\">Apartir do nível 120 você poderá trabalhar por 11h.</font>";
		} else {
			echo " <font size=\"1\">Apartir do nível 120 você poderá trabalhar por 10h.</font>";
		}
	} elseif ($player->level < 140){
		if (($player->reino == '2') or ($player->vip > time())) {
			echo " <font size=\"1\">Apartir do nível 140 você poderá trabalhar por 12h.</font>";
		} else {
			echo " <font size=\"1\">Apartir do nível 140 você poderá trabalhar por 11h.</font>";
		}
	}

echo "</td></tr></table>";
echo "</fieldset>";

echo "<table width=\"100%\" border=\"0\">";
echo "<tr><td width=\"30%\"><input type=\"submit\" id='link' class='neg' name=\"submit\" value=\"Trabalhar\" /></td><td width=\"70%\" align=\"right\">";
echo "<font size=\"1\"><b>Salário:</b> " . $ganha . " moedas de ouro por hora.</font><br/><font size=\"1\">Você ainda pode trabalhar por " . (72 - $totaltime) . "h esta semana.</font>";

echo "</td></tr></table></form>";
echo "<br />";

echo "<table width=\"100%\">";
echo "<tr><td align=\"center\" bgcolor=\"#E1CBA4\"><b>últimos Trabalhos</b></td></tr>";
$query1 = $db->execute("select * from `work` where `player_id`=? and `status`!='t' order by `start` desc limit 10", array($player->id));
if ($query1->recordcount() > 0)
{
	while ($log1 = $query1->fetchrow())
	{
		$valortempo = time() - $log1['start'];
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

		echo "<tr>";
		if ($log1['status'] == 'a'){
		echo "<td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\"><div title=\"header=[" . $valortempo2 . " " . $auxiliar2 . "] body=[]\"><font size=\"1\">Você começou a trabalhar como " . $log1['worktype'] . " mas abandonou seu trabalho.</font></div></td>";
		}else{
		echo "<td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\"><div title=\"header=[" . $valortempo2 . " " . $auxiliar2 . "] body=[]\"><font size=\"1\">Você trabalhou como " . $log1['worktype'] . " por " . $log1['worktime'] . " horas e ganhou " . ($log1['worktime'] * $log1['gold']) . " moedas de ouro.</font></div></td>";
		}
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

include("templates/private_footer.php");
?>
