<?php
include("lib.php");
define("PAGENAME", "Fórum");
$player = check_user($secret_key, $db);

if ($player->gm_rank < 3) {
	include("templates/private_header.php");
	echo "Você não pode acessar esta página! <a href=\"select_forum.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
}


if ($_GET['answer'])
{
	$query = $db->execute("select `question_id`, `a_user_id`, `a_answer` from `forum_answer` where `id`=?", array($_GET['answer']));
	if ($query->recordcount() != 1)
	{
		include("templates/private_header.php");
		echo "Este post não existe! <a href=\"select_forum.php\">Voltar</a>.";
		include("templates/private_footer.php");
		exit;
	} else {
		$postagem = $query->fetchrow();
		$usuario = $db->execute("select * from `players` where `id`=?", array($postagem['a_user_id']));
		$usuario = $usuario->fetchrow();

	}
		if ($_POST['alert']){
			if (!$_POST['motivo']) {
				include("templates/private_header.php");
				echo "Você precisa digitar o motivo do alerta! <a href=\"forum_alert.php?answer=" . $_GET['answer'] . "\">Voltar</a>.";
				include("templates/private_footer.php");
				exit;
			}

			if (!$_POST['days']) {
				include("templates/private_header.php");
				echo "Você precisa digitar o nível de alerta! <a href=\"forum_alert.php?answer=" . $_GET['answer'] . "\">Voltar</a>.";
				include("templates/private_footer.php");
				exit;
			}

			if ($player->gm_rank <= $usuario['gm_rank']) {
				include("templates/private_header.php");
				echo "Você não pode alertar seus colegas e superiores! <a href=\"forum_alert.php?answer=" . $_GET['answer'] . "\">Voltar</a>.";
				include("templates/private_footer.php");
				exit;
			}

			

			$db->execute("update `players` set `alerts`=`alerts`+? where `id`=?", array($_POST['days'], $usuario['id']));
			$logmsg = "Você foi alertado no fórum em " . strip_tags($_POST['days']) . "%.<br/><b>Motivo:</b> " . strip_tags($_POST['motivo']) . "";
			addlog($usuario['id'], $logmsg, $db);

			$logmsg = "" . showName($usuario['id'], $db, 'off', 'off') . " foi alertado em " . strip_tags($_POST['days']) . "% pelo moderador <b>" . $player->username . "</b><br/><b>Motivo:</b> " . strip_tags($_POST['motivo']) . "";
			forumlog($logmsg, $db, 2, $_GET['answer']);

			include("templates/private_header.php");
			echo "" . showName($usuario['id'], $db, 'off') . " foi alertado em " . strip_tags($_POST['days']) . "%! <a href=\"view_topic.php?id=" . $postagem['question_id'] . "\">Voltar</a>.";
			include("templates/private_footer.php");
			exit;

		}

	include("templates/private_header.php");
	echo "<b><font size=\"1px\">Selecione o motivo do alerta do post de " . showName($postagem['a_user_id'], $db, 'off') . ".</font></b><br/>";

	echo "<p><i><center>" . $postagem['a_answer'] . "</center></i></p>";

	echo "<form method=\"POST\" action=\"forum_alert.php?answer=" . $_GET['answer'] . "\">";
		echo "<table><tr><td><b>Alertar em:</b></td><td><input type=\"text\" name=\"days\" size=\"3\"/>% <font size=1>(100% resulta em banimento)</font></td></tr>";
		echo "<tr><td><b>Motivo:</b></td><td><input type=\"text\" name=\"motivo\" size=\"40\"/>";

	echo " <input type=\"submit\" name=\"alert\" value=\"Alertar!\"></td></tr></table></form>";
	include("templates/private_footer.php");
	exit;
}

elseif ($_GET['topic'])
{
	$query = $db->execute("select * from `forum_question` where `id`=?", array($_GET['topic']));
	if ($query->recordcount() != 1)
	{
		include("templates/private_header.php");
		echo "Este post não existe! <a href=\"select_forum.php\">Voltar</a>.";
		include("templates/private_footer.php");
		exit;
	} else {
		$postagem = $query->fetchrow();
		$usuario = $db->execute("select * from `players` where `id`=?", array($postagem['user_id']));
		$usuario = $usuario->fetchrow();

	}
		if ($_POST['alert']){
			if (!$_POST['motivo']) {
				include("templates/private_header.php");
				echo "Você precisa digitar o motivo do alerta! <a href=\"forum_alert.php?topic=" . $_GET['topic'] . "\">Voltar</a>.";
				include("templates/private_footer.php");
				exit;
			}

			if (!$_POST['days']) {
				include("templates/private_header.php");
				echo "Você precisa digitar o nível de alerta! <a href=\"forum_alert.php?topic=" . $_GET['topic'] . "\">Voltar</a>.";
				include("templates/private_footer.php");
				exit;
			}

			if ($player->gm_rank <= $usuario['gm_rank']) {
				include("templates/private_header.php");
				echo "Você não pode alertar seus colegas e superiores! <a href=\"forum_alert.php?topic=" . $_GET['topic'] . "\">Voltar</a>.";
				include("templates/private_footer.php");
				exit;
			}


			$db->execute("update `players` set `alerts`=`alerts`+? where `id`=?", array($_POST['days'], $usuario['id']));
			$logmsg = "Você foi alertado no fórum em " . strip_tags($_POST['days']) . "%.<br/><b>Motivo:</b> " . strip_tags($_POST['motivo']) . "";
			addlog($usuario['id'], $logmsg, $db);

			$logmsg = "" . showName($usuario['id'], $db, 'off', 'off') . " foi alertado em " . strip_tags($_POST['days']) . "% pelo moderador <b>" . $player->username . "</b><br/><b>Motivo:</b> " . strip_tags($_POST['motivo']) . "";
			forumlog($logmsg, $db, 1, $_GET['topic']);

			include("templates/private_header.php");
			echo "" . showName($usuario['id'], $db, 'off') . " foi alertado em " . strip_tags($_POST['days']) . "%! <a href=\"view_topic.php?id=" . $postagem['id'] . "\">Voltar</a>.";
			include("templates/private_footer.php");
			exit;

		}

	include("templates/private_header.php");
	echo "<b><font size=\"1px\">Selecione o motivo do alerta do post de " . showName($postagem['user_id'], $db, 'off') . ".</font></b><br/>";

	echo "<p><i><center>" . $postagem['detail'] . "</center></i></p>";

	echo "<form method=\"POST\" action=\"forum_alert.php?topic=" . $_GET['topic'] . "\">";
		echo "<table><tr><td><b>Alertar em:</b></td><td><input type=\"text\" name=\"days\" size=\"3\"/>% <font size=1>(100% resulta em banimento)</font></td></tr>";
		echo "<tr><td><b>Motivo:</b></td><td><input type=\"text\" name=\"motivo\" size=\"40\"/>";

	echo " <input type=\"submit\" name=\"alert\" value=\"Alertar!\"></td></tr></table></form>";
	include("templates/private_footer.php");
	exit;

} else {
	include("templates/private_header.php");
	echo "Selecione uma postagem para alertar. <a href=\"select_forum.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
}
?>