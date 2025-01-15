<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Missões");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkhp.php");
include(__DIR__ . "/checkwork.php");

//QUEST jeweled ring

if ($player->promoted == 'f') {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
	echo "<i>Você precisa ter uma vocação superior para fazer esta missão!</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo '</fieldset>';
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if ($player->level < 100) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
	echo "<i>Seu nível é muito baixo!</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

switch ($_GET['act'] ?? null) {
	case "warrior":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Além da força, iteligência e coragem, um grande guerreiro precisa de ótimos itens. Vejo que você tem ótimos itens, mas está faltando uma coisa.</i><br>\n";
		echo "<a href=\"quest1.php?act=what\">Oquê?</a> | <a href=\"home.php\">Voltar</a>.";
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		break;

	case "what":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Você já ouviu falar no jeweled ring? Ele é capas de aumentar seu ataque, sua defesa e sua resistência.</i><br>\n";
		echo "<i>Eu posso te ajudar a conseguir este precioso anel, irei te dizer tudo que é necessário se você me pagar uma pequena quantia de <b>120000 moedas de ouro</b>.</i><br>\n";
		echo '<a href="quest1.php?act=pay">Eu pago!</a> | <a href="home.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		break;

	case "pay":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Você aceita pagar <b>120000 moedas de ouro</b> para saber tudo que precisa?</i><br>\n";
		echo '<a href="quest1.php?act=confirmpay">Sim</a> | <a href="home.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		break;

	case "raderon":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Você tem certeza disso? Raderon é muito forte!</i><br>\n";
		echo "<a href=\"raderon.php?act=attack\">Sim</a> | <a href=\"quest1.php\">Não</a>.";
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		break;

	case "who":
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Minha história é muito longa, eu já fui um grande guerreiro e agora ajudo as pessoas que querem seguir meu caminho.</i><br>\n";
		echo '<a href="quest1.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		break;

	case "confirmpay":
		$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 2]);
		if ($verificacao->recordcount() == 0) {
			if ($player->gold - 120000 < 0) {
				include(__DIR__ . "/templates/private_header.php");
				echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
				echo "<i>Você não possui esta quantia de ouro!</i><br/><br/>\n";
				echo '<a href="home.php">Voltar</a>.';
				echo "</fieldset>";
				include(__DIR__ . "/templates/private_footer.php");
				exit;
			}

			$query = $db->execute("update `players` set `gold`=? where `id`=?", [$player->gold - 120000, $player->id]);
			$insert['player_id'] = $player->id;
			$insert['quest_id'] = 2;
			$insert['quest_status'] = 1;
			$query = $db->autoexecute('quests', $insert, 'INSERT');
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
			echo "<i>Pronto, agora podemos continuar com as missões.</i><br>\n";
			echo '<a href="quest1.php">Continuar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "Você já me pagou esta taixa!</i><br/><br/>\n";
		echo '<a href="home.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;

	case "continue1":
		$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 2]);
		$statux = $verificacao->fetchrow();

		if ($verificacao->recordcount() == 0) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
			echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
			echo '<a href="home.php">Voltar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		if (($statux['quest_status'] ?? null) != 1) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
			echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
			echo '<a href="home.php">Voltar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}


		$selectfirstitem = $db->execute("select * from `items` where `player_id`=? and `item_id`=?", [$player->id, 112]);
		if ($selectfirstitem->recordcount() == 0) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
			echo "<i>Você não possui um Jeweled Crystal.</i><br/>\n";
			echo '<a href="home.php">Voltar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [2, $player->id, 2]);
		$query = $db->execute("delete from `items` where `item_id`=? and `player_id`=? limit ?", [112, $player->id, 1]);
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Obrigado, agora podemos passar para a próxima missão.</i><br>\n";
		echo '<a href="quest1.php">Continuar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;

		break;

	case "continue2":
		$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 2]);
		$statux = $verificacao->fetchrow();

		if ($verificacao->recordcount() == 0) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
			echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
			echo '<a href="home.php">Voltar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		if (($statux['quest_status'] ?? null) != 2) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
			echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
			echo '<a href="home.php">Voltar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}


		$selectfirstitem = $db->execute("select * from `items` where `player_id`=? and `item_id`=?", [$player->id, 112]);
		if ($selectfirstitem->recordcount() == 0) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
			echo "<i>Você não possui um Jeweled Crystal.</i><br/>\n";
			echo '<a href="home.php">Voltar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [3, $player->id, 2]);
		$query = $db->execute("delete from `items` where `item_id`=? and `player_id`=? limit ?", [112, $player->id, 1]);
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Obrigado, agora podemos passar para a próxima missão.</i><br>\n";
		echo '<a href="quest1.php">Continuar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		break;

	case "continue3":
		$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 2]);
		$statux = $verificacao->fetchrow();

		if ($verificacao->recordcount() == 0) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
			echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
			echo '<a href="home.php">Voltar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		if (($statux['quest_status'] ?? null) != 3) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
			echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
			echo '<a href="home.php">Voltar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}


		$selectfirstitem = $db->execute("select * from `items` where `player_id`=? and `item_id`=?", [$player->id, 112]);
		if ($selectfirstitem->recordcount() == 0) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
			echo "<i>Você não possui um Jeweled Crystal.</i><br/>\n";
			echo '<a href="home.php">Voltar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [4, $player->id, 2]);
		$query = $db->execute("delete from `items` where `item_id`=? and `player_id`=? limit ?", [112, $player->id, 1]);
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Obrigado, agora podemos passar para a próxima missão.</i><br>\n";
		echo '<a href="quest1.php">Continuar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		break;


	case "titanium":
		$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 2]);
		$statux = $verificacao->fetchrow();

		if ($verificacao->recordcount() == 0) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
			echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
			echo '<a href="home.php">Voltar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		if (($statux['quest_status'] ?? null) != 5) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
			echo "<i>Um erro desconhecido ocorreu! Contate o administrador.</i><br/><br/>\n";
			echo '<a href="home.php">Voltar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}


		$selectfirstitem = $db->execute("select * from `items` where `player_id`=? and `item_id`=?", [$player->id, 111]);
		if ($selectfirstitem->recordcount() == 0) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
			echo "<i>Você não possui uma Titanium Wheel.</i><br/>\n";
			echo '<a href="home.php">Voltar</a>.';
			echo "</fieldset>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		$query = $db->execute("update `quests` set `quest_status`=? where `player_id`=? and `quest_id`=?", [90, $player->id, 2]);
		$query = $db->execute("delete from `items` where `item_id`=? and `player_id`=? limit ?", [111, $player->id, 1]);
		$insert['player_id'] = $player->id;
		$insert['item_id'] = 163;
		$db->autoexecute('items', $insert, 'INSERT');
		$ringid = $db->Insert_ID();
		$db->execute("update `items` set `for`=`for`+?, `vit`=`vit`+?, `agi`=`agi`+?, `res`=`res`+? where `id`=?", [20, 20, 20, 20, $ringid]);
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
		echo "<i>Pronto, ai está seu Jeweled Ring.</i><br>\n";
		echo "(Acesse seu inventário para equipá-lo.)<br>\n";
		echo '<a href="home.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
		break;
}
?>
<?php
$verificacao = $db->execute("select * from `quests` where `player_id`=? and `quest_id`=?", [$player->id, 2]);
$quest = $verificacao->fetchrow();

if ($verificacao->recordcount() == 0) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
	echo "<i>A muito tempo ninguém procura por mim. Oquê lhe traz aqui?</i><br/>\n";
	echo "<a href=\"quest1.php?act=who\">Quem é você?</a> | <a href=\"quest1.php?act=warrior\">Quero me tornar um grande guerreiro</a> | <a href=\"home.php\">Voltar</a>.";
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if (($quest['quest_status'] ?? null) == 1) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
	echo "<i>Para criar o anel, são necessários três <b>Jeweled Crystals</b>. Você pode obtê-los matando Dragões de Pedra ou comprando no mercado.</i><br/>\n";
	echo "<i>Quando conseguir o primeiro jeweled crystal volte aqui.</i><br/>\n";
	echo "<a href=\"quest1.php?act=continue1\">Já possuo o jeweled crystal</a> | <a href=\"home.php\">Voltar</a>.";
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if (($quest['quest_status'] ?? null) == 2) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
	echo "<i>Você já me entregou um <b>jeweled crystal</b>, preciso de mais dois. Você pode obtê-los matando Dragões de Pedra ou comprando no mercado.</i><br/>\n";
	echo "<i>Quando conseguir o segundo jeweled crystal volte aqui.</i><br/>\n";
	echo "<a href=\"quest1.php?act=continue2\">Já possuo o jeweled crystal</a> | <a href=\"home.php\">Voltar</a>.";
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if (($quest['quest_status'] ?? null) == 3) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
	echo "<i>Você já me entregou dois <b>jeweled crystals</b>, preciso de mais um. Você pode obtelo matando Dragões de Pedra ou comprando no mercado.</i><br/>\n";
	echo "<i>Quando conseguir o terceiro jeweled crystal volte aqui.</i><br/>\n";
	echo "<a href=\"quest1.php?act=continue3\">Já possuo o jeweled crystal</a> | <a href=\"home.php\">Voltar</a>.";
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if (($quest['quest_status'] ?? null) == 4) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
	echo "<i>Agora que possuo todos os cristais necessários só preciso de uma peça para montar o anel, uma titanium wheel. A única maneira de obtê-la é matando Raderon, um poderoso guerreiro.</i><br/><br/>\n";
	echo '<a href="quest1.php?act=raderon">Quero lutar contra Raderon</a> | <a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if (($quest['quest_status'] ?? null) == 5) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
	echo "<i>Nossa! Você conseguiu mesmo vencer raderon?!</i><br/>\n";
	echo "<i>Vamos acabar logo com isso, me entregue a titanium wheel e eu criarei o anel.</i><br/>\n";
	echo '<a href="quest1.php?act=titanium">Entregar a titanium wheel</a> | <a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

if (($quest['quest_status'] ?? null) == 90) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Thoy Magor</b></legend>\n";
	echo "<i>Você já fez esta missão!</i><br/>\n";
	echo '<a href="home.php">Voltar</a>.';
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

?>
