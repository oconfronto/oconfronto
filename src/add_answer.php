<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Principal");
$player = check_user($db);

include(__DIR__ . "/checkforum.php");
include(__DIR__ . "/templates/private_header.php");
$tbl_name="forum_answer"; // Table name

// Get value of id that sent from hidden field
$id=$_POST['id'];

if (!$_POST['a_answer']) {
		echo "<fieldset><legend><b>Erro</b></legend>Você precisa preencher todos os campos!<BR>";
		echo "<a href='view_topic.php?id=".$id."'>Voltar</a></fieldset>";
            include(__DIR__ . "/templates/private_footer.php");
            exit;
}

$fecxhado = $db->GetOne("select `closed` from `forum_question` where `id`=?", [$id]);
if ($fecxhado['closed'] == 't'){
		echo "<fieldset><legend><b>Erro</b></legend>Este tópico está fechado.<BR>";
		echo "<a href='view_topic.php?id=".$id."'>Voltar</a></fieldset>";
            include(__DIR__ . "/templates/private_footer.php");
            exit;
}

$foruminfo = $db->execute("select * from `forum_question` where `id`=?", [$id]);
if ($foruminfo->recordcount() != 1){
		echo "<fieldset><legend><b>Erro</b></legend>Este tópico não existe.<BR>";
		echo "<a href='select_forum.php'>Voltar</a></fieldset>";
            include(__DIR__ . "/templates/private_footer.php");
            exit;
}


$categoryae = $db->GetOne("select `category` from `forum_question` where `id`=?", [$id]);
$servae = $db->GetOne("select `serv` from `forum_question` where `id`=?", [$id]);
if (($categoryae == 'gangues' || $categoryae == 'trade') && $player->serv != $servae){
		echo "<fieldset><legend><b>Erro</b></legend>Você não pode postar aqui.<BR>";
		echo "<a href='select_forum.php'>Voltar</a></fieldset>";
            include(__DIR__ . "/templates/private_footer.php");
            exit;
}

// Find highest answer number.
$sql=sprintf("SELECT MAX(a_id) AS Maxa_id FROM %s WHERE question_id='%s'", $tbl_name, $id);
$result=$db->execute($sql);
$rows=$result->fetchrow();

// add + 1 to highest answer number and keep it in variable name "$Max_id". if there no answer yet set it = 1
$Max_id = $rows ? $rows['Maxa_id']+1 : 1;


// get values that sent from form
$a_answer=$_POST['a_answer'];

$notavelreply=strip_tags((string) $a_answer);
$texto=nl2br($notavelreply);

$time = time();
$datetime=date("d/m/y H:i:s");

// Insert answer
$sql2=sprintf("INSERT INTO %s(question_id, a_id, a_user_id, a_answer, a_datetime)VALUES('%s', '%s', '%s', '%s', '%d')", $tbl_name, $id, $Max_id, $player->id, $texto, $time);
$sql4 = $db->execute("update `forum_question` set `last_post`=?, `last_post_date`=? where `id`=?", [time(), $datetime, $id]);
$sql5 = $db->execute("update `players` set `posts`=`posts`+1 where `id`=?", [$player->id]);
$result2=$db->execute($sql2);

if($result2){
echo "<fieldset><legend><b>Sucesso</b></legend>Mensagem enviada com sucesso!<BR>";

	$total_answers = $db->execute("select `a_id` from `forum_answer` where `question_id`=?", [$id]);
	$pagenumber = ceil($total_answers->recordcount() / 5);
	if ($pagenumber < 1){
		$pagenumber = 1;
	}

echo '<a href="view_topic.php?page=' . $pagenumber . "&id=" . $id . '">Visualizar sua mensagem</a></fieldset>';

// If added new answer, add value +1 in reply column
$tbl_name2="forum_question";
$sql3=sprintf("UPDATE %s SET reply='%s' WHERE id='%s'", $tbl_name2, $Max_id, $id);
$result3=$db->execute($sql3);

}
else {
echo "<fieldset><legend><b>Erro</b></legend>Um erro inesperado ocorreu.<BR>";
echo "<a href=select_forum.php>Voltar</a></fieldset>";
}

?>
<?php
include(__DIR__ . "/templates/private_footer.php");
?>
