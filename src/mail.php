<?php

declare(strict_types=1);

/*************************************/
/*           ezRPG script            */
/*         Written by Zeggy          */
/*  http://code.google.com/p/ezrpg   */
/*    http://www.ezrpgproject.com/   */
/*************************************/

include(__DIR__ . "/lib.php");
include(__DIR__ . "/bbcode.php");
define("PAGENAME", "Mensagens");
$player = check_user($db);

$errormsg = '<font color="red">';
$errors = 0;
if ($_POST['sendmail'] ?? null) {
	//Process mail info, show success message
	$query = $db->execute("select `id`, `gm_rank` from `players` where `username`=?", [$_POST['to'] ?? null]);
	if ($query->recordcount() == 0) {
		$errormsg .= "Este usuário não existe!<br />";
		$errors = 1;
	}

	$sendto = $query->fetchrow();

	if (!($_POST['body'] ?? null)) {
		$errormsg .= "Você precisa digitar uma mensagem!<br />";
		$errors = 1;
	}

	if (($sendto['gm_rank'] ?? null) > 10 && $player->gm_rank < 2) {
		$errormsg .= "Você não pode enviar mensagens diretamente para o administrador!<br />";
		$errormsg .= "Se o assunto for sério mande uma mensagem para um de nossos moderadores:<br/>";
		$query4 = $db->execute("select `username` from `players` where `gm_rank`>2 and `id`!=1 order by rand()");

		while ($member1 = $query4->fetchrow()) {
			$errormsg .= '<a href="mail.php?act=compose&to=' . $member1['username'] . '">';
			$errormsg .= $member1['username'];
			$errormsg .= "</a> | ";
		}

		$errormsg .= "<br />";
		$errors = 1;
	}

	$ignorado = $db->execute("select * from `ignored` where `uid`=? and `bid`=?", [$sendto['id'] ?? null, $player->id]);
	if ($ignorado->recordcount() > 0) {
		$errormsg .= "Você está sendo ignorado por este usuário e não poderá enviar mensagens para ele.<br />";
		$errors = 1;
	}


	if ($errors != 1) {
		$insert['to'] = $sendto['id'];
		$insert['from'] = $player->id;
		$insert['body'] = $_POST['body'];
		$insert['body'] = htmlentities((string) ($_POST['body'] ?? null), ENT_QUOTES);
		$insert['subject'] = (($_POST['subject'] ?? null) == "") ? "Sem Assunto" : $_POST['subject'] ?? null;
		$insert['time'] = time();
		$query = $db->execute("insert into `mail` (`to`, `from`, `body`, `subject`, `time`) values (?, ?, ?, ?, ?)", [$insert['to'] ?? null, $insert['from'] ?? null, $insert['body'] ?? null, $insert['subject'] ?? null, $insert['time'] ?? null]);
		if ($query) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset>";
			echo "<legend><b>Sucesso</b></legend>";
			echo "Sua mensagem foi enviada com sucesso para " . $_POST['to'] . '!<br/><a href="mail.php">Voltar.</a>';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		$errormsg .= "Erro, a mensagem não pode ser enviada.";
		//Add to admin error log, or whatever, maybe for another version ;)

	}
}

$errormsg .= "</font><br />\n";

include(__DIR__ . "/templates/private_header.php");
?>

<script>
	function checkAll() {
		count = document.inbox.elements.length;
		for (i = 0; i < count; i++) {
			if (document.inbox.elements[i].checked == 1) {
				document.inbox.elements[i].checked = 0;
				document.inbox.check.checked = 0;
			} else {
				document.inbox.elements[i].checked = 1;
				document.inbox.check.checked = 1;
			}
		}
	}
</script>

<?php
echo "<p><center>";
if (($_GET['act'] ?? null) != "enviadas" && ($_GET['act'] ?? null) != "ignore" && ($_GET['act'] ?? null) != "compose") {
	echo '<a href="mail.php"><b>Caixa de entrada</b></a> | ';
} else {
	echo '<a href="mail.php">Caixa de entrada</a> | ';
}

if (($_GET['act'] ?? null) == "enviadas") {
	echo '<a href="mail.php?act=enviadas"><b>Mensagens enviadas</b></a> | ';
} else {
	echo '<a href="mail.php?act=enviadas">Mensagens enviadas</a> | ';
}

if (($_GET['act'] ?? null) == "ignore") {
	echo "<a href=\"mail.php?act=ignore\"><b>Usuários ignorados</b></a> | ";
} else {
	echo "<a href=\"mail.php?act=ignore\">Usuários ignorados</a> | ";
}

if (($_GET['act'] ?? null) == "compose") {
	echo '<a href="mail.php?act=compose"><b>Escrever mensagem</b></a>';
} else {
	echo '<a href="mail.php?act=compose">Escrever mensagem</a>';
}

echo "</p></center>";

switch ($_GET['act'] ?? null) {
	case "ignore": //Reading a message
		if ($_POST['add'] ?? null) {
			$query = $db->execute("select `id`, `gm_rank` from `players` where `username`=?", [$_POST['add'] ?? null]);
			if ($query->recordcount() == 0) {
				echo "Este ususuário não existe!<br/><a href=\"mail.php?act=ignore\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				exit;
			}

			$ignore = $query->fetchrow();
			if (($ignore['id'] ?? null) == $player->id) {
				echo "Você não pode ignorar você mesmo!<br><a href=\"mail.php?act=ignore\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				exit;
			}

			$query = $db->execute("select * from `ignored` where `bid`=? and `uid`=?", [$ignore['id'] ?? null, $player->id]);
			if ($query->recordcount() > 0) {
				echo "Você já está ignorando este usuário!<br><a href=\"mail.php?act=ignore\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				exit;
			}

			$insert['uid'] = $player->id;
			$insert['bid'] = $ignore['id'];
			$query = $db->autoexecute('ignored', $insert, 'INSERT');
			if ($query) {
				echo "" . showName($ignore['id'], $db, 'off') . ' foi ignorado!<br><a href="mail.php?act=ignore">Voltar</a>.';
				include(__DIR__ . "/templates/private_footer.php");
				exit;
			}

			echo 'Um erro desconhecido ocorreu!<br><a href="mail.php?act=ignore">Voltar</a>.';
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		//Reading a message

		if ($_GET['delete'] ?? null) {
			$query = $db->execute("delete from `ignored` where `uid`=? and `bid`=?", [$player->id, $_GET['delete'] ?? null]);
			if ($query) {
				echo "Agora " . showName($_GET['delete'], $db, 'off') . " não está mais sendo ignorado!<br><a href=\"mail.php?act=ignore\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				exit;
			}

			echo 'Um erro desconhecido ocorreu!<br><a href="mail.php?act=ignore">Voltar</a>.';
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		echo "<fieldset>";
		echo "<legend><b>Usuários Ignorados</b></legend>";

		$query = $db->execute("select `bid` from `ignored` WHERE `uid`=?", [$player->id]);
		if ($query->recordcount() == 0) {
			echo "<p><center>Você não está ignorando ninguém.</center></p>";
		} else {

			while ($friend = $query->fetchrow()) {
				echo "<table width=\"100%\">\n";
				echo '<tr><td width="60%">' . showName($friend['bid'], $db, 'off') . '</td><td><a href="mail.php?act=compose&to=' . showName($friend['bid'], $db, 'off', 'off') . '">Mensagem</a> | <a href="mail.php?act=ignore&delete=' . $friend['bid'] . '">Deletar</a></td></tr>';
				echo "</table>";
			}
		}

		echo "</fieldset>";

		if ($query->recordcount() == 0) {
			echo "<font size=\"1px\">Você está ignorando " . $query->recordcount() . " usuário(s)</font>";
		}

		echo "<br/><br/>\n";
		echo "<fieldset>\n";
		echo "<legend><b>Ignorar Usuário</b></legend>\n";
		echo "<form method=\"POST\" action=\"mail.php?act=ignore\">\n";
		echo "<table width=\"100%\">\n";
		echo "<tr>\n<td width=\"20%\">Nome do usuário:</td>\n<td width=\"30%\"><input type=\"text\" name=\"add\" size=\"25\"/></td>";
		echo "<td width=\"50%\"><input type=\"submit\" value=\"Ignorar Usuário\" /></td></tr>\n";
		echo "</table>\n";
		echo "</form>\n</fieldset>\n";
		break;


	case "read": //Reading a message
		$query = $db->execute("select `id`, `to`, `from`, `subject`, `body`, `time`, `status` from `mail` where `id`=?", [$_GET['id'] ?? null]);
		if ($query->recordcount() == 1) {
			$msg = $query->fetchrow();

			if ($player->id != ($msg['to'] ?? null) && $player->id != ($msg['from'] ?? null)) {
				echo "OPS! Parece que um erro ocorreu.";
				break;
			}

			echo "<table width=\"100%\" border=\"0\">\n";
			echo '<tr><td width="20%"><b>Para:</b></td><td width="80%">' . showName($msg['to'], $db) . "</td></tr>\n";
			echo '<tr><td width="20%"><b>De:</b></td><td width="80%">' . showName($msg['from'], $db) . "</td></tr>\n";

			$mes = date("M", $msg['time']);
			$mes_ano["Jan"] = "Janeiro";
			$mes_ano["Feb"] = "Fevereiro";
			$mes_ano["Mar"] = "Março";
			$mes_ano["Apr"] = "Abril";
			$mes_ano["May"] = "Maio";
			$mes_ano["Jun"] = "Junho";
			$mes_ano["Jul"] = "Julho";
			$mes_ano["Aug"] = "Agosto";
			$mes_ano["Sep"] = "Setembro";
			$mes_ano["Oct"] = "Outubro";
			$mes_ano["Nov"] = "Novembro";
			$mes_ano["Dec"] = "Dezembro";

			echo '<tr><td width="20%"><b>Data:</b></td><td width="80%">' . date("d", $msg['time']) . " de " . $mes_ano[$mes] . " de " . date("Y, g:i A", $msg['time']) . "</td></tr>";
			echo '<tr><td width="20%"><b>Assunto:</b></td><td width="80%">' . stripslashes((string) ($msg['subject'] ?? null)) . "</td></tr>";
			echo '<tr><td width="20%"><b>Mensagem:</b></td><td width="80%">' . (new bbcode())->parse(stripslashes(nl2br((string) ($msg['body'] ?? null)))) . "</td></tr>";
			echo "</table>";
			if ($player->id == ($msg['to'] ?? null) && ($msg['status'] ?? null) == "unread") {
				$query = $db->execute("update `mail` set `status`='read' where `id`=?", [$msg['id'] ?? null]);
			}

			echo "<br /><br />\n";
			echo "<table width=\"30%\">\n";
			echo "<tr><td width=\"50%\">\n";
			if ($player->id == ($msg['to'] ?? null)) {
				echo "<form method=\"post\" action=\"mail.php?act=compose\">\n";
				echo '<input type="hidden" name="to" value="' . showName($msg['from'], $db, "off", "off") . "\" />\n";
				echo '<input type="hidden" name="subject" value="RE: ' . stripslashes((string) ($msg['subject'] ?? null)) . "\" />\n";
				$reply = explode("\n", (string) ($msg['body'] ?? null));
				foreach ($reply as $key => $value) {
					$reply[$key] = ">>" . $value;
				}

				$reply = implode("\n", $reply);
				echo "<input type=\"hidden\" name=\"body\" value=\"\n\n\n" . $reply . "\" />\n";
				echo "<input type=\"submit\" value=\"Responder\" />\n";
				echo "</form>\n";
				echo "</td><td width=\"50%\">\n";
				echo "<form method=\"post\" action=\"mail.php?act=delete\">\n";
				echo '<input type="hidden" name="id" value="' . $msg['id'] . "\" />\n";
				echo "<input type=\"submit\" name=\"delone\" value=\"Deletar\" />\n";
				echo "</form>\n";
			}

			echo "</td></tr>\n</table>";
		} else {
			echo "OPS! Parece que um erro ocorreu.2";
		}

		break;

	case "compose": //Composing mail (justt he form, processing is at the top of the page)
		echo $errormsg;
		echo "<fieldset>";
		echo "<legend><b>Escrever Mensagem</b></legend>";
		echo "<form method=\"POST\" action=\"mail.php?act=compose\">\n";
		echo "<table width=\"100%\" border=\"0\">\n";
		echo '<tr><td width="20%"><b>Para:</b></td><td width="80%"><input type="text" name="to" value="';
		echo (($_POST['to'] ?? null) != "") ? $_POST['to'] ?? null : $_GET['to'] ?? null;
		echo "\" /></td></tr>\n";
		echo '<tr><td width="20%"><b>Assunto:</b></td><td width="80%"><input type="text" name="subject" value="';
		echo (($_POST['subject'] ?? null) != "") ? stripslashes((string) ($_POST['subject'] ?? null)) : stripslashes((string) ($_GET['subject'] ?? null));
		echo "\" /></td></tr>\n";
		echo '<tr><td width="20%"><b>Mensagem:</b></td><td width="80%"><textarea name="body" rows="15" cols="50">';
		echo (($_POST['body'] ?? null) != "") ? stripslashes(stripslashes((string) ($_POST['body'] ?? null))) : stripslashes(stripslashes((string) ($_GET['body'] ?? null)));
		echo "</textarea></td></tr>\n";
		echo "<tr><td></td><td><input type=\"submit\" value=\"Enviar Mensagem\" name=\"sendmail\" /></td></tr>\n";
		echo "</table>\n";
		echo "</form>\n";
		echo "</fieldset>";
		break;

	case "delete":
		if ($_POST['delone'] ?? null) {
			//Deleting message from viewing page, single delete
			if (!($_POST['id'] ?? null)) {
				echo "Uma mensagem deve ser selecionada!";
			} else {
				$query = $db->getone("select count(*) as `count` from `mail` where `id`=? and `to`=?", [$_POST['id'] ?? null, $player->id]);
				if (($query['count'] = 0) !== 0) {
					//In case there are some funny guys out there ;)
					echo "Esta(s) mensagem não pertence a você!";
				} elseif (!($_POST['deltwo'] ?? null)) {
					echo "Você tem certeza que quer deletar esta(s) mensagem(s)?<br /><br />\n";
					echo "<form method=\"post\" action=\"mail.php?act=delete\">\n";
					echo '<input type="hidden" name="id" value="' . $_POST['id'] . "\" />\n";
					echo "<input type=\"hidden\" name=\"deltwo\" value=\"1\" />\n";
					echo "<input type=\"submit\" name=\"delone\" value=\"Deletar\" />\n";
					echo "</form>";
				} else {
					$query = $db->execute("delete from `mail` where `id`=?", [$_POST['id'] ?? null]);
					echo "A mensagem foi deletada com sucesso!";
					//Redirect back to inbox, or show success message
					//Can be changed in the admin panel
				}
			}
		} elseif ($_POST['delmultiple'] ?? null) {
			//Deleting messages from inbox, multiple selections
			if (!($_POST['id'] ?? null)) {
				echo "A message must be selected!";
			} else {
				foreach ($_POST['id'] ?? null as $msg) {
					$query = $db->getone("select count(*) as `count` from `mail` where `id`=? and `to`=?", [$msg, $player->id]);
					if (($query['count'] = 0) !== 0) {
						//In case there are some funny guys out there ;)
						echo "Esta(s) mensagem(s) não pertence a você!";
						$delerror = 1;
					}
				}

				if (!$delerror) {
					if (!($_POST['deltwo'] ?? null)) {
						echo "Você tem certeza que quer deletar esta(s) mensagem(s)?<br /><br />\n";
						echo "<form method=\"post\" action=\"mail.php?act=delete\">\n";
						foreach ($_POST['id'] ?? null as $msg) {
							echo '<input type="hidden" name="id[]" value="' . $msg . "\" />\n";
						}

						echo "<input type=\"hidden\" name=\"deltwo\" value=\"1\" />\n";
						echo "<input type=\"submit\" name=\"delmultiple\" value=\"Deletar\" />\n";
						echo "</form>";
					} else {
						foreach ($_POST['id'] ?? null as $msg) {
							$query = $db->execute("delete from `mail` where `id`=?", [$msg]);
						}

						echo "A mensagem foi deletada com sucesso!";
						//Redirect back to inbox, or show success message
						//Can be changed in the admin panel (TODO)
					}
				}
			}
		}

		break;

	case "enviadas":
		echo "<table width=\"100%\" border=\"0\">\n";
		echo "<tr>\n";
		echo "<td width=\"20%\"><b>Para</b></td>\n";
		echo "<td width=\"35%\"><b>Assunto</b></td>\n";
		echo "<td width=\"40%\"><b>Data</b></td>\n";
		echo "</tr>\n";
		$query = $db->execute("select `id`, `to`, `subject`, `time` from `mail` where `from`=? order by `time` desc", [$player->id]);
		if ($query->recordcount() > 0) {
			$bool = 1;
			while ($msg = $query->fetchrow()) {
				echo '<tr class="row' . $bool . "\">\n";
				echo '<td width="20%" style="vertical-align: middle;">';
				echo showName($msg['to'], $db);
				echo "</td>\n";

				echo '<td width="40%" style="vertical-align: middle;">';
				echo '<a href="mail.php?act=read&id=' . $msg['id'] . '">' . stripslashes((string) ($msg['subject'] ?? null)) . "</a>";
				echo "</td>\n";

				$mes = date("M", $msg['time']);
				$mes_ano["Jan"] = "Janeiro";
				$mes_ano["Feb"] = "Fevereiro";
				$mes_ano["Mar"] = "Março";
				$mes_ano["Apr"] = "Abril";
				$mes_ano["May"] = "Maio";
				$mes_ano["Jun"] = "Junho";
				$mes_ano["Jul"] = "Julho";
				$mes_ano["Aug"] = "Agosto";
				$mes_ano["Sep"] = "Setembro";
				$mes_ano["Oct"] = "Outubro";
				$mes_ano["Nov"] = "Novembro";
				$mes_ano["Dec"] = "Dezembro";

				echo '<td width="40%" style="vertical-align: middle;">' . date("d", $msg['time']) . " de " . $mes_ano[$mes] . " de " . date("Y, g:i A", $msg['time']) . "</td>\n";
				echo "</tr>\n";
				$bool = ($bool == 1) ? 2 : 1;
			}
		} else {
			echo "<tr class=\"row1\">\n";
			echo "<td colspan=\"4\"><b>Sem mensagens</b></td>\n";
			echo "</tr>\n";
		}

		echo "</table>\n";
		break;


	default: //Show inbox
		echo "<form method=\"post\" action=\"mail.php?act=delete\" name=\"inbox\">\n";
		echo "<table width=\"100%\" border=\"0\">\n";
		echo "<tr>\n";
		echo "<td width=\"5%\"><center><input type=\"checkbox\" onclick=\"javascript: checkAll();\" name=\"check\" /></center></td>\n";
		echo "<td width=\"20%\"><b>De</b></td>\n";
		echo "<td width=\"35%\"><b>Assunto</b></td>\n";
		echo "<td width=\"40%\"><b>Data</b></td>\n";
		echo "</tr>\n";
		$query = $db->execute("select `id`, `from`, `subject`, `time`, `status` from `mail` where `to`=? order by `time` desc limit 25", [$player->id]);
		if ($query->recordcount() > 0) {
			$qsfwwwy = $db->execute("select `id`, `from`, `subject`, `time`, `status` from `mail` where `to`=?", [$player->id]);
			if ($qsfwwwy->recordcount() > 25) {
				echo "<br/>";
				echo "<center><b>Mostrando 25 mensagens de " . $qsfwwwy->recordcount() . ". Delete mensagens para exibir as outras.</b></center>";
				echo "<br/>";
			}

			$bool = 1;
			while ($msg = $query->fetchrow()) {
				echo '<tr class="row' . $bool . "\">\n";
				echo '<td width="5%"><center><input type="checkbox" name="id[]" value="' . $msg['id'] . "\" /></center></td>\n";

				echo '<td width="20%" style="vertical-align: middle;">';
				echo showName($msg['from'], $db);
				echo "</td>\n";

				echo '<td width="35%" style="vertical-align: middle;">';
				echo (($msg['status'] ?? null) == "unread") ? "<b>" : "";
				echo '<a href="mail.php?act=read&id=' . $msg['id'] . '">' . stripslashes((string) ($msg['subject'] ?? null)) . "</a>";
				echo (($msg['status'] ?? null) == "unread") ? "</b>" : "";
				echo "</td>\n";

				$mes = date("M", $msg['time']);
				$mes_ano["Jan"] = "Janeiro";
				$mes_ano["Feb"] = "Fevereiro";
				$mes_ano["Mar"] = "Março";
				$mes_ano["Apr"] = "Abril";
				$mes_ano["May"] = "Maio";
				$mes_ano["Jun"] = "Junho";
				$mes_ano["Jul"] = "Julho";
				$mes_ano["Aug"] = "Agosto";
				$mes_ano["Sep"] = "Setembro";
				$mes_ano["Oct"] = "Outubro";
				$mes_ano["Nov"] = "Novembro";
				$mes_ano["Dec"] = "Dezembro";

				echo '<td width="40%" style="vertical-align: middle;">' . date("d", $msg['time']) . " de " . $mes_ano[$mes] . " de " . date("Y, g:i A", $msg['time']) . "</td>\n";
				echo "</tr>";
				$bool = ($bool == 1) ? 2 : 1;
			}
		} else {
			echo "<tr class=\"row1\">\n";
			echo "<td colspan=\"4\"><b>Sem mensagens</b></td>\n";
			echo "</tr>\n";
		}

		echo "</table>";
		echo "<input type=\"submit\" name=\"delmultiple\" value=\"Deletar Selecionados\" />\n";
		echo "</form>";
		break;
}

include(__DIR__ . "/templates/private_footer.php");
?>