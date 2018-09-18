<?php
include("lib.php");
define("PAGENAME", "Fórum");
$player = check_user($secret_key, $db);

include("checkforum.php");
include("templates/private_header.php");
include_once('pagination.class.php');

$items = 15;
$page = 1;


if ((!$_GET['cat']) or (($_GET['cat'] != 'noticias') and ($_GET['cat'] != 'reino') and ($_GET['cat'] != 'sugestoes') and ($_GET['cat'] != 'gangues') and ($_GET['cat'] != 'trade') and ($_GET['cat'] != 'duvidas') and ($_GET['cat'] != 'fan') and ($_GET['cat'] != 'outros') and ($_GET['cat'] != 'off')))
{
	echo "Nenhuma categoria foi selecionada! <a href=\"select_forum.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
}

$cate = $_GET['cat'];

if ($cate == 'gangues') {
$categoria = "Clãs";
}elseif ($cate == 'trade') {
$categoria = "Compro/Vendo";
}elseif ($cate == 'noticias') {
$categoria = "Notícias";
}elseif ($cate == 'sugestoes') {
$categoria = "Sugestões";
}elseif ($cate == 'duvidas') {
$categoria = "Dúvidas";
}elseif ($cate == 'fan') {
$categoria = "Fanwork";
}elseif ($cate == 'off') {
$categoria = "Off-Topic";
}else{
$categoria = $cate;
}

if ($_GET['success'] == 'true'){
	echo showAlert("Tópico postado com sucesso!");
}


echo "<b><font size=\"1\"><a href=\"select_forum.php\">Fóruns</a> -> <a href=\"main_forum.php?cat=" . $cate . "\">" . ucfirst($categoria) . "</a></font></b>";


if (isset($_GET['page']) and is_numeric($_GET['page']) and $page = $_GET['page']) {
         $limit = "limit ".(($page-1)*$items).",$items";
} else {
         $limit = "limit $items";
}

	if (($cate == 'gangues') or ($cate == 'trade') or ($cate == 'reino')){
		if ($cate == 'reino'){
			$sqlStr = "SELECT SQL_CACHE * FROM forum_question WHERE category='$cate' and serv='$player->serv' and reino='$player->reino' ORDER BY fixo ASC, last_post DESC $limit";
			$sqlStrAux = "SELECT count(*) as total FROM forum_question WHERE category='$cate' and serv='$player->serv' and reino='$player->reino' ORDER BY fixo ASC, last_post DESC";
		}else{
			$sqlStr = "SELECT SQL_CACHE * FROM forum_question WHERE category='$cate' and serv='$player->serv' ORDER BY fixo ASC, last_post DESC $limit";
			$sqlStrAux = "SELECT count(*) as total FROM forum_question WHERE category='$cate' and serv='$player->serv' ORDER BY fixo ASC, last_post DESC";
		}
	}else{
		$sqlStr = "SELECT SQL_CACHE * FROM forum_question WHERE category='$cate' ORDER BY fixo ASC, last_post DESC $limit";
		$sqlStrAux = "SELECT count(*) as total FROM forum_question WHERE category='$cate' ORDER BY fixo ASC, last_post DESC";
	}

$aux = Mysql_Fetch_Assoc(mysql_query($sqlStrAux));
$query = $db->execute($sqlStr, $values);


echo "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"1\" bgcolor=\"#CCCCCC\">";
echo "<tr>";
echo "<th width=\"55%\" align=\"center\" bgcolor=\"#E1CBA4\">Tópico</th>";
echo "<th width=\"15%\" align=\"center\" bgcolor=\"#E1CBA4\">Visitas</th>";
echo "<th width=\"15%\" align=\"center\" bgcolor=\"#E1CBA4\">Respostas</th>";
echo "<th width=\"15%\" align=\"center\" bgcolor=\"#E1CBA4\"><font size=1>Última Postagem</font></th>";
echo "</tr>";

if ($aux['total'] > 0) {

         $p = new pagination;
         $p->Items($aux['total']);
         $p->limit($items);
         $p->target("?cat=" . $_GET['cat'] . "");
         $p->currentPage($page);

         while($rows = $query->fetchrow()){
		echo "<tr class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\"><td>";
			if ($rows['fixo'] == 't'){
			echo "<b>Fixo:</b> ";
			}else if($rows['closed'] == 't'){
			echo "<b>Fechado:</b> ";
			}
		$total_answers = $db->execute("select * from `forum_answer` where `question_id`=?", array($rows['id']));
		$pagenumber = ceil($total_answers->recordcount() / 5);
		if ($pagenumber < 1){
			$pagenumber = 1;
		}
		echo "<b><a href=\"view_topic.php?page=" . $pagenumber . "&id=" . $rows['id'] . "\">" . textLimit(stripslashes($rows['topic']), 75) . "</a></b><br />";

			if ($rows['reply'] > 0){
			$lastpostid = $db->GetOne("select SQL_CACHE `a_user_id` from `forum_answer` where `question_id`=? order by `a_datetime` DESC", array($rows['id']));
				echo "<font size=\"1\">Último post por " . showName($lastpostid, $db) . "</font></td>";
			}else{
				echo "<font size=\"1\">Iniciado por " . showName($rows['user_id'], $db) . "</font></td>";
			}
	
		echo "<td align=\"center\">" . $rows['view'] . "</td>";
		echo "<td align=\"center\">" . $rows['reply'] . "</td>";
		echo "<td align=\"center\">" . date("d/m/y H:i:s", $rows['last_post']) . "</td>";
		echo "</tr>";
            }
 
} else {
	echo "<tr class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\"><td align=\"center\"><b>Nenhum tópico encontrado.</b></td><td align=\"center\">#</td><td align=\"center\">#</td><td align=\"center\">#</td></tr>";
}

echo "<tr>";
echo "<td colspan=\"5\" align=\"right\" bgcolor=\"#E1CBA4\"><a href=\"create_topic.php?category=" . $cate . "\"><strong>Criar novo Tópico</strong> </a></td>";
echo "</tr>";
echo "</table>";

if ($aux['total'] > 0) {
         $p->show();
}

include("templates/private_footer.php");
?>