<?php
	include("lib.php");
	$player = check_user($secret_key, $db);
	$error = 0;

if (($_GET['itid']) and ($_GET['tile'])) {
	if (($_GET['itid'] < 1) or (!is_numeric($_GET['itid']))){
		$error = 1;
	}

	elseif (($_GET['tile'] < 1) or (!is_numeric($_GET['tile']))){
		$error = 1;
	}

	$checkitem = $db->execute("select * from `items` where `id`=? and `player_id`=?", array($_GET['itid'], $player->id));
	if ($checkitem->recordcount() != 1) {
		$error = 1;
	}

		if ($error == 0){
			$itstatus = $db->GetOne("select `status` from `items` where `id`=? and `player_id`=?", array($_GET['itid'], $player->id));
				if ($itstatus == 'equipped'){

					$unequipinfo = $db->execute("select items.item_id, items.item_bonus, items.vit, blueprint_items.type, blueprint_items.effectiveness from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.id=?", array($_GET['itid']));
					$item = $unequipinfo->fetchrow();
                    
                    
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

				$db->execute("update `items` set `status`='unequipped' where `id`=? and `player_id`=?", array($_GET['itid'], $player->id));
				}


			$backpackcount = $db->execute("select items.id, items.tile from `items`, `blueprint_items` where items.player_id=? and items.status='unequipped' and items.item_id=blueprint_items.id and blueprint_items.type!='potion' and blueprint_items.type!='stone' and items.mark='f' limit 49", array($player->id));

			if ($_GET['tile'] <= 1){
				$limit1 = 0;
				$limit2 = 1;
			}else{
				$limit1 = ($_GET['tile'] - 1);
				$limit2 = ($_GET['tile'] - 1);
			}

			$tileexists = $db->execute("select items.tile from `items`, `blueprint_items` where items.player_id=? and items.status='unequipped' and items.item_id=blueprint_items.id and blueprint_items.type!='potion' and blueprint_items.type!='stone' and items.mark='f' order by items.tile asc limit ?,?", array($player->id, $limit1, $limit2));
			$tileitd = $db->GetOne("select `tile` from `items` where `id`=? and `player_id`=?", array($_GET['itid'], $player->id));

				if ($_GET['tile'] > $backpackcount->recordcount()){
					$biggesttile = $db->GetOne("select `tile` from `items` where `player_id`=? order by `tile` desc", array($player->id));
					$db->execute("update `items` set `tile`=? where `id`=? and `player_id`=?", array($biggesttile + 1, $_GET['itid'], $player->id));
				}elseif ($tileexists->recordcount() > 0){
					$tilenumber = $tileexists->fetchrow();

						if ($_GET['tile'] <= 1){
							$db->execute("update `items` set `tile`=? where `id`=? and `player_id`=?", array($tilenumber['tile'] - 1, $_GET['itid'], $player->id));
						}elseif ($tileitd > $tilenumber['tile']){
							$db->execute("update `items` set `tile`=? where `id`=? and `player_id`=?", array($tilenumber['tile'] - 1, $_GET['itid'], $player->id));
						}else{
							$db->execute("update `items` set `tile`=? where `id`=? and `player_id`=?", array($tilenumber['tile'] + 1, $_GET['itid'], $player->id));
						}
				}
		}
}

header("Location: inventory.php");
exit;
?>