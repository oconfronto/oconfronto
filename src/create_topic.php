<?php
include("lib.php");
define("PAGENAME", "Fórum");
$player = check_user($secret_key, $db);
include("checkforum.php");

if ($_POST['submit']) {
	$verifica = $db->GetOne("select `imperador` from `reinos` where `id`=?", array($player->reino));

	if (!$_POST['detail'] or !$_POST['topic']) {
		$error = "Você precisa preencher todos os campos.";
	}

	elseif ($_POST['category'] == 'none') {
		$error = "Você precisa escolher uma categoria.";
	}

	elseif (($_POST['category'] != 'reino') and ($_POST['category'] != 'sugestoes') and ($_POST['category'] != 'gangues') and ($_POST['category'] != 'trade') and ($_POST['category'] != 'duvidas') and ($_POST['category'] != 'outros') and ($_POST['category'] != 'fan') and ($_POST['category'] != 'off') and ($player->gm_rank < 9)) {
		$error = "Você não possui autorização para criar tópicos nesta categoria.";
	}

	elseif (($_POST['category'] == 'reino') and ($player->id != $verifica) and ($player->gm_rank < 9)) {
		$error = "Você não possui autorização para criar tópicos nesta categoria.";
	
	} else {

		if (!$_POST['vota']) {
			$vota = "f";
		} else {
			$vota = "t";
		}

			$texto = strip_tags($_POST['detail']);
			$texto = nl2br($texto);

		$insert['topic'] = $_POST['topic'];
		$insert['category'] = $_POST['category'];
		$insert['detail'] = $texto;
		$insert['user_id'] = $player->id;
		$insert['datetime'] = date("d/m/y H:i:s");
		$insert['postado'] = time();
		$insert['last_post'] = time();
		$insert['last_post_date'] = date("d/m/y H:i:s");
		$insert['vota'] = $vota;
		$insert['serv'] = $player->serv;
		$insert['reino'] = $player->reino;
		$db->autoexecute('forum_question', $insert, 'INSERT');

		$db->execute("update `players` set `posts`=`posts`+1 where `id`=?", array($player->id));
		header("Location: main_forum.php?cat=" . $_POST['category'] . "&success=true");
		exit;
	}
}


include("templates/private_header.php");

if ($error){
	echo showAlert($error, "red");
}

echo "<script type=\"text/javascript\" src=\"static/bbeditor/ed.js\"></script>";
	echo "<table width=\"100%\" border=\"0px\">";
		echo "<tr><td class=\"brown\" width=\"100%\"><center><b>Criar novo Tópico</b></center></td></tr>";
		echo "<tr class=\"salmon\"><td>";
			echo "<form method=\"post\" action=\"create_topic.php\">";
			echo "<b>Título:</b> <input name=\"topic\" type=\"text\" id=\"topic\" size=\"35\" value=\"" . $_POST['topic'] . "\" />";
			echo " <b>Categoria:</b> <select name=\"category\">";

				$verifica = $db->GetOne("select `imperador` from `reinos` where `id`=?", array($player->reino));

				if (($_POST['category'] == 'none') or (!$_POST['category'])){
					echo "<option value=\"none\" selected=\"selected\">Selecione</option>";
				} else {
					echo "<option value=\"none\">Selecione</option>";
				}

				if ($player->gm_rank > 9) {
					if (($_POST['category'] == 'noticias') or ($_GET['category'] == 'noticias')){
						echo "<option value=\"noticias\" selected=\"selected\">Notícias</option>";
					} else {
						echo "<option value=\"noticias\">Notícias</option>";
					}
				}

				if ($verifica == $player->id) {
					if (($_POST['category'] == 'reino') or ($_GET['category'] == 'reino')){
						echo "<option value=\"reino\" selected=\"selected\">Reino</option>";
					} else {
						echo "<option value=\"reino\">Reino</option>";
					}
				}

				if (($_POST['category'] == 'sugestoes') or ($_GET['category'] == 'sugestoes')){
					echo "<option value=\"sugestoes\" selected=\"selected\">Sugestões</option>";
				} else {
					echo "<option value=\"sugestoes\">Sugestões</option>";
				}

				if (($_POST['category'] == 'gangues') or ($_GET['category'] == 'gangues')){
					echo "<option value=\"gangues\" selected=\"selected\">Clãs</option>";
				} else {
					echo "<option value=\"gangues\">Clãs</option>";
				}

				if (($_POST['category'] == 'trade') or ($_GET['category'] == 'trade')){
					echo "<option value=\"trade\" selected=\"selected\">Compro/Vendo</option>";
				} else {
					echo "<option value=\"trade\">Compro/Vendo</option>";
				}

				if (($_POST['category'] == 'duvidas') or ($_GET['category'] == 'duvidas')){
					echo "<option value=\"duvidas\" selected=\"selected\">Duvidas</option>";
				} else {
					echo "<option value=\"duvidas\">Duvidas</option>";
				}

				if (($_POST['category'] == 'fan') or ($_GET['category'] == 'fan')){
					echo "<option value=\"fan\" selected=\"selected\">Fanwork</option>";
				} else {
					echo "<option value=\"fan\">Fanwork</option>";
				}

				if (($_POST['category'] == 'outros') or ($_GET['category'] == 'outros')){
					echo "<option value=\"outros\" selected=\"selected\">Outros</option>";
				} else {
					echo "<option value=\"outros\">Outros</option>";
				}

				if (($_POST['category'] == 'off') or ($_GET['category'] == 'off')){
					echo "<option value=\"off\" selected=\"selected\">Off-Topic</option>";
				} else {
					echo "<option value=\"off\">Off-Topic</option>";
				}

			echo "</select>";
		echo "</td></tr>";
		echo "<tr class=\"salmon\"><td>";
			echo "<script>edToolbar('detail');</script>";
			echo "<textarea name=\"detail\" rows=\"12\" id=\"detail\" class=\"ed\" style=\"width: 98%;\"></textarea>";
			echo "<script>document.getElementById('detail').value = '" . $_POST['detail'] . "';</script>";
		echo "</td></tr>";
		echo "<tr class=\"salmon\"><td>";
			echo "<table width=\"100%\" border=\"0\"><tr>";
				echo "<td width=\"50%\"><input type=\"submit\" name=\"submit\" value=\"Criar Tópico\" /></td>";
				echo "<td width=\"50%\" align=\"right\"><input type=\"checkbox\" name=\"vota\" value=\"yes\"> Ativar Votação</td>";
			echo "</tr></table>";
		echo "</td></tr>";
	echo "</table>";

include("templates/private_footer.php");
?>