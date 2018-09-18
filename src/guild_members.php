<?php

/*************************************/
/*           ezRPG script            */
/*         Written by Khashul        */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.bbgamezone.com/     */
/*************************************/

include("lib.php");
define("PAGENAME", "Membros do Clã");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkguild.php");

//Populates $guild variable
$query = $db->execute("select * from `guilds` where `name` like '$player->guild'");

if ($query->recordcount() == 0) {
    header("Location: home.php");
} else {
    $guild = $query->fetchrow();
}

$total_players = $db->getone("select count(ID) as `count` from `players` where `guild` like '$player->guild'");

include("templates/private_header.php");
?>

<fieldset>
<legend><b>Membros do Clã</b></legend>
<table width="100%" border="0">
<tr>
<th width="35%"><b>Usuário</b></td>
<th width="15%"><b>Nivel</b></td>
<th width="20%"><b>Status</b></td>
<th width="30%"><b>Opções</b></td>
</tr>
<?php
//Select all members ordered by level (highest first, members table also doubles as rankings table)
$query = $db->execute("select `id`, `username`, `level`, `hp` from `players` where `guild` like '$player->guild' order by `level` desc");

while($member = $query->fetchrow())
{
	echo "<tr>\n";
	echo "<td><a href=\"profile.php?id=" . $member['username'] . "\">";
	echo ($member['username'] == $player->username)?"<b>":"";
	echo $member['username'];
	echo ($member['username'] == $player->username)?"</b>":"";
	echo "</a></td>\n";
	echo "<td>" . $member['level'] . "</td>\n";
	echo "<td>";
	if ($member['hp'] < 1) {
	echo "<font color=\"red\">Morto</font>";
	}else{
	echo "<font color=\"green\">Vivo</font>";
	}
	echo "</td>\n";
	echo "<td><a href=\"mail.php?act=compose&to=" . $member['username'] . "\">Mensagem</a></td>";
	echo "</tr>";
}
?>
</table>
</fieldset>

<?php include("templates/private_footer.php");
?>