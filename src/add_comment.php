<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Principal");
$player = check_user($secret_key, $db);

include(__DIR__ . "/templates/private_header.php");

$tbl_name="forum_question"; // Table name

if (!$_POST['detail']) {
		echo "<fieldset><legend><b>Erro</b></legend>Você precisa preencher todos os campos!<BR>";
		echo "<a href='edit_comment.php'>Voltar</a></fieldset>";
            include(__DIR__ . "/templates/private_footer.php");
            exit;
}


	$procuramengperfil = $db->execute("select `perfil` from `profile` where `player_id`=?", array($player->id));

    $topic=$_POST['detail'];
    $topic2=strip_tags($topic);
    $texto=nl2br($topic2);

	if ($procuramengperfil->recordcount() == 0)
	{
		$insert['player_id'] = $player->id;
		$insert['perfil'] = $texto;
		$upddadet = $db->autoexecute('profile', $insert, 'INSERT');

        echo "<fieldset><legend><b>Sucesso</b></legend>Perfil atualizado com sucesso!<BR>";
        echo '<a href="profile.php?id=' . $player->username . '">Visualizar perfil</a></fieldset>';
	}
	else
	{
        $db->execute("update `profile` set `perfil`=? where `player_id`=?", array($texto, $player->id));
        echo "<fieldset><legend><b>Sucesso</b></legend>Perfil atualizado com sucesso!<BR>";
        echo '<a href="profile.php?id=' . $player->username . '">Visualizar perfil</a></fieldset>';
	}

// get data that sent from form

mysql_close();
?>
<?php
include(__DIR__ . "/templates/private_footer.php");
?>
