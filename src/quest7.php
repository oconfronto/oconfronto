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

$verificacao1 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 15]);
$verificacao2 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 16]);

if ($verificacao1->recordcount() > 0) {
	$quest1 = $verificacao1->fetchrow();
}

if ($verificacao2->recordcount() > 0) {
	$quest2 = $verificacao2->fetchrow();
}


$verificacao3 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=? and `quest_status`=?", [$player->id, 14, 90]);
if ($verificacao3->recordcount() < 1) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Missão</b></legend>\n";
	echo "<i>Você precisa completar a missão do pacote imperial primeiro.</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if ($player->level < 400) {
	$needlvl = $player->level + 4;
} elseif ($player->level < 500) {
	$needlvl = $player->level + 3;
} elseif ($player->level < 600) {
	$needlvl = $player->level + 2;
} else {
	$needlvl = $player->level + 1;
}


switch ($_GET['act']) {

	case "question":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Você terá 24 horas para atingir o nível " . $needlvl . ". Aceite meu desafio e se tiver sucesso, passará para a próxima etapa do treinamento.</i><br><br>\n";
		echo '<a href="quest7.php?act=acept">Aceitar</a> / <a href="quest7.php?act=decline">Recusar</a>';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		break;


	case "decline":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Precisa se preparar mais? Ok, volte quando estiver pronto.</i><br><br>\n";
		echo "<a href=\"home.php\">Página Principal</a>.";
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		break;

	case "acept":
		if ($verificacao1->recordcount() > 0) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Aviso</b></legend>\n";
			echo "<i>Você já aceitou o desafio.</i><br><br>\n";
			echo "<a href=\"home.php\">Página Principal</a>.";
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		include(__DIR__ . "/templates/private_header.php");
		$insert['player_id'] = $player->id;
		$insert['quest_id'] = 15;
		$insert['quest_status'] = time() + 86400;
		$query = $db->autoexecute('quests', $insert, 'INSERT');
		$insert['player_id'] = $player->id;
		$insert['quest_id'] = 16;
		$insert['quest_status'] = $needlvl;
		$query = $db->autoexecute('quests', $insert, 'INSERT');
		echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
		echo "<i>Ótimo, agora seja rápido e atinja o nível " . $needlvl . " o mais rápido possivel. Volte aqui depois de alcançar este nível.</i><br /><br />";
		echo '<a href="home.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");

		break;

	case "retry":
		$delety1 = $db->execute("delete from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 15]);
		$delety2 = $db->execute("delete from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 16]);

		header("Location: quest7.php?act=question");

		break;
}
?>
<?php
if ($verificacao1->recordcount() == 0) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
	echo "<i>" . $player->username . ", você precisa me provar que é um guerreiro dedicado, e terá de realizar um desafio.</i><br/><br>\n";
	echo "<a href=\"quest7.php?act=question\">Qual é o Desafio?</a> / <a href=\"home.php\">Voltar</a>.";
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if ($quest1['quest_status'] > 100) {
	if ($quest1['quest_status'] < time()) {
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [2, $player->id, 15]);
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Missão</b></legend>\n";
		echo "<i>Você demorou demais para atingir o nível necessário. Você falhou no desafio.</i><br><br>";
		echo '<a href="quest7.php">Continuar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	if ($quest1['quest_status'] > time() && $player->level >= $quest2['quest_status']) {
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [80, $player->id, 15]);
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Missão</b></legend>\n";
		echo "<i>Parabéns, você atingiu o nível necessário a tempo.</i><br><br>";
		echo '<a href="quest7.php">Falar com Alexander</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	include(__DIR__ . "/templates/private_header.php");
	$time = ($quest1['quest_status'] - time());
	$time_remaining = ceil($time / 60);
	echo "<fieldset><legend><b>Missão</b></legend>\n";
	echo "<i>Você ainda não atingiu o nível " . $quest2['quest_status'] . ".</i><br>";
	echo "<i>Você ainda tem {$time_remaining} minuto(s) para alcançar este nível.</i><br><br>\n";
	echo "<a href=\"home.php\">Página Principal</a>.";
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if ($quest1['quest_status'] == 2) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
	echo "<i>Parece que você falhou no seu desafio, mas estarei lhe dando outra chance. Deseja tentar novamente?</i><br><br>";
	echo '<a href="quest7.php?act=retry">Sim</a> / <a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if ($quest1['quest_status'] == 80) {
	$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [90, $player->id, 15]);
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Alexander, o Rei</b></legend>\n";
	echo "<i>Vejo que você é um guerreiro dedicado, e agora está mais mais próximo de fazer parte da elite imperial.</i><br><br>";
	echo '<a href="quest8.php">Continuar Treinamento</a> / <a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if ($quest1['quest_status'] == 90) {
	header("Location: quest8.php");
}

?>
