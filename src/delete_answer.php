<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Frum");
$player = check_user($secret_key, $db);

include(__DIR__ . "/templates/private_header.php");

if ((!$_GET['topic'] | !$_GET['a']) !== 0)
{
	echo 'Um erro desconhecido ocorreu! <a href="main_forum.php">Voltar</a>.';
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

	$procuramensagem = $db->execute("select * from `forum_answer` where `question_id`=? and `id`=?", array($_GET['topic'], $_GET['a']));
	if ($procuramensagem->recordcount() == 0)
	{
	echo "Voc no pode apagar esta mensagem! <a href=\"main_forum.php\">Voltar</a>.";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
	}
 
		$editmsg = $procuramensagem->fetchrow();

	if ($editmsg['a_user_id'] != $player->id && $player->gm_rank < 3)
	{
	echo "Voc no pode apagar esta mensagem! <a href=\"main_forum.php\">Voltar</a>.";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
	}
 $editandomensagem = $editmsg['a_answer'];
 
if(isset($_POST['submit']))
{
        $removeposts = $db->execute("select `a_user_id` from `forum_answer` where `question_id`=? and `id`=? ", array($_GET['topic'], $_GET['a']));
	$player = $removeposts->fetchrow();
	$query = $db->execute("update `players` set `posts`=`posts`-1 where `id`=?", array($player['a_user_id']));

        $real = $db->execute("delete from `forum_answer` where `question_id`=? and `id`=? ", array($_GET['topic'], $_GET['a']));
	$real2 = $db->execute("update `forum_question` set `reply`=`reply`-1 where `id`=?", array($_GET['topic']));
	echo 'Postagem removida com sucesso! <a href="view_topic.php?id=' . $_GET['topic'] . '">Voltar</a>.';
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

?>

<table width="500" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
<tr>
<form method="POST" action="delete_answer.php?topic=<?=$_GET['topic']?>&a=<?=$_GET['a']?>">
<td>
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
<tr>
<td colspan="3" bgcolor="#E6E6E6"><strong>Deseja apagar esta mensagem?</strong></td>
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
include(__DIR__ . "/templates/private_footer.php");
?>
