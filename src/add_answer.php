<?php
include("lib.php");
define("PAGENAME", "Principal");
$player = check_user($secret_key, $db);

include("checkforum.php");
include("templates/private_header.php");
$tbl_name="forum_answer"; // Table name

// Get value of id that sent from hidden field
$id=$_POST['id'];

if (!$_POST['a_answer']) {
		echo "<fieldset><legend><b>Erro</b></legend>Você precisa preencher todos os campos!<BR>";
		echo "<a href='view_topic.php?id=".$id."'>Voltar</a></fieldset>";
            include("templates/private_footer.php");
            exit;
}

$fecxhado = $db->GetOne("select `closed` from `forum_question` where `id`=?", array($id));
if ($fecxhado['closed'] == 't'){
		echo "<fieldset><legend><b>Erro</b></legend>Este tópico está fechado.<BR>";
		echo "<a href='view_topic.php?id=".$id."'>Voltar</a></fieldset>";
            include("templates/private_footer.php");
            exit;
}

$foruminfo = $db->execute("select * from `forum_question` where `id`=?", array($id));
if ($foruminfo->recordcount() != 1){
		echo "<fieldset><legend><b>Erro</b></legend>Este tópico não existe.<BR>";
		echo "<a href='select_forum.php'>Voltar</a></fieldset>";
            include("templates/private_footer.php");
            exit;
}


$categoryae = $db->GetOne("select `category` from `forum_question` where `id`=?", array($id));
$servae = $db->GetOne("select `serv` from `forum_question` where `id`=?", array($id));
if ((($categoryae == 'gangues') or ($categoryae == 'trade')) and ($player->serv != $servae)){
		echo "<fieldset><legend><b>Erro</b></legend>Você não pode postar aqui.<BR>";
		echo "<a href='select_forum.php'>Voltar</a></fieldset>";
            include("templates/private_footer.php");
            exit;
}

// Find highest answer number.
$sql="SELECT MAX(a_id) AS Maxa_id FROM $tbl_name WHERE question_id='$id'";
$result=mysql_query($sql);
$rows=mysql_fetch_array($result);

// add + 1 to highest answer number and keep it in variable name "$Max_id". if there no answer yet set it = 1
if ($rows) {
$Max_id = $rows['Maxa_id']+1;
}
else {
$Max_id = 1;
}


// get values that sent from form
$a_answer=$_POST['a_answer'];

$notavelreply=strip_tags($a_answer);
$texto=nl2br($notavelreply);

$time = time();
$datetime=date("d/m/y H:i:s");

// Insert answer
$sql2="INSERT INTO $tbl_name(question_id, a_id, a_user_id, a_answer, a_datetime)VALUES('$id', '$Max_id', '$player->id', '$texto', '$time')";
$sql4 = $db->execute("update `forum_question` set `last_post`=?, `last_post_date`=? where `id`=?", array(time(), $datetime, $id));
$sql5 = $db->execute("update `players` set `posts`=`posts`+1 where `id`=?", array($player->id));
$result2=mysql_query($sql2);

if($result2){
echo "<fieldset><legend><b>Sucesso</b></legend>Mensagem enviada com sucesso!<BR>";

	$total_answers = $db->execute("select `a_id` from `forum_answer` where `question_id`=?", array($id));
	$pagenumber = ceil($total_answers->recordcount() / 5);
	if ($pagenumber < 1){
		$pagenumber = 1;
	}

echo "<a href=\"view_topic.php?page=" . $pagenumber . "&id=" . $id . "\">Visualizar sua mensagem</a></fieldset>";

// If added new answer, add value +1 in reply column
$tbl_name2="forum_question";
$sql3="UPDATE $tbl_name2 SET reply='$Max_id' WHERE id='$id'";
$result3=mysql_query($sql3);

}
else {
echo "<fieldset><legend><b>Erro</b></legend>Um erro inesperado ocorreu.<BR>";
echo "<a href=select_forum.php>Voltar</a></fieldset>";
}

mysql_close();
?>
<?php
include("templates/private_footer.php");
?>