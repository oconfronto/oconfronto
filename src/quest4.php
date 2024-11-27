<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Missões");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");

//QUEST melhoria do jeweled ring

if ($player->level < 130) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Gadudj</b></legend>\n";
	echo "<i>Seu nível é muito baixo!</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

$verificaring = $db->execute("select * from `items` where `player_id`=? and `item_id`=163", [$player->id]);
if ($verificaring->recordcount() == 0) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Missão</b></legend>\n";
	echo "<i>Você não possui o jeweled ring necessário para fazer esta missão.</i>";
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}


switch ($_GET['act'] ?? null) {

	case "who":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Gadudj</b></legend>\n";
		echo "<i>Eu faço modificações nos itens das pessoas, deixando-os melhores.</i><br><br>\n";
		echo '<a href="quest4.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;

	case "help":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Gadudj</b></legend>\n";
		echo "<i>Bom, vejo que você está usando um jeweled ring. Ele é um anel muito poderoso mas eu posso deixá-lo ainda melhor.<br>Se você me pagar uma quantia de 250000, modificarei seu anel e ele poderá aumentar até 15% sua agilidade e força. O que acha?</i><br><br>\n";
		echo '<a href="quest4.php?act=acept">Aceito</a> | <a href="quest4.php?act=decline">Recuso</a> | <a href="home.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;

	case "decline":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Gadudj</b></legend>\n";
		echo "<i>Bom, a escolha é sua.</i><br><br>\n";
		echo '<a href="home.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;

	case "acept":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Gadudj</b></legend>\n";
		echo "<i>Tem certeza que deseja pagar 250000 pela modificação do seu jeweled ring?</i><br><br>\n";
		echo '<a href="quest4.php?act=confirmpay">Sim, tenho certeza</a> | <a href="home.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;

	case "confirmpay":
		$verificaring = $db->execute("select * from `items` where `player_id`=? and `item_id`=163", [$player->id]);
		if ($verificaring->recordcount() == 0) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Missão</b></legend>\n";
			echo "<i>Você não possui o jeweled ring necessário para fazer esta missão.</i>";
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		$verificacao = $db->execute("select `id` from `quests` where `player_id`=? and `quest_id`=7", [$player->id]);
		if ($verificacao->recordcount() == 0) {
			if ($player->gold - 250000 < 0) {
				include(__DIR__ . "/templates/private_header.php");
				echo "<fieldset><legend><b>Gadudj</b></legend>\n";
				echo "<i>Você não possui esta quantia de ouro!</i><br/><br/>\n";
				echo '<a href="home.php">Voltar</a>.';
				echo "</fieldset>";
				include(__DIR__ . "/templates/private_footer.php");
				exit;
			}

			$query = $db->execute("update `players` set `gold`=? where `id`=?", [$player->gold - 250000, $player->id]);
			$insert['player_id'] = $player->id;
			$insert['quest_id'] = 7;
			$insert['quest_status'] = time() + 36000;
			$query = $db->autoexecute('quests', $insert, 'INSERT');
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Gadudj</b></legend>\n";
			echo "<i>Obrigado. Irei optimizar seu anel.  Me encontre aqui em 10h.<br/>Lembre-se que você está sem o seu anel agora, então não tire os seus itens que precisam do jeweled ring.</i><br><br>\n";
			echo '<a href="home.php">Voltar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Gadudj</b></legend>\n";
		echo "Você já me pagou!</i><br/><br/>\n";
		echo '<a href="home.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;

	case "search":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Missão</b></legend>\n";
		echo "<i>Depois de algum tempo procurando você avistou Gadudj.<br/>Você tem certeza que deseja atacalo? ele possui nível 112!</i><br><br>\n";
		echo '<a href="gadudj.php">Atacar Gadudj</a> | <a href="quest4.php?act=assassin">Contratar assassino</a> | <a href="home.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;

	case "assassin":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Missão</b></legend>\n";
		echo "<i>Você encontrou um guerreiro capaz de matar Gadudj. Ele está cobrando 80000 pelo serviço. Você aceita a oferta?</i><br><br>\n";
		echo '<a href="quest4.php?act=buy">Aceito</a> | <a href="quest4.php?act=refuse">Recuso</a> | <a href="home.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;

	case "refuse":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Missão</b></legend>\n";
		echo "<i>Então você terá que enfrentar Gadudj.</i><br><br>\n";
		echo '<a href="gadudj.php">Atacar Gadudj</a> | <a href="home.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;

	case "buy":

		$vepaoiseee = $db->execute("select `id` from `quests` where `player_id`=? and `quest_id`=7 and `quest_status`=2", [$player->id]);
		if ($vepaoiseee->recordcount() == 0) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Aviso</b></legend>\n";
			echo "<i>Um erro desconhecido ocorreu. Contate o administrador.</i><br/>\n";
			echo '<a href="home.php">Voltar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		$vepaooosswwe = $db->execute("select `id` from `quests` where `player_id`=? and `quest_id`=7 and `quest_status`=3", [$player->id]);
		if ($vepaooosswwe->recordcount() == 0) {
			if ($player->gold - 80000 < 0) {
				include(__DIR__ . "/templates/private_header.php");
				echo "<fieldset><legend><b>Gadudj</b></legend>\n";
				echo "<i>Você não possui esta quantia de ouro!</i><br/><br/>\n";
				echo '<a href="home.php">Voltar</a>.';
				echo "</fieldset>";
				include(__DIR__ . "/templates/private_footer.php");
				exit;
			}

			$query = $db->execute("update `players` set `gold`=? where `id`=?", [$player->gold - 80000, $player->id]);
			$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [90, $player->id, 7]);
			$verificaringeq = $db->execute("select * from `items` where `player_id`=? and `item_id`=163 and `status`='equipped'", [$player->id]);
			if ($verificaringeq->recordcount() > 0) {
				$db->execute("update `players` set `hp`=`hp`-500, `maxhp`=`maxhp`-500 where `id`=?", [$player->id]);
			}

			$query = $db->execute("delete from `items` where `player_id`=? and `item_id`=?", [$player->id, 163]);
			$insert['player_id'] = $player->id;
			$insert['item_id'] = 168;
			$db->autoexecute('items', $insert, 'INSERT');
			$ringid = $db->Insert_ID();
			$db->execute("update `items` set `for`=`for`+?, `vit`=`vit`+?, `agi`=`agi`+?, `res`=`res`+? where `id`=?", [30, 30, 30, 30, $ringid]);
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Missão</b></legend>\n";
			echo "<i>O assassino que você contratou matou Gadudj e recuperou seu Jeweled Ring.<br>Sua missão acabou.</i><br><br>\n";
			echo '<a href="home.php">Voltar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Missão</b></legend>\n";
		echo "Você já pagou ao assassino!</i><br/><br/>\n";
		echo '<a href="home.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
}
?>
<?php
$verificacao1 = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 7]);
$quest1 = $verificacao1->fetchrow();

if ($verificacao1->recordcount() == 0) {
	$verificaring = $db->execute("select * from `items` where `player_id`=? and `item_id`=163", [$player->id]);
	if ($verificaring->recordcount() == 0) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Missão</b></legend>\n";
		echo "<i>Você não possui o jeweled ring necessário para fazer esta missão.</i>";
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Gadudj</b></legend>\n";
	echo "<i>Olá " . $player->username . ". Como posso te ajudar?</i><br/><br>\n";
	echo "<a href=\"quest4.php?act=who\">Quem é você?</a> | <a href=\"quest4.php?act=help\">Optimizar itens</a> | <a href=\"home.php\">Voltar</a>.";
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if (($quest1['quest_status'] ?? null) > 100) {
	if (($quest1['quest_status'] ?? null) < time()) {
		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [2, $player->id, 7]);
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Missão</b></legend>\n";
		echo "<i>Parece que Gadudj fugiu com seu jeweled ring! Você terá que procurar e matá-lo para recuperar seu anel.</i><br><br>";
		echo '<a href="quest4.php?act=search">Procurar por Gadudj</a> | <a href="home.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	include(__DIR__ . "/templates/private_header.php");
	$time = ($quest1['quest_status'] - time());
	$time_remaining = ceil($time / 60);
	echo "<fieldset><legend><b>Missão</b></legend>\n";
	echo "<i>Você tem que esperar por Gadudj.</i><br>";
	echo "<i>Faltam {$time_remaining} minuto(s) para ele chegar.</i><br><br>\n";
	echo "<a href=\"home.php\">Página Principal</a>.";
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if (($quest1['quest_status'] ?? null) == 2) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Missão</b></legend>\n";
	echo "<i>Parece que Gadudj fugiu com seu jeweled ring! Você terá que procurar e matá-lo para recuperar seu anel.</i><br><br>";
	echo '<a href="quest4.php?act=search">Procurar por Gadudj</a> | <a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if (($quest1['quest_status'] ?? null) == 90) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Missão</b></legend>\n";
	echo "<i>Você já terminou esta missão.</i>";
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}


?>
