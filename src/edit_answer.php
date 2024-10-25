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
if ((!$_GET['topic'] | !$_GET['a']) !== 0) {
	echo 'Um erro desconhecido ocorreu! <a href="main_forum.php">Voltar</a>.';
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

$procuramensagem = $db->execute("select * from `forum_answer` where `question_id`=? and `id`=?", [$_GET['topic'], $_GET['a']]);
if ($procuramensagem->recordcount() == 0) {
	echo "Voc no pode editar esta mensagem! <a href=\"main_forum.php\">Voltar</a>.";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

$editmsg = $procuramensagem->fetchrow();

if ($editmsg['a_user_id'] != $player->id && $player->gm_rank < 2) {
	echo "Voc no pode editar esta mensagem! <a href=\"main_forum.php\">Voltar</a>.";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

$texto = $editmsg['a_answer'];
$quebras = ['<br />', '<br>', '<br/>'];
$editandomensagem = str_replace($quebras, "", $texto);

if (isset($_POST['submit'])) {

	if (!$_POST['detail']) {
		echo "Voc precisa preencher todos os campos! <a href=\"edit_answer.php?topic=" . $_GET['topic'] . "&a=" . $_GET['a'] . '">Voltar</a>.';
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	$novaresposto = strip_tags((string) $_POST['detail'], '');
	$quebras = ['<br />', '<br>', '<br/>'];
	$newresposta = str_replace($quebras, "\n", $novaresposto);
	$texto = nl2br($newresposta);

	$real = $db->execute("update `forum_answer` set `a_answer`=? where `question_id`=? and `id`=? ", [$texto, $_GET['topic'], $_GET['a']]);
	echo 'Postagem editada com sucesso! <a href="view_topic.php?id=' . $_GET['topic'] . '">Voltar</a>.';
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

?>

<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
	<tr>
		<form method="POST" action="edit_answer.php?topic=<?= $_GET['topic'] ?>&a=<?= $_GET['a'] ?>">
			<td>
				<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
					<tr>
						<td colspan="3" bgcolor="#E6E6E6"><strong>Editar resposta</strong> </td>
					</tr>
					<tr>
						<td>
							<script>
								edToolbar('detail');
							</script><textarea name="detail" rows="12" id="detail" class="ed"><?= $editandomensagem ?></textarea>
						</td>
					</tr>
					<tr>
						<td><input type="submit" name="submit" value="Enviar" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="javascript:window.open('example.html', '_blank','top=100, left=100, height=400, width=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');">Dicas de formatao</a></td>
					</tr>
				</table>
			</td>
		</form>
	</tr>
</table>
<?php
include(__DIR__ . "/templates/private_footer.php");
?>