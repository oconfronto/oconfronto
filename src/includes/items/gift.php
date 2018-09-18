<?php
if ($_GET['gift'])
{
$numgifts = $db->execute("select `id` from `items` where `player_id`=? and `id`=? and `item_id`=? and `mark`='f'", array($player->id, $_GET['gift'], 155));
if ($numgifts->recordcount() != 1){
	include("templates/private_header.php");
	echo "<fieldset><legend><b>Erro</b></legend>\n";
        echo "Item não encontrado.<br />";
        echo "<a href=\"inventory.php\">Voltar</a>.";
	echo "</fieldset>";
        include("templates/private_footer.php");
        exit;
}elseif ($player->level < 50){
	include("templates/private_header.php");
	echo "<fieldset><legend><b>Erro</b></legend>\n";
        echo "Você não possui nível suficiente para abrir o presente.<br />";
        echo "<a href=\"inventory.php\">Voltar</a>.";
	echo "</fieldset>";
        include("templates/private_footer.php");
        exit;
}else{
	$gifte = $numgifts->fetchrow();
	$numgifts = $db->execute("delete from `items` where `id`=?", array($_GET['gift']));

	$itemchance =  rand(1, 30);
		if ($itemchance < 20){
			$sotona =  rand(1, 30);
				if ($sotona < 20){
				$sorteiaitem = $db->execute("select `id`, `name` from `blueprint_items` where `type`!=? and `type`!=? and `type`!=? and `type`!=? and `canbuy`!=? order by rand() limit 1", array(addon, quest, stone, potion, f));
				}else{
				$sorteiaitem = $db->execute("select `id`, `name` from `blueprint_items` where `type`!=? and `type`!=? and `type`!=? and `type`!=? order by rand() limit 1", array(addon, quest, stone, potion));
				}
		$giftitem = $sorteiaitem->fetchrow();

			$insert['player_id'] = $player->id;
			$insert['item_id'] = $giftitem['id'];
			$addlootitemwin = $db->autoexecute('items', $insert, 'INSERT');

		include("templates/private_header.php");
		echo "<fieldset><legend><b>Presente</b></legend>\n";
       		echo "Você abriu seu presente e encontrou um/uma " . $giftitem['name'] . ".<br />";
        	echo "<a href=\"inventory.php\">Voltar</a>.";
		echo "</fieldset>";
        	include("templates/private_footer.php");
        	exit;

		}else{
		$goldchance =  rand(1, 30);
			if ($goldchance < 5){
			$ganhagold = rand(1, 3000);
			}else if ($goldchance < 10){
			$ganhagold = rand(1, 30000);
			}else if ($goldchance < 15){
			$ganhagold = rand(1, 90000);
			}else if ($goldchance < 25){
			$ganhagold = rand(1, 140000);
			}else if ($goldchance < 31){
			$ganhagold = rand(1, 200000);
			}

			$ganhagold = ceil($itemchance * $ganhagold);

			$query = $db->execute("update `players` set `gold`=`gold`+? where `id`=?", array($ganhagold, $player->id));

		include("templates/private_header.php");
		echo "<fieldset><legend><b>Presente</b></legend>\n";
       		echo "Você abriu seu presente e encontrou " . $ganhagold . " de ouro.<br />";
        	echo "<a href=\"inventory.php\">Voltar</a>.";
		echo "</fieldset>";
        	include("templates/private_footer.php");
        	exit;

		}
}
}
?>