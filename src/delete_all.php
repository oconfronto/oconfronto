<?php

include("lib.php");
define("PAGENAME", "Fórum");
$player = check_user($secret_key, $db);


include("templates/private_header.php");


if (!$_GET['player'])
{
	echo "Nenhum usuário foi selecionado! <a href=\"select_forum.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
}elseif ($player->gm_rank < 50)
	{
	echo "Só o administrador pode acessar esta página! <a href=\"select_forum.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
}else{

$user = $db->execute("select `username`, `gm_rank` from `players` where `id`=?", array($_GET['player']));
if ($user->recordcount() == 0) {
	echo "Este usuário não existe! <a href=\"select_forum.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
}

    $user2 = $user->fetchrow();


if(isset($_POST['deleteall']))
{
	$logalert2 = "Todas as postagens de " . $user2['username'] . " foram apagadas por <b>" . $player->username . "</b>";
	forumlog($logalert2, $db);

	$removeposts = $db->execute("select `a_id` from `forum_answer` where `a_user_id`=?", array($_GET['player']));
	while($player = $removeposts->fetchrow())
	{
	$query = $db->execute("update `question_id` set `reply`=`reply`-1 where `id`=?", array($player['a_id']));
	}

	$removeposts2 = $db->execute("select `id` from `forum_question` where `user_id`=?", array($_GET['player']));
	while($player2 = $removeposts2->fetchrow())
	{
	$query = $db->execute("delete from `forum_answer` where `question_id`=?", array($player2['id']));
	}

        $real = $db->execute("delete from `forum_question` where `user_id`=?", array($_GET['player']));
        $real = $db->execute("delete from `forum_answer` where `a_user_id`=?", array($_GET['player']));
	$query = $db->execute("update `players` set `posts`=0 where `id`=?", array($_GET['player']));

	echo "Todas as postagens de " . $user2['username'] . " foram deletadas! <a href=\"select_forum.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
}


	echo "<form method=\"POST\" action=\"delete_all.php?player=" . $_GET['player'] . "\">";
	echo "<b>Tem certeza que deseja apagar todas as mensagens de " . $user2['username'] . "? Essa é uma ação irreversivel!</b><br/>";
	echo "<input type=\"submit\" name=\"deleteall\" value=\"Deletar todas as mensagens de " . $user2['username'] . "\"></form>";


}
include("templates/private_footer.php");
?>