<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Grupos de Caça");
$player = check_user($db);


if (!$_GET['id']) {
	header("Location: home.php");
} else {
	$query = $db->execute("select * from `group_invite` where `group_id`=? and `invited_id`=?", [$_GET['id'], $player->id]);
	if ($query->recordcount() == 0) {
		include(__DIR__ . "/templates/private_header.php");
		echo "Grupo de caça não encontrado.<br/>";
		echo '<a href="home.php">Voltar</a>.';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	$group = $query->fetchrow();
	$countamembros = $db->execute("select * from `groups` where `id`=?", [$_GET['id']]);
	$leaderlevel = $db->GetOne("select `level` from `players` where `id`=?", [$_GET['id']]);
	$leadername = $db->GetOne("select `username` from `players` where `id`=?", [$_GET['id']]);

	include(__DIR__ . "/templates/private_header.php");
	if (($player->level + 30) < $leaderlevel) {
		echo "A diferença de nível entre você e o lider do grupo é maior que 30 níveis.<br/>";
		echo '<a href="home.php">Voltar</a>.';
	} elseif (($player->level - 30) > $leaderlevel) {
		echo "A diferença de nível entre você e o lider do grupo é maior que 30 níveis.<br/>";
		echo '<a href="home.php">Voltar</a>.';
	} elseif ($player->level < 30) {
		echo "Seu nível é inferior à 30.<br/>";
		echo '<a href="home.php">Voltar</a>.';
	} elseif ($countamembros->recordcount() > 3) {
		echo "Este grupo já está cheio.<br/>";
		echo '<a href="home.php">Voltar</a>.';
	} else {
		$insert['id'] = $_GET['id'];
		$insert['player_id'] = $player->id;
		$query = $db->autoexecute('groups', $insert, 'INSERT');

		$query = $db->execute("delete from `group_invite` where `group_id`=? and `invited_id`=?", [$_GET['id'], $player->id]);

		$log1 = $db->execute("select `player_id` from `groups` where `id`=? and `player_id`!=?", [$_GET['id'], $player->id]);
		while ($p1 = $log1->fetchrow()) {
			$logmsg1 = 'Agora <a href="profile.php?id=' . $player->username . '">' . $player->username . "</a> faz parte do grupo de caça de <a href=\"profile.php?id=" . $leadername . '">' . $leadername . "</a>.";
			addlog($p1['player_id'], $logmsg1, $db);
		}

		echo "Você acaba de entrar no grupo de caça de " . $leadername . ".<br/>";
		echo '<a href="friendlist.php">Voltar</a>.';
	}

	include(__DIR__ . "/templates/private_footer.php");
	exit;
}
