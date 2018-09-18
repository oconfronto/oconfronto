<?php

include("lib.php");
define("PAGENAME", "Promoção");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkhp.php");
include("checkwork.php");


	if ($setting->promo == a){

	$query = $db->execute("update `settings` set `value`='ff' where `name`='promo'");
	$query = $db->execute("update `settings` set `value`=0 where `name`='end_promo'");
	$query = $db->execute("truncate `promo`");

	include("templates/private_header.php");
	echo "<fieldset><legend><b>Anulada</b></legend>\n";
	echo "A promoção foi anulada por fraude.";
	echo "</fieldset>";
	echo "<br/>";
	echo "<a href=\"home.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
	}



	if ($setting->promo == ff){
	include("templates/private_header.php");
	echo "<fieldset><legend><b>Anulada</b></legend>\n";
	echo "A promoção foi anulada por fraude.";
	echo "</fieldset>";
	echo "<br/>";
	echo "<a href=\"home.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
	}


if ($setting->promo == t)
{

	if (time() > $setting->end_promo){

	$query = $db->execute("update `settings` set `value`='f' where `name`='promo'");

	include("templates/private_header.php");

	$wpaodsla = $db->execute("select * from `promo` order by `refs` desc limit 0,1");
	$ipwpwpwpa = $wpaodsla->fetchrow();

	$query = $db->execute("update `players` set `bank`=? where `id`=?", array($player->bank + $setting->promo_premio, $ipwpwpwpa['player_id']));
		$logmsg = "Você ganhou a promoção do jogo e <b>" . $setting->promo_premio . " de ouro</b> foram depositados na sua conta bancária.";
		addlog($ipwpwpwpa['player_id'], $logmsg, $db);
		$premiorecebido = "" . $setting->win_id . " de ouro";

	$query = $db->execute("update `settings` set `value`=? where `name`='promo_last_winner'", array($ipwpwpwpa['username']));
	$query = $db->execute("update `settings` set `value`=0 where `name`='end_promo'");
	$query = $db->execute("truncate `promo`");

	
	echo "<fieldset><legend><b>Não existem promoções no momento</b></legend>\n";

	echo "<table>";
	echo "<tr>";
	echo "<td><b>Último ganhador:</b></td>";
	echo "<td>" . $setting->promo_last_winner . "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><b>Prêmio recebido:</b></td>";
	echo "<td>" . $setting->promo_premio . "</td>";
	echo "</tr>";
	echo "</table>";
	echo "</fieldset>";
	echo "<br/>";
	echo "<a href=\"home.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
	}


	if ($_POST['join'])
	{
		$checausuario = $db->execute("select `id` from `promo` where `player_id`=?", array($player->id));
		if ($checausuario->recordcount() > 0){
		include("templates/private_header.php");
		echo "Você já está participando da promoção!<br/><a href=\"promo.php\">Voltar</a>.";
		include("templates/private_footer.php");
		$error = 1;
		exit;
		}
		
		if ($error == 0){

		$insert['player_id'] = $player->id;
		$insert['username'] = $player->username;
		$query = $db->autoexecute('promo', $insert, 'INSERT');

			include("templates/private_header.php");
			echo "Agora você está participando da promoção!<br/><font size=\"1\">Convide o máximo de pessoas que conseguir por esse link: <b>http://www.oconfronto.co.nr/?r=" . $player->id . "</b></font><br/><a href=\"promo.php\">Voltar</a>.";
			include("templates/private_footer.php");
			exit;
		}

	}

	include("templates/private_header.php");

	echo "<fieldset><legend><b>Promoção</b></legend>\n";
	echo "<table>";
	echo "<tr>";
	echo "<td><b>Como funciona:</b></td>";
	echo "<td>Quem convidar mais usuários para o jogo através de seu link de referência em <b>" . $setting->promo_tempo . "</b> ganhará o prêmio.</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><b>Prêmio:</b></td>";
	echo "<td>" . $setting->promo_premio . " de ouro.</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><b>Nº de participantes:</b></td>";
	$nparticipantes = $db->execute("select `id` from `promo`");
	echo "<td>" . $nparticipantes->recordcount() . "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><b>Tempo restante:</b></td>";
		$end = $setting->end_promo - time();
		$days = floor($end/60/60/24);
		$hours = $end/60/60%24;
		$minutes = $end/60%60;
		$comecaem = "$days dias $hours horas $minutes minutos";
	echo "<td>" . $comecaem . " <a href=\"promo.php\">Atualizar</a></td>";
	echo "</tr>";
	echo "</table>";
	echo "</fieldset>";
	echo "<font size=\"1\"><b>Seu link de referência:</b> <a href=\"http://www.oconfronto.co.nr/?r=" . $player->id . "\">http://www.oconfronto.co.nr/?r=" . $player->id . "</a></font>";
	echo "<br/><br/>";

	echo "<fieldset><legend><b>Participantes</b> (os 15 que mais convidaram usuários)</legend>\n";
	echo "<table>";

	$query44887 = $db->execute("select * from `promo` order by `refs` desc limit 0,15");
	if ($query44887->recordcount() < 1){
	echo "<tr>\n";
	echo "<td>Nenhum participante no momento.</td>\n";
	echo "</tr>\n";
	}else{
	echo "<tr>";
	echo "<th width=\"50%\"><b>Usuário</b></td>";
	echo "<th width=\"50%\"><b>Nº de usuários convidados</b></td>";
	echo "</tr>";	
	while($member = $query44887->fetchrow())
	{
	echo "<tr>\n";
	echo "<td><a href=\"profile.php?id=" . $member['username'] . "\">";
	echo ($member['username'] == $player->username)?"<b>":"";
	echo $member['username'];
	echo ($member['username'] == $player->username)?"</b>":"";
	echo "</a></td>\n";
	echo "<td>" . $member['refs'] . "</td>\n";
	echo "</tr>\n";
	}
	}
	echo "</table>";
	echo "</fieldset>";
	


	$checausuario2 = $db->execute("select `refs` from `promo` where `player_id`=?", array($player->id));
	if ($checausuario2->recordcount() > 0){
	$checausuario3 = $checausuario2->fetchrow();
	echo " <b>Você já convidou:</b> <font size=\"1\">" . $checausuario3['refs'] . " usuários</font> | <b>Link de referência:</b> <font size=\"1\">http://www.oconfronto.co.nr/?r=" . $player->id . "</font>";
	}else{
	echo "<br/>";
	echo "<form method=\"POST\" action=\"promo.php\">";
	echo "<input type=\"submit\" name=\"join\" value=\"Participar da promoção\">";
	echo "</form>";
	}

	include("templates/private_footer.php");
	exit;

	}else{
	include("templates/private_header.php");
	echo "<fieldset><legend><b>Não existem promoções no momento</b></legend>\n";

	echo "<table>";
	echo "<tr>";
	echo "<td><b>Último ganhador:</b></td>";
	echo "<td>" . $setting->promo_last_winner . "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td><b>Prêmio recebido:</b></td>";
	echo "<td>" . $setting->promo_premio . "</td>";
	echo "</tr>";
	echo "</table>";
	echo "</fieldset>";
	echo "<br/>";
	echo "<a href=\"home.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
}

?>