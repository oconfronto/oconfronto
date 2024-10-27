<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Chat");
$player = check_user($db);
if (isset($_GET['act']) == 'showmsg') {
	header('Content-type: text/html; charset=utf-8');
	$check = $db->execute("select * from `pending` where `pending_id`=31 and `player_id`=?", [$player->id]);
	if ($check->recordcount() == 0) {
		$countmsgs = $db->execute("select * from `user_chat` where `reino`=0 and `guild`=0 order by `time` asc");
		$getmsgs = $db->execute("select * from `user_chat` where `reino`=0 and `guild`=0  order by `time` asc limit 13");
	} else {
		$user = $check->fetchrow();

		if ($user['pending_status'] == 'reino') {
			$countmsgs = $db->execute("select * from `user_chat` where `reino`=? order by `time` asc", [$player->reino]);
			$getmsgs = $db->execute("select * from `user_chat` where `reino`=? order by `time` asc limit 13", [$player->reino]);
		} else {
			$countmsgs = $db->execute("select * from `user_chat` where `guild`=? and `guild`!=0 order by `time` asc", [$player->guild]);

			$orda = $countmsgs->recordcount() >= 13 ? $countmsgs->recordcount() - 13 : 0;

			$getmsgs = $db->execute("select * from `user_chat` where `guild`=? and `guild`!=0 order by `time` asc limit ?, ?", [$player->guild, $orda, $countmsgs->recordcount()]);
		}
	}

	if ($getmsgs->recordcount() == 0) {
		if (($player->guild == NULL || $player->guild == 0) && $user['pending_status'] == 'cla') {
			echo "<font size=\"1\"><center><b>Voc&ecirc; não possui um clã.</center></font>";
		} else {
			echo '<font size="1"><center><b>Nenhuma mensagem recente.</center></font>';
		}
	} else {
		$firstmsg = 0;
		while ($msg = $getmsgs->fetchrow()) {

			$ignorado = $db->execute("select * from `ignored` where `uid`=? and `bid`=?", [$player->id, $msg['player_id']]);
			if ($ignorado->recordcount() == 0) {
				echo antiBreak('<font size="1px"><font color="grey">' . date('H:i', $msg['time']) . "</font> " . showName($msg['player_id'], $db) . ": " . $msg['msg'] . "</font><br/>", "50");
				if ($firstmsg == 0) {
					$firsttime = $msg['time'];
					$firstmsg = 1;
				}
			}
		}

		if ($countmsgs->recordcount() > 13) {
			if ($check->recordcount() == 0) {
				$db->execute("delete from `user_chat` where `time`=?", [$firsttime]);
			} else {
				$user = $check->fetchrow();

				if ($user['pending_status'] == 'reino') {
					$db->execute("delete from `user_chat` where `time`=? and `reino`=?", [$firsttime, $player->reino]);
				} elseif ($user['pending_status'] == 'reino') {
					$db->execute("delete from `user_chat` where `time`=? and `guild`=?", [$firsttime, $player->guild]);
				}
			}
		}
	}

	exit;
}

if (isset($_POST['submit']) && ($_POST['status'] && $_POST['style'])) {
	if ($_POST['status'] == 'onl') {
		$db->execute("delete from `pending` where `pending_id`=30 and `player_id`=?", [$player->id]);
	} elseif ($_POST['status'] == 'ocp') {

		$check = $db->execute("select * from `pending` where `pending_id`=30 and `player_id`=?", [$player->id]);
		if ($check->recordcount() == 0) {
			$insert['player_id'] = $player->id;
			$insert['pending_id'] = 30;
			$insert['pending_status'] = 'ocp';
			$insert['pending_time'] = time();
			$query = $db->autoexecute('pending', $insert, 'INSERT');
		} else {
			$db->execute("update `pending` set `pending_status`='ocp' where `pending_id`=30 and `player_id`=?", [$player->id]);
		}
	} elseif ($_POST['status'] == 'inv') {

		$check = $db->execute("select * from `pending` where `pending_id`=30 and `player_id`=?", [$player->id]);
		if ($check->recordcount() == 0) {
			$insert['player_id'] = $player->id;
			$insert['pending_id'] = 30;
			$insert['pending_status'] = 'inv';
			$insert['pending_time'] = time();
			$query = $db->autoexecute('pending', $insert, 'INSERT');
		} else {
			$db->execute("update `pending` set `pending_status`='inv' where `pending_id`=30 and `player_id`=?", [$player->id]);
		}
	}

	if ($_POST['style'] == 'chat') {
		$db->execute("delete from `pending` where `pending_id`=31 and `player_id`=?", [$player->id]);
	} elseif ($_POST['style'] == 'reino') {

		$check = $db->execute("select * from `pending` where `pending_id`=31 and `player_id`=?", [$player->id]);
		if ($check->recordcount() == 0) {
			$insert['player_id'] = $player->id;
			$insert['pending_id'] = 31;
			$insert['pending_status'] = 'reino';
			$insert['pending_time'] = time();
			$query = $db->autoexecute('pending', $insert, 'INSERT');
		} else {
			$db->execute("update `pending` set `pending_status`='reino' where `pending_id`=31 and `player_id`=?", [$player->id]);
		}
	} elseif ($_POST['style'] == 'cla') {

		$check = $db->execute("select * from `pending` where `pending_id`=31 and `player_id`=?", [$player->id]);
		if ($check->recordcount() == 0) {
			$insert['player_id'] = $player->id;
			$insert['pending_id'] = 31;
			$insert['pending_status'] = 'cla';
			$insert['pending_time'] = time();
			$query = $db->autoexecute('pending', $insert, 'INSERT');
		} else {
			$db->execute("update `pending` set `pending_status`='cla' where `pending_id`=31 and `player_id`=?", [$player->id]);
		}
	}
}

include(__DIR__ . "/templates/private_header.php");
?>

<script type="text/javascript">
	function runScript(e) {
		if (e.keyCode == 13) {
			submitMsg();
		}
	}

	function submitMsg() {
		var msg = 'sendmsg.php?msg=' + document.getElementById('msg').value;
		LoadPage(msg, 'envia');
		LoadPage('online.php?act=showmsg', 'chatdiv');

		document.getElementById('msg').value = '';
		document.getElementById('msg').focus();
	}
</script>

<?php

echo '<script type="text/javascript">';
echo "setTimeout(function() { Ajax('online.php?act=showmsg', 'chatdiv'); }, 500);";
echo "</script>";

echo '<table width="100%">';
echo '<tr><td class="brown" width="100%"><center><b>';

$check = $db->execute("select * from `pending` where `pending_id`=31 and `player_id`=?", [$player->id]);
if ($check->recordcount() == 0) {
	echo "Chat Geral";
} else {
	$user = $check->fetchrow();

	if ($user['pending_status'] == 'reino') {
		echo "Chat do Reino";
	} else {
		echo "Chat do Clã";
	}
}

echo "</b></center></td></tr>";
echo '<tr class="off"><td>';

echo "<center><div style='display:none' id=\"envia\"></div><div id=\"chatdiv\" class=\"scroll\" style=\"background-color:#FFFDE0; overflow: auto; height:215px; width:98%; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px; text-align: left;\"></div></center>";
echo "<center><b>" . $player->username . ':</b> <input type="text" id="msg" name="msg" size="65" value="" onkeypress="return runScript(event)"/> <input type="button" onclick="submitMsg()" value="Enviar Mensagem"/></center>';

echo "</td></tr>";
echo "</table>";
echo "<br />";



echo '<table width="100%" align="center">';
echo '<tr><td width="35%">';

echo '<table width="100%">';
echo "<tr><td class=\"brown\" width=\"100%\"><center><b>Configurações</b></center></td></tr>";
echo '<tr class="off"><td>';

echo '<form method="POST" action="online.php">';
echo '<table width="100%">';
echo '<tr><th width="35%"><b>Status:</b></th><th>';
echo '<select name="status">';

$check = $db->execute("select * from `pending` where `pending_id`=30 and `player_id`=?", [$player->id]);
if ($check->recordcount() == 0) {
	echo '<option value="onl" selected="selected">Online</option>';
	echo '<option value="ocp">Ocupado</option>';
	echo "<option value=\"inv\">Invisível</option>";
} else {
	$user = $check->fetchrow();

	if ($user['pending_status'] == 'ocp') {
		echo '<option value="onl">Online</option>';
		echo '<option value="ocp" selected="selected">Ocupado</option>';
		echo "<option value=\"inv\">Invisível</option>";
	} else {
		echo '<option value="onl">Online</option>';
		echo '<option value="ocp">Ocupado</option>';
		echo "<option value=\"inv\" selected=\"selected\">Invisível</option>";
	}
}

echo "</select></th><th></th>";

echo "</tr>";
echo '<tr><th width="35%"><b>Chat:</b></th><th>';
echo '<select name="style">';

$check = $db->execute("select * from `pending` where `pending_id`=31 and `player_id`=?", [$player->id]);
if ($check->recordcount() == 0) {
	echo '<option value="chat" selected="selected">Geral</option>';
	echo '<option value="reino">Reino</option>';
	echo "<option value=\"cla\">Clã</option>";
} else {
	$user = $check->fetchrow();

	if ($user['pending_status'] == 'reino') {
		echo '<option value="chat">Geral</option>';
		echo '<option value="reino" selected="selected">Reino</option>';
		echo "<option value=\"cla\">Clã</option>";
	} else {
		echo '<option value="chat">Geral</option>';
		echo '<option value="reino">Reino</option>';
		echo "<option value=\"cla\" selected=\"selected\">Clã</option>";
	}
}

echo '</select><th><input type="submit" name="submit" value="Enviar"></th></tr>';
echo "</table>";
echo "</form>";

echo "</td></tr>";
echo "</table>";
echo "<center><font size=\"1px\"><b><a href=\"mail.php?act=ignore\">Usuários Ignorados</a></b></font></center>";

echo "</td>";
echo '<td width="65%">';

echo '<table width="100%">';
echo '<tr><td class="brown" width="100%"><center><b>';

$check = $db->execute("select * from `pending` where `pending_id`=31 and `player_id`=?", [$player->id]);
if ($check->recordcount() == 0) {
	echo "Usuários online";
} else {
	$user = $check->fetchrow();

	if ($user['pending_status'] == 'reino') {
		echo "Usuários do reino online";
	} else {
		echo "Usuários do clã online";
	}
}

echo "</b></center></td></tr>";
echo '<tr class="off"><td>';
echo '<table width="100%">';
echo "<tr><td>";

echo '<font size="1px">';

$totalon = 0;
$query = $db->execute("select `player_id` from `user_online`");
while ($online = $query->fetchrow()) {

	$check = $db->execute("select * from `pending` where `pending_id`=31 and `player_id`=?", [$player->id]);
	if ($check->recordcount() == 0) {
		echo "" . showName($online['player_id'], $db) . " | ";
		$totalon += 1;
	} else {
		$user = $check->fetchrow();

		if ($user['pending_status'] == 'reino') {
			$getname = $db->execute("select `id` from `players` where `id`=? and `reino`=? order by `username` asc", [$online['player_id'], $player->reino]);
			while ($member = $getname->fetchrow()) {
				echo "" . showName($member['id'], $db) . " | ";
				$totalon += 1;
			}
		} else {
			$getname = $db->execute("select `id` from `players` where `id`=? and `guild`=? order by `username` asc", [$online['player_id'], $player->guild]);
			while ($member = $getname->fetchrow()) {
				echo "" . showName($member['id'], $db) . " | ";
				$totalon += 1;
			}
		}
	}
}

echo "<b>Total:</b> " . $totalon . "";
if ($check->recordcount() == 0) {
	echo " <b>Recorde:</b> " . $setting->user_record . "";
}

echo "<br/>";

if ($query->recordcount() > $setting->user_record) {
	$query = $db->execute("update `settings` set `value`=? where `name`='user_record'", [$query->recordcount()]);
}

echo "</font>";

echo "</td></tr>";
echo "</table>";
echo "</td></tr>";
echo "</table>";
echo "</td></tr>";
echo "</table>";

include(__DIR__ . "/templates/private_footer.php");
?>