<?php

declare(strict_types=1);

session_start();

if ($_GET['header'] ?? null) {
	header('Content-type: text/html; charset=utf-8');
	include(__DIR__ . "/config.php");
	include(__DIR__ . "/functions.php");
}

$ipp = $_SERVER['REMOTE_ADDR'];

if ((($_SESSION['Login'] ?? null)['player_id'] ?? null) > 0) {
	$player = check_user($db);
	$checknosite = $db->execute("select `time` from `user_online` where `player_id`=?", [$player->id]);

	if ($checknosite->recordcount() == 0) {
		$insert['player_id'] = $player->id;
		$insert['ip'] = $ipp;
		$insert['time'] = time();
		$insert['login'] = time();
		$insert['serv'] = $player->serv;
		$insertchecknosite = $db->autoexecute('user_online', $insert, 'INSERT');
	} else {
		$db->execute("update `user_online` set `time`=? where `player_id`=?", [time(), $player->id]);
	}


	$querydelete = $db->execute("select * from `user_online` where `time`<?", [time() - 15]);
	while ($delete = $querydelete->fetchrow()) {
		$db->execute("update `players` set `uptime`=`uptime`+? where `id`=?", [$delete['time'] - $delete['login'], $delete['player_id'] ?? null]);
		$db->execute("delete from `user_online` where `id`=?", [$delete['id'] ?? null]);
	}

	$mailcount = $db->execute("select `id` from `mail` where `to`=? and `status`='unread'", [$player->id]);
	if ($mailcount->recordcount() > 0) {
		echo showAlert("Você tem " . $mailcount->recordcount() . ' <a href="mail.php">mensagem(s)</a> não lida(s)!');
	}

	$queryloginfriend = $db->execute("select `fname` from `friends` where `uid`=?", [$player->acc_id]);
	while ($loginfriend = $queryloginfriend->fetchrow()) {
		$frienddeide = $db->GetOne("select `id` from `players` where `username`=?", [$loginfriend['fname'] ?? null]);
		$veruserfrindlogin = $db->execute("select `id` from `user_online` where `player_id`=? and `login`>?", [$frienddeide, (time() - 20)]);

		if ($veruserfrindlogin->recordcount() == 1) {
			echo showAlert("Seu(a) amigo(a) <b>" . showName($frienddeide, $db, 'off') . "</b> acabou de entrar.", "green");
		}
	}

	$verificarLog = $db->execute("select `id`, `msg` from `user_log` where `player_id`=? order by time desc limit 3", [$player->id]);
	while ($showLog = $verificarLog->fetchrow()) {
		$verificarLogStatus = $db->execute("select `id` from `user_log` where `id`=? and `show`<='3'", [$showLog['id'] ?? null]);
		if ($verificarLogStatus->recordcount() == 1) {
			$db->execute("update `user_log` set `show`=`show`+1 where `id`=?", [$showLog['id'] ?? null]);
			echo showAlert("" . $showLog['msg'] . "");
			break;
		}
	}

	$verificaLuta = $db->execute("select `e_id`, `p_id` from `duels` where `status`=? and (`p_id`=? or `e_id`=?)", [$player->id, $player->id, $player->id]);
	if ($verificaLuta->recordcount() > 0) {
		$alertLuta = $verificaLuta->fetchrow();
		if (($alertLuta['p_id'] ?? null) == $player->id) {
			$enemyname = $db->GetOne("select `username` from `players` where `id`=?", [$alertLuta['e_id'] ?? null]);
		} else {
			$enemyname = $db->GetOne("select `username` from `players` where `id`=?", [$alertLuta['p_id'] ?? null]);
		}

		echo showAlert("" . $enemyname . " está aguardando por você no duelo, <a href=\"duel.php?luta=true&new=true\">clique aqui</a> para iniciar a luta");
	}

	$db->execute("update `players` set `last_active`=? where `id`=?", [time(), $player->id]);

	// Calcula o progresso da experiência
	$progressExp = ($player->exp * 100) / maxExp($player->level);
	// Garante que o progresso esteja entre 0 e 100
	$progressExp = is_numeric($progressExp) && $progressExp > 0 && $progressExp <= 100 ? round($progressExp) : 0;


	echo '<script language="javascript">';	
	echo sprintf("\$('#vl_pontos').html('<div style=\"font-size:11px;text-align:center\" id=\"vl_pontos\"><b>Pontos de status: </b>%s</div>');", $player->stat_points);
	echo "$('#vl_pontosMisticos').html('<font size=\"1px\"><b>Pontos místicos:</b> " . $player->magic_points . "</font>');";
	echo "$('#bar-hp').attr('style', '--percentage: " . ceil(($player->hp * 100) / $player->maxhp) . "%').html('" . $player->hp . " / " . $player->maxhp . "');";
	echo "$('#bar-mp').attr('style', '--percentage: " . ceil(($player->mana * 100) / $player->maxmana) . "%').html('" . $player->mana . " / " . $player->maxmana . "');";
	echo "$('#bar-en').attr('style', '--percentage: " . ceil(($player->energy * 100) / $player->maxenergy) . "%').html('" . $player->energy . " / " . $player->maxenergy . "');";
	echo "$('#bar-exp').attr('style', '--percentage: " . ceil(($player->exp * 100) / maxExp($player->level)) . "%').html('" . $player->exp . " / " . maxExp($player->level) . "');";
	echo "$('#player-level').html('" . $player->level . "');";
	echo "$('#player-gold').html('" . number_format($player->gold, 0, '', '.') . "');";
	echo "</script>";	
} else {
	echo showAlert("Conexão perdida com o servidor.", "red");
}
