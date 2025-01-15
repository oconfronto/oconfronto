<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Missões");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");

if ($player->level < 300) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Missão</b></legend>\n";
	echo "<i>Seu nível é muito baixo!</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

$verificacao1 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 18]);

if ($verificacao1->recordcount() > 0) {
	$quest1 = $verificacao1->fetchrow();
}


$verificacao3 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=? and `quest_status`=?", [$player->id, 17, 90]);
if ($verificacao3->recordcount() < 1) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Missão</b></legend>\n";
	echo "<i>Você precisa completar outra missão primeiro.</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

switch ($_GET['act'] ?? null) {

	case "question":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Seu último desafio será trazer os olhos de Zanoth, a criatura mais temida que se conheçe. Ele se localiza no monte das almas, ao sul do império.</i><br><br>\n";
		echo '<a href="quest9.php?act=acept">Aceitar Desafio</a> / <a href="quest9.php?act=decline">Recusar</a>';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		break;


	case "decline":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Não fico surpreso por recusar, ele é um monstro muito poderoso. Volte quando achar que está pronto.</i><br><br>\n";
		echo "<a href=\"home.php\">Página Principal</a>.";
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		break;

	case "acept":
		if ($verificacao1->recordcount() > 0) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Aviso</b></legend>\n";
			echo "<i>Você já aceitou esta missão.</i><br><br>\n";
			echo "<a href=\"home.php\">Página Principal</a>.";
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		include(__DIR__ . "/templates/private_header.php");
		$insert['player_id'] = $player->id;
		$insert['quest_id'] = 18;
		$insert['quest_status'] = 1;
		$query = $db->autoexecute('quests', $insert, 'INSERT');
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Boa sorte em sua jornada guerreiro, espero que tenha sucesso.</i><br /><br />";
		echo '<a href="quest9.php">Procurar por Zanoth</a> / <a href="home.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		break;

	case "noready":
		if (($quest1['quest_status'] ?? null) == 1 || ($quest1['quest_status'] ?? null) == 2) {
			$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [2, $player->id, 18]);
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Missão</b></legend>\n";
			echo "<i>Você sente que ainda não está pronto e decide se esconder no monte das almas.</i><br><br>\n";
			echo "<a href=\"home.php\">Página Principal</a>.";
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		} else {
			header("Location: home.php");
		}

		break;

	case "ready":
		if (($quest1['quest_status'] ?? null) == 1 || ($quest1['quest_status'] ?? null) == 2 || ($quest1['quest_status'] ?? null) == 3) {
			$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [3, $player->id, 18]);

			header("Location: monster.php?act=attack&id=" . (49 * $player->id) . "");
		} else {
			header("Location: home.php");
		}

		break;

	case "abort":
		if ($verificacao1->recordcount() > 0) {
			$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [90, $player->id, 18]);
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Missão</b></legend>\n";
			echo "<i>Você abandonou a missão.</i><br><br>\n";
			echo '<a href="home.php">Voltar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		} else {
			header("Location: home.php");
		}

		break;
}
?>
<?php
if ($verificacao1->recordcount() == 0) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
	echo "<i>Seu ùltimo desafio será trazer os olhos de Zanoth, a criatura mais temida que se conheçe.</i><br/><br>\n";
	echo '<a href="quest9.php?act=question">Quais Itens?</a> / <a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if (($quest1['quest_status'] ?? null) == 1) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Missão</b></legend>\n";
	echo "<i>Você chegou ao monte das almas, e ouve sons de uma criatura poderoza. Podem ser os gritos de Zanoth. Está pronto para enfrentá-lo?</i><br/><br>\n";
	echo "<a href=\"quest9.php?act=ready\">Sim</a> / <a href=\"quest9.php?act=noready\">Não</a>";
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if (($quest1['quest_status'] ?? null) == 2) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Missão</b></legend>\n";
	echo "<i>Você está escondido no monte das almas, e continua ouvindo sons que parecem vir de Zanoth. Está pronto para enfrentá-lo?</i><br/><br>\n";
	echo "<a href=\"quest9.php?act=ready\">Sim</a> / <a href=\"quest9.php?act=noready\">Não</a>";
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if (($quest1['quest_status'] ?? null) == 3) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Missão</b></legend>\n";
	echo "<i>Você ainda não matou Zanoth. Deseja enfrentá-lo?</i><br/><br>\n";
	echo "<a href=\"quest9.php?act=ready\">Sim</a> / <a href=\"quest9.php?act=noready\">Não</a>";
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if (($quest1['quest_status'] ?? null) == 80) {
	$vesetemoeye = $db->execute("select * from `items` where `item_id`=160 and `player_id`=?", [$player->id]);
	if ($vesetemoeye->recordcount() == 0) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Sinto muito, mas não consigo encontrar os olhos de Zanoth no seu inventário.</i><br><br>\n";
		echo "<a href=\"home.php\">Voltar</a> / <a href=\"quest9.php?act=abort\">Abandonar missão</a>";
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	} else {

		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [90, $player->id, 18]);
		$noeyes = $db->execute("delete from `items` where `item_id`=160 and `player_id`=? limit 1", [$player->id]);

		$insert['player_id'] = $player->id;
		$insert['item_id'] = 161;
		$query = $db->autoexecute('items', $insert, 'INSERT');

		$insert['player_id'] = $player->id;
		$insert['item_id'] = 162;
		$query = $db->autoexecute('items', $insert, 'INSERT');

		$insert['player_id'] = $player->id;
		$insert['medalha'] = "Elite Imperial";
		$insert['motivo'] = "" . $player->username . " faz parte da elite imperial.";
		$query = $db->autoexecute('medalhas', $insert, 'INSERT');

		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>" . $player->username . ", você me provou ser um ótimo guerreiro, e como passou por meus testes com sucesso. Agora você faz parte da elite imperial.<br/>Membros da elite imperial devem usar ótimos itens, então tome esta <u>Holy Armor</u> e estas <u>Holy Legs</u>.</i><br><br>";
		echo "<a href=\"home.php\">Página Principal</a>.";
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}
}

if (($quest1['quest_status'] ?? null) == 90) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Missão</b></legend>\n";
	echo "<i>Você já terminou esta missão.</i><br><br>";
	echo "<a href=\"home.php\">Página Principal</a>.";
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

?>
