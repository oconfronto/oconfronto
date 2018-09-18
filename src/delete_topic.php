<?php

include("lib.php");
define("PAGENAME", "Fórum");
$player = check_user($secret_key, $db);

include("templates/private_header.php");

if (!$_GET['topic'])
{
	echo "Um erro desconhecido ocorreu! <a href=\"main_forum.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
}
	if ($player->gm_rank > 2){
	$procuramensagem = $db->execute("select `topic`, `detail` from `forum_question` where `id`=?", array($_GET['topic']));
	}else{
	$procuramensagem = $db->execute("select `topic`, `detail` from `forum_question` where `id`=? and `user_id`=?", array($_GET['topic'], $player->id));
	}
	if ($procuramensagem->recordcount() == 0)
	{
	echo "Você não pode apagar este tópico! <a href=\"main_forum.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
	}
	else
	{
		
		$editmsg = $procuramensagem->fetchrow();
		$editandomensagem = "" . $editmsg['detail'] . "";
	}
if(isset($_POST['submit']))
{
	if ($player->gm_rank > 2){
	$logalert2 = "O tópico " . $editmsg['topic'] . " foi deletado pelo moderador <b>" . $player->username . "</b>";
	forumlog($logalert2, $db);
	}

	$removeposts = $db->execute("select `a_user_id` from `forum_answer` where `question_id`=?", array($_GET['topic']));
	while($player = $removeposts->fetchrow())
	{
	$query = $db->execute("update `players` set `posts`=`posts`-1 where `id`=?", array($player['a_user_id']));
	}

	$removeposts2 = $db->execute("select `user_id` from `forum_question` where `id`=?", array($_GET['topic']));
	$player2 = $removeposts2->fetchrow();
	$query = $db->execute("update `players` set `posts`=`posts`-1 where `id`=?", array($player2['user_id']));

        $real = $db->execute("delete from `forum_question` where `id`=?", array($_GET['topic']));
        $real = $db->execute("delete from `forum_answer` where `question_id`=?", array($_GET['topic']));
        $real = $db->execute("delete from `thumb` where `topic_id`=?", array($_GET['topic']));
	echo "Tópico removido com sucesso! <a href=\"main_forum.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
}

?>

<table width="500" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
<tr>
<form method="POST" action="delete_topic.php?topic=<?=$_GET['topic']?>">
<td>
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
<tr>
<td colspan="3" bgcolor="#E6E6E6"><strong>Deseja apagar este tópico?</strong></td>
</tr>
<tr>
<td><?=$editandomensagem?></td>
</tr>
<tr>
<td><br><input type="submit" name="submit" value="Apagar" /></td>
</tr>
</table>
</td>
</form>
</tr>
</table>
<?php
include("templates/private_footer.php");
?>