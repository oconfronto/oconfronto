<?php

declare(strict_types=1);

ob_start(); // Inicia o buffer de saída
include(__DIR__ . "/lib.php");
define("PAGENAME", "Inventário");

$player = check_user($db);

include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkhp.php");
include(__DIR__ . "/checkwork.php");

include(__DIR__ . "/includes/items/gift.php");
include(__DIR__ . "/includes/items/goldbar.php");
include(__DIR__ . "/includes/items/magiccrystal.php");
include(__DIR__ . "/includes/actions/transfer-potions.php");
include(__DIR__ . "/includes/actions/transfer-items.php");

include(__DIR__ . "/templates/private_header.php");

$tuto = false;

$tutorial = $db->execute("select * from `pending` where `pending_id`=2 and `pending_status`=4 and `player_id`=?", [$player->id]);
if ($tutorial->recordcount() > 0) {
    $tutorial = $db->execute("select * from `items` where `player_id`=? and `status`='equipped'", [$player->id]);
    if ($tutorial->recordcount() == 0) {
        global $tuto;
        $tuto = true;
        echo showAlert("<table width=\"100%\"><tr><td width=\"90%\">Itens ajudam na sua força e resistência.<br/><font size=\"1px\">Você pode obter itens <u>lutando contra monstros</u> ou <u>comprando-os no ferreiro</u>.</font><br/><br/>Para equipar seu item, clique em <b style='color:green'>EQUIPAR</b> na arma abaixo.</td><th><font size=\"1px\"><a href=\"start.php?act=5\">Próximo</a></font></th></tr></table>", "white", "left");
    } else {
        global $tuto;
        $tuto = false;
        echo showAlert("ótimo, <a href=\"start.php?act=5\">clique aqui</a> para continuar seu tutorial.", "green");
    }
}

if ($_GET['sellit'] ?? null) {
	$query = $db->execute("select items.id, items.item_id, items.item_bonus, items.status, items.mark, blueprint_items.name, blueprint_items.price, blueprint_items.img, blueprint_items.type from `blueprint_items`, `items` where items.item_id=blueprint_items.id and items.player_id=? and items.id=?", [$player->id, $_GET['sellit'] ?? null]);

	if ($query->recordcount() > 0) {
		$sell = $query->fetchrow(); //Get item info
		if (($sell['item_bonus'] ?? null) > 10) {
			$valordavenda = floor(($sell['price'] / 2) + (($sell['item_bonus'] * $sell['price']) / 5) + 3000000);
		} else {
			$valordavenda = floor(($sell['price'] / 2) + (($sell['item_bonus'] * $sell['price']) / 5));
		}
	}

	if ($query->recordcount() == 0) {
		echo '<div style="background-color:#EEA2A2; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';
		echo '<table width="100%" align="center"><tr>';
		echo "<td width=\"100%\" align=\"center\">Este item não existe!</td>";
		echo "</tr></table>";
		echo "</div>";
	} elseif (($sell['item_id'] ?? null) == 111 || ($sell['item_id'] ?? null) == 116 || ($sell['item_id'] ?? null) == 163 || ($sell['item_id'] ?? null) == 168) {
		echo '<div style="background-color:#EEA2A2; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';
		echo '<table width="100%" align="center"><tr>';
		echo "<td width=\"100%\" align=\"center\">Você não pode vender este item!</td>";
		echo "</tr></table>";
		echo "</div>";
	} elseif (($sell['type'] ?? null) == 'stone') {
		echo '<div style="background-color:#EEA2A2; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';
		echo '<table width="100%" align="center"><tr>';
		echo "<td width=\"100%\" align=\"center\">Você não pode vender pedras.</td>";
		echo "</tr></table>";
		echo "</div>";
	} elseif (($sell['status'] ?? null) == 'equipped') {
		echo '<div style="background-color:#EEA2A2; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';
		echo '<table width="100%" align="center"><tr>';
		echo "<td width=\"100%\" align=\"center\">Você não pode vender um item que está em uso.</td>";
		echo "</tr></table>";
		echo "</div>";
	} elseif (($_GET['sellit'] ?? null) > 0 && ($_GET['comfirm'] ?? null) != true) {
		echo '<div style="background-color:#EEA2A2; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';
		echo '<table width="100%" align="center"><tr>';
		echo '<td width="10%" align="center"><img src="static/images/itens/' . $sell['img'] . '" border="0"></td>';
		echo '<td width="55%">Deseja vender seu(a) ' . $sell['name'] . " + " . $sell['item_bonus'] . "<br/>por " . $valordavenda . " moedas de ouro?</td>";
        echo "<td width=\"35%\" align=\"right\"><a href=\"inventory_mobile.php\">Não, obrigado.</a><br/><b><a href=\"inventory_mobile.php?sellit=" . $_GET['sellit'] . '&comfirm=true">Desejo vender o item.</a></b></td>';
		echo "</tr></table>";
		echo "</div>";
	} elseif (($_GET['sellit'] ?? null) > 0 && ($_GET['comfirm'] ?? null) == true) {
		if (($sell['mark'] ?? null) == 't') {
			$db->execute("delete from `market` where `market_id`=?", [$_GET['sellit'] ?? null]);
		}

		$db->execute("delete from `items` where `id`=?", [$_GET['sellit'] ?? null]);
		$db->execute("update `players` set `gold`=`gold`+? where `id`=?", [$valordavenda, $player->id]);

		echo '<div style="background-color:#EEA2A2; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';
		echo '<table width="100%" align="center"><tr>';
		echo '<td width="100%" align="center">Você vendeu seu(a) ' . $sell['name'] . " + " . $sell['item_bonus'] . " por " . $valordavenda . " moedas de ouro.</td>";
		echo "</tr></table>";
		echo "</div>";
	}
}

if ($_GET['mature'] ?? null) {
	$querymature = $db->execute("select items.id, items.item_bonus, items.status, items.mark, blueprint_items.name, blueprint_items.price, blueprint_items.img, blueprint_items.type from `blueprint_items`, `items` where items.item_id=blueprint_items.id and items.player_id=? and items.id=?", [$player->id, $_GET['mature'] ?? null]);    

	if ($querymature->recordcount() > 0) {
		$mature = $querymature->fetchrow();

		if (($mature['item_bonus'] ?? null) == 0) {
			$precol = ceil($mature['price'] / 3.5);
		} elseif (($mature['item_bonus'] ?? null) == 1) {
			$precol = ceil(($mature['price'] / 3.5) * 1.3);
		} elseif (($mature['item_bonus'] ?? null) == 2) {
			$precol = ceil(($mature['price'] / 3.5) * 1.7);
		} elseif (($mature['item_bonus'] ?? null) == 3) {
			$precol = ceil(($mature['price'] / 3.5) * 2);
		} else {
			$precol = ceil(($mature['price'] / 3.5) * ($mature['item_bonus'] / 1.85));
		}
	}    

	if ($querymature->recordcount() == 0) {
		echo '<div style="background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';
		echo '<table width="100%" align="center"><tr>';
		echo "<td width=\"100%\" align=\"center\">Este item não existe!</td>";
		echo "</tr></table>";
		echo "</div>";
	} elseif (($mature['item_bonus'] ?? null) > 8) {
		echo '<div style="background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';
		echo '<table width="100%" align="center"><tr>';
		echo "<td width=\"100%\" align=\"center\">Seu item já está maturado ao máximo! (+9)</td>";
		echo "</tr></table>";
		echo "</div>";
	} elseif (($mature['mark'] ?? null) == 't') {
		echo '<div style="background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';
		echo '<table width="100%" align="center"><tr>';
		echo "<td width=\"100%\" align=\"center\">Você não pode maturar itens a venda no mercado.</td>";
		echo "</tr></table>";
		echo "</div>";
	} elseif (($mature['type'] ?? null) == 'addon' || ($mature['type'] ?? null) == 'potion' || ($mature['type'] ?? null) == 'stone' || ($mature['type'] ?? null) == 'ring') {
		echo '<div style="background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';
		echo '<table width="100%" align="center"><tr>';
		echo "<td width=\"100%\" align=\"center\">Você não pode maturar este tipo de item.</td>";
		echo "</tr></table>";
		echo "</div>";
	} elseif (($mature['price'] ?? null) < 1000) {
		echo '<div style="background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';
		echo '<table width="100%" align="center"><tr>';
		echo "<td width=\"100%\" align=\"center\">Você não pode maturar este item.";
		echo "<br/>Itens com preços mais baixos que mil moedas de ouro não podem ser maturados.";
		echo "</td></tr></table>";
		echo "</div>";

        // var_dump('Até aqui foi');
        // exit;

	} elseif (($_GET['mature'] ?? null) > 0 && ($_GET['comfirm'] ?? null) != true) {
		echo '<div style="background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';
		echo '<table width="100%" align="center"><tr>';
		echo '<td width="10%" align="center"><img src="static/images/itens/' . $mature['img'] . '" border="0"></td>';
		echo '<td width="55%">Deseja maturar seu(a) ' . $mature['name'] . " + " . $mature['item_bonus'] . "<br/>por " . $precol . " moedas de ouro?</td>";
        echo "<td width=\"35%\" align=\"right\"><a href=\"inventory_mobile.php\">Não, obrigado.</a><br/><b><a href=\"inventory_mobile.php?mature=" . $_GET['mature'] . '&comfirm=true">Desejo maturar o item.</a></b></td>';
		echo "</tr></table>";
		echo "</div>";
	} elseif (($_GET['mature'] ?? null) > 0 && ($_GET['comfirm'] ?? null) == true) {

		if ($precol > $player->gold) {
			echo '<div style="background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';
			echo '<table width="100%" align="center"><tr>';
			echo "<td width=\"100%\" align=\"center\">Você não pode pagar pela maturação. (" . $precol . " moedas de ouro)</td>";
			echo "</tr></table>";
			echo "</div>";
		} else {


			if (($mature['type'] ?? null) == 'amulet' && ($mature['status'] ?? null) == 'equipped') {
				$addhp = 40;
				$extramana = 10;
			} else {
				$addhp = 0;
				$extramana = 0;
			}

			$db->execute("update `items` set `item_bonus`=? where `id`=?", [$mature['item_bonus'] + 1, $mature['id'] ?? null]);
			$db->execute("update `players` set `hp`=`hp`+?, `maxhp`=`maxhp`+?, `mana`=`mana`+?, `maxmana`=`maxmana`+?, `extramana`=`extramana`+?, `gold`=`gold`-? where `id`=?", [$addhp, $addhp, $extramana, $extramana, $extramana, $precol, $player->id]);

			echo '<div style="background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px">';
			echo '<table width="100%" align="center"><tr>';
			echo '<td width="100%" align="center">Você maturou seu(a) ' . $mature['name'] . " por " . $precol . " moedas de ouro.<br />Os atributos de seu item subiram em 2 pontos.</td>";
			echo "</tr></table>";
			echo "</div>";
		}
	}
}

if ($_GET['sell'] ?? null) {
    // lógica de venda de itens
    $itemId = $_GET['sell'];
    // lógica de venda de itens
    header("Location: inventory_mobile.php?sellit=" . $itemId . "&comfirm=true");
    exit; // Importante para parar a execução do script após o redirecionamento
}

echo '<div id="main_container" style="font-size: 0.75rem;">';

$query = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=136 and `mark`='f' order by rand()", [$player->id]);
$numerodepocoes = $query->recordcount();

$query2 = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=137 and `mark`='f' order by rand()", [$player->id]);
$numerodepocoes2 = $query2->recordcount();

$query3 = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=148 and `mark`='f' order by rand()", [$player->id]);
$numerodepocoes3 = $query3->recordcount();

$query4 = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=150 and `mark`='f' order by rand()", [$player->id]);
$numerodepocoes4 = $query4->recordcount();

echo "<fieldset style='padding:0px;border:1px solid #b9892f; text-align:center;'>";
echo "<fieldset style='margin-bottom:5px;border:0px;text-align:center;'><b>Poções</b></fieldset>";
echo "<table width=\"100%\" style='margin: 0 auto;'>";

// Health Potion and Big Health Potion
echo "<tr>";
echo "<td><table width=\"80px\" style='margin: 0 auto;'><tr><td><div title=\"header=[Health Potion] body=[Recupera até 5 mil de vida.]\"><img src=\"static/images/itens/healthpotion.gif\"></div></td><td><b>x" . $numerodepocoes . "</b>";
if ($numerodepocoes > 0) {
    $item = $query->fetchrow();
    echo '<br/><a href="hospt.php?act=potion&pid=' . $item['id'] . '">Usar</a>';
}
echo "</td></tr></table></td>";

echo "<td><table width=\"80px\" style='margin: 0 auto;'><tr><td><div title=\"header=[Big Health Potion] body=[Recupera até 10 mil de vida.]\"><img src=\"static/images/itens/bighealthpotion.gif\"></div></td><td><b>x" . $numerodepocoes3 . "</b>";
if ($numerodepocoes3 > 0) {
    $item3 = $query3->fetchrow();
    echo '<br/><a href="hospt.php?act=potion&pid=' . $item3['id'] . '">Usar</a>';
}
echo "</td></tr></table></td>";
echo "</tr>";

// Mana Potion and Energy Potion
echo "<tr>";
echo "<td><table width=\"80px\" style='margin: 0 auto;'><tr><td><div title=\"header=[Mana Potion] body=[Recupera até 500 de mana.]\"><img src=\"static/images/itens/manapotion.gif\"></div></td><td><b>x" . $numerodepocoes4 . "</b>";
if ($numerodepocoes4 > 0) {
    $item4 = $query4->fetchrow();
    echo '<br/><a href="hospt.php?act=potion&pid=' . $item4['id'] . '">Usar</a>';
}
echo "</td></tr></table></td>";

echo "<td><table width=\"80px\" style='margin: 0 auto;'><tr><td><div title=\"header=[Energy Potion] body=[Recupera até 50 de energia.]\"><img src=\"static/images/itens/energypotion.gif\"></div></td><td><b>x" . $numerodepocoes2 . "</b>";
if ($numerodepocoes2 > 0) {
    $item2 = $query2->fetchrow();
    echo '<br/><a href="hospt.php?act=potion&pid=' . $item2['id'] . '">Usar</a>';
}
echo "</td></tr></table></td>";
echo "</tr>";

// Transfer and Sell Potions
echo "<tr>";
echo "<td><a id=\"link\" class=\"neg\" style='margin: 0 auto;color:#fff;' href=\"hospt.php?act=sell\">Vender Poções</a></td>";
echo "<td><a id=\"link\" class=\"neg\" style='margin: 0 auto;color:#fff;' href=\"inventory_mobile.php?transpotion=true\">Transferir Poções</a></td>";
echo "</tr>";

echo "</table>";
echo "</fieldset>";

echo "<br>";
echo "<fieldset style='padding:0px;border:1px solid #b9892f;'>";
echo "<fieldset style='margin-bottom:5px;border:0px;text-align:center;'><b>Enviar itens</b></fieldset>";

$verifikeuser = $db->execute("select `id` from `quests` where `quest_id`=4 and `quest_status`=90 and `player_id`=?", [$player->id]);

if ($player->level < $setting->activate_level) {
	echo "<center><p><font size=\"1\">Para poder transferir itens sua conta precisa estar ativa.<br/>Ela será ativada automaticamente quando você alcançar o nível " . $setting->activate_level . ".</font></p></center>";
} elseif ($verifikeuser->recordcount() == 0) {
	echo "<center><font size=\"1\">Você precisa chegar ao nível 40 e completar uma missão para utilizar esta função.</font></center>";
	if ($player->level > 39) {
		echo "<center><font size=\"1\"><a href=\"quest2.php\"><b>Clique aqui para fazer a missão.</b></a></font></center>";
	}
} elseif ($player->transpass == 'f') {
	echo '<form method="POST" action="transferpass.php">';
	echo "<center><i>Escolha uma senha de transferência para enviar ouro e itens</i><p><font size=\"1px\"><b>Senha:</b></font> <input type=\"password\" name=\"pass\" size=\"15\"/> <font size=\"1px\"><b>Confirme:</b></font> <input type=\"password\" name=\"pass2\" size=\"15\"/> <input type=\"submit\" name=\"submit\" value=\"Definir Senha\"></p><br/><font size=\"1px\">Lembre-se desta senha, ela sempre será usada para fazer transferências bancárias.</font></center>";
	echo "</form>";
} else {

	echo '<table width="100%">';
	echo '<form method="POST" action="inventory_mobile.php">';
	echo "<tr><td width=\"40%\">Usuário:</td><td><input autocomplete='off' type=\"text\" name=\"username\" size=\"20\"/></td></tr>";
	echo '<tr><td width="40%">Item:</td><td>';

	$queoppa = $db->execute("select items.id, items.item_bonus, items.item_id, items.mark, items.for, items.vit, items.agi, items.res, blueprint_items.name from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type!='stone' and blueprint_items.type!='potion' and items.mark='f' order by blueprint_items.type, blueprint_items.name asc", [$player->id]);
	if ($queoppa->recordcount() == 0) {
		echo "<b>Você não possui itens.</b>";
	} else {
		echo '<select name="itselected">';
		while ($item = $queoppa->fetchrow()) {
			$bonus1 = " (+" . $item['item_bonus'] . ") ";
			$bonus2 = "";
			$bonus3 = "";
			$bonus4 = "";
			$bonus5 = "";

			if (($item['for'] ?? null) > 0) {
				$bonus2 = " +" . $item['for'] . "F";
			}

			if (($item['vit'] ?? null) > 0) {
				$bonus3 = " +" . $item['vit'] . "V";
			}

			if (($item['agi'] ?? null) > 0) {
				$bonus4 = " +" . $item['agi'] . "A";
			}

			if (($item['res'] ?? null) > 0) {
				$bonus5 = " +" . $item['res'] . "R";
			}

			echo '<option value="' . $item['id'] . '">' . $item['name'] . " " . $bonus1 . "" . $bonus2 . "" . $bonus3 . "" . $bonus4 . "" . $bonus5 . "</option>";
		}

		echo "</select>";
	}

	echo "</td></tr>";
	echo "<tr><td width=\"40%\">Senha de transferência:</td><td><input autocomplete='off' type=\"password\" name=\"passcode\" size=\"20\"/></td></tr>";
	echo '<tr><td colspan="2" align="center"><input type="submit" name="transferitems" value="Enviar"></td></tr>';
	echo "</table></form>";
	echo '<font size="1"><a href="forgottrans.php">Esqueceu sua senha de transferência?</a></font>';

	$morelogs = 1;
}

echo "</fieldset>";
if ($morelogs == 1) {
	echo "<center><font size=\"1\"><a href=\"#\" onclick=\"javascript:window.open('logitem.php', '_blank','top=100, left=100, height=350, width=450, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');\">Transferências realizadas nos últimos 14 dias.</a></font></center>";
}

echo "</div>";
echo '<div id="inventory">';

function fetchItems($playerId, $status)
{
    global $db;
    return $db->execute("
        SELECT 
            items.id, 
            items.item_id, 
            items.item_bonus, 
            items.for, 
            items.vit, 
            items.agi, 
            items.res, 
            items.status, 
            blueprint_items.name, 
            blueprint_items.img, 
            blueprint_items.effectiveness, 
            blueprint_items.type, 
            blueprint_items.description, 
            blueprint_items.price,
            blueprint_items.needlvl,
            blueprint_items.needpromo,
            blueprint_items.voc
        FROM `items` 
        JOIN `blueprint_items` ON items.item_id=blueprint_items.id 
        WHERE 
            items.player_id=? 
            AND items.status=? 
            AND blueprint_items.type !='potion' 
            AND blueprint_items.type!='stone' 
            AND items.mark='f' 
        ORDER BY items.tile"
        , [$playerId, $status]
    );
}

function fetchPlayers($playerId)
{
    global $db;
    return $db->execute("select * FROM players where id=?", [$playerId]);
}

function displayItemOptions(array $item, $action, $label): ?string
{
    if (($item['item_bonus'] ?? null) == 0) {
        $precol = ceil($item['price'] / 3.5);
    } elseif (($item['item_bonus'] ?? null) == 1) {
        $precol = ceil(($item['price'] / 3.5) * 1.3);
    } elseif (($item['item_bonus'] ?? null) == 2) {
        $precol = ceil(($item['price'] / 3.5) * 1.7);
    } elseif (($item['item_bonus'] ?? null) == 3) {
        $precol = ceil(($item['price'] / 3.5) * 2);
    } else {
        $precol = ceil(($item['price'] / 3.5) * ($item['item_bonus'] / 1.85));
    }

    if (($item['item_bonus'] ?? null) > 10) {
        $valordavenda = floor(($item['price'] / 2) + (($item['item_bonus'] * $item['price']) / 5) + 3000000);
    } else {
        $valordavenda = floor(($item['price'] / 2) + (($item['item_bonus'] * $item['price']) / 5));
    }

    if ($action == 'sell') {
        return sprintf("<a onclick=\"return confirm('Tem certeza que deseja VENDER o item %s +%s no valor de: %s ?');\" href=\"inventory_mobile.php?%s=%s\">%s</a>", $item['name'], $item['item_bonus'], $valordavenda, $action, $item['id'], $label);
    }

    if ($action == 'maturar') {
        return sprintf("<a onclick=\"return confirm('Tem certeza que deseja MATURAR o item %s +%s no valor de: %s ?');\" href=\"inventory_mobile.php?%s=%s\">%s</a>", $item['name'], $item['item_bonus'], $precol, $action, $item['id'], $label);
    }

    global $tuto;
    if ($tuto) {
        echo "<style>
            .txt-tutorial-equip{
            font-size:14px;
            animation: piscar 2.5s infinite;
            }
            @keyframes piscar{
            0%, 100%{color:#745927}
            100%{color:green}            
            }
            </style>";

        return sprintf("<a href=\"inventory_mobile.php?%s=%s\"><b class='txt-tutorial-equip'>->%s<-</b></a>", $action, $item['id'], $label);
    }

    return sprintf('<a href="inventory_mobile.php?%s=%s">%s</a>', $action, $item['id'], $label);
}

function displayItemCard(array $item, $type, array $player, int $bool): string
{
    $options = [];
    if ($type === 'equipped') {
        $options[] = displayItemOptions($item, 'unequip', 'Desequipar');
    } else {
        $options[] = displayItemOptions($item, 'equip', 'Equipar');
    }

    $options[] = displayItemOptions($item, 'sell', 'Vender');
    $options[] = displayItemOptions($item, 'maturar', 'Maturar');

    $type = "";
    if (($item['type'] ?? null) == 'amulet') {
        $type = "Vitalidade";
    }

    if (($item['type'] ?? null) == 'weapon') {
        $type = "Ataque";
    }

    if (($item['type'] ?? null) == 'armor') {
        $type = "Defesa";
    }

    if (($item['type'] ?? null) == 'boots') {
        $type = "Agilidade";
    }

    if (($item['type'] ?? null) == 'legs') {
        $type = "Defesa";
    }

    if (($item['type'] ?? null) == 'helmet') {
        $type = "Defesa";
    }

    if (($item['type'] ?? null) == 'shield') {
        $type = "Defesa";
    }

    $atributo = "";
    if (($item['type'] ?? null) != 'ring') {
        // $atributo =  $type . (': ' . $item['effectiveness']);
        $atributo =  $type . (': ' . ((int)$item['item_bonus'] > 0 ? ((int)$item['effectiveness'] + ((int)$item['item_bonus'] * 2)) : (int)$item['effectiveness']));
    } else {
        switch ($item['item_id'] ?? null) {
            case 163:
                $item['for'] = 10;
                $item['vit'] = 10;
                $item['agi'] = 10;
                $item['res'] = 10;
                break;
            case 164:
                $item['for'] = 10;
                break;
            case 165:
                $item['vit'] = 10;
                break;
            case 166:
                $item['agi'] = 10;
                break;
            case 167:
                $item['res'] = 10;
                break;
            case 168:
                $item['for'] = 20;
                $item['vit'] = 20;
                $item['agi'] = 20;
                $item['res'] = 20;
                break;
            case 169:
                $item['for'] = 10;
                $item['res'] = 15;
                break;
            case 170:
                $item['vit'] = 15;
                $item['agi'] = 15;
                $item['res'] = 5;
                break;
            case 172:
                $item['for'] = 40;
                $item['vit'] = 30;
                $item['agi'] = 40;
                $item['res'] = 30;
                break;
            case 176:
                $item['for'] = 30;
                $item['vit'] = 40;
                $item['agi'] = 30;
                $item['res'] = 40;
                break;
            case 178:
                //Não seu qual atributo dá ainda.
                break;
            default:
        }
    }

    $bonus1 = "";
    $bonus2 = "";
    $bonus3 = "";
    $bonus4 = "";
    $bonus5 = "";

    if (($item['item_bonus'] ?? null) > 0) {
        $bonus1 = " (+" . $item['item_bonus'] . ")";
    }

    if (($item['for'] ?? null) > 0) {
        $bonus2 = ' <font color="gray">+' . $item['for'] . "F</font>";
    }

    if (($item['vit'] ?? null) > 0) {
        $bonus3 = ' <font color="green">+' . $item['vit'] . "V</font>";
    }

    if (($item['agi'] ?? null) > 0) {
        $bonus4 = ' <font color="blue">+' . $item['agi'] . "A</font>";
    }

    if (($item['res'] ?? null) > 0) {
        $bonus5 = ' <font color="red">+' . $item['res'] . "R</font>";
    }

    return '<div class="item-card" style="width: 150px; display: inline-block; vertical-align: top; margin: 5px; position: relative;">
                <div class="item-description-icon" style="position: absolute; top: 5px; right: 5px; cursor: pointer;" onclick="alert(\'' . addslashes($item['description']) . '\')">
                    <img class="item-card-help" src="static/images/help.gif" alt="Descrição">
                </div>
                <img src="static/images/itens/' . $item['img'] . '" alt="' . $item['name'] . '">
                <div class="item-name">'. $item['name'] . $bonus1 . '<br>
                    Lv: ' . LevelRequired($player, $item) . ' | Voc: ' . returnClassOfItem($item['voc'], $player['voc']) . ($item['needpromo'] == 't' ? '<br><font color="red">(Voc.Sup Exigida)</font>':"") . ($item['needring'] == 't' ? ' <br><font color="red">[Nec.Anel]</font>':"") .
                    '<br>' . $bonus2 . 
                    ' ' . $bonus3 . 
                    ' ' . $bonus4 . 
                    ' ' . $bonus5 . 
                '</div>
                <div class="item-attribute">' . $atributo . '</div>
                <div class="item-actions">' . implode('<br>', $options) . '</div>
            </div>';
}


function returnClassOfItem($vocationId, $playervoc)
{
    //'archer','knight','mage'
    switch ($vocationId) {
        case '1':
            if($playervoc != 'archer'){
                return '<font color="red">Arqueiro</font>';                
            }
            else{
                return 'Arqueiro';
            }
            break;
        case '3':

            if($playervoc != 'mage'){
                return '<font color="red">Mago</font>';                
            }
            else{
                return 'Mago';                
            }
            break;
        case '2':

            if($playervoc != 'knight'){
                return '<font color="red">Guerreiro</font>';                
            }
            else
            {
                return 'Guerreiro';                
            }
            break;
        default:
            return 'Todas';
            break;
    }
}

function LevelRequired(array $p, array $i){   

    if ((int)$p['level'] > (int)$i['needlvl']) {

        return $i['needlvl'];
    }
    else
    {
        return '<font color="red">'. $i['needlvl'] .'</font>';
    }    
}

function displayItemsAsCards($playerId, $status, $title): void
{
    $items = fetchItems($playerId, $status);
    $player = fetchPlayers($playerId)->fetchrow();
    echo sprintf("<div style='text-align:center'><h3>%s</h3></div>", $title);
    if ($items->recordcount() > 0) {
        echo "<div class='items-container' style='text-align: center;'>";
        while ($item = $items->fetchrow()) {
            echo displayItemCard($item, $status, $player, 1);
        }
        echo "</div>";
    } else {
        echo "<div style='text-align:center'><p>Nenhum item encontrado.</p></div>";
    }
}

if ($_GET['maturar'] ?? null) {
    // lógica de maturação de itens
    $itemId = $_GET['maturar'];
    // lógica de maturação de itens
    header("Location: inventory_mobile.php?mature=" . $itemId . "&comfirm=true");
    exit;
}

if ($_GET['equip'] ?? null) {
    // lógica de equipar itens    
    $itemId = $_GET['equip'];
    // lógica de equipar itens
    header("Location: equipit.php?itid=" . $itemId);
    exit;
}

if ($_GET['unequip'] ?? null) {
    // lógica de desequipar itens
    $itemId = $_GET['unequip'];
    // lógica de desequipar itens
    header("Location: moveit.php?itid=" . $itemId . "&tile=1");
    exit;
}


displayItemsAsCards($player->id, 'equipped', 'Itens Equipados');
displayItemsAsCards($player->id, 'unequipped', 'Itens na Mochila');
echo "</div>";
echo "</div>";

include(__DIR__ . "/templates/private_footer.php");
ob_end_flush(); // Envia o conteúdo do buffer e limpa
