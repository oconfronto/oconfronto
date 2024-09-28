<?php
session_start();

if ($_GET['header']) {
	header('Content-type: text/html; charset=utf-8');
	include("config.php");
	include("functions.php");
}

$ipp = $_SERVER['REMOTE_ADDR'];

if ($_SESSION['Login']['player_id'] > 0) {
	$player = check_user($secret_key, $db);
	$checknosite = $db->execute("select `time` from `user_online` where `player_id`=?", array($player->id));

	if ($checknosite->recordcount() == 0) {
		$insert['player_id'] = $player->id;
		$insert['ip'] = $ipp;
		$insert['time'] = time();
		$insert['login'] = time();
		$insert['serv'] = $player->serv;
		$insertchecknosite = $db->autoexecute('user_online', $insert, 'INSERT');
	} else {
		$db->execute("update `user_online` set `time`=? where `player_id`=?", array(time(), $player->id));
	}


	$querydelete = $db->execute("select * from `user_online` where `time`<?", array(time() - 15));
	while ($delete = $querydelete->fetchrow()) {
		$db->execute("update `players` set `uptime`=`uptime`+? where `id`=?", array($delete['time'] - $delete['login'], $delete['player_id']));
		$db->execute("delete from `user_online` where `id`=?", array($delete['id']));
	}

	$mailcount = $db->execute("select `id` from `mail` where `to`=? and `status`='unread'", array($player->id));
	if ($mailcount->recordcount() > 0) {
		echo showAlert("Voc&ecirc; tem " . $mailcount->recordcount() . " <a href=\"mail.php\">mensagem(s)</a> n&atilde;o lida(s)!");
	}

	$queryloginfriend = $db->execute("select `fname` from `friends` where `uid`=?", array($player->acc_id));
	while ($loginfriend = $queryloginfriend->fetchrow()) {
		$frienddeide = $db->GetOne("select `id` from `players` where `username`=?", array($loginfriend['fname']));
		$veruserfrindlogin = $db->execute("select `id` from `user_online` where `player_id`=? and `login`>?", array($frienddeide, (time() - 20)));

		if ($veruserfrindlogin->recordcount() == 1) {
			echo showAlert("Seu(a) amigo(a) <b>" . showName($frienddeide, $db, 'off') . "</b> acabou de entrar.", "green");
		}
	}

	$verificarLog = $db->execute("select `id`, `msg` from `user_log` where `player_id`=? order by time desc limit 3", array($player->id));
	while ($showLog = $verificarLog->fetchrow()) {
		$verificarLogStatus = $db->execute("select `id` from `user_log` where `id`=? and `show`<='3'", array($showLog['id']));
		if ($verificarLogStatus->recordcount() == 1) {
			$db->execute("update `user_log` set `show`=`show`+1 where `id`=?", array($showLog['id']));
			echo showAlert("" . $showLog['msg'] . "");
			break;
		}
	}

	$verificaLuta = $db->execute("select `e_id`, `p_id` from `duels` where `status`=? and (`p_id`=? or `e_id`=?)", array($player->id, $player->id, $player->id));
	if ($verificaLuta->recordcount() > 0) {
		$alertLuta = $verificaLuta->fetchrow();
		if ($alertLuta['p_id'] == $player->id) {
			$enemyname = $db->GetOne("select `username` from `players` where `id`=?", array($alertLuta['e_id']));
		} else {
			$enemyname = $db->GetOne("select `username` from `players` where `id`=?", array($alertLuta['p_id']));
		}

		echo showAlert("" . $enemyname . " está aguardando por você no duelo, <a href=\"duel.php?luta=true&new=true\">clique aqui</a> para iniciar a luta");
	}

	$db->execute("update `players` set `last_active`=? where `id`=?", array(time(), $player->id));

	// Calcula o progresso da experiência
	$progressExp = ($player->exp * 100) / maxExp($player->level);
	// Garante que o progresso esteja entre 0 e 100
	$progressExp = is_numeric($progressExp) && $progressExp > 0 && $progressExp <= 100 ? round($progressExp) : 0;


	echo "<script language=\"javascript\">";
	echo "$('#bar-hp').animate({width: '" . ceil(($player->hp * 100) / $player->maxhp) . "%'}).html('<span>" . $player->hp . " / " . $player->maxhp . "</span>');";
	echo "$('#bar-mp').animate({width: '" . ceil(($player->mana * 100) / $player->maxmana) . "%'}).html('<span>" . $player->mana . " / " . $player->maxmana . "</span>');";
	echo "$('#bar-en').animate({width: '" . ceil(($player->energy * 100) / $player->maxenergy) . "%'}).html('<span>" . $player->energy . " / " . $player->maxenergy . "</span>');";
	echo "$('#player-gold').html('" . number_format($player->gold) . " moedas');";
	echo "$('#expbar').animate({width: '" . $progressExp . "%'});";
	echo "$('#expbarText').text('" . number_format($player->exp) . " / " . number_format(maxExp($player->level)) . " (" . number_format($progressExp) . "%)');";
	echo "$('#vl_pontos').html('<div style=\"font-size:11px;text-align:center\" id=\"vl_pontos\"><b>Pontos de status: </b>$player->stat_points</div>');";
	echo "$('#vl_pontosMisticos').html('<font size=\"1px\"><b>Pontos místicos:</b> " . $player->magic_points . "</font>');";
	echo "$('#player-gold').html('" . number_format($player->gold, 0, '', '.') . " moedas');";
	echo "$('#nv_atual').html('<b>Nível:</b>" . $player->level . "');";
	echo "$('#nv_futuro').html('<b>Nível:</b>" . ($player->level + 1) . "');";
	echo "</script>";
} else {
	echo showAlert("Conex&atilde;o perdida com o servidor.", "red");
}
