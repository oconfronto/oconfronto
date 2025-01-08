<?php

declare(strict_types=1);

define('PAGENAME', 'Equipment');

include(__DIR__ . "/lib.php");
$player = check_user($db);

if ($_GET['itid'] ?? null) {
    $query = $db->execute("select `id`, `status`, `item_id` from `items` where `id`=? and `player_id`=?", [$_GET['itid'] ?? null, $player->id]);
    if ($query->recordcount() == 1) {
        $item = $query->fetchrow();

        //arruma anis sem atributos
        $update = 0;
        if (($item['item_id'] ?? null) == 163) {
            $for = 10;
            $vit = 10;
            $agi = 10;
            $res = 10;
            $update = 5;
        } elseif (($item['item_id'] ?? null) == 164) {
            $for = 10;
            $vit = 0;
            $agi = 0;
            $res = 0;
            $update = 5;
        } elseif (($item['item_id'] ?? null) == 165) {
            $for = 0;
            $vit = 10;
            $agi = 0;
            $res = 0;
            $update = 5;
        } elseif (($item['item_id'] ?? null) == 166) {
            $for = 0;
            $vit = 0;
            $agi = 10;
            $res = 0;
            $update = 5;
        } elseif (($item['item_id'] ?? null) == 167) {
            $for = 0;
            $vit = 0;
            $agi = 0;
            $res = 10;
            $update = 5;
        } elseif (($item['item_id'] ?? null) == 168) {
            $for = 20;
            $vit = 20;
            $agi = 20;
            $res = 20;
            $update = 5;
        } elseif (($item['item_id'] ?? null) == 169) {
            $for = 10;
            $vit = 0;
            $agi = 0;
            $res = 15;
            $update = 5;
        } elseif (($item['item_id'] ?? null) == 170) {
            $for = 0;
            $vit = 15;
            $agi = 15;
            $res = 5;
            $update = 5;
        } elseif (($item['item_id'] ?? null) == 172) {
            $for = 40;
            $vit = 30;
            $agi = 40;
            $res = 30;
            $update = 5;
        } elseif (($item['item_id'] ?? null) == 176) {
            $for = 30;
            $vit = 40;
            $agi = 30;
            $res = 40;
            $update = 5;
        } elseif (($item['item_id'] ?? null) == 178) {
            $for = 40;
            $vit = 40;
            $agi = 40;
            $res = 40;
            $update = 5;
        }

        if ($update == 5) {
            $db->execute("update `items` set `for`=?, `vit`=?, `agi`=?, `res`=? where `id`=?", [$for, $vit, $agi, $res, $item['id'] ?? null]);
            $query = $db->execute("select `id`, `status`, `item_id` from `items` where `id`=? and `player_id`=?", [$item['id'] ?? null, $player->id]);
            $item = $query->fetchrow();
        }

        // fim arrumao anis   

        switch ($item['status'] ?? null) {
            case "unequipped": //User wants to equip item
                //$itemtype = $db->getone("select `type` from `blueprint_items` where `id`=?", array($item['item_id']));

                //Equip the selected item
                $ckitexs = $db->execute("select items.id, items.item_id, items.mark, items.player_id, blueprint_items.voc, blueprint_items.needlvl, blueprint_items.needpromo, blueprint_items.type from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and items.id=?", [$player->id, $_GET['itid'] ?? null]);
                $ddckitexs = $ckitexs->fetchrow();
                if ($ckitexs->recordcount() == 0) {
                    include(__DIR__ . "/templates/private_header.php");
                    echo 'Um erro desconhecido ocorreu. <a href="inventory_mobile.php">Voltar</a>.';
                    include(__DIR__ . "/templates/private_footer.php");
                    exit;
                }

                if (($ddckitexs['voc'] ?? null) == '1' && $player->voc != 'archer') {
                    include(__DIR__ . "/templates/private_header.php");
                    echo "Você não pode usar este item. <a href=\"inventory_mobile.php\">Voltar</a>.";
                    include(__DIR__ . "/templates/private_footer.php");
                    exit;
                }

                if (($ddckitexs['voc'] ?? null) == '2' && $player->voc != 'knight') {
                    include(__DIR__ . "/templates/private_header.php");
                    echo "Você não pode usar este item. <a href=\"inventory_mobile.php\">Voltar</a>.";
                    include(__DIR__ . "/templates/private_footer.php");
                    exit;
                }

                if (($ddckitexs['voc'] ?? null) == '3' && $player->voc != 'mage') {
                    include(__DIR__ . "/templates/private_header.php");
                    echo "Você não pode usar este item. <a href=\"inventory_mobile.php\">Voltar</a>.";
                    include(__DIR__ . "/templates/private_footer.php");
                    exit;
                }

                if (($ddckitexs['type'] ?? null) == 'shield' && $player->voc == 'archer') {
                    include(__DIR__ . "/templates/private_header.php");
                    echo "Arqueiros não podem usar escudos. <a href=\"inventory_mobile.php\">Voltar</a>.";
                    include(__DIR__ . "/templates/private_footer.php");
                    exit;
                }

                $lvlbonus = $player->vip > time() ? 10 : 0;

                if (($ddckitexs['needlvl'] ?? null) > ($player->level + $lvlbonus)) {
                    include(__DIR__ . "/templates/private_header.php");
                    echo "Você não tem nível suficiente para usar este item. <a href=\"inventory_mobile.php\">Voltar</a>.";
                    include(__DIR__ . "/templates/private_footer.php");
                    exit;
                }

                if (($ddckitexs['type'] ?? null) == 'addon') {
                    include(__DIR__ . "/templates/private_header.php");
                    echo "Você não pode usar este item. <a href=\"inventory_mobile.php\">Voltar</a>.";
                    include(__DIR__ . "/templates/private_footer.php");
                    exit;
                }

                if (($ddckitexs['mark'] ?? null) == 't') {
                    include(__DIR__ . "/templates/private_header.php");
                    echo "Você não pode usar um item que está à venda no mercado. <a href=\"inventory_mobile.php\">Voltar</a>.";
                    include(__DIR__ . "/templates/private_footer.php");
                    exit;
                }

                if (($ddckitexs['needpromo'] ?? null) == 't' && $player->promoted == 'f') {
                    include(__DIR__ . "/templates/private_header.php");
                    echo "Apenas usuários de vocação superior podem usar este item. <a href=\"inventory_mobile.php\">Voltar</a>.";
                    include(__DIR__ . "/templates/private_footer.php");
                    exit;
                }

                if (($ddckitexs['needpromo'] ?? null) == 'p' && $player->promoted != 'p') {
                    include(__DIR__ . "/templates/private_header.php");
                    echo "Apenas usuários de vocação suprema podem usar este item. <a href=\"inventory_mobile.php\">Voltar</a>.";
                    include(__DIR__ . "/templates/private_footer.php");
                    exit;
                }

                //Check if another item is already equipped
                $unequip = $db->getone("select items.id from `items`, `blueprint_items` where items.item_id = blueprint_items.id and blueprint_items.type=(select `type` from `blueprint_items` where `id`=?) and items.player_id=? and `status`='equipped'", [$item['item_id'] ?? null, $player->id]);
                if ($unequip) //If so, then unequip it (only one item may be equipped at any one time)
                {
                    $player = check_user($db); //Get new stats
                    $query = $db->execute("select items.item_id, items.item_bonus, items.vit, blueprint_items.type, blueprint_items.effectiveness from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.id=?", [$unequip]);
                    $item = $query->fetchrow();

                    //pega valor dos adicionais
                    if (($item['type'] ?? null) == 'amulet') {
                        $extrahp = (($item['effectiveness'] + ($item['item_bonus'] * 2) + $item['vit']) * 20);
                        $extramana = (($item['effectiveness'] + ($item['item_bonus'] * 2) + $item['vit']) * 5);
                    } else {
                        $extrahp = ($item['vit'] * 20);
                        $extramana = ($item['vit'] * 5);
                    }

                    $playerhp = $player->hp > $extrahp ? $player->hp - $extrahp : 1;

                    $playermana = $player->mana - $extramana;
                    if ($playermana < 0) {
                        $playermana = 0;
                    }

                    $db->execute("update `players` set `hp`=?, `maxhp`=`maxhp`-?, `mana`=?, `maxmana`=`maxmana`-?, `extramana`=`extramana`-? where `id`=?", [$playerhp, $extrahp, $playermana, $extramana, $extramana, $player->id]);

                    $db->execute("update `items` set `status`='unequipped' where `id`=?", [$unequip]);
                }

                $player = check_user($db); //Get new stats
                $query = $db->execute("select items.item_id, items.item_bonus, items.vit, blueprint_items.type, blueprint_items.effectiveness from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.id=?", [$_GET['itid'] ?? null]);
                $item = $query->fetchrow();

                //pega valor dos adicionais
                if (($item['type'] ?? null) == 'amulet') {
                    $extrahp = (($item['effectiveness'] + ($item['item_bonus'] * 2) + $item['vit']) * 20);
                    $extramana = (($item['effectiveness'] + ($item['item_bonus'] * 2) + $item['vit']) * 5);
                } else {
                    $extrahp = ($item['vit'] * 20);
                    $extramana = ($item['vit'] * 5);
                }

                $db->execute("update `players` set `hp`=`hp`+?, `maxhp`=`maxhp`+?, `mana`=`mana`+?, `maxmana`=`maxmana`+?, `extramana`=`extramana`+? where `id`=?", [$extrahp, $extrahp, $extramana, $extramana, $extramana, $player->id]);

                $db->execute("update `items` set `status`='equipped' where `id`=?", [$_GET['itid'] ?? null]);
                break;
            case "equipped":
            default: //Set status to unequipped, in case the item had no status when it was inserted into db
                $player = check_user($db); //Get new stats
                $query = $db->execute("select items.item_id, items.item_bonus, items.vit, blueprint_items.type, blueprint_items.effectiveness from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.id=?", [$_GET['itid'] ?? null]);
                $item = $query->fetchrow();

                //pega valor dos adicionais
                if (($item['type'] ?? null) == 'amulet') {
                    $extrahp = (($item['effectiveness'] + ($item['item_bonus'] * 2) + $item['vit']) * 20);
                    $extramana = (($item['effectiveness'] + ($item['item_bonus'] * 2) + $item['vit']) * 5);
                } else {
                    $extrahp = ($item['vit'] * 20);
                    $extramana = ($item['vit'] * 5);
                }

                $playerhp = $player->hp > $extrahp ? $player->hp - $extrahp : 1;

                $playermana = $player->mana - $extramana;
                if ($playermana < 0) {
                    $playermana = 0;
                }

                $db->execute("update `players` set `hp`=?, `maxhp`=`maxhp`-?, `mana`=?, `maxmana`=`maxmana`-?, `extramana`=`extramana`-? where `id`=?", [$playerhp, $extrahp, $playermana, $extramana, $extramana, $player->id]);

                $db->execute("update `items` set `status`='unequipped' where `id`=?", [$_GET['itid'] ?? null]);
                break;
        }
    }
}


header("Location: inventory_mobile.php");
exit;
