<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Frum");
$player = check_user($db);

include(__DIR__ . "/checkforum.php");
include(__DIR__ . "/templates/private_header.php");
?>
<script type="text/javascript" src="static/bbeditor/ed.js"></script>
<?php
if (!$_GET['topic'])
{
	echo 'Um erro desconhecido ocorreu! <a href="main_forum.php">Voltar</a>.';
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

	if ($player->gm_rank > 2) {
	$procuramensagem = $db->execute("select `topic`, `detail`, `fixo`, `closed`, `vota` from `forum_question` where `id`=?", [$_GET['topic']]);
	}else{
	$procuramensagem = $db->execute("select `topic`, `detail`, `fixo`, `closed`, `vota` from `forum_question` where `id`=? and `user_id`=?", [$_GET['topic'], $player->id]);
	}
 
	if ($procuramensagem->recordcount() == 0)
	{
	echo "Voc no pode editar este topico! <a href=\"main_forum.php\">Voltar</a>.";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
	}

 $editmsg = $procuramensagem->fetchrow();
 $texto = $editmsg['detail'];
 $quebras = ['<br />', '<br>', '<br/>'];
 $editandomensagem = str_replace($quebras, "", $texto);
 
if(isset($_POST['submit']))
{

if (!$_POST['detail'])
{
	echo "Voc precisa preencher todos os campos! <a href=\"edit_topic.php?topic=" . $_GET['topic'] . '">Voltar</a>.';
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

$novaresposto=strip_tags($_POST['detail']);
	$quebras = ['<br />', '<br>', '<br/>'];
	$newresposta = str_replace($quebras, "\n", $novaresposto);
$texto=nl2br($newresposta);

$fixo = !$_POST['fixo'] || $player->gm_rank < 2 ? "f" : "t";

$closed = !$_POST['closed'] || $player->gm_rank < 2 ? "f" : "t";

$vota = !$_POST['vota'] || $player->gm_rank < 2 ? "f" : "t";

$real = $db->execute("update `forum_question` set `topic`=?, `detail`=?, `fixo`=?, `closed`=?, `vota`=? where `id`=?", [$_POST['topic'], $texto, $fixo, $closed, $vota, $_GET['topic']]);
	echo 'Postagem editada com sucesso! <a href="view_topic.php?id=' . $_GET['topic'] . '">Voltar</a>.';
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

?>

<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
<tr>
<form method="POST" action="edit_topic.php?topic=<?=$_GET['topic']?>">
<td>
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
<tr>
<td colspan="3" bgcolor="#E6E6E6"><strong>Editar Tpico</strong> </td>
</tr>
<tr>
<td><b>Titulo:</b>&nbsp;&nbsp;<input name="topic" type="text" size="35" value="<?=$editmsg['topic'];?>"/></td>
</tr>
<tr>
<td><script>edToolbar('detail'); </script><textarea name="detail" rows="12" id="detail" class="ed"><?=$editandomensagem?></textarea></td>
</tr>
<tr>
<td><input type="submit" name="submit" value="Enviar" />
<?php 
if ($player->gm_rank > 2){
	if ($editmsg['fixo'] == 't'){
	echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"fixo\" VALUE=\"yes\" checked> Fixar Tpico";
	}else{
	echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"fixo\" VALUE=\"yes\"> Fixar Tpico";
	}

	if ($editmsg['closed'] == 't'){
	echo '&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="closed" VALUE="yes" checked> Fechado';
	}else{
	echo '&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="closed" VALUE="yes"> Fechado';
	}
}

	if ($editmsg['vota'] == 't'){
	echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"vota\" VALUE=\"yes\" checked> Ativar Votao";
	}else{
	echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"vota\" VALUE=\"yes\"> Ativar Votao";
	}
 
?></td>
</tr>
</table>
</td>
</form>
</tr>
</table>
<?php
include(__DIR__ . "/templates/private_footer.php");
?>
