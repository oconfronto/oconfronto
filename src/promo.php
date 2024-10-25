<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Promoção");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkhp.php");
include(__DIR__ . "/checkwork.php");


if ($setting->promo == "a") {

	$query = $db->execute("update `settings` set `value`='ff' where `name`='promo'");
	$query = $db->execute("update `settings` set `value`=0 where `name`='end_promo'");
	$query = $db->execute("truncate `promo`");

	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Anulada</b></legend>\n";
	echo "A promoção foi anulada por fraude.";
	echo "</fieldset>";
	echo "<br/>";
	echo '<a href="home.php">Voltar</a>.';
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}



if ($setting->promo == "ff") {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Anulada</b></legend>\n";
	echo "A promoção foi anulada por fraude.";
	echo "</fieldset>";
	echo "<br/>";
	echo '<a href="home.php">Voltar</a>.';
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}


if ($setting->promo == "t") {

	if (time() > $setting->end_promo) {

		$query = $db->execute("update `settings` set `value`='f' where `name`='promo'");

		include(__DIR__ . "/templates/private_header.php");

		$wpaodsla = $db->execute("select * from `promo` order by `refs` desc limit 0,1");
		$ipwpwpwpa = $wpaodsla->fetchrow();

		$query = $db->execute("update `players` set `bank`=? where `id`=?", [$player->bank + $setting->promo_premio, $ipwpwpwpa['player_id']]);
		$logmsg = "Você ganhou a promoção do jogo e <b>" . $setting->promo_premio . " de ouro</b> foram depositados na sua conta bancï¿½ria.";
		addlog($ipwpwpwpa['player_id'], $logmsg, $db);
		$premiorecebido = "" . $setting->win_id . " de ouro";

		$query = $db->execute("update `settings` set `value`=? where `name`='promo_last_winner'", [$ipwpwpwpa['username']]);
		$query = $db->execute("update `settings` set `value`=0 where `name`='end_promo'");
		$query = $db->execute("truncate `promo`");


		echo "<fieldset><legend><b>Nï¿½o existem promoï¿½ï¿½es no momento</b></legend>\n";

		echo "<table>";
		echo "<tr>";
		echo "<td><b>ï¿½ltimo ganhador:</b></td>";
		echo "<td>" . $setting->promo_last_winner . "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td><b>Prêmio recebido:</b></td>";
		echo "<td>" . $setting->promo_premio . "</td>";
		echo "</tr>";
		echo "</table>";
		echo "</fieldset>";
		echo "<br/>";
		echo '<a href="home.php">Voltar</a>.';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}


	if ($_POST['join']) {
		$checausuario = $db->execute("select `id` from `promo` where `player_id`=?", [$player->id]);
		if ($checausuario->recordcount() > 0) {
			include(__DIR__ . "/templates/private_header.php");
			echo "Você jï¿½ estï¿½ participando da promoção!<br/><a href=\"promo.php\">Voltar</a>.";
			include(__DIR__ . "/templates/private_footer.php");
			$error = 1;
			exit;
		}

		if ($error == 0) {

			$insert['player_id'] = $player->id;
			$insert['username'] = $player->username;
			$query = $db->autoexecute('promo', $insert, 'INSERT');

			include(__DIR__ . "/templates/private_header.php");
			echo "Agora você estï¿½ participando da promoção!<br/><font size=\"1\">Convide o mï¿½ximo de pessoas que conseguir por esse link: <b>" . $domain_url . "/?r=" . $player->id . '</b></font><br/><a href="promo.php">Voltar</a>.';
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}
	}

	include(__DIR__ . "/templates/private_header.php");

	echo "<fieldset><legend><b>promoção</b></legend>\n";
	echo "<table>";
	echo "<tr>";
	echo "<td><b>Como funciona:</b></td>";
	echo "<td>Quem convidar mais usuï¿½rios para o jogo atravï¿½s de seu link de referï¿½ncia em <b>" . $setting->promo_tempo . "</b> ganharï¿½ o prêmio.</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><b>Prêmio:</b></td>";
	echo "<td>" . $setting->promo_premio . " de ouro.</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><b>Nï¿½ de participantes:</b></td>";
	$nparticipantes = $db->execute("select `id` from `promo`");
	echo "<td>" . $nparticipantes->recordcount() . "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><b>Tempo restante:</b></td>";
	$end = $setting->end_promo - time();
	$days = floor($end / 60 / 60 / 24);
	$hours = $end / 60 / 60 % 24;
	$minutes = $end / 60 % 60;
	$comecaem = sprintf('%s dias %d horas %d minutos', $days, $hours, $minutes);
	echo "<td>" . $comecaem . ' <a href="promo.php">Atualizar</a></td>';
	echo "</tr>";
	echo "</table>";
	echo "</fieldset>";
	echo "<font size=\"1\"><b>Seu link de referï¿½ncia:</b> <a href=\"" . $domain_url . "/?r=" . $player->id . '">' . $domain_url . "/?r=" . $player->id . "</a></font>";
	echo "<br/><br/>";

	echo "<fieldset><legend><b>Participantes</b> (os 15 que mais convidaram usuï¿½rios)</legend>\n";
	echo "<table>";

	$query44887 = $db->execute("select * from `promo` order by `refs` desc limit 0,15");
	if ($query44887->recordcount() < 1) {
		echo "<tr>\n";
		echo "<td>Nenhum participante no momento.</td>\n";
		echo "</tr>\n";
	} else {
		echo "<tr>";
		echo "<th width=\"50%\"><b>Usuï¿½rio</b></td>";
		echo "<th width=\"50%\"><b>Nï¿½ de usuï¿½rios convidados</b></td>";
		echo "</tr>";
		while ($member = $query44887->fetchrow()) {
			echo "<tr>\n";
			echo '<td><a href="profile.php?id=' . $member['username'] . '">';
			echo ($member['username'] == $player->username) ? "<b>" : "";
			echo $member['username'];
			echo ($member['username'] == $player->username) ? "</b>" : "";
			echo "</a></td>\n";
			echo "<td>" . $member['refs'] . "</td>\n";
			echo "</tr>\n";
		}
	}

	echo "</table>";
	echo "</fieldset>";



	$checausuario2 = $db->execute("select `refs` from `promo` where `player_id`=?", [$player->id]);
	if ($checausuario2->recordcount() > 0) {
		$checausuario3 = $checausuario2->fetchrow();
		echo " <b>Você já convidou:</b> <font size=\"1\">" . $checausuario3['refs'] . " usuários</font> | <b>Link de referência:</b> <font size=\"1\">" . $domain_url . "/?r=" . $player->id . "</font>";
	} else {
		echo "<br/>";
		echo '<form method="POST" action="promo.php">';
		echo "<input type=\"submit\" name=\"join\" value=\"Participar da promoção\">";
		echo "</form>";
	}

	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

include(__DIR__ . "/templates/private_header.php");
echo "<fieldset><legend><b>Nï¿½o existem promoï¿½ï¿½es no momento</b></legend>\n";
echo "<table>";
echo "<tr>";
echo "<td><b>ï¿½ltimo ganhador:</b></td>";
echo "<td>" . $setting->promo_last_winner . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td><b>Prêmio recebido:</b></td>";
echo "<td>" . $setting->promo_premio . "</td>";
echo "</tr>";
echo "</table>";
echo "</fieldset>";
echo "<br/>";
echo '<a href="home.php">Voltar</a>.';
include(__DIR__ . "/templates/private_footer.php");
exit;
