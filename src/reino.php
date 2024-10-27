<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Reino");
$player = check_user($db);

$query = $db->execute("select * from `reinos` where `id`=?", [$player->reino]);
$reino = $query->fetchrow();

if (($reino['poll'] + 604800) < time() && $reino['imperador'] > 0) {
	$db->execute("update `reinos` set `imperador`=0, `poll`=? where `id`=?", [(time() + 172800), $reino['id']]);

	$query = $db->execute("select `id` from `players` where `reino`=?", [$reino['id']]);
	while ($member = $query->fetchrow()) {
		$logmsg = "O reinado de uma semana de " . showName($reino['imperador'], $db, 'off') . " acabou, e as eleições para novo imperador estão abertas.";
		addlog($member['id'], $logmsg, $db);
	}

	$insert['reino'] = $reino['id'];
	$insert['log'] = "O reinado de uma semana de " . showName($reino['imperador'], $db, 'off') . " acabou, e as eleições para novo imperador estão abertas.";
	$insert['time'] = time();
	$db->autoexecute('log_reino', $insert, 'INSERT');

	header("Location: reino.php");
	exit;
}

include(__DIR__ . "/templates/private_header.php");

if ($reino['imperador'] == 0 && isset($_POST['vote'])) {
	$verifica = $db->execute("select * from `reino_votes` where `player_id`=?", [$player->id]);
	if ($verifica->recordcount() == 0) {
		$votes = $db->execute("select * from `reino_tovote` where `player_id`=?", [$_POST['vote']]);
		if ($votes->recordcount() == 1) {
			$insert['player_id'] = $player->id;
			$insert['vote_id'] = $_POST['vote'];
			$insert['reino_id'] = $player->reino;
			$db->autoexecute('reino_votes', $insert, 'INSERT');

			echo showAlert("Voto efetuado com sucesso!", "green");
		} else {
			echo showAlert("Este usuário não é candidato á imperador.", "red");
		}
	} else {
		echo showAlert("Você já votou nestas eleições.", "red");
	}
}

echo '<table width="100%">';
echo '<tr><th width="20%">';

echo '<center><img src="static/images/' . $reino['imagem'] . '" width="82px" height="82px" border="0px" alt="' . $reino['nome'] . '"/></center>';

echo "</th>";
echo '<td width="80%">';


echo '<table width="100%">';
echo '<tr><td class="brown" width="100%"><center><b>' . $reino['nome'] . "</b></center></td></tr>";
echo '<tr class="salmon"><td>';

echo '<table width="100%">';
$query = $db->execute("select `id`, `kills`, `akills`, `level` from `players` where `reino`=? and `gm_rank`<10", [$player->reino]);
echo "<tr><td><b>Membros:</b></td><td>" . $query->recordcount() . " guerreiros</td></tr>";
echo "<tr><td><b>Imperador:</b></td><td>" . showName($reino['imperador'], $db, 'off') . "</td></tr>";
echo "<tr><td><b>Ouro nos cofres:</b></td><td>" . $reino['ouro'] . " moedas de ouro</td></tr>";
echo "<tr><td><b>Pontuação:</b></td><td>";

while ($points = $query->fetchrow()) {
	$totalpoints = $totalpoints + ($points['kills'] * 20) + ($points['level'] * 50) - ($points['akills'] * 15);
}

echo "" . ceil($totalpoints / ($query->recordcount() * 1.5)) . " pontos";
echo "</td></tr>";
echo "</table>";

echo "</td></tr>";
echo "</table>";

echo "</td></tr>";
echo "</table>";
echo "<br/>";

if ($reino['imperador'] == $player->id) {
	echo '<table width="100%">';
	echo "<tr><td class=\"brown\" width=\"100%\"><center><b>Administração - " . $reino['nome'] . "</b></center></td></tr>";
	echo "<tr><td>";
	echo '<table width="100%" style="text-align: center; font-weight: bold;">';
	echo "<td class=\"on\"><font size=\"1px\"><a href=\"reino_gates.php\">Abrir Portões</a></font></td>";
	echo '<td class="on"><font size="1px"><a href="reino_tax.php">Ajustar Impostos</a></font></td>';
	echo "<td class=\"on\"><font size=\"1px\"><a href=\"reino_work.php\">Bônus Salariais</a></font></td>";
	echo '<td class="on"><font size="1px"><a href="create_topic.php?category=reino">Postar Mensagem</a></font></td>';
	echo "</table>";
	echo "</tr></td>";
	echo "</table>";
	echo "<br/>";
}

echo '<table width="100%">';
echo '<tr><td width="45%">';

if ($reino['imperador'] != 0) {
	echo '<table width="100%">';
	echo "<tr><td class=\"brown\" width=\"100%\"><center><b>Possíveis sucessores do Imperador</b></center></td></tr>";

	echo '<td class="salmon"><ul>';
	$query = $db->execute("select `username` from `players` where `reino`=? order by `uptime`+(`posts` * 420)+(`akills` * 30)-(`kills` * 10) desc limit 5", [$player->reino]);
	while ($member = $query->fetchrow()) {
		echo "<li>" . $member['username'] . "</li>";
	}

	echo "</ul></td></tr>";

	echo "</table>";

	$temponext = ceil((($reino['poll'] + 604800) - time()) / 86400);
	echo '<center><font size="1px">' . $temponext . " dia(s) para a próxima eleição.</font></center>";
} else {

	if ($reino['imperador'] == 0 && time() > $reino['poll']) {
		$total = $db->execute("select `vote_id` from `reino_votes` where `reino_id`=? group by `vote_id` order by count(*) desc limit 1", [$reino['id']]);
		$total = $total->fetchrow();

		$db->execute("update `reinos` set `imperador`=? where `id`=?", [$total['vote_id'], $reino['id']]);
		$db->execute("delete from `reino_votes` where `reino_id`=?", [$reino['id']]);
		$db->execute("delete from `reino_tovote` where `reino_id`=?", [$reino['id']]);

		$insert['reino'] = $reino['id'];
		$insert['log'] = "" . $reino['nome'] . " acaba de ganhar um novo imperador: " . showName($total['vote_id'], $db, 'off') . ".";
		$insert['time'] = time();
		$db->autoexecute('log_reino', $insert, 'INSERT');

		$query = $db->execute("select `id` from `players` where `id`!=? and `reino`=?", [$total['vote_id'], $reino['id']]);
		while ($member = $query->fetchrow()) {
			$logmsg = "" . showName($total['vote_id'], $db, 'off') . " agora é o novo Imperador do Reino.";
			addlog($member['id'], $logmsg, $db);
		}

		$logmsg = "Parabéns, você foi eleito Imperador do Reino. <a href=\"reino.php\">Clique aqui</a> para acessar a administração do mesmo.";
		addlog($total['vote_id'], $logmsg, $db);
	}

	echo '<table width="100%">';
	echo "<tr><td class=\"brown\" width=\"100%\"><center><b>Eleição do novo Imperador</b></center></td></tr>";
	echo '<td class="salmon">';

	$votes = $db->execute("select * from `reino_tovote` where `reino_id`=?", [$reino['id']]);
	if ($votes->recordcount() == 0) {
		$query = $db->execute("select `id` from `players` where `reino`=? order by `uptime`+(`posts` * 420)+(`akills` * 30)-(`kills` * 10) desc limit 5", [$reino['id']]);
		while ($member = $query->fetchrow()) {
			$insert['player_id'] = $member['id'];
			$insert['reino_id'] = $reino['id'];
			$db->autoexecute('reino_tovote', $insert, 'INSERT');
		}

		$db->execute("update `reinos` set `poll`=? where `id`=?", [time() + 172800, $reino['id']]);
		$query = $db->execute("select * from `reinos` where `id`=?", [$player->reino]);
		$reino = $query->fetchrow();
	}

	echo '<form method="POST" action="reino.php">';
	echo '<table width="100%">';
	echo '<tr class="salmon"><td width="70%">';

	$votes = $db->execute("select * from `reino_tovote` where `reino_id`=?", [$reino['id']]);
	while ($vote = $votes->fetchrow()) {
		echo '<input type="radio" name="vote" value="' . $vote['player_id']  . '"> ' . showName($vote['player_id'], $db, 'off') . "<br/>";
	}

	echo '</td><th width="30%">';
	echo '<center><input type="submit" name="submit" value="Votar"></center>';
	echo "</th></tr></table>";
	echo "</form>";

	echo "</td></tr>";
	echo "</table>";

	$tempo = ceil($reino['poll'] - time());

	if ($tempo < 60) {
		$uptime = ceil($tempo);
		$tempo = "" . $uptime . " segundo(s)";
	} elseif ($tempo < 3600) {
		$uptime = ceil($tempo / 60);
		$tempo = "" . $uptime . " minuto(s)";
	} elseif ($tempo < 86400) {
		$uptime = floor($tempo / 3600);
		$extra = ceil(($tempo - ($uptime * 3600)) / 60);
		$tempo = "" . $uptime . " hora(s) e " . $extra . " minuto(s)";
	} elseif ($tempo > 86400) {
		$uptime = floor($tempo / 86400);
		$extra = ceil(($tempo - ($uptime * 86400)) / 3600);
		$tempo = "" . $uptime . " dia(s) e " . $extra . " hora(s)";
	}

	echo '<center><font size="1px">' . $tempo . " para o término da eleição.</font></center>";
}

echo "</td>";
echo '<td width="55%">';

echo '<table width="100%">';
echo "<tr><td class=\"brown\" width=\"100%\"><center><b>últimas Atividades</b></center></td></tr>";

$query = $db->execute("select * from `log_reino` where `reino`=? order by `time` desc limit 5", [$player->reino]);
if ($query->recordcount() > 0) {
	while ($log = $query->fetchrow()) {

		$valortempo = time() - $log['time'];
		if ($valortempo < 60) {
			$auxiliar = "segundo(s) atrás.";
		} elseif ($valortempo < 3600) {
			$valortempo = ceil($valortempo / 60);
			$auxiliar = "minuto(s) atrás.";
		} elseif ($valortempo < 86400) {
			$valortempo = ceil($valortempo / 3600);
			$auxiliar = "hora(s) atrás.";
		} elseif ($valortempo < 86400) {
			$valortempo = ceil($valortempo / 86400);
			$auxiliar = "dia(s) atrás.";
		}

		echo "<tr>";
		echo "<td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\"><div title=\"header=[Log] body=[" . $valortempo . " " . $auxiliar . ']"><font size="1">' . $log['log'] . "</font></div></td>";
		echo "</tr>";
	}
} else {
	echo "<tr>";
	echo '<td class="off"><font size="1">Nenhum registro encontrado!</font></td>';
	echo "</tr>";
}


echo "</table>";
echo "<center><font size=\"1\"><a href=\"#\" onclick=\"javascript:window.open('taskslogs.php', '_blank','top=100, left=100, height=350, width=520, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');\">Exibir mais registros</a></font></center>";

echo "</td></tr>";
echo "</table>";

include(__DIR__ . "/templates/private_footer.php");
