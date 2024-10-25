<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Principal");
$player = check_user($db);

include(__DIR__ . "/checkforum.php");
include(__DIR__ . "/templates/private_header.php");

if (!$_POST['detail'] || !$_POST['topic']) {
	echo "<fieldset><legend><b>Erro</b></legend>Você precisa preencher todos os campos!<BR>";
	echo "<a href=\"#\" onClick='javascript: history.back();'>Voltar</a></fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if ($_POST['category'] == 'none') {
	echo "<fieldset><legend><b>Erro</b></legend>Você precisa escolher uma categoria!<BR>";
	echo "<a href=\"#\" onClick='javascript: history.back();'>Voltar</a></fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

$verifica = $db->GetOne("select `imperador` from `reinos` where `id`=?", [$player->reino]);
if ($_POST['category'] != 'sugestoes' && $_POST['category'] != 'gangues' && $_POST['category'] != 'trade' && $_POST['category'] != 'duvidas' && $_POST['category'] != 'outros' && $_POST['category'] != 'fan' && $_POST['category'] != 'off' && $player->gm_rank < 9) {
	echo "<fieldset><legend><b>Erro</b></legend>Você não tem autorização para criar tópicos nesta categoria!<BR>";
	echo "<a href=\"#\" onClick='javascript: history.back();'>Voltar</a></fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}



$topic = $_POST['topic'];
$category = $_POST['category'];
$detail = $_POST['detail'];
$datetime = date("d/m/y H:i:s");

$notavel = strip_tags((string) $detail);
$texto = nl2br($notavel);


$vota = $_POST['vota'] ? "t" : "f";

$time = time();

$insert['topic'] = $topic;
$insert['category'] = $category;
$insert['detail'] = $texto;
$insert['user_id'] = $player->id;
$insert['datetime'] = $datetime;
$insert['postado'] = $time;
$insert['last_post'] = $time;
$insert['last_post_date'] = $datetime;
$insert['vota'] = $vota;
$insert['serv'] = $player->serv;
$result = $db->autoexecute('forum_question', $insert, 'INSERT');

$sql5 = $db->execute("update `players` set `posts`=`posts`+1 where `id`=?", [$player->id]);

if ($result) {
	echo "<fieldset><legend><b>Sucesso</b></legend>Tópico postado com sucesso!<BR>";
	echo "<a href=main_forum.php?cat=" . $category . ">Visualizar mensagem</a></fieldset>";
} else {
	echo "<fieldset><legend><b>Erro</b></legend>Um erro inesperado ocorreu.<BR>";
	echo "<a href=select_forum.php>Voltar</a></fieldset>";
}

?>
<?php
include(__DIR__ . "/templates/private_footer.php");
?>
