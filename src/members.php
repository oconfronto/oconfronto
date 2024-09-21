<?php
include("lib.php");
define("PAGENAME", "Membros");
$player = check_user($secret_key, $db);

$limit = 10;

$page = (intval($_GET['page']) == 0)?1:intval($_GET['page']); //Start on page 1 or $_GET['page']

$begin = ($limit * $page) - $limit; //Starting point for query

if ($_GET['voctype'] == 'archer') {
	$searchvoc = "and `voc`='archer'";
} else if ($_GET['voctype'] == 'knight') {
	$searchvoc = "and `voc`='knight'";
} else if ($_GET['voctype'] == 'mage') {
	$searchvoc = "and `voc`='mage'";
}else{
	$searchvoc = "";
}

if ($_GET['reino'] == 1) {
	$searchrei = "and `reino`='1'";
} else if ($_GET['reino'] == 2) {
	$searchrei = "and `reino`='2'";
} else if ($_GET['reino'] == 3) {
	$searchrei = "and `reino`='3'";
}else{
	$searchrei = "";
}

$total_players = $db->getone("select count(ID) as `count` from `players` where `reino`!='0' and `serv`=? $searchvoc $searchrei", array($player->serv));

include("templates/private_header.php");
    
    if ($_GET['error']) {
        echo showAlert("Usuário não encontrado.", "red");
    }

echo "<form method=\"get\" action=\"members.php\">\n";
echo "<table width=\"100%\" class=\"brown\"  style='border:1px solid #b6804e;height:28px;'><tr>";
	echo "<td width=\"16%\"><center>";
		echo "<b>Pág:</b>&nbsp;<select name=\"page\">";
		$numpages = $total_players / $limit;
			for ($i = 1; $i <= $numpages; $i++)
			{
			//Display page numbers
			echo ($i == $page)?"<option value=\"" . $i . "\" selected=\"" . $i . "\">" . $i . "</option>":"<option value=\"" . $i . "\">" . $i . "</option>";
			}

		if (($total_players % $limit) != 0)
		{
			//Display last page number if there are left-over users in the query
			echo ($i == $page)?"<option value=\"" . $i . "\" selected=\"" . $i . "\">" . $i . "</option>":"<option value=\"" . $i . "\">" . $i . "</option>";
		}
		echo "</select>";
	echo "</center></td>";

	if (!$_GET['orderby']) {
	$selecum = "selected";
	} else if ($_GET['orderby'] == level) {
	$selecum = "selected";
	} else if ($_GET['orderby'] == gold) {
	$selecdois = "selected";
	} else if ($_GET['orderby'] == kills) {
	$selectres = "selected";
	} else if ($_GET['orderby'] == monsterkilled) {
	$selecquatro = "selected";
	}else{
	$selecum = "selected";
	}

	echo "<td width=\"32%\"><center><b>Ordem:</b>&nbsp;<select name=\"orderby\"><option value=\"level\" " . $selecum . ">Nível</option><option value=\"gold\" " . $selecdois . ">Ouro</option><option value=\"kills\" " . $selectres . ">Assassinatos</option><option value=\"monsterkilled\" " . $selecquatro . ">Monstros mortos</option></select></center></td>";


	if (!$_GET['reino']) {
	$selecum = "selected";
	} else if ($_GET['reino'] == 0) {
	$selecum = "selected";
	} else if ($_GET['reino'] == 1) {
	$selecdois = "selected";
	} else if ($_GET['reino'] == 2) {
	$selectres = "selected";
	} else if ($_GET['reino'] == 3) {
	$selecquatro = "selected";
	}else{
	$selecum = "selected";
	}

	echo "<td width=\"23%\"><center><b>Reino:</b>&nbsp;<select name=\"reino\"><option value=\"0\" " . $selecum . ">Qualquer</option><option value=\"1\" " . $selecdois . ">Cathal</option><option value=\"2\" " . $selectres . ">Eroda</option><option value=\"3\" " . $selecquatro . ">Turkic</option></select></center></td>";

	if (!$_GET['voctype']) {
	$selvocum = "selected";
	} else if ($_GET['voctype'] == all) {
	$selvocum = "selected";
	} else if ($_GET['voctype'] == archer) {
	$selvocdois = "selected";
	} else if ($_GET['voctype'] == knight) {
	$selvoctres = "selected";
	} else if ($_GET['voctype'] == mage) {
	$selvocquatro = "selected";
	}else{
	$selvocum = "selected";
	}

	echo "<td width=\"23%\"><center><b>Voc:</b>&nbsp;<select name=\"voctype\"><option value=\"all\" " . $selvocum . ">Todas</option><option value=\"archer\" " . $selvocdois . ">Arqueiro</option><option value=\"knight\" " . $selvoctres . ">Cavaleiro</option><option value=\"mage\" " . $selvocquatro . ">Mago</option></select></center></td>";
	echo "<td width=\"6%\"><center><input type=\"submit\" id=\"link\" class=\"aff\" value=\"Ir\"></center></td>";
echo "</tr></table>";
echo "</form>";

if (!$_GET['orderby']) {
$ordenarpor = "order by `level` desc, `exp` desc";
} else if ($_GET['orderby'] == level) {
$ordenarpor = "order by `level` desc, `exp` desc";
} else if ($_GET['orderby'] == gold) {
$ordenarpor = "order by `gold`+`bank` desc";
} else if ($_GET['orderby'] == kills) {
$ordenarpor = "order by `kills` desc";
} else if ($_GET['orderby'] == monsterkilled) {
$ordenarpor = "order by `monsterkilled` desc";
}else{
$ordenarpor = "order by `level` desc, `exp` desc";
}

//Select all members ordered by level (highest first, members table also doubles as rankings table)
$query = $db->execute("select `id`, `username`, `gm_rank`, `level`, `guild`, `avatar`, `voc`, `promoted` from `players` where `gm_rank`<10 and `reino`!='0' and `serv`=? $searchvoc $searchrei $ordenarpor limit ?,?", array($player->serv, $begin, $limit));

if ($query->recordcount() > 0){
echo "<br/><table width=\"100%\" border=\"0\">";
echo "<tr>";
echo "<th width=\"10%\"><b>Imagem</b></td>";
echo "<th width=\"35%\"><b>Usuário</b></td>";
echo "<th width=\"20%\"><b>Nivel</b></td>";
echo "<th width=\"20%\"><b>Vocação</b></td>";
echo "<th width=\"15%\"><b>Opções</b></td>";
echo "</tr>";

$bool = 1;
while($member = $query->fetchrow())
{
	echo "<tr class=\"row" . $bool . "\">\n";

	echo "<td height=\"64px\"><div style=\"position: relative;\">";
	echo "<img src=\"" . $member['avatar'] . "\" width=\"64px\" height=\"64px\" style=\"position: absolute; top: 1; left: 1;\" alt=\"" . $member['username'] . "\" border=\"0\">";

	$checkranknosite = $db->execute("select `time` from `user_online` where `player_id`=?", array($member['id']));
	if ($checkranknosite->recordcount() > 0) {
	echo "<a href=\"javascript:void(0)\" onclick=\"javascript:chatWith('" . str_replace(" ","_",$member['username']) . "')\"><img src=\"images/online1.png\" width=\"64px\" height=\"64px\" style=\"position: absolute; top: 1; left: 1;\" alt=\"" . $member['username'] . "\" border=\"0px\"></a>";
	}

	echo "</div></td>";

	echo "<td>";
	if ($member['guild'] != NULL){
		$gtag = $db->GetOne("select `tag` from `guilds` where `id`=?", array($member['guild']));
		echo "[" . $gtag . "] ";
	}
	echo "<a href=\"profile.php?id=" . $member['username'] . "\">";
	echo ($member['username'] == $player->username)?"<b>":"";
	echo $member['username'];
	echo ($member['username'] == $player->username)?"</b>":"";
	echo "</a></td>\n";

	echo "<td>" . $member['level'] . "</td>\n";
	echo "<td>";

		if ($member['voc'] == 'archer' and $member['promoted'] == 'f'){
		echo "Caçador";
		} else if ($member['voc'] == 'knight' and $member['promoted'] == 'f'){
		echo "Espadachim";
		} else if ($member['voc'] == 'mage' and $member['promoted'] == 'f'){
		echo "Bruxo";
		} else if (($member['voc'] == 'archer') and ($member['promoted'] == 't' or $member['promoted'] == 's' or $member['promoted'] == 'r')){
		echo "Arqueiro";
		} else if (($member['voc'] == 'knight') and ($member['promoted'] == 't' or $member['promoted'] == 's' or $member['promoted'] == 'r')){
		echo "Guerreiro";
		} else if (($member['voc'] == 'mage') and ($member['promoted'] == 't' or $member['promoted'] == 's' or $member['promoted'] == 'r')){
		echo "Mago";
		} else if ($member['voc'] == 'archer' and $member['promoted'] == 'p'){
		echo "Arqueiro Royal";
		} else if ($member['voc'] == 'knight' and $member['promoted'] == 'p'){
		echo "Cavaleiro";
		} else if ($member['voc'] == 'mage' and $member['promoted'] == 'p'){
		echo "Arquimago";
		}
	echo "</td>\n";
	echo "<td><font size=\"1\"><a href=\"mail.php?act=compose&to=" . $member['username'] . "\">Mensagem</a><br/><a href=\"battle.php?act=attack&username=" . $member['username'] . "\">Lutar</a><br/>+ <a href=\"friendlist.php?add=".$member['username']."\">Amigo</a></font></td>\n";
	echo "</tr>\n";
	$bool = ($bool==1)?2:1;
}
echo "</table>";
}else{
	echo "<br/><center><b>Nenhum usuário encontrado.</center><br/>";
}

echo "<table width=\"100%\" border=\"0\"><tr>";
echo "<td width=\"50%\">";
	echo ($page != 1)?"<a href=\"members.php?limit=" . $limit . "&page=" . ($page-1) . "&orderby=" . $_GET['orderby'] . "&reino=" . $_GET['reino'] . "&voctype=" . $_GET['voctype'] . "\"><b>Página anterior</b></a>":"<b>Página anterior</b>";
echo "</td>";
echo "<td width=\"50%\" align=\"right\">";
	echo (($total_players - ($limit * $page)) > 0)?"<a href=\"members.php?limit=" . $limit . "&page=" . ($page+1) . "&orderby=" . $_GET['orderby'] . "&reino=" . $_GET['reino'] . "&voctype=" . $_GET['voctype'] . "\"><b>Próxima página</b></a> ":"<b>Próxima página</b>";
echo "</td>";
echo "</tr></table>";

    echo "<br/><p><form method=\"get\" action=\"profile.php\">\n";
    echo "<table width=\"100%\" align=\"center\" class=\"salmon\">";
    echo "<tr><td align=\"center\" bgcolor=\"#E1CBA4\" colspan=\"3\"><b>Procurar por usuário</b></td></tr>";
    echo "<tr class=\"salmon\">";
    echo "<th width=\"30%\" align=\"center\"><b>Usuário</b>:</th>";
    echo "<th width=\"40%\" align=\"center\"><input type=\"text\" name=\"id\" size=\"30\" /></th>";
    echo "<th width=\"30%\" align=\"center\"><input id=\"link\" class=\"aff\" type=\"submit\" value=\"Procurar\" /></th>";
    echo "</tr>";
    echo "</table>";
    echo "</form></p>";

include("templates/private_footer.php");
?>
