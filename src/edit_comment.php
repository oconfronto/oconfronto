<?php

include("lib.php");
define("PAGENAME", "Editar Comentário");
$player = check_user($secret_key, $db);


include("templates/private_header.php");
?>
<script type="text/javascript" src="bbeditor/ed.js"></script>
<?php
	$procuramengperfil = $db->execute("select `perfil` from `profile` where `player_id`=?", array($player->id));
	if ($procuramengperfil->recordcount() == 0)
	{
		$mencomentario = "Sem comentários.";
	}
	else
	{
		$comentdocara = $procuramengperfil->fetchrow();
		$quebras = Array( '<br />', '<br>', '<br/>' );
		$mencomentario = str_replace($quebras, "", $comentdocara['perfil']);
	}

?>

<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
<tr>
<form id="form1" name="form1" method="post" action="add_comment.php">
<td>
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
<tr>
<td colspan="3" bgcolor="#E6E6E6"><strong>Editar comentários do perfil</strong> </td>
</tr>
<tr>
<td><script>edToolbar('detail'); </script><textarea name="detail" rows="12" id="detail" class="ed"><?=$mencomentario?></textarea></td>
</tr>
<tr>
<td><input type="submit" name="Submit" value="Enviar" /> <input type="reset" name="Submit2" value="Apagar" />&nbsp;&nbsp;<b>Tags:</b>&nbsp;&lt;a&gt;,&nbsp;&lt;b&gt;,&nbsp;&lt;br&gt;&nbsp;e&nbsp;&lt;center&gt;.</td>
</tr>
</table>
</td>
</form>
</tr>
</table>
<?php
include("templates/private_footer.php");
?>