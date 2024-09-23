<?php
include("lib.php");
define("PAGENAME", "Criar Personagem");
$acc = check_acc($secret_key, $db);
include("templates/acc-header.php");

$querynumplayers = $db->execute("select `id` from `players` where `acc_id`=?", array($acc->id));

if ($querynumplayers->recordcount() >= 12) {
	echo "<span id=\"aviso-a\"></span>";
	echo "<br/><p><center>Voc&ecirc; já atingiu o número máximo de personagens por conta.<br/>Voc&ecirc; não pode mais criar usuários nesta conta. <a href=\"characters.php\">Voltar</a>.</center></p><br/>";
	include("templates/acc-footer.php");
	exit;
} else {


	$msg1 = "";
	$msg2 = "";
	$error = 0;
	$erro1 = 0;
	$erro2 = 0;

	if ($_POST['register']) {

		$pat[0] = "/^\s+/";
		$pat[1] = "/\s{2,}/";
		$pat[2] = "/\s+\$/";
		$rep[0] = "";
		$rep[1] = " ";
		$rep[2] = "";
		$nomedeusuari0 = ucwords(preg_replace($pat, $rep, strtolower($_POST['username'])));

		//Check if username has already been used
		$query = $db->execute("select `id` from `players` where `username`=?", array($nomedeusuari0));
		//Check username
		if (!$_POST['username']) { //If username isn't filled in...
			$msg1 .= "Voc&ecirc; precisa digitar um nome de usuário!<br />\n"; //Add to error message
			$error = 1; //Set error check
			$erro1 = 1;
		} elseif (strlen($nomedeusuari0) < 3) { //If username is too short...
			$msg1 .= "Seu nome de usuário deve ter mais que 2 caracteres!<br />\n"; //Add to error message
			$error = 1; //Set error check
			$erro1 = 1;
		} else if (strlen($nomedeusuari0) > 15) { //If username is too short...
			$msg1 .= "Seu nome de usuário deve ser de 15 caracteres ou menos!<br />\n"; //Add to error message
			$error = 1; //Set error check
			$erro1 = 1;
		} else if (!preg_match("/^[A-Za-z[:space:]\-]+$/", $_POST['username'])) { //If username contains illegal characters...
			$msg1 .= "Seu nome de usuário não pode conter <b>números</b> ou <b>caracteres especiais</b>!<br />\n"; //Add to error message
			$error = 1; //Set error check
			$erro1 = 1;
		} else if ($query->recordcount() > 0) {
			$msg1 .= "Este nome de usuário já está sendo usado!<br />\n";
			$error = 1; //Set error check
			$erro1 = 1;
		}

		if ($_POST['voc'] == 'none') {
			$msg2 .= "Voc&ecirc; precisa escolher uma vocação!";
			$error = 1;
			$erro2 = 1;
		}

		if (($_POST['voc'] != 'archer') and ($_POST['voc'] != 'knight') and ($_POST['voc'] != 'mage') and ($_POST['voc'] != 'none')) {
			$msg2 .= "Voc&ecirc; precisa escolher uma vocação!";
			$error = 1; //Set error check
			$erro2 = 1;
		}


		if ($error == 0) {
			$pat[0] = "/^\s+/";
			$pat[1] = "/\s{2,}/";
			$pat[2] = "/\s+\$/";
			$rep[0] = "";
			$rep[1] = " ";
			$rep[2] = "";
			$nomedeusuario = ucwords(preg_replace($pat, $rep, strtolower($_POST['username'])));
			$nomedeusuario2 = ucwords(strtolower($_POST['username']));

			$checkvip = $db->execute("select `vip` from `players` where `acc_id`=? and `vip`>? limit 1", array($acc->id, time()));
			if ($checkvip->recordcount() > 0) {
				$vip = $checkvip->fetchrow();
				$vip = $vip['vip'];
			} else {
				$vip = 0;
			}

			$insert['acc_id'] = $acc->id;
			$insert['username'] = $nomedeusuario;
			$insert['registered'] = time();
			$insert['last_active'] = time();
			$insert['ip'] = $_SERVER['REMOTE_ADDR'];
			$insert['voc'] = $_POST['voc'];
			$insert['serv'] = 1;
			$insert['vip'] = $vip;
			$query = $db->autoexecute('players', $insert, 'INSERT');

			$playerid = $db->execute("select `id` from `players` where `username`=?", array($nomedeusuario));
			$player = $playerid->fetchrow();

			if ($_POST['voc'] == 'archer') {
				$insert['player_id'] = $player['id'];
				$insert['item_id'] = 81;
				$query = $db->autoexecute('items', $insert, 'INSERT');
			} elseif ($_POST['voc'] == 'knight') {
				$insert['player_id'] = $player['id'];
				$insert['item_id'] = 8;
				$query = $db->autoexecute('items', $insert, 'INSERT');
			} elseif ($_POST['voc'] == 'mage') {
				$insert['player_id'] = $player['id'];
				$insert['item_id'] = 92;
				$query = $db->autoexecute('items', $insert, 'INSERT');
			}

			$numpots = 3;
			$playerpots = $player['id'];

			for ($i = 0; $i < $numpots; $i++) {
				$insert['player_id'] = $playerpots;
				$insert['item_id'] = 136;
				$query = $db->autoExecute('items', $insert, 'INSERT');
			}

			if ($query) {
				echo "<span id=\"aviso-a\"></span>";
				echo "<br/><p><center style=\"font-size: 14px;font-weight: bold;text-align: center;\">Seu personagem foi criado com sucesso!<br />";
				echo "<a style=\"color:#ffef8f;\" href=\"login.php?id=" . $player['id'] . "\">Clique aqui</a> e comece a jogar com " . $nomedeusuario . ".</center></p><br />";
				include("templates/acc-footer.php");
				exit;
			}
		}
	}

?>
	<span id="aviso-a">
		<?php
		if ($msg1 != "") {
			echo $msg1;
		} else if ($msg2 != "") {
			echo $msg2;
		}

		if ($_POST['register']) {
			if ($msg1 == "") {
				$certo1 = 1;
			}
			if ($msg2 == "") {
				$certo2 = 1;
			}
		}
		?>
	</span>

	<br />
	<?php include("box.php"); ?>
	<form method="POST" action="newchar.php">
		<table width="90%" align="center" border=\"0px\">
			<tr>
				<td width="28%"><b>Nome</b>:</td>
				<td width="72%"><input type="text" name="username" id="username" value="<?= $_POST['username']; ?>"
						class="inp" size="20" /><span id="msgbox"><?php
																	if ($erro1 == 1) {
																		echo "<span id=\"erro\"></span>";
																	} else if ($certo1 == 1) {
																		echo "<span id=\"certo\"></span>";
																	} ?></span></td>
			</tr>
			<tr>
				<td width="28%"><b>Vocação</b>:</td>
				<td width="72%"><select name="voc" onchange="swapText(this)" class="inp">
						<option value="none" selected="selected">Selecione</option>
						<option value="knight">Guerreiro</option>
						<option value="mage">Mago</option>
						<option value="archer">Arqueiro</option>
					</select><?php
								if ($erro2 == 1) {
									echo "<span id=\"erro\"></span>";
								} else if ($certo2 == 1) {
									echo "<span id=\"certo\"></span>";
								} ?></td>
			</tr>
			<tr>
				<td colspan="2" style="font-size: 14px;font-weight: bold;text-align: center;padding: 15px;">
					<div id="textDiv">
						<div>Escolha sua vocação.</div>
				</td>
			</tr>
		</table>
		<br />
		<center><button type="submit" name="register" value="Criar Personagem" class="personagem"></button></center>
	</form>
<?php
	include("templates/acc-footer.php");
}
?>