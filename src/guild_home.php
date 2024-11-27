<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Concentração do Clã");
$player = check_user($db);
include(__DIR__ . "/bbcode.php");
include(__DIR__ . "/checkbattle.php");

$guildonline = 0;
include(__DIR__ . "/checkguild.php");

//Populates $guild variable
$query = $db->execute("select * from `guilds` where `id`=?", [$player->guild]);

if ($query->recordcount() == 0) {
	header("Location: home.php");
} else {
	$guild = $query->fetchrow();
}

if (($_GET['act'] ?? null) == 'showmsg') {
	header('Content-type: text/html; charset=utf-8');

	$countmsgs = $db->execute("select * from `user_chat` where `guild`=? order by `time` asc", [$player->guild]);

	$orda = $countmsgs->recordcount() >= 13 ? $countmsgs->recordcount() - 13 : 0;

	$getmsgs = $db->execute("select * from `user_chat` where `guild`=? order by `time` asc limit ?, ?", [$player->guild, $orda, $countmsgs->recordcount()]);

	if ($getmsgs->recordcount() == 0) {
		echo '<font size="1"><center><b>Nenhuma mensagem recente.</center></font>';
	} else {
		while ($msg = $getmsgs->fetchrow()) {
			echo antiBreak('<font size="1">' . showName($msg['player_id'], $db) . ": " . $msg['msg'] . "</font><br/>", "50");
		}
	}

	exit;
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
		var msg = 'sendmsg.php?msg=' + document.getElementById('msg').value + '&guild=true';
		LoadPage(msg, 'envia');
		LoadPage('guild_home.php?act=showmsg', 'chatdiv');

		document.getElementById('msg').value = '';
		document.getElementById('msg').focus();
	}
</script>

<?php
$bbcode = new bbcode();
echo '<script type="text/javascript">';
echo "setTimeout(function() { Ajax('guild_home.php?act=showmsg', 'chatdiv'); }, 500);";
echo "</script>";

echo '<table width="100%"><tr><td width="20%">';
echo '<center><img src="static/' . $guild['img'] . '" alt="' . $guild['name'] . '"  width="150" height="150" border="0"></center>';
echo "</td>";
echo '<td width="80%">';
echo '<center><div id="envia"></div><div id="chatdiv" class="scroll" style="background-color:#FFFDE0; overflow: auto; height:100px; width:98%; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px; text-align: left;"></div></center>';
echo "<center><b>" . $player->username . ":</b> <input type=\"text\" id=\"msg\" name=\"msg\" size=\"45\" value=\"\" onkeypress=\"return runScript(event)\"/>
             <input type=\"button\" onclick=\"submitMsg()\" style='float:right;' id=\"link\" class=\"normal\" value=\"Enviar\"/></center>";
echo "</td>";
echo "</td></tr></table>";
echo '<table width="100%">';
echo "<tr>";
echo "<td><table width=\"100%\" class=\"brown\" id=\"nvbarra\" style='height:25px;'>";
echo "<tr>";
echo '<td width="25%"><b>Lider:</b> <a href="profile.php?id=' . $guild['leader'] . '">' . $guild['leader'] . "</a></td>";
echo '<td width="30%"><b>Vice-Lider:</b> ';
if (($guild['vice'] ?? null) != NULL) {
	echo '<a href="profile.php?id=' . $guild['vice'] . '">' . $guild['vice'] . "</a>";
} else {
	echo "Ninguém";
}
echo "</td>";
echo '<td width="20%"><b>Membros:</b> ' . $guild['members'] . "</td>";
echo '<td width="25%"><b>Tesouro:</b> ' . $guild['gold'] . "</td>";
echo "</tr>";
echo "</table></td>";
echo "</tr>";

echo "<tr>";
echo "<td><table width=\"100%\" class=\"brown\" style='background:#ffe8aa;'>";
echo "<tr>";
echo '<td class="salmon">';
$descrikon = stripslashes((string) ($guild['blurb'] ?? null));
$descrikon = $bbcode->parse($descrikon);
echo textLimit($descrikon, 5000, 125);
echo "</td>";
echo "</tr>";

echo "<tr><td class=\"brown\" id=\"nvbarra\" style='height:25px;'>";

echo "<font size=\"1px\"><b>Membros do clã online:</b> ";
$checkonne = $db->execute("select `player_id` from `user_online`");
while ($online = $checkonne->fetchrow()) {
	$getname = $db->execute("select `username` from `players` where `id`=? and `guild`=? order by `username` asc", [$online['player_id'] ?? null, $guild['id'] ?? null]);
	while ($member = $getname->fetchrow()) {
		echo '<a href="profile.php?id=' . $member['username'] . '">';
		echo (($member['username'] ?? null) == $player->username) ? "<b>" : "";
		echo $member['username'] ?? null;
		echo (($member['username'] ?? null) == $player->username) ? "</b>" : "";
		echo "</a> | ";

		$guildonline += 1;
	}
}

echo "<b>Total:</b> " . $guildonline . "</font>";
echo "</td></tr>";

echo "</table></td>";
echo "</tr>";
echo "</table>";

echo "<br/>";

if (($guild['motd'] ?? null) != NULL) {
	echo '<table width="100%" class="brown">';
	echo "<tr>";
	echo '<td width="100%"><b><i><center>' . $guild['motd'] . "</center></i></b></td>";
	echo "</tr>";
	echo "</table>";
}

echo "<br/>";

echo "<table width=\"100%\" class=\"brown\" style='background:#ffe8aa;' >";
echo "<tr style='height:20px;' id='nvbarra'>";
echo "<td width=\"100%\"><b>Pagamento do Clã</b></td>";
echo "</tr>";
echo '<tr class="salmon">';

$valortempo = $guild['pagopor'] - time();
if ($valortempo < 60) {
	$valortempo2 = $valortempo;
	$auxiliar2 = "segundo(s)";
} elseif ($valortempo < 3600) {
	$valortempo2 = floor($valortempo / 60);
	$auxiliar2 = "minuto(s)";
} elseif ($valortempo < 86400) {
	$valortempo2 = floor($valortempo / 3600);
	$auxiliar2 = "hora(s)";
} elseif ($valortempo > 86400) {
	$valortempo2 = floor($valortempo / 86400);
	$auxiliar2 = "dia(s)";
}

echo "<td width=\"100%\"><i><center><b>Clã pago por:</b> " . $valortempo2 . " " . $auxiliar2 . ". <a href=\"guild_treasury.php\">Clique para enviar ouro</a>.<br>Este clã será deletado se o tempo acabar e os lideres não pagarem mais.</center></i></td>";
echo "</tr>";
echo "</table>";

echo "<br/>";
echo "<form><center><table width='300'><tr>";
if ($player->username == ($guild['leader'] ?? null) || $player->username == ($guild['vice'] ?? null)) {
	echo "<td><input id=\"link\" class=\"neg\" type=\"button\" VALUE=\"Administração\" ONCLICK=\"window.location.href='guild_admin.php'\"></td>&nbsp;";
}

echo "
<td><input type=\"button\" id=\"link\" class=\"neg\" VALUE=\"Perfil do Clã\" ONCLICK=\"window.location.href='guild_profile.php?id=" . $guild['id'] . "&redirect=false'\"></td>&nbsp;
<td><input type=\"button\" id=\"link\" class=\"neg\" VALUE=\"Tesouro\" ONCLICK=\"window.location.href='guild_treasury.php'\">&nbsp;
<td><input type=\"button\" id=\"link\" class=\"neg\" VALUE=\"Abandonar Clã\" ONCLICK=\"window.location.href='guild_leave.php'\"></td></tr></table>";
echo "</center></form>";

include(__DIR__ . "/templates/private_footer.php");
?>