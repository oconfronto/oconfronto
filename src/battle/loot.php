<?php
if ($enemy->loot > 1)
	{
	$chanceloot = rand(1, $enemy->loot);
		if ($chanceloot == $enemy->loot) {
			$veositemz = $db->execute("select `item_id`, `item_prepo`, `item_name` from `loot` where `monster_id`=?", array($enemy->id));
				if ($veositemz->recordcount() == 0)
				{
				$mensagem = "Contate ao administrador que o monstro " . $enemy->username . " está com erros.";
				}else{
				$loot_item = $veositemz->fetchrow();
				$mensagem = "<u><b>Você encontrou " . $loot_item['item_prepo'] . " " . $loot_item['item_name'] . " com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
				$lootstatus = 5;
				$loot_id = $loot_item['item_id'];
				$lootbonus1 = 0;
				$lootbonus2 = 0;
				$lootbonus3 = 0;
				$lootbonus4 = 0;
				}
			}else{
			$lootstatus = 2;
		}
}elseif ($enemy->loot == 1){
	$sorteioitem = rand(1, 50);
	if ($sorteioitem == 38){
		$sorteiaitem = $db->execute("select `id`, `name` from `blueprint_items` where `type`!=? and `type`!=? and `type`!=? and `type`!=? and `type`!=? and `price`>? and `price`<? order by rand() limit 1", array(addon, quest, stone, potion, ring, $expdomonstro * 2.5, $expdomonstro * 3.5));
		if ($sorteiaitem->recordcount() == 0){
		$mensagem = "Contate ao administrador que o monstro " . $enemy->username . " está com erros.";
		$lootstatus = 2;
		}else{
		$loot_item2 = $sorteiaitem->fetchrow();
			$sorteiabonus1 = rand(1, 5);
			if ($sorteiabonus1 == 2) {
			$lootbonus1 = rand(1, 4);
			$lootbonus1m = " +" . $lootbonus1 . "F";
			}else{
			$lootbonus1 = 0;
			$lootbonus1m = "";
			}

			$sorteiabonus2 = rand(1, 5);
			if ($sorteiabonus2 == 2) {
			$lootbonus2 = rand(1, 4);
			$lootbonus2m = " +" . $lootbonus2 . "V";
			}else{
			$lootbonus2 = 0;
			$lootbonus2m = "";
			}

			$sorteiabonus3 = rand(1, 5);
			if ($sorteiabonus3 == 5) {
			$lootbonus3 = rand(1, 4);
			$lootbonus3m = " +" . $lootbonus3 . "A";
			}else{
			$lootbonus3 = 0;
			$lootbonus3m = "";
			}

			$sorteiabonus4 = rand(1, 5);
			if ($sorteiabonus4 == 5) {
			$lootbonus4 = rand(1, 4);
			$lootbonus4m = " +" . $lootbonus4 . "R";
			}else{
			$lootbonus4 = 0;
			$lootbonus4m = "";
			}

		$mensagem = "<u><b>Você encontrou um/uma " . $loot_item2['name'] . "" . $lootbonus1m . "" . $lootbonus2m . "" . $lootbonus3m . "" . $lootbonus4m . " com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
		$lootstatus = 5;
		$loot_id = $loot_item2['id'];
		}
	}else{
	$lootstatus = 2;
	}
}

		if ($lootstatus == 2){
		$sorteioitem2 = rand(1, 55);
		if ($sorteioitem2 == 43){
			if ($player->level < 50){
			$sorteiapotion = rand(1, 3);
			if ($sorteiapotion == 3){
			$mensagem = "<u><b>Você encontrou uma Mana Potion com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
			$lootstatus = 5;
			$loot_id = 150;
			$lootbonus1 = 0;
			$lootbonus2 = 0;
			$lootbonus3 = 0;
			$lootbonus4 = 0;
			}else{
			$mensagem = "<u><b>Você encontrou uma Health Potion com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
			$lootstatus = 5;
			$loot_id = 136;
			$lootbonus1 = 0;
			$lootbonus2 = 0;
			$lootbonus3 = 0;
			$lootbonus4 = 0;
			}
			}
			else if (($player->level > 49) and ($player->level < 100)){
			$sorteiapotion = rand(1, 4);
			if (($sorteiapotion == 1) or ($sorteiapotion == 2)){
			$mensagem = "<u><b>Você encontrou uma Health Potion com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
			$lootstatus = 5;
			$loot_id = 136;
			$lootbonus1 = 0;
			$lootbonus2 = 0;
			$lootbonus3 = 0;
			$lootbonus4 = 0;
			}
			elseif ($sorteiapotion == 3){
			$mensagem = "<u><b>Você encontrou uma Mana Potion com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
			$lootstatus = 5;
			$loot_id = 150;
			$lootbonus1 = 0;
			$lootbonus2 = 0;
			$lootbonus3 = 0;
			$lootbonus4 = 0;
			}else{
			$mensagem = "<u><b>Você encontrou uma Energy Potion com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
			$lootstatus = 5;
			$loot_id = 137;
			$lootbonus1 = 0;
			$lootbonus2 = 0;
			$lootbonus3 = 0;
			$lootbonus4 = 0;

			}
			}else{
			$sorteiapotion = rand(1, 4);
			if (($sorteiapotion == 1) or ($sorteiapotion == 2)){
			$mensagem = "<u><b>Você encontrou uma Big Health Potion com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
			$lootstatus = 5;
			$loot_id = 148;
			$lootbonus1 = 0;
			$lootbonus2 = 0;
			$lootbonus3 = 0;
			$lootbonus4 = 0;
			}
			elseif ($sorteiapotion == 3){
			$mensagem = "<u><b>Você encontrou uma Mana Potion com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
			$lootstatus = 5;
			$loot_id = 150;
			$lootbonus1 = 0;
			$lootbonus2 = 0;
			$lootbonus3 = 0;
			$lootbonus4 = 0;
			}else{
			$mensagem = "<u><b>Você encontrou uma Energy Potion com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
			$lootstatus = 5;
			$loot_id = 137;
			$lootbonus1 = 0;
			$lootbonus2 = 0;
			$lootbonus3 = 0;
			$lootbonus4 = 0;
			}
			}
		}
		}

	if (($lootstatus == 2) and ($player->level > 10) and ($player->level < 90)){
		$sorteiarings = rand(1, 600);
			if ($sorteiarings == 278){
			$choosering = rand(1, 4);
				if ($choosering == 1){
					$mensagem = "<u><b>Você encontrou um Strength Ring com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
					$lootstatus = 5;
					$loot_id = 164;
					$lootbonus1 = 10;
					$lootbonus2 = 0;
					$lootbonus3 = 0;
					$lootbonus4 = 0;
				}elseif ($choosering == 2){
					$mensagem = "<u><b>Você encontrou um Vitality Ring com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
					$lootstatus = 5;
					$loot_id = 165;
					$lootbonus1 = 0;
					$lootbonus2 = 10;
					$lootbonus3 = 0;
					$lootbonus4 = 0;
				}elseif ($choosering == 3){
					$mensagem = "<u><b>Você encontrou um Agility Ring com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
					$lootstatus = 5;
					$loot_id = 166;
					$lootbonus1 = 0;
					$lootbonus2 = 0;
					$lootbonus3 = 10;
					$lootbonus4 = 0;
				}elseif ($choosering == 4){
					$mensagem = "<u><b>Você encontrou um Resistance Ring com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
					$lootstatus = 5;
					$loot_id = 167;
					$lootbonus1 = 0;
					$lootbonus2 = 0;
					$lootbonus3 = 0;
					$lootbonus4 = 10;
				}

			}else{
			$lootstatus = 2;
			}
	}

	if (($lootstatus == 2) and ($player->level < 90)){
		$sorteiarings = rand(1, 1200);
			if ($sorteiarings == 567){
			$choosering = rand(1, 2);
				if ($choosering == 1){
					$mensagem = "<u><b>Você encontrou um Dark Ring com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
					$lootstatus = 5;
					$loot_id = 169;
					$lootbonus1 = 10;
					$lootbonus2 = 0;
					$lootbonus3 = 0;
					$lootbonus4 = 15;
				}elseif ($choosering == 2){
					$mensagem = "<u><b>Você encontrou um Energy Ring com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
					$lootstatus = 5;
					$loot_id = 170;
					$lootbonus1 = 0;
					$lootbonus2 = 15;
					$lootbonus3 = 15;
					$lootbonus4 = 5;
				}
			}else{
			$lootstatus = 2;
			}
	}

	if (($lootstatus == 2) and ($player->level > 75)){
		$sorteiaorbsinho = rand(1, 9500);
			if ($sorteiaorbsinho == 2523){
			$mensagem = "<u><b>Você encontrou um Oddin Orb com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
			$lootstatus = 5;
			$loot_id = 156;
			$lootbonus1 = 0;
			$lootbonus2 = 0;
			$lootbonus3 = 0;
			$lootbonus4 = 0;
			}else{
			$lootstatus = 2;
			}
	}

	if (($lootstatus == 2) and ($player->level > 90)){
		$sorteiaorbsinho = rand(1, 13650);
			if ($sorteiaorbsinho == 3599){
			$mensagem = "<u><b>Você encontrou uma Magic Golden Bar com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
			$lootstatus = 5;
			$loot_id = 157;
			$lootbonus1 = 0;
			$lootbonus2 = 0;
			$lootbonus3 = 0;
			$lootbonus4 = 0;
			}else{
			$lootstatus = 2;
			}
	}

	if (($lootstatus == 2) and ($player->level > 120)){
		$sorteiaorbsinho = rand(1, 11600);
			if ($sorteiaorbsinho == 4853){
			$mensagem = "<u><b>Você encontrou um Magic Crystal com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
			$lootstatus = 5;
			$loot_id = 177;
			$lootbonus1 = 0;
			$lootbonus2 = 0;
			$lootbonus3 = 0;
			$lootbonus4 = 0;
			}else{
			$lootstatus = 2;
			}
	}

	if ($lootstatus == 999999){
		$sorteiapresentinho = rand(1, 1500);
			if ($sorteiapresentinho == 165){
			$mensagem = "<u><b>Você encontrou um Presente com " . $enemy->prepo . " " . $enemy->username . "</b></u>";
			$lootstatus = 5;
			$loot_id = 155;
			$lootbonus1 = 0;
			$lootbonus2 = 0;
			$lootbonus3 = 0;
			$lootbonus4 = 0;
			}else{
			$lootstatus = 2;
			}
	}
?>