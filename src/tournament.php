<?php

include("lib.php");
define("PAGENAME", "Torneio");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkhp.php");
include("checkwork.php");


if ($player->level < 100){
$tier = 1;
} elseif (($player->level > 99) and ($player->level < 200)){
$tier = 2;
} elseif (($player->level > 199) and ($player->level < 300)){
$tier = 3;
} elseif (($player->level > 299) and ($player->level < 400)){
$tier = 4;
} elseif (($player->level > 399) and ($player->level < 1000)){
$tier = 5;
}

$unc1 = "tournament_" . $tier . "_" . $player->serv . "";
$unc2 = "tour_lvl1_" . $tier . "_" . $player->serv . "";
$unc3 = "tour_lvl2_" . $tier . "_" . $player->serv . "";
$unc4 = "tour_members_" . $tier . "_" . $player->serv . "";
$unc5 = "tour_price_" . $tier . "_" . $player->serv . "";
$unc6 = "tour_win_" . $tier . "_" . $player->serv . "";
$unc7 = "end_tour_" . $tier . "_" . $player->serv . "";
$unc8 = "last_tour_" . $tier . "_" . $player->serv . "";


if ($setting->$unc1 == t)
{

	if (time() > $setting->$unc7){
		$query = $db->execute("update `settings` set `value`='y' where `name`='$unc1'");
		header("Location: tournament.php");
	}

	if ($_POST['join'])
	{
		$checasejaestainscrito = $db->execute("select `id` from `players` where `id`=? and `tour`='t' and `serv`=? and `tier`=?", array($player->id, $player->serv, $tier));

		if ($checasejaestainscrito->recordcount() > 0){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Torneio</b></legend>\n";
		echo "Você já está inscrito no torneio.";
		echo "</fieldset>";
		echo "<br/>";
		echo "<a href=\"tournament.php\">Voltar</a>.";
		include("templates/private_footer.php");
		exit;
		}

		if ($player->level < $setting->$unc2){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Torneio</b></legend>\n";
		echo "Seu nível é muito baixo. (Nível minimo: " . $setting->$unc2 . ")";
		echo "</fieldset>";
		echo "<br/>";
		echo "<a href=\"tournament.php\">Voltar</a>.";
		include("templates/private_footer.php");
		exit;
		}

		if ($player->level > $setting->$unc3){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Torneio</b></legend>\n";
		echo "Seu nível é muito alto. (Nível máximo: " . $setting->$unc3 . ")";
		echo "</fieldset>";
		echo "<br/>";
		echo "<a href=\"tournament.php\">Voltar</a>.";
		include("templates/private_footer.php");
		exit;
		}

		if ($player->gold < $setting->$unc5){
		include("templates/private_header.php");
		echo "<fieldset><legend><b>Torneio</b></legend>\n";
		echo "Você não possui ouro suficiente. (" . $setting->$unc5 . " de ouro)";
		echo "</fieldset>";
		echo "<br/>";
		echo "<a href=\"tournament.php\">Voltar</a>.";
		include("templates/private_footer.php");
		exit;
		}

		$query7895 = $db->execute("update `players` set `gold`=?, `tour`='t', `killed`=0, `tier`=? where `id`=?", array($player->gold - $setting->$unc5, $tier, $player->id));
		$query = $db->execute("update `settings` set `value`=? where `name`='$unc4'", array($setting->$unc4 + 1));

		include("templates/private_header.php");
		echo "<fieldset><legend><b>Torneio</b></legend>\n";
		echo "Agora você está participando do torneio.";
		echo "</fieldset>";
		echo "<br/>";
		echo "<a href=\"tournament.php\">Voltar</a>.";
		include("templates/private_footer.php");
		exit;
	}

	include("templates/private_header.php");

	echo "<fieldset><legend><b>Torneio</b></legend>\n";
	echo "<table>";
	echo "<tr>";
	echo "<td><b>Prêmio:</b></td>";
	echo "<td>" . $setting->$unc6 . "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td><b>NÂº de participantes:</b></td>";
	echo "<td>" . $setting->$unc4 . "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td><b>Preço da inscrição:</b></td>";
	echo "<td>" . $setting->$unc5 . " de ouro</td>";
	echo "</tr>";

	echo "<tr>";
	$end = $setting->$unc7 - time();
	$days = floor($end/60/60/24);
	$hours = $end/60/60%24;
	$minutes = $end/60%60;
	$comecaem = "$days dia(s) $hours hora(s) $minutes minuto(s)";
	$nova_data = date("d/m/Y G:i", $setting->$unc7);
	echo "<td><b>O torneio irá começar em:</b></td>";
	echo "<td>" . $comecaem . " <a href=\"tournament.php\">Atualizar</a><br/><b>Dia:</b> " . $nova_data . "</td>";
	echo "</tr>";

	echo "</table>";
	echo "</fieldset>";
	echo "<br/>";
	echo "<form method=\"POST\" action=\"tournament.php\">";
	echo "<input type=\"submit\" name=\"join\" value=\"Inscrever-se no torneio\"><font size=\"1\">&nbsp;&nbsp;&nbsp;&nbsp;<b>Apenas usuários entre os níveis " . $setting->$unc2 . " á " . $setting->$unc3 . " podem se inscrever.</b></font>";
	echo "</form>";

	include("templates/private_footer.php");
	exit;
	}elseif ($setting->$unc1 == y){

		$chekka1 = $db->execute("select `id` from `players` where `tour`='t' and `serv`=? and `tier`=?", array($player->serv, $tier));

		if ($chekka1->recordcount() < 5){
			while($aviso = $chekka1->fetchrow()) {
			$devolveouropago = $db->execute("update `players` set `bank`=`bank`+? where `id`=?", array($setting->$unc5, $aviso['id']));
			$logmsg = "O torneio para seu nível foi cancelado, pois não foram inscritos participantes suficientes.<br/>O ouro pago, " . $setting->$unc5 . ", foi depositado na sua conta bancária.";
			addlog($aviso['id'], $logmsg, $db);
			}

			$nobodywinmsg = "Ninguém";

			$query = $db->execute("update `players` set `tour`='f', `killed`=0 where `serv`=? and `tier`=?", array($player->serv, $tier));
			$query = $db->execute("update `settings` set `value`=? where `name`='$unc8'", array($nobodywinmsg));
			$query = $db->execute("update `settings` set `value`=0 where `name`='$unc4'");
			$query = $db->execute("update `settings` set `value`=0 where `name`='$unc7'");
			$query = $db->execute("update `settings` set `value`='f' where `name`='$unc1'");

			header("Location: tournament.php");
			exit;
		}


		$chekka2 = $db->execute("select `id`, `username` from `players` where `killed`>0 and `tour`='t' and `serv`=? and `tier`=?", array($player->serv, $tier));
      		$num1 = $chekka1->recordcount();
		$num2 = $chekka2->recordcount();
       		$chekka3 = $num1 - $num2;
		$winner = $chekka1->fetchrow();

		if ($chekka3 < 2){
			if ($chekka3 == 1){

				$wqrpgfdin = $db->execute("select `id`, `username`, `bank`, `killed` from `players` where `tour`='t' and `serv`=? and `tier`=? and `killed`=0 limit 0,1", array($player->serv, $tier));
       				$winneb1 = $wqrpgfdin->fetchrow();

				$query = $db->execute("update `players` set `bank`=? where `id`=?", array($winneb1['bank'] + $setting->$unc6, $winneb1['id']));
				$logmsg = "Você venceu o torneio e <b>" . $setting->$unc6 . " de ouro</b> foram depositados na sua conta bancária.";
				addlog($winneb1['id'], $logmsg, $db);

				$insert['player_id'] = $winneb1['id'];   	  
				$insert['medalha'] = "Guerreiro Exemplar";
				$insert['motivo'] = "Venceu o torneio de usuários de nível " . $setting->$unc2 . " á " . $setting->$unc3 . ".";
				$query = $db->autoexecute('medalhas', $insert, 'INSERT');

				$insert['fname'] = $player->username;
				$insert['log'] = "<a href=\"profile.php?id=" . $player->username . "\">" . $player->username . "</a> venceu o torneio de usuários de nível " . $setting->$unc2 . " á " . $setting->$unc3 . ".";
				$insert['time'] = time();
				$query = $db->autoexecute('log_friends', $insert, 'INSERT');


				$query = $db->execute("update `players` set `tour`='f', `killed`=0 where `serv`=? and `tier`=?", array($player->serv, $tier));
				$query = $db->execute("update `settings` set `value`=? where `name`='$unc8'", array($winneb1['username']));
				$query = $db->execute("update `settings` set `value`=0 where `name`='$unc4'");
				$query = $db->execute("update `settings` set `value`=0 where `name`='$unc7'");
				$query = $db->execute("update `settings` set `value`='f' where `name`='$unc1'");

				header("Location: tournament.php");
				exit;
				}

				elseif ($chekka3 == 0){
				$wqweqwwin = $db->execute("select `id`, `username`, `bank`, `killed` from `players` where `tour`='t' and `serv`=? and `tier`=? order by `killed` desc limit 0,1", array($player->serv, $tier));
       				$winneb2 = $wqweqwwin->fetchrow();

				$query = $db->execute("update `players` set `bank`=? where `id`=?", array($winneb2['bank'] + $setting->$unc6, $winneb2['id']));
				$logmsg = "Você venceu o torneio e <b>" . $setting->$unc6 . " de ouro</b> foram depositados na sua conta bancária.";
				addlog($winneb2['id'], $logmsg, $db);

				$insert['player_id'] = $winneb2['id'];   	  
				$insert['medalha'] = "Guerreiro Exemplar";
				$insert['motivo'] = "Venceu o torneio de usuários de nível " . $setting->$unc2 . " á " . $setting->$unc3 . ".";
				$query = $db->autoexecute('medalhas', $insert, 'INSERT');

				$query = $db->execute("update `players` set `tour`='f', `killed`=0 where `serv`=? and `tier`=?", array($player->serv, $tier));
				$query = $db->execute("update `settings` set `value`=? where `name`='$unc8'", array($winneb2['username']));
				$query = $db->execute("update `settings` set `value`=0 where `name`='$unc4'");
				$query = $db->execute("update `settings` set `value`=0 where `name`='$unc7'");
				$query = $db->execute("update `settings` set `value`='f' where `name`='$unc1'");

				header("Location: tournament.php");
				exit;
				}
		}	


		include("templates/private_header.php");

			echo "<fieldset><legend><b>Torneio</b></legend>\n";
			echo "<table>";

			echo "<tr>";
			echo "<td><b>Prêmio:</b></td>";
			echo "<td>" . $setting->$unc6 . "</td>";
			echo "</tr>";

			echo "<tr>";
			echo "<td><b>Usuários inscritos:</b></td>";
			echo "<td>" . $setting->$unc4 . "</td>";
			echo "</tr>";

			echo "<tr>";
			echo "<td><b>Usuários eliminados:</b></td>";
			$userseliminados = $db->execute("select `id` from `players` where `tour`='t' and `killed`>0 and `serv`=? and `tier`=?", array($player->serv, $tier));
			echo "<td>" . $userseliminados->recordcount() . "</td>";
			echo "</tr>";

			echo "<tr>";
			echo "<td><b>Usuários restantes:</b></td>";
			echo "<td>" . ($setting->$unc4 - $userseliminados->recordcount()) . "</td>";
			echo "</tr>";


			echo "</table>";
			echo "</fieldset>";
			echo "<br/>";


		echo "<fieldset><legend><b>Participantes</b></legend>\n";
		echo "<table>";
			$sdfsdfoiewfwe = $db->execute("select `id`, `username`, `level`, `killed`, `ban`, `hp` from `players` where `tour`='t' and `serv`=? and `tier`=? order by `level` desc", array($player->serv, $tier));
       			while($member = $sdfsdfoiewfwe->fetchrow())
			{
			if ($member['ban'] > time()){
				$logmsg = "Você foi desclassificado do torneio pois estava banido.";
				addlog($member['id'], $logmsg, $db);
			$query = $db->execute("update `players` set `tour`='f' where `id`=?", array($member['id']));
			}elseif (($member['hp'] < 1) and ($member['killed'] == 0)){
				$logmsg = "Você foi desclassificado do torneio pois estava morto quando ele começou.";
				addlog($member['id'], $logmsg, $db);
			$query = $db->execute("update `players` set `tour`='f' where `id`=?", array($member['id']));
			}elseif ($member['level'] > $setting->$unc3){
				$logmsg = "Você foi desclassificado do torneio pois seu nível estava acima do permitido.";
				addlog($member['id'], $logmsg, $db);
			$query = $db->execute("update `players` set `tour`='f' where `id`=?", array($member['id']));
			}else{
       			echo "<tr>";
   			echo "<td><b>Usuário:</b> " . $member['username'] . "<td>";
			echo "<td><b>Nível:</b> " . $member['level'] . "<td>";
			if ($member['killed'] > 0){
			echo "<td><b>Status:</b> <font color=\"red\">Eliminado</font><td>";
			}elseif (($member['hp'] < 1) and ($member['killed'] == 0)){
			echo "<td><b>Status:</b> <font color=\"red\">Eliminado</font><td>";
			}else{
			echo "<td><b>Opções:</b> <a href=\"mail.php?act=compose&amp;to=" . $member['username'] . "\">Mensagem</a> | <a href=\"battle.php?act=attack&amp;username=" . $member['username'] . "\">Lutar</a><td>";
			}
			echo "</tr>";
			}
			}
		echo "</table>";
		echo "</fieldset>";
			$checwerwasaao = $db->execute("select `username` from `players` where `tour`='t' and `id`=? and `serv`=? and `tier`=?", array($player->id, $player->serv, $tier));
      			if ($checwerwasaao->recordcount() < 1){
				$tourstatus = "Você não se inscreveu.";
			}else{
				if ($player->killed > 0){
				$tourstatus = "Você foi eliminado.";
				}else{
				$tourstatus = "Você está participando.";
				}
			}
		echo "<b>Seu status no torneio:</b> " . $tourstatus . "";
		echo "<br/><br/>";
		echo "<a href=\"tournament.php\">Voltar</a>.";
		include("templates/private_footer.php");
		exit;
	}else{
	include("templates/private_header.php");

	$lastinfo1 = "last_tour_1_" . $player->serv . "";
	$lastinfo2 = "last_tour_2_" . $player->serv . "";
	$lastinfo3 = "last_tour_3_" . $player->serv . "";
	$lastinfo4 = "last_tour_4_" . $player->serv . "";
	$lastinfo5 = "last_tour_5_" . $player->serv . "";

	$wininfo1 = "tour_win_1_" . $player->serv . "";
	$wininfo2 = "tour_win_2_" . $player->serv . "";
	$wininfo3 = "tour_win_3_" . $player->serv . "";
	$wininfo4 = "tour_win_4_" . $player->serv . "";
	$wininfo5 = "tour_win_5_" . $player->serv . "";

	$endinffo1 = "tournament_1_" . $player->serv . "";
	$endinffo2 = "tournament_2_" . $player->serv . "";
	$endinffo3 = "tournament_3_" . $player->serv . "";
	$endinffo4 = "tournament_4_" . $player->serv . "";
	$endinffo5 = "tournament_5_" . $player->serv . "";

	echo "<fieldset><legend><b>Torneio níveis 1 - 99</b></legend>\n";
	if (($setting->$endinffo1 == y) or ($setting->$endinffo1 == t)){
	echo "<br/><center><b>Este torneio ainda não acabou.</b></center><br/>";
	}else{
	echo "<table>";
	echo "<tr>";
	echo "<td><b>último vencedor:</b></td>";
	if (($setting->$lastinfo1 == 'Ninguém') or ($setting->$lastinfo1 == NULL)){
	echo "<td>Ninguém. <b>Torneio cancelado.</b></td>";
	}else{
	echo "<td>" . $setting->$lastinfo1 . "</td>";
	}
	echo "</tr>";
	echo "<tr>";
	echo "<td><b>Prêmio recebido:</b></td>";
	if (($setting->$lastinfo1 == 'Ninguém') or ($setting->$lastinfo1 == NULL)){
	echo "<td>0</td>";
	}else{
	echo "<td>" . $setting->$wininfo1 . "</td>";
	}
	echo "</tr>";
	echo "</table>";
	}
	echo "</fieldset>";

	echo "<br/><fieldset><legend><b>Torneio níveis 100 - 199</b></legend>\n";
	if (($setting->$endinffo2 == y) or ($setting->$endinffo2 == t)){
	echo "<br/><center><b>Este torneio ainda não acabou.</b></center><br/>";
	}else{
	echo "<table>";
	echo "<tr>";
	echo "<td><b>último vencedor:</b></td>";
	if (($setting->$lastinfo2 == 'Ninguém') or ($setting->$lastinfo2 == NULL)){
	echo "<td>Ninguém. <b>Torneio cancelado.</b></td>";
	}else{
	echo "<td>" . $setting->$lastinfo2 . "</td>";
	}
	echo "</tr>";
	echo "<tr>";
	echo "<td><b>Prêmio recebido:</b></td>";
	if (($setting->$lastinfo2 == 'Ninguém') or ($setting->$lastinfo2 == NULL)){
	echo "<td>0</td>";
	}else{
	echo "<td>" . $setting->$wininfo2 . "</td>";
	}
	echo "</tr>";
	echo "</table>";
	}
	echo "</fieldset>";

	echo "<br/><fieldset><legend><b>Torneio níveis 200 - 299</b></legend>\n";
	if (($setting->$endinffo3 == y) or ($setting->$endinffo3 == t)){
	echo "<br/><center><b>Este torneio ainda não acabou.</b></center><br/>";
	}else{
	echo "<table>";
	echo "<tr>";
	echo "<td><b>último vencedor:</b></td>";
	if (($setting->$lastinfo3 == 'Ninguém') or ($setting->$lastinfo3 == NULL)){
	echo "<td>Ninguém. <b>Torneio cancelado.</b></td>";
	}else{
	echo "<td>" . $setting->$lastinfo3 . "</td>";
	}
	echo "</tr>";
	echo "<tr>";
	echo "<td><b>Prêmio recebido:</b></td>";
	if (($setting->$lastinfo3 == 'Ninguém') or ($setting->$lastinfo3 == NULL)){
	echo "<td>0</td>";
	}else{
	echo "<td>" . $setting->$wininfo3 . "</td>";
	}
	echo "</tr>";
	echo "</table>";
	}
	echo "</fieldset>";

	echo "<br/><fieldset><legend><b>Torneio níveis 300 - 399</b></legend>\n";
	if (($setting->$endinffo4 == y) or ($setting->$endinffo4 == t)){
	echo "<br/><center><b>Este torneio ainda não acabou.</b></center><br/>";
	}else{
	echo "<table>";
	echo "<tr>";
	echo "<td><b>último vencedor:</b></td>";
	if (($setting->$lastinfo4 == 'Ninguém') or ($setting->$lastinfo4 == NULL)){
	echo "<td>Ninguém. <b>Torneio cancelado.</b></td>";
	}else{
	echo "<td>" . $setting->$lastinfo4 . "</td>";
	}
	echo "</tr>";
	echo "<tr>";
	echo "<td><b>Prêmio recebido:</b></td>";
	if (($setting->$lastinfo4 == 'Ninguém') or ($setting->$lastinfo4 == NULL)){
	echo "<td>0</td>";
	}else{
	echo "<td>" . $setting->$wininfo4 . "</td>";
	}
	echo "</tr>";
	echo "</table>";
	}
	echo "</fieldset>";


	echo "<br/><fieldset><legend><b>Torneio níveis 400 - 999</b></legend>\n";
	if (($setting->$endinffo5 == y) or ($setting->$endinffo5 == t)){
	echo "<br/><center><b>Este torneio ainda não acabou.</b></center><br/>";
	}else{
	echo "<table>";
	echo "<tr>";
	echo "<td><b>último vencedor:</b></td>";
	if (($setting->$lastinfo5 == 'Ninguém') or ($setting->$lastinfo5 == NULL)){
	echo "<td>Ninguém. <b>Torneio cancelado.</b></td>";
	}else{
	echo "<td>" . $setting->$lastinfo5 . "</td>";
	}
	echo "</tr>";
	echo "<tr>";
	echo "<td><b>Prêmio recebido:</b></td>";
	if (($setting->$lastinfo5 == 'Ninguém') or ($setting->$lastinfo5 == NULL)){
	echo "<td>0</td>";
	}else{
	echo "<td>" . $setting->$wininfo5 . "</td>";
	}
	echo "</tr>";
	echo "</table>";
	}
	echo "</fieldset>";

	echo "<br/><center><i>As inscrições para o torneio abrirão automaticamente todas as Sextas-Feiras.</i></center>";
	include("templates/private_footer.php");
	exit;
}

?>