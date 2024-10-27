<?php

declare(strict_types=1);

if (isset($_GET['gift'])) {
	$numgifts = $db->execute("select `id` from `items` where `player_id`=? and `id`=? and `item_id`=? and `mark`='f'", [$player->id, $_GET['gift'], 155]);
	if ($numgifts->recordcount() != 1) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Erro</b></legend>\n";
		echo "Item não encontrado.<br />";
		echo '<a href="inventory.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	if ($player->level < 50) {
		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Erro</b></legend>\n";
		echo "Você não possui nível suficiente para abrir o presente.<br />";
		echo '<a href="inventory.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	$gifte = $numgifts->fetchrow();
	$numgifts = $db->execute("delete from `items` where `id`=?", [$_GET['gift']]);
	$itemchance =  random_int(1, 30);
	if ($itemchance < 20) {
		$sotona =  random_int(1, 30);
		if ($sotona < 20) {
			$sorteiaitem = $db->execute("select `id`, `name` from `blueprint_items` where `type`!=? and `type`!=? and `type`!=? and `type`!=? and `canbuy`!=? order by rand() limit 1", ["addon", "quest", "stone", "potion", "f"]);
		} else {
			$sorteiaitem = $db->execute("select `id`, `name` from `blueprint_items` where `type`!=? and `type`!=? and `type`!=? and `type`!=? order by rand() limit 1", ["addon", "quest", "stone", "potion"]);
		}

		$giftitem = $sorteiaitem->fetchrow();

		$insert['player_id'] = $player->id;
		$insert['item_id'] = $giftitem['id'];
		$addlootitemwin = $db->autoexecute('items', $insert, 'INSERT');

		include(__DIR__ . "/templates/private_header.php");
		echo "<fieldset><legend><b>Presente</b></legend>\n";
		echo "Você abriu seu presente e encontrou um/uma " . $giftitem['name'] . ".<br />";
		echo '<a href="inventory.php">Voltar</a>.';
		echo "</fieldset>";
		include(__DIR__ . "/templates/private_footer.php");
		exit;
	}

	$goldchance =  random_int(1, 30);
	if ($goldchance < 5) {
		$ganhagold = random_int(1, 3000);
	} elseif ($goldchance < 10) {
		$ganhagold = random_int(1, 30000);
	} elseif ($goldchance < 15) {
		$ganhagold = random_int(1, 90000);
	} elseif ($goldchance < 25) {
		$ganhagold = random_int(1, 140000);
	} elseif ($goldchance < 31) {
		$ganhagold = random_int(1, 200000);
	}

	$ganhagold = ceil($itemchance * $ganhagold);
	$query = $db->execute("update `players` set `gold`=`gold`+? where `id`=?", [$ganhagold, $player->id]);
	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Presente</b></legend>\n";
	echo "Você abriu seu presente e encontrou " . $ganhagold . " de ouro.<br />";
	echo '<a href="inventory.php">Voltar</a>.';
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}
