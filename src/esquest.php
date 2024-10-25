<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Missão");
$player = check_user($db);

$quest = $db->execute("select * from `bixos` where `player_id`=? and `quest`='t'", [$player->id]);
$quest = $quest->fetchrow();

switch ($_GET['act']) {
	case "accept":
		if ($quest['hp'] == 1) {
			$db->execute("delete from `bixos` where `player_id`=?", [$player->id]);
			$db->execute("update `players` set `lutando`=0 where `id`=?", [$player->id]);

			if ($player->level >= 15 || ($player->strength + $player->vitality + $player->agility + $player->resistance) > 30) {
				$db->execute("update `players` set `gold`=`gold`+? where `id`=?", [5000, $player->id]);
				include(__DIR__ . "/templates/private_header.php");
				echo "<fieldset><legend><b>Missão</b></legend>\n";
				echo "<i>Você salvou o jovem da morte e ele te recompensou com 5000 moedas de ouro.</i><br><br>\n";
				echo '<a href="monster.php?act=attack&id=' . $quest['id'] . "\">Continuar Caça</a>.";
				echo "</fieldset>";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			} else {
				$db->execute("update `players` set `hp`=0, `mana`=0, `deadtime`=?, `deaths`=`deaths`+1 where `id`=?", [time() + $setting->dead_time, $player->id]);
				include(__DIR__ . "/templates/private_header.php");
				echo "<fieldset><legend><b>Missão</b></legend>\n";
				echo "<i>Você tentou salvar o jovem mas acabou sendo morto pelos lobos.</i><br><br>\n";
				echo '<a href="home.php">Voltar</a>.';
				echo "</fieldset>";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}
		} elseif ($quest['hp'] == 2) {
			$db->execute("delete from `bixos` where `player_id`=?", [$player->id]);
			$db->execute("update `players` set `lutando`=0 where `id`=?", [$player->id]);

			$potions = $db->execute("select * from `items` where `item_id`=136 and `player_id`=?", [$player->id]);
			if ($potions->recordcount() == 0) {
				include(__DIR__ . "/templates/private_header.php");
				echo "<fieldset><legend><b>Missão</b></legend>\n";
				echo "<i>Você não possui nenhuma poção para vender.</i><br><br>\n";
				echo '<a href="monster.php?act=attack&id=' . $quest['id'] . "\">Continuar Caça</a>.";
				echo "</fieldset>";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			} else {
				$db->execute("delete from `items` where `item_id`=136 and `player_id`=? limit 1", [$player->id]);
				$db->execute("update `players` set `gold`=`gold`+? where `id`=?", [7500, $player->id]);

				include(__DIR__ . "/templates/private_header.php");
				echo "<fieldset><legend><b>Missão</b></legend>\n";
				echo "<i>Você vendeu uma Health Potion por 7500 moedas de ouro.</i><br><br>\n";
				echo '<a href="monster.php?act=attack&id=' . $quest['id'] . "\">Continuar Caça</a>.";
				echo "</fieldset>";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}
		}

	case "decline":
		$db->execute("delete from `bixos` where `player_id`=?", [$player->id]);
		$db->execute("update `players` set `lutando`=0 where `id`=?", [$player->id]);

		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Missão</b></legend>\n";
		echo "<i>Você recusou e agora continua sua caça.</i><br><br>\n";
		echo '<a href="monster.php?act=attack&id=' . $quest['id'] . "\">Continuar Caça</a>.";
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		break;
}

if ($quest['hp'] == 1) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Missão</b></legend>\n";
	echo "<i>Enquanto você caçava vê um jovem guerreiro sendo atacado por lobos. O que você deseja fazer?</i><br/><br>\n";
	echo '<a href="esquest.php?act=accept">Atacar lobos</a> | <a href="esquest.php?act=decline">Ignorar</a>.';
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}
?>
<?php

if ($quest['hp'] == 2) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Missão</b></legend>\n";
	echo "<i>Ao caminho de sua caça você encontra um homem a beira da morte. Ele está lhe oferecendo 7500 moedas de ouro por uma simples poção de vida. O que você deseja fazer?</i><br/><br>\n";
	echo "<a href=\"esquest.php?act=accept\">Vender poção</a> | <a href=\"esquest.php?act=decline\">Ignorar</a>.";
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

include(__DIR__ . "/templates/private_header.php");
echo "<fieldset><legend><b>Erro</b></legend>\n";
echo "<i>Você não pode acessar esta página.</i><br><br>";
echo '<a href="home.php">Voltar</a>.';
echo "</fieldset>";
include(__DIR__ . "/templates/private_footer.php");
exit;
?>
