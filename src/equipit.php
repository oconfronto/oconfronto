<?php
	include("lib.php");
	$player = check_user($secret_key, $db);

if ($_GET['itid'])
{
	$query = $db->execute("select `id`, `status`, `item_id` from `items` where `id`=? and `player_id`=?", array($_GET['itid'], $player->id));
	if ($query->recordcount() == 1)
	{
		$item = $query->fetchrow();
        
        //arruma anŽis sem atributos
        $update = 0;
        if ($item['item_id'] == 163)
        {
            $for = 10;
            $vit = 10;
            $agi = 10;
            $res = 10;
            $update = 5;
        }
        elseif ($item['item_id'] == 164)
        {
            $for = 10;
            $vit = 0;
            $agi = 0;
            $res = 0;
            $update = 5;
        }
        elseif ($item['item_id'] == 165)
        {
            $for = 0;
            $vit = 10;
            $agi = 0;
            $res = 0;
            $update = 5;
        }
        elseif ($item['item_id'] == 166)
        {
            $for = 0;
            $vit = 0;
            $agi = 10;
            $res = 0;
            $update = 5;
        }
        elseif ($item['item_id'] == 167)
        {
            $for = 0;
            $vit = 0;
            $agi = 0;
            $res = 10;
            $update = 5;
        }
        elseif ($item['item_id'] == 168)
        {
            $for = 20;
            $vit = 20;
            $agi = 20;
            $res = 20;
            $update = 5;
        }
        elseif ($item['item_id'] == 169)
        {
            $for = 10;
            $vit = 0;
            $agi = 0;
            $res = 15;
            $update = 5;
        }
        elseif ($item['item_id'] == 170)
        {
            $for = 0;
            $vit = 15;
            $agi = 15;
            $res = 5;
            $update = 5;
        }
        elseif ($item['item_id'] == 172)
        {
            $for = 40;
            $vit = 30;
            $agi = 40;
            $res = 30;
            $update = 5;
        }
        elseif ($item['item_id'] == 176)
        {
            $for = 30;
            $vit = 40;
            $agi = 30;
            $res = 40;
            $update = 5;
        }
        elseif ($item['item_id'] == 178)
        {
            $for = 40;
            $vit = 40;
            $agi = 40;
            $res = 40;
            $update = 5;
        }
        
        if ($update == 5) {
            $db->execute("update `items` set `for`=?, `vit`=?, `agi`=?, `res`=? where `id`=?", array($for, $vit, $agi, $res, $item['id']));
            $query = $db->execute("select `id`, `status`, `item_id` from `items` where `id`=? and `player_id`=?", array($item['id'], $player->id));
            $item = $query->fetchrow();
        }
        // fim arruma‹o anŽis   
        
		switch($item['status'])
		{
			case "unequipped": //User wants to equip item
				//$itemtype = $db->getone("select `type` from `blueprint_items` where `id`=?", array($item['item_id']));
				
				//Equip the selected item
				$ckitexs = $db->execute("select items.id, items.item_id, items.mark, items.player_id, blueprint_items.voc, blueprint_items.needlvl, blueprint_items.needpromo, blueprint_items.type from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and items.id=?", array($player->id, $_GET['itid']));
				$ddckitexs = $ckitexs->fetchrow();
				if ($ckitexs->recordcount() == 0)
				{
				include("templates/private_header.php");
				echo "Um erro desconhecido ocorreu. <a href=\"inventory.php\">Voltar</a>.";
				include("templates/private_footer.php");
				exit;
				}
				if (($ddckitexs['voc'] == '1') and ($player->voc != 'archer'))
				{
				include("templates/private_header.php");
				echo "Você não pode usar este item. <a href=\"inventory.php\">Voltar</a>.";
				include("templates/private_footer.php");
				exit;
				}
				if (($ddckitexs['voc'] == '2') and ($player->voc != 'knight'))
				{
				include("templates/private_header.php");
				echo "Você não pode usar este item. <a href=\"inventory.php\">Voltar</a>.";
				include("templates/private_footer.php");
				exit;
				}
				if (($ddckitexs['voc'] == '3') and ($player->voc != 'mage'))
				{
				include("templates/private_header.php");
				echo "Você não pode usar este item. <a href=\"inventory.php\">Voltar</a>.";
				include("templates/private_footer.php");
				exit;
				}
				if (($ddckitexs['type'] == 'shield') and ($player->voc == 'archer'))
				{
				include("templates/private_header.php");
				echo "Arqueiros não podem usar escudos. <a href=\"inventory.php\">Voltar</a>.";
				include("templates/private_footer.php");
				exit;
				}

                if ($player->vip > time()) {
                    $lvlbonus = 10;
                } else {
                    $lvlbonus = 0;
                }
				if ($ddckitexs['needlvl'] > ($player->level + $lvlbonus))
				{
				include("templates/private_header.php");
				echo "Você não tem nível suficiente para usar este item. <a href=\"inventory.php\">Voltar</a>.";
				include("templates/private_footer.php");
				exit;
				}

				if ($ddckitexs['type'] == 'addon')
				{
				include("templates/private_header.php");
				echo "Você não pode usar este item. <a href=\"inventory.php\">Voltar</a>.";
				include("templates/private_footer.php");
				exit;
				}

				if ($ddckitexs['mark'] == t)
				{
				include("templates/private_header.php");
				echo "Você não pode usar um item que está à venda no mercado. <a href=\"inventory.php\">Voltar</a>.";
				include("templates/private_footer.php");
				exit;
				}

				if (($ddckitexs['needpromo'] == 't') and ($player->promoted == 'f'))
				{
				include("templates/private_header.php");
				echo "Apenas usuários de vocação superior podem usar este item. <a href=\"inventory.php\">Voltar</a>.";
				include("templates/private_footer.php");
				exit;
				}

				if (($ddckitexs['needpromo'] == 'p') and ($player->promoted != 'p'))
				{
				include("templates/private_header.php");
				echo "Apenas usuários de vocação suprema podem usar este item. <a href=\"inventory.php\">Voltar</a>.";
				include("templates/private_footer.php");
				exit;
				}

				//Check if another item is already equipped
				$unequip = $db->getone("select items.id from `items`, `blueprint_items` where items.item_id = blueprint_items.id and blueprint_items.type=(select `type` from `blueprint_items` where `id`=?) and items.player_id=? and `status`='equipped'", array($item['item_id'], $player->id));
				if ($unequip) //If so, then unequip it (only one item may be equipped at any one time)
				{
					$player = check_user($secret_key, $db); //Get new stats
					$query = $db->execute("select items.item_id, items.item_bonus, items.vit, blueprint_items.type, blueprint_items.effectiveness from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.id=?", array($unequip));
					$item = $query->fetchrow();

                    //pega valor dos adicionais
					if ($item['type'] == amulet){
                        $extrahp = (($item['effectiveness'] + ($item['item_bonus'] * 2) + $item['vit']) * 20);
                        $extramana = (($item['effectiveness'] + ($item['item_bonus'] * 2) + $item['vit']) * 5);
                    } else {
                        $extrahp = ($item['vit'] * 20);
						$extramana = ($item['vit'] * 5);
                    }
                    
                        if ($player->hp > $extrahp)
                        {
                            $playerhp = $player->hp - $extrahp;
                        } else {
                            $playerhp = 1;
                        }
                        
                        $playermana = $player->mana - $extramana;
                        if ($playermana < 0) {
                            $playermana = 0;
                        }
                        
						$db->execute("update `players` set `hp`=?, `maxhp`=`maxhp`-?, `mana`=?, `maxmana`=`maxmana`-?, `extramana`=`extramana`-? where `id`=?", array($playerhp, $extrahp, $playermana, $extramana, $extramana, $player->id));

					$db->execute("update `items` set `status`='unequipped' where `id`=?", array($unequip));
				}

				$player = check_user($secret_key, $db); //Get new stats
				$query = $db->execute("select items.item_id, items.item_bonus, items.vit, blueprint_items.type, blueprint_items.effectiveness from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.id=?", array($_GET['itid']));
				$item = $query->fetchrow();
                
                    //pega valor dos adicionais
                    if ($item['type'] == amulet){
                        $extrahp = (($item['effectiveness'] + ($item['item_bonus'] * 2) + $item['vit']) * 20);
                        $extramana = (($item['effectiveness'] + ($item['item_bonus'] * 2) + $item['vit']) * 5);
                    } else {
                        $extrahp = ($item['vit'] * 20);
                        $extramana = ($item['vit'] * 5);
                    }
                    
                    $db->execute("update `players` set `hp`=`hp`+?, `maxhp`=`maxhp`+?, `mana`=`mana`+?, `maxmana`=`maxmana`+?, `extramana`=`extramana`+? where `id`=?", array($extrahp, $extrahp, $extramana, $extramana, $extramana, $player->id));

				$db->execute("update `items` set `status`='equipped' where `id`=?", array($_GET['itid']));
				break;
      			case "equipped": //User wants to unequip item
					$player = check_user($secret_key, $db); //Get new stats
					$query = $db->execute("select items.item_id, items.item_bonus, items.vit, blueprint_items.type, blueprint_items.effectiveness from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.id=?", array($_GET['itid']));
					$item = $query->fetchrow();

                    //pega valor dos adicionais
                    if ($item['type'] == amulet){
                        $extrahp = (($item['effectiveness'] + ($item['item_bonus'] * 2) + $item['vit']) * 20);
                        $extramana = (($item['effectiveness'] + ($item['item_bonus'] * 2) + $item['vit']) * 5);
                    } else {
                        $extrahp = ($item['vit'] * 20);
                        $extramana = ($item['vit'] * 5);
                    }
                    
                    if ($player->hp > $extrahp)
                    {
                        $playerhp = $player->hp - $extrahp;
                    } else {
                        $playerhp = 1;
                    }
                    
                    $playermana = $player->mana - $extramana;
                    if ($playermana < 0) {
                        $playermana = 0;
                    }
                    
                    $db->execute("update `players` set `hp`=?, `maxhp`=`maxhp`-?, `mana`=?, `maxmana`=`maxmana`-?, `extramana`=`extramana`-? where `id`=?", array($playerhp, $extrahp, $playermana, $extramana, $extramana, $player->id));
                    
				$db->execute("update `items` set `status`='unequipped' where `id`=?", array($_GET['itid']));
				break;
			default: //Set status to unequipped, in case the item had no status when it was inserted into db
					$player = check_user($secret_key, $db); //Get new stats
					$query = $db->execute("select items.item_id, items.item_bonus, items.vit, blueprint_items.type, blueprint_items.effectiveness from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.id=?", array($_GET['itid']));
					$item = $query->fetchrow();

                    //pega valor dos adicionais
                    if ($item['type'] == amulet){
                        $extrahp = (($item['effectiveness'] + ($item['item_bonus'] * 2) + $item['vit']) * 20);
                        $extramana = (($item['effectiveness'] + ($item['item_bonus'] * 2) + $item['vit']) * 5);
                    } else {
                        $extrahp = ($item['vit'] * 20);
                        $extramana = ($item['vit'] * 5);
                    }
                    
                    if ($player->hp > $extrahp)
                    {
                        $playerhp = $player->hp - $extrahp;
                    } else {
                        $playerhp = 1;
                    }
                    
                    $playermana = $player->mana - $extramana;
                    if ($playermana < 0) {
                        $playermana = 0;
                    }
                    
                    $db->execute("update `players` set `hp`=?, `maxhp`=`maxhp`-?, `mana`=?, `maxmana`=`maxmana`-?, `extramana`=`extramana`-? where `id`=?", array($playerhp, $extrahp, $playermana, $extramana, $extramana, $player->id));
                    
				$db->execute("update `items` set `status`='unequipped' where `id`=?", array($_GET['itid']));
				break;
		}
	}
}


header("Location: inventory.php");
exit;
?>