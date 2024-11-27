<?php

declare(strict_types=1);

$lib = __DIR__ . "/lib.php";
$header = __DIR__ . "/templates/private_header.php";
$footer = __DIR__ . "/templates/private_footer.php";

include_once $lib;

define("PAGENAME", "Principal");
$player = check_user($db);

include_once $header;

$procuramengperfil = $db->execute("select `perfil` from `profile` where `player_id`=?", [$player->id]);

$topic = $_POST['detail'] ?? null;
$topic2 = strip_tags((string) $topic);
$texto = nl2br($topic2);

if ($procuramengperfil->recordcount() == 0) {
	$insert['player_id'] = $player->id;
	$insert['perfil'] = $texto;
	$upddadet = $db->autoexecute('profile', $insert, 'INSERT');

	echo "<fieldset><legend><b>Sucesso</b></legend>Perfil atualizado com sucesso!<BR>";
	echo '<a href="profile.php?id=' . $player->username . '">Visualizar perfil</a></fieldset>';
} else {
	$db->execute("update `profile` set `perfil`=? where `player_id`=?", [$texto, $player->id]);
	echo "<fieldset><legend><b>Sucesso</b></legend>Perfil atualizado com sucesso!<BR>";
	echo '<a href="profile.php?id=' . $player->username . '">Visualizar perfil</a></fieldset>';
}

include_once $footer;
