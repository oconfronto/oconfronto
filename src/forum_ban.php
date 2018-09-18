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

if(isset($_POST['ban']))
{

if (!$_POST['motivo'])
{
	echo "Você precisa digitar o motivo do banimento! <a href=\"forum_ban.php?player=" . $_GET['player'] . "\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
}

if (!$_POST['days'])
{
	echo "Você precisa digitar o tempo do banimento! <a href=\"forum_ban.php?player=" . $_GET['player'] . "\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
}

if (!$_POST['days'])
{
	echo "O numero de dias digitado não é válido! <a href=\"forum_ban.php?player=" . $_GET['player'] . "\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
}
if ($player->gm_rank <= $user2['gm_rank']){
	echo "Você não pode banir Moderadores/Administradores! <a href=\"select_forum.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
}


if ($_POST['days'] > 998){
$ban = $db->execute("update `players` set `alerts`=? where `id`=?", array(forever, $_GET['player']));
	$logmsg = "Você foi banido do fórum permanentemente.<br/><b>Motivo:</b> " . strip_tags($_POST['motivo']) . "";
}else{
$tempo = time();
$dias = ceil($_POST['days'] * 86400);
$totaldias = ceil($tempo + $dias);
$ban = $db->execute("update `players` set `alerts`=? where `id`=?", array($totaldias, $_GET['player']));
	$logmsg = "Você foi banido do fórum por " . strip_tags($_POST['days']) . " dias.<br/><b>Motivo:</b> " . strip_tags($_POST['motivo']) . "";
}
	addlog($_GET['player'], $logmsg, $db);

	$logalert2 = "" . $user2['username'] . " foi banido do fórum por " . strip_tags($_POST['days']) . " dia(s) pelo moderador <b>" . $player->username . "</b><br/><b>Motivo:</b> " . strip_tags($_POST['motivo']) . "";
	forumlog($logalert2, $db);

	echo "" . $user2['username'] . " foi banido do fórum! <a href=\"select_forum.php\">Voltar</a>.";
	include("templates/private_footer.php");
	exit;
}

	
	echo "<form method=\"POST\" action=\"forum_ban.php?player=" . $_GET['player'] . "\">";
	echo "<b>Deseja banir " . $user2['username'] . " do fórum por quanto tempo?</b><br/>";
	echo "<b>Banir por:</b> <input type=\"text\" name=\"days\" size=\"5\"/> dias. <font size=1>(digite 999 para banir permanentemente).</font><br/>";
	echo "<b>Motivo:</b> <input type=\"text\" name=\"motivo\" size=\"30\"/> ";
	echo " <input type=\"submit\" name=\"ban\" value=\"Banir!\"></form>";
	include("templates/private_footer.php");
	exit;
	
}
include("templates/private_footer.php");
?>