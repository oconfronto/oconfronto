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
}elseif ($player->gm_rank < 3)
	{
	echo "Você não pode acessar esta página! <a href=\"select_forum.php\">Voltar</a>.";
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


$ban = $db->execute("update `players` set `alerts`=0 where `id`=?", array($_GET['player']));
	$logmsg = "Você foi desbanido do fórum pelo moderador <b>" . $player->username . "</b>";
	addlog($_GET['player'], $logmsg, $db);

	$logalert2 = "" . $user2['username'] . " foi desbanido do fórum pelo moderador <b>" . $player->username . "</b>";
	forumlog($logalert2, $db);

	echo "" . $user2['username'] . " foi desbanido do fórum! <a href=\"select_forum.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;

include("templates/private_footer.php");
?>