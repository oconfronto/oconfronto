<?php
/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.ezrpgproject.com/   */
/*************************************/

include("lib.php");
define("PAGENAME", "Log");
$player = check_user($secret_key, $db);

include("templates/private_header.php");

echo "<table width=\"100%\">";
echo "<tr><td align=\"center\" bgcolor=\"#E1CBA4\"><b>Logs de Usuário</b></td></tr>";
$query0 = $db->execute("select `id`, `msg`, `status`, `time` from `user_log` where `player_id`=? order by `time` desc limit 10", array($player->id));
if ($query0->recordcount() > 0)
{
	while ($log0 = $query0->fetchrow())
	{
		$read0 = $db->execute("update `user_log` set `status`='read' where `player_id`=? and `status`='unread' and `id`=?", array($player->id, $log0['id']));

		$valortempo = time() - $log0['time'];
		if ($valortempo < 60){
		$valortempo2 = $valortempo;
		$auxiliar2 = "segundo(s) atrás.";
		}else if($valortempo < 3600){
		$valortempo2 = floor($valortempo / 60);
		$auxiliar2 = "minuto(s) atrás.";
		}else if($valortempo < 86400){
		$valortempo2 = floor($valortempo / 3600);
		$auxiliar2 = "hora(s) atrás.";
		}else if($valortempo > 86400){
		$valortempo2 = floor($valortempo / 86400);
		$auxiliar2 = "dia(s) atrás.";
		}

		echo "<tr>";
		echo "<td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\"><div title=\"header=[" . $valortempo2 . " " . $auxiliar2 . "] body=[]\"><font size=\"1\">" . $log0['msg'] . "</font></div></td>";
		echo "</tr>";
	}
}
else
{
	echo "<tr>";
	echo "<td class=\"off\"><font size=\"1\">Nenhum registro encontrado!</font></td>";
	echo "</tr>";
}
echo "</table>";
$count0 = $db->execute("select `id` from `user_log` where `player_id`=?", array($player->id));
if ($count0->recordcount() > 10){
echo "<center><font size=\"1\"><a href=\"#\" onclick=\"javascript:window.open('userlog.php', '_blank','top=100, left=100, height=350, width=520, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');\">Exibir mais logs de usuário</a></font></center>";
}
echo "<br/><br/>";


echo "<table width=\"100%\">";
echo "<tr><td align=\"center\" bgcolor=\"#E1CBA4\"><b>Logs de Batalha</b></td></tr>";
$query1 = $db->execute("select `id`, `msg`, `status`, `time` from `logbat` where `player_id`=? order by `time` desc limit 5", array($player->id));
if ($query1->recordcount() > 0)
{
	while ($log1 = $query1->fetchrow())
	{
		$read1 = $db->execute("update `logbat` set `status`='read' where `player_id`=? and `status`='unread' and `id`=?", array($player->id, $log1['id']));

		$valortempo = time() - $log1['time'];
		if ($valortempo < 60){
		$valortempo2 = $valortempo;
		$auxiliar2 = "segundo(s) atrás.";
		}else if($valortempo < 3600){
		$valortempo2 = floor($valortempo / 60);
		$auxiliar2 = "minuto(s) atrás.";
		}else if($valortempo < 86400){
		$valortempo2 = floor($valortempo / 3600);
		$auxiliar2 = "hora(s) atrás.";
		}else if($valortempo > 86400){
		$valortempo2 = floor($valortempo / 86400);
		$auxiliar2 = "dia(s) atrás.";
		}

		echo "<tr>";
		echo "<td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\"><div title=\"header=[" . $valortempo2 . " " . $auxiliar2 . "] body=[]\"><font size=\"1\">" . $log1['msg'] . "</font></div></td>";
		echo "</tr>";
	}
}
else
{
	echo "<tr>";
	echo "<td class=\"off\"><font size=\"1\">Nenhum registro encontrado!</font></td>";
	echo "</tr>";
}
echo "</table>";
$count1 = $db->execute("select `id` from `logbat` where `player_id`=?", array($player->id));
if ($count1->recordcount() > 5){
echo "<center><font size=\"1\"><a href=\"#\" onclick=\"javascript:window.open('logbat.php', '_blank','top=100, left=100, height=350, width=520, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');\">Exibir mais logs de batalha</a></font></center>";
}
echo "<br/><br/>";



echo "<table width=\"100%\">";
echo "<tr><td align=\"center\" bgcolor=\"#E1CBA4\"><b>Logs de Ouro</b></td></tr>";
$query2 = $db->execute("select * from `log_gold` where `player_id`=? order by `time` desc limit 5", array($player->id));
if ($query2->recordcount() > 0)
{
	while ($trans = $query2->fetchrow())
	{
		$read1 = $db->execute("update `log_gold` set `status`='read' where `player_id`=? and `status`='unread' and `id`=?", array($player->id, $trans['id']));

		echo "<tr>";

		if ($trans['action'] == enviou){
		$auxiliar = "para";
		}else{
		$auxiliar = "de";
		}

		$valortempo = time() -  $trans['time'];
		if ($valortempo < 60){
		$valortempo2 = $valortempo;
		$auxiliar2 = "segundo(s) atrás.";
		}else if($valortempo < 3600){
		$valortempo2 = floor($valortempo / 60);
		$auxiliar2 = "minuto(s) atrás.";
		}else if($valortempo < 86400){
		$valortempo2 = floor($valortempo / 3600);
		$auxiliar2 = "hora(s) atrás.";
		}else if($valortempo > 86400){
		$valortempo2 = floor($valortempo / 86400);
		$auxiliar2 = "dia(s) atrás.";
		}

		echo "<td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\"><div title=\"header=[" . $valortempo2 . " " . $auxiliar2 . "] body=[]\">";
		if ($trans['action'] == doou){
		echo "<font size=\"1\">Você enviou <b>" . $trans['value'] . "</b> de ouro para o clã <b><a href=\"guild_profile.php?id=" . $trans['name2'] . "\">" . $trans['name2'] . "</a></b></font></div></td>";
		}elseif ($trans['action'] == ganhou){
		echo "<font size=\"1\">Você recebeu <b>" . $trans['value'] . "</b> de ouro para o clã <b><a href=\"guild_profile.php?id=" . $trans['name2'] . "\">" . $trans['name2'] . "</a></b></font></div></td>";
		}else{
		echo "<font size=\"1\">Você " . $trans['action'] . " <b>" . $trans['value'] . "</b> de ouro " . $auxiliar . " <b><a href=\"profile.php?id=" . $trans['name2'] . "\">" . $trans['name2'] . "</a></b></font></div></td>";
		}

		echo "</tr>";
	}
}
else
{
	echo "<tr>";
	echo "<td class=\"off\"><font size=\"1\">Nenhum registro encontrado!</font></td>";
	echo "</tr>";
}
echo "</table>";
$count2 = $db->execute("select `id` from `log_gold` where `player_id`=?", array($player->id));
if ($count2->recordcount() > 5){
echo "<center><font size=\"1\"><a href=\"#\" onclick=\"javascript:window.open('loggold.php', '_blank','top=100, left=100, height=350, width=450, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');\">Exibir mais logs de ouro</a></font></center>";
}
echo "<br/><br/>";



echo "<table width=\"100%\">";
echo "<tr><td align=\"center\" bgcolor=\"#E1CBA4\"><b>Logs de Itens</b></td></tr>";
$query3 = $db->execute("select * from `log_item` where `player_id`=? order by `time` desc limit 5", array($player->id));
if ($query3->recordcount() > 0)
{
	while ($trans = $query3->fetchrow())
	{
		$read1 = $db->execute("update `log_item` set `status`='read' where `player_id`=? and `status`='unread' and `id`=?", array($player->id, $trans['id']));

		echo "<tr>";


		if ($trans['action'] == enviou){
		$auxiliar = "para";
		}else{
		$auxiliar = "de";
		}

		$valortempo = time() -  $trans['time'];
		if ($valortempo < 60){
		$valortempo2 = $valortempo;
		$auxiliar2 = "segundo(s) atrás.";
		}else if($valortempo < 3600){
		$valortempo2 = floor($valortempo / 60);
		$auxiliar2 = "minuto(s) atrás.";
		}else if($valortempo < 86400){
		$valortempo2 = floor($valortempo / 3600);
		$auxiliar2 = "hora(s) atrás.";
		}else if($valortempo > 86400){
		$valortempo2 = floor($valortempo / 86400);
		$auxiliar2 = "dia(s) atrás.";
		}

		echo "<td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\"><div title=\"header=[" . $valortempo2 . " " . $auxiliar2 . "] body=[]\">";
		if ($trans['action'] == devolveu){
		echo "<font size=\"1\">O administrador devolveu seu/sua <b>" . $trans['value'] . "</b> para <b><a href=\"profile.php?id=" . $trans['name2'] . "\">" . $trans['name2'] . "</a></b></font></div></td>";
		}elseif ($trans['action'] == recuperou){
		echo "<font size=\"1\">O administrador recuperou seu/sua <b>" . $trans['value'] . "</b> que estava com <b><a href=\"profile.php?id=" . $trans['name2'] . "\">" . $trans['name2'] . "</a></b></font></div></td>";
		}else{
		echo "<font size=\"1\">Você " . $trans['action'] . " " . $trans['value'] . " " . $auxiliar . " <b><a href=\"profile.php?id=" . $trans['name2'] . "\">" . $trans['name2'] . "</a></b>" . $trans['aditional'] . "</font></div></td>";
		}
		echo "</tr>";
	}
}
else
{
	echo "<tr>";
	echo "<td class=\"off\"><font size=\"1\">Nenhum registro encontrado!</font></td>";
	echo "</tr>";
}
echo "</table>";
$count3 = $db->execute("select `id` from `log_item` where `player_id`=?", array($player->id));
if ($count3->recordcount() > 5){
echo "<center><font size=\"1\"><a href=\"#\" onclick=\"javascript:window.open('logitem.php', '_blank','top=100, left=100, height=350, width=450, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');\">Exibir mais logs de itens</a></font></center>";
}
echo "<br/><br/>";


echo "<table width=\"100%\">";
echo "<tr><td align=\"center\" bgcolor=\"#E1CBA4\"><b>Logs da Conta</b></td></tr>";
$query4 = $db->execute("select `id`, `msg`, `status`, `time` from `account_log` where `player_id`=? order by `time` desc limit 5", array($player->acc_id));
if ($query4->recordcount() > 0)
{
	while ($log0 = $query4->fetchrow())
	{
		$read0 = $db->execute("update `account_log` set `status`='read' where `player_id`=? and `status`='unread' and `id`=?", array($player->acc_id, $log0['id']));

		$valortempo = time() - $log0['time'];
		if ($valortempo < 60){
		$valortempo2 = $valortempo;
		$auxiliar2 = "segundo(s) atrás.";
		}else if($valortempo < 3600){
		$valortempo2 = floor($valortempo / 60);
		$auxiliar2 = "minuto(s) atrás.";
		}else if($valortempo < 86400){
		$valortempo2 = floor($valortempo / 3600);
		$auxiliar2 = "hora(s) atrás.";
		}else if($valortempo > 86400){
		$valortempo2 = floor($valortempo / 86400);
		$auxiliar2 = "dia(s) atrás.";
		}

		echo "<tr>";
		echo "<td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\"><div title=\"header=[" . $valortempo2 . " " . $auxiliar2 . "] body=[]\"><font size=\"1\">" . $log0['msg'] . "</font></div></td>";
		echo "</tr>";
	}
}
else
{
	echo "<tr>";
	echo "<td class=\"off\"><font size=\"1\">Nenhum registro encontrado!</font></td>";
	echo "</tr>";
}
echo "</table>";
$count4 = $db->execute("select `id` from `account_log` where `player_id`=?", array($player->acc_id));
if ($count4->recordcount() > 5){
echo "<center><font size=\"1\"><a href=\"#\" onclick=\"javascript:window.open('accountlog.php', '_blank','top=100, left=100, height=350, width=520, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');\">Exibir mais logs da conta</a></font></center>";
}
echo "<br/><br/>";


include("templates/private_footer.php");
?>