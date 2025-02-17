<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Membros");
$player = check_user($db);

$limit = 10;

$page = (intval($_GET['page']) == 0) ? 1 : intval($_GET['page']); //Start on page 1 or $_GET['page']

$begin = ($limit * $page) - $limit; //Starting point for query

if (($_GET['voctype'] ?? null) == 'archer') {
	$searchvoc = "and `voc`='archer'";
} elseif (($_GET['voctype'] ?? null) == 'knight') {
	$searchvoc = "and `voc`='knight'";
} elseif (($_GET['voctype'] ?? null) == 'mage') {
	$searchvoc = "and `voc`='mage'";
} else {
	$searchvoc = "";
}

if (($_GET['reino'] ?? null) == 1) {
	$searchrei = "and `reino`='1'";
} elseif (($_GET['reino'] ?? null) == 2) {
	$searchrei = "and `reino`='2'";
} elseif (($_GET['reino'] ?? null) == 3) {
	$searchrei = "and `reino`='3'";
} else {
	$searchrei = "";
}

$total_players = $db->getone(sprintf("select count(ID) as `count` from `players` where `reino`!='0' and `serv`=? %s %s", $searchvoc, $searchrei), [$player->serv]);

include(__DIR__ . "/templates/private_header.php");

if ($_GET['error'] ?? null) {
	echo showAlert("Usuário não encontrado.", "red");
}

echo '<style>
@media only screen and (max-width: 600px) {
	table.brown, table.salmon {
		width: 100%;
		font-size: 14px;
	}
	table.brown td, table.salmon td {
		display: block;
		width: 100%;
	}
	table.brown select, table.salmon select, table.brown input, table.salmon input {
		width: 100%;
	}
	table.brown td center, table.salmon td center {
		text-align: left;
	}
	table.brown th, table.salmon th {
		display: none;
	}
	table.brown tr, table.salmon tr {
		display: block;
		margin-bottom: 10px;
	}
	table.brown tr.row1, table.brown tr.row2 {
		display: block;
		margin-bottom: 10px;
	}
	table.brown tr.row1 td, table.brown tr.row2 td {
		display: block;
		width: 100%;
	}
	table.brown tr.row1 td div, table.brown tr.row2 td div {
		position: relative;
	}
	table.brown tr.row1 td div img, table.brown tr.row2 td div img {
		width: 100%;
		height: auto;
	}
}
</style>';

echo "<form method=\"get\" action=\"members.php\">\n";
echo "<table width=\"100%\" class=\"brown\"  style='border:1px solid #b6804e;height:28px;'><tr>";
echo '<td width="16%"><center>';
echo "<b>Pág:</b>&nbsp;<select name=\"page\">";
$numpages = $total_players / $limit;
for ($i = 1; $i <= $numpages; ++$i) {
	//Display page numbers
	echo ($i == $page) ? '<option value="' . $i . '" selected="' . $i . '">' . $i . "</option>" : '<option value="' . $i . '">' . $i . "</option>";
}

if (($total_players % $limit) != 0) {
	//Display last page number if there are left-over users in the query
	echo ($i == $page) ? '<option value="' . $i . '" selected="' . $i . '">' . $i . "</option>" : '<option value="' . $i . '">' . $i . "</option>";
}

echo "</select>";
echo "</center></td>";

if (!($_GET['orderby'] ?? null)) {
	$selecum = "selected";
} elseif (($_GET['orderby'] ?? null) == "level") {
	$selecum = "selected";
} elseif (($_GET['orderby'] ?? null) == "gold") {
	$selecdois = "selected";
} elseif (($_GET['orderby'] ?? null) == "kills") {
	$selectres = "selected";
} elseif (($_GET['orderby'] ?? null) == "monsterkilled") {
	$selecquatro = "selected";
} else {
	$selecum = "selected";
}

echo '<td width="32%"><center><b>Ordem:</b>&nbsp;<select name="orderby"><option value="level" ' . $selecum . ">Nível</option><option value=\"gold\" " . $selecdois . '>Ouro</option><option value="kills" ' . $selectres . '>Assassinatos</option><option value="monsterkilled" ' . $selecquatro . ">Monstros mortos</option></select></center></td>";


if (!($_GET['reino'] ?? null)) {
	$selecum = "selected";
} elseif (($_GET['reino'] ?? null) == 0) {
	$selecum = "selected";
} elseif (($_GET['reino'] ?? null) == 1) {
	$selecdois = "selected";
} elseif (($_GET['reino'] ?? null) == 2) {
	$selectres = "selected";
} elseif (($_GET['reino'] ?? null) == 3) {
	$selecquatro = "selected";
} else {
	$selecum = "selected";
}

echo '<td width="23%"><center><b>Reino:</b>&nbsp;<select name="reino"><option value="0" ' . $selecum . '>Qualquer</option><option value="1" ' . $selecdois . '>Cathal</option><option value="2" ' . $selectres . '>Eroda</option><option value="3" ' . $selecquatro . ">Turkic</option></select></center></td>";

if (!($_GET['voctype'] ?? null)) {
	$selvocum = "selected";
} elseif (($_GET['voctype'] ?? null) == "all") {
	$selvocum = "selected";
} elseif (($_GET['voctype'] ?? null) == "archer") {
	$selvocdois = "selected";
} elseif (($_GET['voctype'] ?? null) == "knight") {
	$selvoctres = "selected";
} elseif (($_GET['voctype'] ?? null) == "mage") {
	$selvocquatro = "selected";
} else {
	$selvocum = "selected";
}

echo '<td width="23%"><center><b>Voc:</b>&nbsp;<select name="voctype"><option value="all" ' . $selvocum . '>Todas</option><option value="archer" ' . $selvocdois . '>Arqueiro</option><option value="knight" ' . $selvoctres . '>Cavaleiro</option><option value="mage" ' . $selvocquatro . ">Mago</option></select></center></td>";
echo '<td width="6%"><center><input type="submit" id="link" class="aff" value="Ir"></center></td>';
echo "</tr></table>";
echo "</form>";

if (!($_GET['orderby'] ?? null)) {
	$ordenarpor = "order by `level` desc, `exp` desc";
} elseif (($_GET['orderby'] ?? null) == "level") {
	$ordenarpor = "order by `level` desc, `exp` desc";
} elseif (($_GET['orderby'] ?? null) == "gold") {
	$ordenarpor = "order by `gold`+`bank` desc";
} elseif (($_GET['orderby'] ?? null) == "kills") {
	$ordenarpor = "order by `kills` desc";
} elseif (($_GET['orderby'] ?? null) == "monsterkilled") {
	$ordenarpor = "order by `monsterkilled` desc";
} else {
	$ordenarpor = "order by `level` desc, `exp` desc";
}

//Select all members ordered by level (highest first, members table also doubles as rankings table)
$query = $db->execute(sprintf("select `id`, `username`, `gm_rank`, `level`, `guild`, `avatar`, `voc`, `promoted` from `players` where `gm_rank`<10 and `reino`!='0' and `serv`=? %s %s %s limit ?,?", $searchvoc, $searchrei, $ordenarpor), [$player->serv, $begin, $limit]);

if ($query->recordcount() > 0) {
	echo '<br/><table width="100%" border="0">';
	echo "<tr>";
	echo '<th><b>Imagem</b></td>';
	echo "<th><b>Usuário</b></td>";
	echo '<th><b>Nivel</b></td>';
	echo "<th><b>Vocação</b></td>";
	echo "<th><b>Opções</b></td>";
	echo "</tr>";

	$bool = 1;
	while ($member = $query->fetchrow()) {
		echo '<tr class="row' . $bool . "\">\n";

		echo '<td style="height:64px"><div style="position: relative;">';
		echo '<img src="' . ($member['avatar'] ? $member['avatar'] : "static/anonimo.gif") . '" width="64px" height="64px" style="position: absolute; top: 1; left: 1;" alt="' . $member['username'] . '" border="0">';

		$checkranknosite = $db->execute("select `time` from `user_online` where `player_id`=?", [$member['id'] ?? null]);
		if ($checkranknosite->recordcount() > 0) {
			echo "<a href=\"javascript:void(0)\" onclick=\"javascript:chatWith('" . str_replace(" ", "_", $member['username']) . "')\"><img src=\"static/images/online1.png\" width=\"64px\" height=\"64px\" style=\"position: absolute; top: 1; left: 1;\" alt=\"" . $member['username'] . '" border="0px"></a>';
		}

		echo "</div></td>";

		echo "<td>";
		if (($member['guild'] ?? null) != NULL) {
			$gtag = $db->GetOne("select `tag` from `guilds` where `id`=?", [$member['guild'] ?? null]);
			echo "[" . $gtag . "] ";
		}

		echo '<a href="profile.php?id=' . $member['username'] . '">';
		echo (($member['username'] ?? null) == $player->username) ? "<b>" : "";
		echo $member['username'] ?? null;
		echo (($member['username'] ?? null) == $player->username) ? "</b>" : "";
		echo "</a></td>\n";

		echo "<td>" . $member['level'] . "</td>\n";
		echo "<td>";

		if (($member['voc'] ?? null) == 'archer' && ($member['promoted'] ?? null) == 'f') {
			echo "Caçador";
		} elseif (($member['voc'] ?? null) == 'knight' && ($member['promoted'] ?? null) == 'f') {
			echo "Espadachim";
		} elseif (($member['voc'] ?? null) == 'mage' && ($member['promoted'] ?? null) == 'f') {
			echo "Bruxo";
		} elseif (($member['voc'] ?? null) == 'archer' && (($member['promoted'] ?? null) == 't' || ($member['promoted'] ?? null) == 's' || ($member['promoted'] ?? null) == 'r')) {
			echo "Arqueiro";
		} elseif (($member['voc'] ?? null) == 'knight' && (($member['promoted'] ?? null) == 't' || ($member['promoted'] ?? null) == 's' || ($member['promoted'] ?? null) == 'r')) {
			echo "Guerreiro";
		} elseif (($member['voc'] ?? null) == 'mage' && (($member['promoted'] ?? null) == 't' || ($member['promoted'] ?? null) == 's' || ($member['promoted'] ?? null) == 'r')) {
			echo "Mago";
		} elseif (($member['voc'] ?? null) == 'archer' && ($member['promoted'] ?? null) == 'p') {
			echo "Arqueiro Royal";
		} elseif (($member['voc'] ?? null) == 'knight' && ($member['promoted'] ?? null) == 'p') {
			echo "Cavaleiro";
		} elseif (($member['voc'] ?? null) == 'mage' && ($member['promoted'] ?? null) == 'p') {
			echo "Arquimago";
		}

		echo "</td>\n";
		echo '<td><font size="1"><a href="mail.php?act=compose&to=' . $member['username'] . '">Mensagem</a><br/><a href="battle.php?act=attack&username=' . $member['username'] . '">Lutar</a><br/>+ <a href="friendlist.php?add=' . $member['username'] . "\">Amigo</a></font></td>\n";
		echo "</tr>\n";
		$bool = ($bool == 1) ? 2 : 1;
	}

	echo "</table>";
} else {
	echo "<br/><center><b>Nenhum usuário encontrado.</center><br/>";
}

echo '<table width="100%" border="0"><tr>';
echo '<td width="50%">';
echo ($page != 1) ? '<a href="members.php?limit=' . $limit . "&page=" . ($page - 1) . "&orderby=" . $_GET['orderby'] . "&reino=" . $_GET['reino'] . "&voctype=" . $_GET['voctype'] . "\"><b>Página anterior</b></a>" : "<b>Página anterior</b>";
echo "</td>";
echo '<td width="50%" align="right">';
echo (($total_players - ($limit * $page)) > 0) ? '<a href="members.php?limit=' . $limit . "&page=" . ($page + 1) . "&orderby=" . $_GET['orderby'] . "&reino=" . $_GET['reino'] . "&voctype=" . $_GET['voctype'] . "\"><b>Próxima página</b></a> " : "<b>Próxima página</b>";
echo "</td>";
echo "</tr></table>";

echo "<br/><p><form method=\"get\" action=\"profile.php\">\n";
echo '<table width="100%" align="center" class="salmon">';
echo "<tr><td align=\"center\" bgcolor=\"#E1CBA4\" colspan=\"3\"><b>Procurar por usuário</b></td></tr>";
echo '<tr class="salmon">';
echo "<th width=\"30%\" align=\"center\"><b>Usuário</b>:</th>";
echo '<th width="40%" align="center"><input type="text" name="id" size="30" /></th>';
echo '<th width="30%" align="center"><input id="link" class="aff" type="submit" value="Procurar" /></th>';
echo "</tr>";
echo "</table>";
echo "</form></p>";

include(__DIR__ . "/templates/private_footer.php");

