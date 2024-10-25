<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Lista de Amigos");
$player = check_user($db);

$maxfriends = 20; //Max friends allowed
//start counting friends
$num_rows_query = $db->execute('SELECT * FROM `friends` WHERE `uid` = ' . $player->acc_id);
$num_rows = $num_rows_query->recordcount();
//end counting friends

$zeroamigos = 0;
$totalgkills = 0;
if ($_GET['add']) {
	$getfname = $db->GetOne("select `username` from `players` where `username`=?", [$_GET['add']]);
	if ($getfname == $player->username) {
		include(__DIR__ . "/templates/private_header.php");
		echo "Voc&ecirc; não pode adicionar voc&ecirc; mesmo!<br><a href=\"friendlist.php\">Voltar á lista de amigos</a> | <a href=\"members.php\">Voltar á lista de membros</a>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	if ($num_rows + 1 > $maxfriends) {
		include(__DIR__ . "/templates/private_header.php");
		echo "Voc&ecirc; atingiu o numero máximo de amigos!<br><a href=\"friendlist.php\">Voltar á lista de amigos</a> | <a href=\"members.php\">Voltar á lista de membros</a>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	$quereya = $db->execute("select * from `friends` where `fname`=? and `uid`=?", [$_GET['add'], $player->acc_id]);
	if ($quereya->recordcount() > 0) {
		include(__DIR__ . "/templates/private_header.php");
		echo "Voc&ecirc; já tem este usuário na sua lista de amigos!<br><a href=\"friendlist.php\">Voltar á lista de amigos</a> | <a href=\"members.php\">Voltar á lista de membros</a>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	$quereya = $db->execute("select `username` from `players` where `username`=?", [$_GET['add']]);
	if ($quereya->recordcount() == 0) {
		include(__DIR__ . "/templates/private_header.php");
		echo "Este usuário não existe!<br><a href=\"friendlist.php\">Voltar á lista de amigos</a> | <a href=\"members.php\">Voltar á lista de membros</a>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	$amigoserver = $db->GetOne("select `serv` from `players` where `username`=?", [$_GET['add']]);
	if ($player->serv != $amigoserver) {
		include(__DIR__ . "/templates/private_header.php");
		echo "Este usuário pertence a outro servidor!<br><a href=\"friendlist.php\">Voltar á lista de amigos</a> | <a href=\"members.php\">Voltar á lista de membros</a>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	include(__DIR__ . "/templates/private_header.php");
	$add = $db->GetOne("select `username` from `players` where `username`=?", [$_GET['add']]);
	$asql = sprintf("INSERT INTO `friends` (`uid` ,`fname`)VALUES ('%s', '%s')", $player->acc_id, $add);
	$aresult = $db->execute($asql);
	if ($aresult) {
		echo "Amigo adicionado!<br><a href=\"friendlist.php\">Voltar á lista de amigos</a> | <a href=\"members.php\">Voltar á lista de membros</a>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	echo "Um erro desconhecido ocorreu!<br><a href=\"friendlist.php\">Voltar á lista de amigos</a> | <a href=\"members.php\">Voltar á lista de membros</a>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if ($_GET['delete']) {
	$dsql = $db->execute("select * from `friends` where `uid`=? and `fname`=?", [$player->acc_id, $_GET['delete']]);
	if ($dsql->recordcount() > 0) {
		$deletaoamigo = $db->execute("delete from `friends` where `uid`=? and `fname`=?", [$player->acc_id, $_GET['delete']]);
		include(__DIR__ . "/templates/private_header.php");
		echo 'Amigo removido!<br><a href="friendlist.php">Voltar</a>.';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	include(__DIR__ . "/templates/private_header.php");
	echo 'Um erro desconhecido ocorreu.<br><a href="friendlist.php">Voltar</a>.';
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if ($_GET['deleteinvite']) {
	$dsql2 = $db->execute("select * from `group_invite` where `group_id`=? and `invited_id`=?", [$player->id, $_GET['deleteinvite']]);
	if ($dsql2->recordcount() > 0) {
		$deletaoconviti = $db->execute("DELETE FROM `group_invite` WHERE `group_id`=? AND `invited_id`=?", [$player->id, $_GET['deleteinvite']]);
		include(__DIR__ . "/templates/private_header.php");
		echo "Convite para grupo de caça removido.<br><a href=\"friendlist.php\">Voltar</a>.";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	include(__DIR__ . "/templates/private_header.php");
	echo 'Um erro desconhecido ocorreu.<br><a href="friendlist.php">Voltar</a>.';
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if ($_GET['deleteconvite']) {
	$dsql4 = $db->execute("select * from `group_invite` where `group_id`=? and `invited_id`=?", [$_GET['deleteconvite'], $player->id]);
	if ($dsql4->recordcount() > 0) {
		$deletaoconviti = $db->execute("DELETE FROM `group_invite` WHERE `group_id`=? AND `invited_id`=?", [$_GET['deleteconvite'], $player->id]);
		include(__DIR__ . "/templates/private_header.php");
		echo 'O convite foi recusado.<br><a href="friendlist.php">Voltar</a>.';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	include(__DIR__ . "/templates/private_header.php");
	echo 'Um erro desconhecido ocorreu.<br><a href="friendlist.php">Voltar</a>.';
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if ($_GET['deletedogrupo']) {
	$dsql3 = $db->execute("select * from `groups` where `id`=? and `player_id`=?", [$player->id, $_GET['deletedogrupo']]);
	if ($dsql3->recordcount() > 0) {

		if ($player->id == $_GET['deletedogrupo']) {
			include(__DIR__ . "/templates/private_header.php");
			echo "Voc&ecirc; não pode se expulsar do seu própio grupo.<br><a href=\"friendlist.php\">Voltar</a>.";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		$logmsg = '<a href="profile.php?id=' . $player->username . '">' . $player->username . "</a> te expulsou do grupo de caça.";
		addlog($_GET['deletedogrupo'], $logmsg, $db);

		$deletegrpomember = $db->execute("DELETE FROM `groups` WHERE `id`=? AND `player_id`=?", [$player->id, $_GET['deletedogrupo']]);
		include(__DIR__ . "/templates/private_header.php");
		echo "Usuário removido do seu grupo de caça.<br><a href=\"friendlist.php\">Voltar</a>.";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	include(__DIR__ . "/templates/private_header.php");
	echo 'Um erro desconhecido ocorreu.<br><a href="friendlist.php">Voltar</a>.';
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

?>
<?php
if ($_GET['addgroup']) {
	$verificaantesdegrupo1 = $db->execute("select `id`, `username`, `level` from `players` where `username`=?", [$_GET['addgroup']]);
	if ($verificaantesdegrupo1->recordcount() == 0) {
		include(__DIR__ . "/templates/private_header.php");
		echo "Amigo não encontrado!<br /><a href=\"friendlist.php\">Voltar</a>.";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	$groupfriend = $verificaantesdegrupo1->fetchrow();
	if ($player->level < 30) {
		include(__DIR__ . "/templates/private_header.php");
		echo "Seu nível é inferior á 30. Apenas usuários de nível 30 ou mais podem criar grupos de caça.<br /><a href=\"friendlist.php\">Voltar</a>.";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	if ($groupfriend['level'] < 30) {
		include(__DIR__ . "/templates/private_header.php");
		echo "O usuário que voc&ecirc; deseja convidar possui nível inferior é 30.<br /><a href=\"friendlist.php\">Voltar</a>.";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	if ($groupfriend['level'] > ($player->level + 30)) {
		include(__DIR__ . "/templates/private_header.php");
		echo "A diferença de nível entre voc&ecirc; e seu amigo é maior que 30 níveis.<br /><a href=\"friendlist.php\">Voltar</a>.";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	if ($groupfriend['level'] < ($player->level - 30)) {
		include(__DIR__ . "/templates/private_header.php");
		echo "A diferença de nível entre voc&ecirc; e seu amigo é maior que 30 níveis.<br /><a href=\"friendlist.php\">Voltar</a>.";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	$checkseeamigo = $db->execute("select * from `friends` WHERE `uid`=? and `fname`=?", [$player->acc_id, $groupfriend['username']]);
	if ($checkseeamigo->recordcount() == 0) {
		include(__DIR__ . "/templates/private_header.php");
		echo "O usuário " . $groupfriend['username'] . " não é seu amigo.<br /><a href=\"friendlist.php\">Voltar</a>.";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	$checkseteminvitegrupo = $db->execute("select * from `group_invite` WHERE `invited_id`=? and `group_id`=?", [$groupfriend['id'], $player->id]);
	if ($checkseteminvitegrupo->recordcount() > 0) {
		include(__DIR__ . "/templates/private_header.php");
		echo "Um convite já foi enviado ao seu amigo.<br /><a href=\"friendlist.php\">Voltar</a>.";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	$checksetemgrupo = $db->execute("select * from `groups` WHERE `player_id`=?", [$groupfriend['id']]);
	if ($checksetemgrupo->recordcount() > 0) {
		include(__DIR__ . "/templates/private_header.php");
		echo "Seu amigo já está em um grupo de caça.<br /><a href=\"friendlist.php\">Voltar</a>.";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	$checksevctemgrupo = $db->execute("select * from `groups` WHERE `player_id`=? and `id`!=?", [$player->id, $player->id]);
	if ($checksevctemgrupo->recordcount() > 0) {
		include(__DIR__ . "/templates/private_header.php");
		echo "Voc&ecirc; já está em um grupo de caça. Para criar um novo grupo primeiro saia de seu grupo atual.<br /><a href=\"friendlist.php\">Voltar</a>.";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	$mandaconvite = $db->execute("select * from `groups` WHERE `id`=?", [$player->id]);
	if ($mandaconvite->recordcount() == 0) {
		$insert['id'] = $player->id;
		$insert['player_id'] = $player->id;
		$criaogrupo = $db->autoexecute('groups', $insert, 'INSERT');
	}

	$insert['group_id'] = $player->id;
	$insert['invited_id'] = $groupfriend['id'];
	$mandaoconvittix = $db->autoexecute('group_invite', $insert, 'INSERT');
	$logmsg = '<a href="profile.php?id=' . $player->username . '">' . $player->username . "</a> está te convidando para fazer parte um grupo de caça. <a href=\"group_accept.php?id=" . $player->id . '">Clique aqui</a> para aceitar.';
	addlog($groupfriend['id'], $logmsg, $db);
	include(__DIR__ . "/templates/private_header.php");
	echo "" . $groupfriend['username'] . " foi convidado para fazer parte do seu grupo de caça.<br /><a href=\"friendlist.php\">Voltar</a>.";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

include(__DIR__ . "/templates/private_header.php");
?>
<fieldset>
	<legend><b>Amigos</b></legend>
	<?php
	$query = $db->execute("select `fname` from `friends` WHERE `uid`=? order by `fname` asc", [$player->acc_id]);
	if ($query->recordcount() == 0) {
		echo "<br/><center><b><font size=\"1\">Você não tem amigos.</font></b></center><br/>";
		$zeroamigos = 5;
	} else {

		echo '<table width="100%" border="0">';
		echo "<tr>";
		echo '<th width="15%"><b>Imagem</b></td>';
		echo "<th width=\"25%\"><b>Usuário</b></td>";
		echo "<th width=\"20%\"><b>nível</b></td>";
		echo "<th width=\"20%\"><b>Vocação</b></td>";
		echo "<th width=\"15%\"><b>Opções</b></td>";
		echo "</tr>";

		while ($friend = $query->fetchrow()) {

			$queryromulo = $db->execute("select `id`, `username`, `gm_rank`, `level`, `avatar`, `voc`, `promoted` from `players` where `username`=?", [$friend['fname']]);
			$member = $queryromulo->fetchrow();
			echo "<tr>\n";

			echo '<td height="64px"><div style="position: relative;">';
			echo '<img src="static/' . $member['avatar'] . '" width="64px" height="64px" style="position: absolute; top: 1; left: 1;" alt="' . $member['username'] . '" border="0">';

			$checkranknosite = $db->execute("select `time` from `user_online` where `player_id`=?", [$member['id']]);
			if ($checkranknosite->recordcount() > 0) {
				echo "<a href=\"javascript:void(0)\" onclick=\"javascript:chatWith('" . str_replace(" ", "_", $member['username']) . "')\"><img src=\"static/images/online1.png\" width=\"64px\" height=\"64px\" style=\"position: absolute; top: 1; left: 1;\" alt=\"" . $member['username'] . '" border="0px"></a>';
			}

			echo "</div></td>";

			echo '<td><a href="profile.php?id=' . $member['username'] . '">';
			echo ($member['username'] == $player->username) ? "<b>" : "";
			echo $member['username'];
			echo ($member['username'] == $player->username) ? "</b>" : "";
			echo "</a></td>\n";
			echo "<td>" . $member['level'] . "</td>\n";
			echo "<td>";
			if ($member['voc'] == 'archer' && $member['promoted'] == 'f') {
				echo "Caçador";
			} elseif ($member['voc'] == 'knight' && $member['promoted'] == 'f') {
				echo "Espadachim";
			} elseif ($member['voc'] == 'mage' && $member['promoted'] == 'f') {
				echo "Bruxo";
			} elseif ($member['voc'] == 'archer' && ($member['promoted'] == 't' || $member['promoted'] == 's' || $member['promoted'] == 'r')) {
				echo "Arqueiro";
			} elseif ($member['voc'] == 'knight' && ($member['promoted'] == 't' || $member['promoted'] == 's' || $member['promoted'] == 'r')) {
				echo "Guerreiro";
			} elseif ($member['voc'] == 'mage' && ($member['promoted'] == 't' || $member['promoted'] == 's' || $member['promoted'] == 'r')) {
				echo "Mago";
			} elseif ($member['voc'] == 'archer' && $member['promoted'] == 'p') {
				echo "Arqueiro Royal";
			} elseif ($member['voc'] == 'knight' && $member['promoted'] == 'p') {
				echo "Cavaleiro";
			} elseif ($member['voc'] == 'mage' && $member['promoted'] == 'p') {
				echo "Arquimago";
			}

			echo "</td>\n";
			echo '<td><font size="1"><a href="mail.php?act=compose&to=' . $member['username'] . '">Mensagem</a><br/><a href="battle.php?act=attack&username=' . $member['username'] . '">Lutar</a><br/> - <a href="friendlist.php?delete=' . $member['username'] . "\">Amigo</a></font></td>\n";
			echo "</tr>\n";
		}

		echo "</table>";
	}
	?>
</fieldset>
<?php
if ($zeroamigos != 5) {
	echo '<font size="1"><b>Voc&ecirc; tem ' . $num_rows . " amigo(s)</b></font>";
}

echo "<br/><br/>\n";
echo "<fieldset>\n";
echo "<legend><b>Grupo de Caça</b></legend>\n";
$procuraseugrupo = $db->execute("select * from `groups` WHERE `player_id`=?", [$player->id]);
if ($procuraseugrupo->recordcount() == 0) {
	echo "<br/><center><b><font size=\"1\">Voc&ecirc; não possui um grupo de caça.</font></b></center><br/>";
	if ($player->level < 30) {
		echo "<center><b><font size=\"1\">Apenas usuários de nível 30 ou mais podem criar grupos de caça.</font></b></center><br/>";
	}
} else {
	$procuragrupoinfo = $procuraseugrupo->fetchrow();
	$iddddoseugrupo = $procuragrupoinfo['id'];

	echo '<table width="100%" border="0">';
	echo "<tr>";
	echo "<th width=\"25%\"><b>Usuário</b></td>";
	echo "<th width=\"10%\"><b>nível</b></td>";
	echo '<th width="10%"><b>EXP</b></td>';
	echo "<th width=\"35%\"><b>Informação</b></td>";
	echo "<th width=\"20%\"><b>Opções</b></td>";
	echo "</tr>";

	$listamembersgrupo = $db->execute("select groups.player_id, groups.exp, groups.kills, players.id, players.username, players.level from `groups`, `players` WHERE groups.id=? and players.id=groups.player_id", [$iddddoseugrupo]);
	while ($grupoaceito = $listamembersgrupo->fetchrow()) {
		echo "<tr>";
		echo '<td><a href="profile.php?id=' . $grupoaceito['username'] . '">' . $grupoaceito['username'] . "</a></td>";
		echo "<td>" . $grupoaceito['level'] . "</td>";

		$porcentoexperiencia = floor(100 / $listamembersgrupo->recordcount());
		echo "<td>" . $porcentoexperiencia . "%</td>";

		echo '<td><font size="1">Gerou ' . $grupoaceito['exp'] . " de experi&ecirc;ncia.</font></td>";
		$totalgkills += $grupoaceito['kills'];

		if ($player->id == $grupoaceito['id']) {
			echo '<td><font size="1"><a href="group_leave.php?id=' . $iddddoseugrupo . '">Sair do Grupo</a></font></td>';
		} elseif ($player->id == $iddddoseugrupo) {
			echo '<td><font size="1"><a href="friendlist.php?deletedogrupo=' . $grupoaceito['id'] . '">Expulsar do Grupo</a></font></td>';
		} else {
			echo '<td><font size="1"><a href="mail.php?act=compose&to=' . $grupoaceito['username'] . '">Mensagem</a></font></td>';
		}

		echo "</tr>";
	}

	$procuraconvidados = $db->execute("select * from `group_invite` WHERE `group_id`=?", [$iddddoseugrupo]);
	if ($procuraconvidados->recordcount() > 0) {
		while ($convidado = $procuraconvidados->fetchrow()) {
			$exibeconvidadosinfo = $db->execute("select `id`, `username`, `level`, `avatar`, `voc`, `promoted` from `players` where `id`=?", [$convidado['invited_id']]);
			$invited = $exibeconvidadosinfo->fetchrow();
			echo "<tr>";
			echo '<td><a href="profile.php?id=' . $invited['username'] . '">' . $invited['username'] . "</a></td>";
			echo "<td>" . $invited['level'] . "</td>";
			echo "<td>#</td>";
			echo '<td><font size="1">Aguardando aceitar convite.</font></td>';

			if ($iddddoseugrupo == $player->id) {
				echo '<td><font size="1"><a href="friendlist.php?deleteinvite=' . $invited['id'] . '">Remover Convite</a></font></td>';
			} else {
				echo '<td><font size="1"><a href="mail.php?act=compose&to=' . $invited['username'] . '">Mensagem</a></font></td>';
			}

			echo "</tr>";
		}
	}

	echo "</table>";
}

if ($procuraseugrupo->recordcount() > 0) {
	echo "<center><font size=\"1\">Seu grupo já matou " . $totalgkills . " monstros.</font></center>";
}

echo "</fieldset>\n";
if ($procuraseugrupo->recordcount() > 0) {
	echo '<center><font size="1">' . $listamembersgrupo->recordcount() . " usuário(s) no grupo. Máximo de 4 usuários.</font></center>";
}


$convitex1 = $db->execute("select * from `group_invite` WHERE `invited_id`=?", [$player->id]);
if ($convitex1->recordcount() > 0) {
	echo "<br/><br/>";
	echo "<fieldset>";
	echo "<legend><b>Convites para grupos de Caça</b></legend>\n";

	echo '<table width="100%" border="0">';
	echo "<tr>";
	echo '<th width="30%"><b>Lider</b></td>';
	echo "<th width=\"10%\"><b>nível</b></td>";
	echo '<th width="20%"><b>Membros</b></td>';
	echo "<th width=\"20%\"><b>Informação</b></td>";
	echo "<th width=\"20%\"><b>Opções</b></td>";
	echo "</tr>";

	while ($convitex2 = $convitex1->fetchrow()) {
		$lidernamy = $db->GetOne("select `username` from `players` where `id`=?", [$convitex2['group_id']]);
		$liderlevy = $db->GetOne("select `level` from `players` where `id`=?", [$convitex2['group_id']]);
		$lidergorupmembis = $db->execute("select * from `groups` WHERE `id`=?", [$convitex2['group_id']]);

		echo "<tr>";
		echo '<td><a href="profile.php?id=' . $lidernamy . '">' . $lidernamy . "</a></td>";
		echo "<td>" . $liderlevy . "</td>";
		echo "<td>" . $lidergorupmembis->recordcount() . "</td>";
		if ($lidergorupmembis->recordcount() > 3) {
			echo '<td><font size="1">Sem Vagas</font></td>';
		} else {
			echo "<td><font size=\"1\">Disponível</font></td>";
		}

		echo '<td><font size="1"><a href="group_accept.php?id=' . $convitex2['group_id'] . '">Aceitar</a> / <a href="friendlist.php?deleteconvite=' . $convitex2['group_id'] . '">Recusar</a></font></td>';
		echo "</tr>";
	}

	echo "</table>";
}

echo "</fieldset>";

echo "<br/><br/>\n";
echo "<fieldset>\n";
echo "<legend><b>Opções</b></legend>\n";
echo "<form method=\"get\" action=\"friendlist.php\">\n";
echo "<table width=\"100%\">\n";
echo "<tr><td width=\"30%\"><b><font size=\"1\">Adicionar Amigo:</font></b></td>\n<td width=\"40%\"><input type=\"text\" name=\"add\" /></td>";
echo '<td width="30%"><input type="submit" value="Adicionar" /></td></tr>';

if (($zeroamigos != 5 && $player->level > 29) && ($procuraseugrupo->recordcount() == 0 || $procuraseugrupo->recordcount() != 0 && $iddddoseugrupo == $player->id)) {
	echo "<tr><td width=\"30%\"><b><font size=\"1\">Adicionar Amigo no Grupo de Caça:</font></b></td>\n<td width=\"40%\">";
	$queryfriends = $db->execute("select `fname` from `friends` WHERE `uid`=?", [$player->acc_id]);
	echo "<select name=\"addgroup\"><option value=''>Selecione</option>";
	while ($result = $queryfriends->fetchrow()) {
		echo '<option value="' . $result['fname'] . '">' . $result['fname'] . "</option>";
	}

	echo "</select></td>";
	echo '<td width="30%"><input type="submit" value="Adicionar" /></td></tr>';
}

echo "</table>\n";
echo "</form>\n</fieldset>\n";
include(__DIR__ . "/templates/private_footer.php");
?>