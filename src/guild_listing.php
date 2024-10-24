<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
include(__DIR__ . "/bbcode.php");
define("PAGENAME", "Clãs");
$player = check_user($secret_key, $db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkguild.php");

if ($player->hp <= 0) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset>";
	echo "<legend><b>Você está morto!</b></legend>\n";
	echo "Vá ao <a href=\"hospt.php\">hospital</a> ou espere 30 minutos.";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

$total_guilds = $db->getone("select count(ID) as `count` from `guilds`");

include(__DIR__ . "/templates/private_header.php");

if ($player->guild == NULL || $player->guild == 0) {
	echo showAlert("<i>Ainda não possui um clã? Tente <a href=\"guild_register.php\"><b>criar o seu</b></a>.</i>", "white", "left");
}

echo '<table width="100%">';
$query = $db->execute("select * from `pwar` where `time`>? and (`status`='t' or `status`='g' or `status`='e') order by `time` desc limit 5", array(time() - 172800));
while ($war = $query->fetchrow()) {
	$guildname = $db->GetOne("select `name` from `guilds` where `id`=?", array($war['guild_id']));
	$enyname = $db->GetOne("select `name` from `guilds` where `id`=?", array($war['enemy_id']));

	if ($war['status'] == 'g') {
		echo "<tr onclick=\"window.location.href='view_war.php?id=" . $war['id'] . "'\"><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\">";
		echo "<center><font size=\"1px\"><b>O clã <a href=\"guild_profile.php?id=" . $war['guild_id'] . '">' . $guildname . "</a> ganhou a batalha contra o clã <a href=\"guild_profile.php?id=" . $war['enemy_id'] . '">' . $enyname . "</a> e ganhou " . $war['bet'] . " moedas de ouro.</b></font></center>";
		echo "</td></tr>";
	} elseif ($war['status'] == 'e') {
		echo "<tr onclick=\"window.location.href='view_war.php?id=" . $war['id'] . "'\"><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\">";
		echo "<center><font size=\"1px\"><b>O clã <a href=\"guild_profile.php?id=" . $war['enemy_id'] . '">' . $enyname . "</a> ganhou a batalha contra o clã <a href=\"guild_profile.php?id=" . $war['guild_id'] . '">' . $guildname . "</a> e ganhou " . $war['bet'] . " moedas de ouro.</b></font></center>";
		echo "</td></tr>";
	} elseif ($war['status'] == 't' && time() < $war['time']) {
		$i = 0;
		$array = explode(", ", $war['players_guild']);
		foreach ($array as $value) {
			$i += 1;
		}

		$valortempo = $war['time'] - time();
		if ($valortempo < 60) {
      $auxiliar = "segundo(s)";
  } elseif ($valortempo < 3600) {
      $valortempo = ceil($valortempo / 60);
      $auxiliar = "minuto(s)";
  } elseif ($valortempo < 86400) {
      $valortempo = ceil($valortempo / 3600);
      $auxiliar = "hora(s)";
  }

		echo "<tr onclick=\"window.location.href='view_war.php?id=" . $war['id'] . "'\"><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\">";
		echo "<center><font size=\"1px\"><b>O clã <a href=\"guild_profile.php?id=" . $war['guild_id'] . '">' . $guildname . "</a> declarou guerra contra o clã <a href=\"guild_profile.php?id=" . $war['enemy_id'] . '">' . $enyname . "</a>.</b></font></center>";
		echo '<center><font size="1px"><b>A batalha entre ' . ($i * 2) . " jogadores ocorrerá em " . $valortempo . " " . $auxiliar . " e conta com a aposta de " . $war['bet'] . " moedas de ouro.</b></font></center>";
		echo "</td></tr>";
	} else {
		echo "<tr onclick=\"window.location.href='view_war.php?id=" . $war['id'] . "'\"><td class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\">";
		echo "<center><font size=\"1px\"><b>Clique aqui e veja a guerra entre os clãs <a href=\"guild_profile.php?id=" . $war['guild_id'] . '">' . $guildname . '</a> e <a href="guild_profile.php?id=' . $war['enemy_id'] . '">' . $enyname . "</a>.</b></font></center>";
		echo "</td></tr>";
	}
}

echo "</table><br/>";

$bbcode = new bbcode();

$query = $db->execute("select * from `guilds` where `serv`=? order by `members` desc", array($player->serv));
if ($query->recordcount() == 0) {
	echo "<p><i><center>Nenhum clã registrado no momento.</center></i></p>";
} else {
	while ($guild = $query->fetchrow()) {
		echo '<table width="100%">';
		echo "<tr>";
		echo '<td width="135px" class="brown"><center><a href="guild_profile.php?id=' . $guild['id'] . '"><img src="static/' . $guild['img'] . '" alt="' . $guild['name'] . '"  width="128" height="128" border="0"></a></center></td>';
		echo '<td class="salmon"><center><b><a href="guild_profile.php?id=' . $guild['id'] . '">' . $guild['name'] . "</a></b></center>";
		$guilddes = stripslashes($guild['blurb']);
		$guilddes = $bbcode->parse($guilddes);
		$guilddes = strip_tags($guilddes);
		echo textLimit($guilddes, 300, 80);
		echo "</td>";

		echo '<td width="15%" class="brown">';
		echo '<table width="100%">';
		echo "<tr>";
		echo '<td align=center><font size="1"><b>Reino</b><br/>';
		if ($guild['reino'] == 1) {
      echo "Cathal";
  } elseif ($guild['reino'] == 2) {
      echo "Eroda";
  } elseif ($guild['reino'] == 3) {
      echo "Turkic";
  } else {
			echo "Nenhum";
		}
  
		echo "</font></td>";
		echo "</tr>";
		echo "<tr>";
		echo '<td align=center><font size="1"><b>Membros</b><br/>' . $guild['members'] . "</font></td>";
		echo "</tr>";
		echo "<tr>";
		echo '<td align=center><font size="1"><b>Pontos</b><br/>XXX</font></td>';
		echo "</tr>";
		echo "<tr>";
		echo "<td align=center>";
		$checkquery = $db->execute("select count(*) inv_count from guild_invites where player_id =? and guild_id =?", array($player->id, $guild['id']));
		$check = $checkquery->fetchrow();
		if ($check['inv_count'] > 0) {
      echo '<font size="1"><a href="guild_join.php?id=' . $guild['id'] . '">Participar</a></font>';
  } elseif ($player->guild == $guild['id'] && $player->username != $guild['leader'] && $player->username != $guild['vice']) {
      echo '<font size="1"><a href="guild_leave.php">Abandonar</a></font>';
  }
  
		echo "</td>";
		echo "</tr>";
		echo "</table>";
		echo "</td>";
		echo "</tr>";
		echo "</table>";
	}
}

include(__DIR__ . "/templates/private_footer.php");
