<?php
include("lib.php");
define("PAGENAME", "Editar perfil");
$player = check_user($secret_key, $db);

$error = 0;

include("templates/private_header.php");


$get = $db->execute("select * from `players` where `username` = '$player->username' and subname > '0'");
if ($get->recordcount() > 0) {
	if ($_POST['subname'] == Alterar) {
		$subtitle = $_POST['subtitle'];
		$sub_color = $_POST['categoria_color'];
		$numero = "10";
		$total = strlen($subtitle);
		if ($total > $numero) {
			echo showAlert("Ta maluco? Só são aceitos nicks com 10 caracteres ou menos.", "red");
		} else {
			if (!empty($subtitle) and !empty($sub_color)) {
				if ($sub_color == "red" or $sub_color == "blue" or $sub_color == "green" or $sub_color == "black") {

					if ($_POST['clean'] == 'yes') {
						$sub_final = "1";
						echo showAlert("Subnick foi removido", "green");
					} else {
						$sub_final = "" . $subtitle . ", " . $sub_color . "";
						echo showAlert("Nick alterado: $player->username [<font color=\"" . $sub_color . "\">" . $subtitle . "</font>]", "green");
					}
					$trocachare = $db->execute("update `players` set `subname`=? where `username`=?", array($sub_final, $player->username));

				} else {
					echo showAlert("Digite uma cor válida", "red");
				}

			} else {
				echo showAlert("Digite um sub nick válido", "red");
			}
		}
	}
}

if ($_GET['success'] == 'true') {
	echo showAlert("Avatar atualizado com sucesso!", "green");
} elseif ($_GET['msg']) {
	echo showAlert("<b>Erro:</b> A imagem enviada não é suportada.<br/>Verifique se a imagem enviada atende todos os requisitos listados abaixo do formulário de envio.", "red", "left");
}

$procuramengperfil = $db->execute("select `perfil` from `profile` where `player_id`=?", array($player->id));
if ($procuramengperfil->recordcount() == 0) {
	$mencomentario = "Sem comentários.";
} else {
	$comentdocara = $procuramengperfil->fetchrow();
	$quebras = array('<br />', '<br>', '<br/>');
	$mencomentario = str_replace($quebras, "", $comentdocara['perfil']);
}


?>

<table width="100%">
	<tr>
		<td width="25%">
			<center><img src="<?php echo $player->avatar ?>" width="120px" height="120px"
					alt="<?php echo $player->username ?>" border="1px"></center>
		</td>
		<td width="75%"><b>Enviar avatar:</b><br />
			<form action="sendfiles.php?avatar=true" method="post" enctype="multipart/form-data">
				<input type="file" name="foto" size="30"><input type="submit" name="upload" value="Enviar">
				<p>
					Envie uma imagem para ser utilizada como avatar.<br />
					A imagem deve ter formato jpg, jpeg, png, bmp ou gif.<br />
					O tamanho da imagem não deve ultrapassar 1 MB.<br />
					A resolução máxima permitida é de 1400x1024.
				</p>
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
							<script>edToolbar('detail'); </script><textarea name="detail" rows="12" id="detail"
								class="ed"><?= $mencomentario ?></textarea>
						</td>
					</tr>
					<tr>
						<td><input type="submit" name="submit" value="Enviar" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="#"
								onclick="javascript:window.open('example.html', '_blank','top=100, left=100, height=400, width=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');">Dicas
								de formatação</a></td>
					</tr>
				</table>
			</td>
		</form>
	</tr>
</table>


<?php include("templates/private_footer.php");
?>