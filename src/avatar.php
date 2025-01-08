<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Editar perfil");
$player = check_user($db);

$error = 0;

include(__DIR__ . "/templates/private_header.php");


$get = $db->execute(sprintf("select * from `players` where `username` = '%s' and subname > '0'", $player->username));
if ($get->recordcount() > 0 && ($_POST['subname'] ?? null) == "alterar") {
	$subtitle = $_POST['subtitle'];
	$sub_color = $_POST['categoria_color'];
	$numero = "10";
	$total = strlen((string) $subtitle);
	if ($total > $numero) {
		echo showAlert("Tá maluco? Só são aceitos nicks com 10 caracteres ou menos.", "red");
	} elseif (!empty($subtitle) && !empty($sub_color)) {
		if ($sub_color == "red" || $sub_color == "blue" || $sub_color == "green" || $sub_color == "black") {

			if (($_POST['clean'] ?? null) == 'yes') {
				$sub_final = "1";
				echo showAlert("Subnick foi removido", "green");
			} else {
				$sub_final = "" . $subtitle . ", " . $sub_color . "";
				echo showAlert(sprintf('Nick alterado: %s [<font color="', $player->username) . $sub_color . '">' . $subtitle . "</font>]", "green");
			}

			$trocachare = $db->execute("update `players` set `subname`=? where `username`=?", [$sub_final, $player->username]);
		} else {
			echo showAlert("Digite uma cor válida", "red");
		}
	} else {
		echo showAlert("Digite um sub nick válido", "red");
	}
}

if ($_POST['upload'] ?? null) {
	if (!($_POST['avatar'] ?? null)) {
		$errmsg .= "Por favor preencha todos os campos!";
		$error = 1;
	} elseif (($_POST['avatar'] ?? null) && (@GetImageSize($_POST['avatar']) === [] || @GetImageSize($_POST['avatar']) === false)) {
		$errmsg .= "O endereço desta imagem não é válido!";
		$error = 1;
	}

	if ($error == 0) {
		$avat = $_POST['avatar'] ?? null;
		$query = $db->execute("update `players` set `avatar`=? where `id`=?", [$avat, $player->id]);
		$msg .= "Você alterou seu avatar com sucesso!";
		// Espera 1.5 segundos antes de atualizar a página
		//  echo "<p><font color='green'>$msg</font></p>";
		echo showAlert("<b>" . $msg . "</b>", "green");
		echo '<meta http-equiv="refresh" content="1.3">';
		exit;
	}

	// Espera 1.5 segundos antes de atualizar a página
	//  echo "<p><font color='green'>$msg</font></p>";
	echo showAlert("<b>" . $errmsg . "</b>", "red");
	echo '<meta http-equiv="refresh" content="1.3">';
	exit;
}


// if ($_GET['success'] == 'true') {
// 	echo showAlert("Avatar atualizado com sucesso!", "green");
// } elseif ($_GET['msg']) {
// 	echo showAlert("<b>Erro:</b> A imagem enviada não é suportada.<br/>Verifique se a imagem enviada atende todos os requisitos listados abaixo do formulário de envio.", "red", "left");
// }

$procuramengperfil = $db->execute("select `perfil` from `profile` where `player_id`=?", [$player->id]);
if ($procuramengperfil->recordcount() == 0) {
	$mencomentario = "Sem comentários.";
} else {
	$comentdocara = $procuramengperfil->fetchrow();
	$quebras = ['<br />', '<br>', '<br/>'];
	$mencomentario = str_replace($quebras, "", $comentdocara['perfil']);
}


?>
<table width="100%">
	<tr>
		<td width="25%">
			<center><img src="<?php $dire = $player->avatar ? $player->avatar : "static/anonimo.gif";
								echo $dire ?>" width="120px" height="120px"
					alt="<?php echo $player->username ?>" border="1px"></center>
		</td>
		<td width="75%"><b>Enviar avatar:</b><br />
			<form method="POST" action="avatar.php">
				<input type="text" name="avatar" value="<?= $player->avatar ?>" size="45" />
				<input type="submit" name="upload" value="Enviar" />
				<!-- <input type="file" name="foto" size="30"><input type="submit" name="upload" value="Enviar"> -->
				<!-- <p>
					Envie uma imagem para ser utilizada como avatar.<br />
					A imagem deve ter formato jpg, jpeg, png, bmp ou gif.<br />
					O tamanho da imagem não deve ultrapassar 1 MB.<br />
					A resolução máxima permitida é de 1400x1024.
				</p> -->
			</form>
		</td>
	</tr>

</table>

<br />

<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#f2e1ce">
	<tr>
		<form id="form1" name="form1" method="post" action="add_comment.php">
			<td>
				<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#f2e1ce">
					<tr>
						<td colspan="3" bgcolor="#E1CBA4"><strong>Editar comentários do perfil</strong> </td>
					</tr>
					<tr>
						<td>
							<script>
								edToolbar('detail');
							</script><textarea style="width:90%" name="detail" rows="12" id="detail"
								class="ed"><?= $mencomentario ?></textarea>
						</td>
					</tr>
					<tr>
						<td><input type="submit" name="submit" value="Enviar" />&nbsp;&nbsp;&nbsp;&nbsp;</td>
					</tr>
				</table>
			</td>
		</form>
	</tr>
</table>


<?php include(__DIR__ . "/templates/private_footer.php");
?>