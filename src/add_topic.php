<?php

include("lib.php");
define("PAGENAME", "Principal");
$player = check_user($secret_key, $db);

include("checkforum.php");
include("templates/private_header.php");

if (!$_POST['detail'] or !$_POST['topic']) {
		echo "<fieldset><legend><b>Erro</b></legend>Você precisa preencher todos os campos!<BR>";
		echo "<a href=\"#\" onClick='javascript: history.back();'>Voltar</a></fieldset>";
            include("templates/private_footer.php");
            exit;
}

if ($_POST['category'] == 'none') {
		echo "<fieldset><legend><b>Erro</b></legend>Você precisa escolher uma categoria!<BR>";
		echo "<a href=\"#\" onClick='javascript: history.back();'>Voltar</a></fieldset>";
            include("templates/private_footer.php");
            exit;
}

$verifica = $db->GetOne("select `imperador` from `reinos` where `id`=?", array($player->reino));
if (($_POST['category'] != 'sugestoes') and ($_POST['category'] != 'gangues') and ($_POST['category'] != 'trade') and ($_POST['category'] != 'duvidas') and ($_POST['category'] != 'outros') and ($_POST['category'] != 'fan') and ($_POST['category'] != 'off') and ($player->gm_rank < 9)) {
		echo "<fieldset><legend><b>Erro</b></legend>Você não tem autorização para criar tópicos nesta categoria!<BR>";
		echo "<a href=\"#\" onClick='javascript: history.back();'>Voltar</a></fieldset>";
            include("templates/private_footer.php");
            exit;
}



$topic=$_POST['topic'];
$category=$_POST['category'];
$detail=$_POST['detail'];
$datetime=date("d/m/y H:i:s");

$notavel=strip_tags($detail);
$texto=nl2br($notavel);


if (!$_POST['vota'])
{
$vota = "f";
}else{
$vota = "t";
}

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

$sql5 = $db->execute("update `players` set `posts`=`posts`+1 where `id`=?", array($player->id));

if($result){
echo "<fieldset><legend><b>Sucesso</b></legend>Tópico postado com sucesso!<BR>";
echo "<a href=main_forum.php?cat=" . $category . ">Visualizar mensagem</a></fieldset>";
}
else {
echo "<fieldset><legend><b>Erro</b></legend>Um erro inesperado ocorreu.<BR>";
echo "<a href=select_forum.php>Voltar</a></fieldset>";
}
mysql_close();
?>
<?php
include("templates/private_footer.php");
?>