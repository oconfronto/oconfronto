<?php

declare(strict_types=1);

/*************************************/
/*           ezRPG script            */
/*         Written by Khashul        */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.bbgamezone.com/     */
/*************************************/

include(__DIR__ . "/lib.php");
define("PAGENAME", "Membros do Clã");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkguild.php");

//Populates $guild variable
$query = $db->execute(sprintf("select * from `guilds` where `name` like '%s'", $player->guild));

if ($query->recordcount() == 0) {
	header("Location: home.php");
} else {
	$guild = $query->fetchrow();
}

$total_players = $db->getone(sprintf("select count(ID) as `count` from `players` where `guild` like '%s'", $player->guild));

include(__DIR__ . "/templates/private_header.php");
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
		$query = $db->execute(sprintf("select `id`, `username`, `level`, `hp` from `players` where `guild` like '%s' order by `level` desc", $player->guild));

		while ($member = $query->fetchrow()) {
			echo "<tr>\n";
			echo '<td><a href="profile.php?id=' . $member['username'] . '">';
			echo (($member['username'] ?? null) == $player->username) ? "<b>" : "";
			echo $member['username'] ?? null;
			echo (($member['username'] ?? null) == $player->username) ? "</b>" : "";
			echo "</a></td>\n";
			echo "<td>" . $member['level'] . "</td>\n";
			echo "<td>";
			if (($member['hp'] ?? null) < 1) {
				echo '<font color="red">Morto</font>';
			} else {
				echo '<font color="green">Vivo</font>';
			}

			echo "</td>\n";
			echo '<td><a href="mail.php?act=compose&to=' . $member['username'] . '">Mensagem</a></td>';
			echo "</tr>";
		}
		?>
	</table>
</fieldset>

<?php include(__DIR__ . "/templates/private_footer.php");
?>