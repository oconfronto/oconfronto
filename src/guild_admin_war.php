<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Administração do Clã");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkguild.php");

$guildquery = $db->execute("select * from `guilds` where `id`=?", [$player->guild]);
if ($guildquery->recordcount() == 0) {
	header("Location: home.php");
} else {
	$guild = $guildquery->fetchrow();
}

$enyguildquery = $db->execute("select * from `guilds` where `id`=?", [$_GET['id'] ?? null]);
if ($enyguildquery->recordcount() == 0) {
	header("Location: guild_admin_enemy.php");
} else {
	$enyguild = $enyguildquery->fetchrow();
}

include(__DIR__ . "/templates/private_header.php");

if ($player->username != ($guild['leader'] ?? null) && $player->username != ($guild['vice'] ?? null)) {
	echo "<p />Você não pode acessar esta página.<p />";
	echo '<a href="home.php">Principal</a><p />';
} else {

	$checkwarquery = $db->execute("select * from `pwar` where ((`guild_id`=?) or (`enemy_id`=?)) and `status`='p'", [$guild['id'] ?? null, $guild['id'] ?? null]);
	if ($checkwarquery->recordcount() > 0) {
		echo "JÁ existe um chamado de guerra contra o clã " . $enyguild['name'] . ".";
		echo '<br/><a href="guild_admin_enemy.php">Voltar</a>.';
	} elseif (($guild['members'] ?? null) < 3) {
		echo "Seu clã não possui membros suficientes para iniciar uma guerra. (min. 3 membros)";
		echo '<br/><a href="guild_admin_enemy.php">Voltar</a>.';
	} elseif (($enyguild['members'] ?? null) < 3) {
		echo "O clã " . $enyguild['name'] . " não possui membros suficientes para iniciar uma guerra. (min. 3 membros)";
		echo '<br/><a href="guild_admin_enemy.php">Voltar</a>.';
	} elseif ($_POST['submit'] ?? null) {
		if (!($_POST['wnumber'] ?? null) || !($_POST['gold'] ?? null)) {
			echo "Por favor preencha todos os campos.";
			echo '<br/><a href="guild_admin_war.php?id=' . $_GET['id'] . '">Voltar</a>.';
		} elseif (!is_numeric($_POST['gold']) || ($_POST['gold'] ?? null) < 1) {
			echo "Insira uma quantia de ouro válida.";
			echo '<br/><a href="guild_admin_war.php?id=' . $_GET['id'] . '">Voltar</a>.';
		} elseif (($_POST['gold'] ?? null) > ($guild['gold'] ?? null)) {
			echo "Seu clã não possui tanto ouro para apostar.";
			echo '<br/><a href="guild_admin_war.php?id=' . $_GET['id'] . '">Voltar</a>.';
		} elseif (($_POST['gold'] ?? null) < 10000) {
			echo "A aposta mínima é de 10000 moedas de ouro.";
			echo '<br/><a href="guild_admin_war.php?id=' . $_GET['id'] . '">Voltar</a>.';
		} elseif (($_POST['wnumber'] ?? null) > ($guild['members'] ?? null)) {
			echo "Seu clã não possui membros suficientes para uma guerra deste tamanho.";
			echo '<br/><a href="guild_admin_war.php?id=' . $_GET['id'] . '">Voltar</a>.';
		} elseif (($_POST['wnumber'] ?? null) > ($enyguild['members'] ?? null)) {
			echo "O clã " . $enyguild['name'] . " não possui membros suficientes para iniciar uma guerra deste tamanho.";
			echo '<br/><a href="guild_admin_war.php?id=' . $_GET['id'] . '">Voltar</a>.';
		} elseif (!($_POST['startwar'] ?? null)) {
			echo "<center>Selecione os " . $_POST['wnumber'] . " membros do seu clã que irão lutar na guerra.</center><center><font size=\"1px\">(eles não precisam estar online no momento da guerra para lutarem)</font></center>";
			$guildmembers = $db->execute("select * from `players` where `guild`=? order by `level` desc", [$guild['id'] ?? null]);
			echo '<form method="post" action="guild_admin_war.php?id=' . $_GET['id'] . "\">\n";
			echo '<input type="hidden" name="wnumber" value="' . $_POST['wnumber'] . '">';
			echo '<input type="hidden" name="gold" value="' . $_POST['gold'] . '">';
			echo '<input type="hidden" name="submit" value="Proclamar Guerra">';
			echo "<table width=\"100%\" border=\"0\">\n";
			echo "<tr>\n";
			echo "<td width=\"5%\"></td>\n";
			echo "<td width=\"30%\"><b>Usuário</b></td>\n";
			echo "<td width=\"10%\"><b>Nível</b></td>\n";
			echo "<td width=\"30%\"><b>Vocação</b></td>\n";
			echo "<td width=\"25%\"><b>Pontuação</b></td>\n";
			echo "</tr>\n";
			while ($member = $guildmembers->fetchrow()) {
				$bool = ($bool == 1) ? 2 : 1;
				echo '<tr class="row' . $bool . "\">\n";
				echo '<td width="5%"><input type="checkbox" name="id[]" value="' . $member['id'] . "\" /></td>\n";
				echo '<td width="30%"><b><a href="profile.php?id=' . $member['username'] . '">' . $member['username'] . "</a></b></td>";
				echo '<td width="10%">' . $member['level'] . "</td>";
				echo '<td width="30%">';

				if (($member['voc'] ?? null) == 'archer' && ($member['promoted'] ?? null) == 'f') {
					echo "Caçador";
				} elseif (($member['voc'] ?? null) == 'knight' && ($member['promoted'] ?? null) == 'f') {
					echo "Espadachim";
				} elseif (($member['voc'] ?? null) == 'mage' && ($member['promoted'] ?? null) == 'f') {
					echo "Bruxo";
				} elseif (($member['voc'] ?? null) == 'archer' && (($member['promoted'] ?? null) == 't' || ($member['promoted'] ?? null) == 's' || ($member['promoted'] ?? null) == 'r')) {
					echo "Arqueiro";
				} elseif (($member['voc'] ?? null) == 'knight' && (($member['promoted'] ?? null) == 't' || ($member['promoted'] ?? null) == 's' || ($member['promoted'] ?? null) == 'r')) {
					echo "Guerreiro";
				} elseif (($member['voc'] ?? null) == 'mage' && (($member['promoted'] ?? null) == 't' || ($member['promoted'] ?? null) == 's' || ($member['promoted'] ?? null) == 'r')) {
					echo "Mago";
				} elseif (($member['voc'] ?? null) == 'archer' && ($member['promoted'] ?? null) == 'p') {
					echo "Arqueiro Royal";
				} elseif (($member['voc'] ?? null) == 'knight' && ($member['promoted'] ?? null) == 'p') {
					echo "Cavaleiro";
				} elseif (($member['voc'] ?? null) == 'mage' && ($member['promoted'] ?? null) == 'p') {
					echo "Arquimago";
				}

				echo "</td>";
				echo '<td width="25%">' . ceil(($member['kills'] * 6) + ($member['monsterkilled'] / 3) + ($member['groupmonsterkilled'] / 12) - ($member['deaths'] * 35)) . "</td></tr>";
			}

			echo "</table>";
			echo '<br/><input type="submit" name="startwar" value="Proclamar Guerra"> <a href="guild_admin_war.php?id=' . $_GET['id'] . '">Voltar</a>.';
		} else {
			if (!($_POST['id'] ?? null)) {

				echo "Selecione os membros do clã que devem participar da guerra.";
				echo '<br/><a href="guild_admin_war.php?id=' . $_GET['id'] . '">Voltar</a>.';
			}

			$totalselected = 0;
			foreach ($_POST['id'] ?? null as $memb) {
				$totalselected += 1;
			}

			if (!($_POST['wnumber'] ?? null) || !($_POST['gold'] ?? null)) {
				echo "Por favor preencha todos os campos.";
				echo '<br/><a href="guild_admin_war.php?id=' . $_GET['id'] . '">Voltar</a>.';
			} elseif (!is_numeric($_POST['gold']) || ($_POST['gold'] ?? null) < 1) {
				echo "Insira uma quantia de ouro válida.";
				echo '<br/><a href="guild_admin_war.php?id=' . $_GET['id'] . '">Voltar</a>.';
			} elseif (($_POST['gold'] ?? null) > ($guild['gold'] ?? null)) {
				echo "Seu clã não possui tanto ouro para apostar.";
				echo '<br/><a href="guild_admin_war.php?id=' . $_GET['id'] . '">Voltar</a>.';
			} elseif (($_POST['gold'] ?? null) < 10000) {
				echo "A aposta mínima é de 10000 moedas de ouro.";
				echo '<br/><a href="guild_admin_war.php?id=' . $_GET['id'] . '">Voltar</a>.';
			} elseif (($_POST['wnumber'] ?? null) > ($guild['members'] ?? null)) {
				echo "Seu clã não possui membros suficientes para uma guerra deste tamanho.";
				echo '<br/><a href="guild_admin_war.php?id=' . $_GET['id'] . '">Voltar</a>.';
			} elseif (($_POST['wnumber'] ?? null) > ($enyguild['members'] ?? null)) {
				echo "O clã " . $enyguild['name'] . " não possui membros suficientes para iniciar uma guerra deste tamanho.";
				echo '<br/><a href="guild_admin_war.php?id=' . $_GET['id'] . '">Voltar</a>.';
			} elseif (($_POST['wnumber'] ?? null) != $totalselected) {
				echo "Você precisa selecionar " . $_POST['wnumber'] . " membros do clã para a guerra.";
				echo '<br/><a href="guild_admin_war.php?id=' . $_GET['id'] . '">Voltar</a>.';
			} else {
				$db->execute("update `guilds` set `gold`=`gold`-?, `blocked`=`blocked`+? where `id`=?", [$_POST['gold'] ?? null, $_POST['gold'] ?? null, $guild['id'] ?? null]);

				$insert['guild_id'] = $guild['id'];
				$insert['enemy_id'] = $enyguild['id'];
				$insert['bet'] = $_POST['gold'];
				foreach ($_POST['id'] ?? null as $memb) {
					$insertnumber += 1;
					if ($insertnumber < $totalselected) {
						$addmemb .= $memb . ', ';
					} else {
						$addmemb .= $memb;
					}

					$logmsg = "Seu clã enviou um pedido de guerra ao clã <b>" . $enyguild['name'] . "</b>, e você foi um dos escolhidos para lutar.<br/>Se o clã <b>" . $enyguild['name'] . "</b> aceitar o convite você será informado.";
					addlog($memb, $logmsg, $db);
				}

				$insert['players_guild'] = $addmemb;
				$db->autoexecute('pwar', $insert, 'INSERT');

				$lider = $db->GetOne("select `id` from `players` where `username`=?", [$enyguild['leader'] ?? null]);
				$logmsg = "O clã <b>" . $guild['name'] . "</b> está enviando um pedido de guerra estilo <b>" . $_POST['wnumber'] . "x" . $_POST['wnumber'] . "</b>.<br/>O valor da aposta é de <b>" . $_POST['gold'] . ' moedas de ouro</b>. <a href="guild_war_request.php?id=' . $db->Insert_ID() . '">Clique aqui</a> para aceitar o convite.';
				addlog($lider, $logmsg, $db);
				if (($enyguild['vice'] ?? null) != NULL) {
					$vice = $db->GetOne("select `id` from `players` where `username`=?", [$enyguild['vice'] ?? null]);
					addlog($vice, $logmsg, $db);
				}

				echo "<i>Você enviou um pedido de guerra para o clã: " . $enyguild['name'] . ".</i><br/><br/><font size=\"1px\">A guerra irá começar algumas horas depois que o clã inimigo aceitar o convite.<br/>As " . $_POST['gold'] . " moedas de ouro apostadas não poderão ser retiradas do tesouro do seu clã enquanto a guerra não ocorrer ou o pedido não for cancelado.</font>";
				echo '<br/><a href="guild_admin_war.php?id=' . $_GET['id'] . '">Voltar</a>.';
			}
		}
	} else {
		echo "<i>Você está prestes a proclamar guerra com o clã: " . $enyguild['name'] . "</i><br/>";
		echo '<font size="1px">Selecione o tamanho da batalha e em seguida a quantia de ouro a ser apostada.</font><br/><br/>';

		echo '<form method="POST" action="guild_admin_war.php?id=' . $_GET['id'] . '">';
		echo "<b>Tamanho da batalha:</b> ";
		echo "<select name=\"wnumber\"><option value=''>Selecione</option>";
		if (($guild['members'] ?? null) >= 3 && ($enyguild['members'] ?? null) >= 3) {
			echo '<option value="3">3x3</option>';
		}

		if (($guild['members'] ?? null) >= 5 && ($enyguild['members'] ?? null) >= 5) {
			echo '<option value="5">5x5</option>';
		}

		if (($guild['members'] ?? null) >= 7 && ($enyguild['members'] ?? null) >= 7) {
			echo '<option value="7">7x7</option>';
		}

		if (($guild['members'] ?? null) >= 10 && ($enyguild['members'] ?? null) >= 10) {
			echo '<option value="10">10x10</option>';
		}

		if (($guild['members'] ?? null) >= 15 && ($enyguild['members'] ?? null) >= 15) {
			echo '<option value="15">15x15</option>';
		}

		echo "</select>";

		echo '<br/><b>Aposta:</b> <input type="text" name="gold" size="20"/> moedas de ouro.';
		echo '<br/><br/><input type="submit" name="submit" value="Proclamar Guerra"> <a href="guild_admin_enemy.php">Voltar</a>.';
		echo "</form>";
	}
}

include(__DIR__ . "/templates/private_footer.php");
