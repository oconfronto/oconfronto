<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Ferreiro");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkhp.php");
include(__DIR__ . "/checkwork.php");

// Add this line near the top of the file, after $player is defined
$voc = $player->voc;

switch ($_GET['act']) {
	case "buy":
		if (!$_GET['id']) //No item ID
		{
			header("Location: shop.php");
			break;
		}

		//Select the item from the database
		$query = $db->execute("select `id`, `name`, `price`, `type`, `voc`, `canbuy` from `blueprint_items` where `id`=?", [$_GET['id']]);

		//Invalid item (it doesn't exist)
		if ($query->recordcount() == 0) {
			header("Location: shop.php");
			break;
		}

		$item = $query->fetchrow();
		$itemprice = $player->reino == '1' || $player->vip > time() ? ceil($item['price'] * 0.9) : $item['price'];

		if ($itemprice > $player->gold) {
			include(__DIR__ . "/templates/private_header.php");
			echo "<b>Ferreiro:</b><br />\n";
			echo "<i>Desculpe, mas você não pode pagar por isto!</i><br /><br />\n";
			echo "<a href=\"inventory.php\">Retornar ao inventário</a> | <a href=\"shop.php\">Retornar a loja</a>";
			include(__DIR__ . "/templates/private_footer.php");
			break;
		}

		if ($item['type'] == 'shield' && $player->voc == 'archer') {
			include(__DIR__ . "/templates/private_header.php");
			echo "<b>Ferreiro:</b><br />\n";
			echo "<i>Desculpe, mas arqueiros não podem usar escudos!</i><br /><br />\n";
			echo "<a href=\"inventory.php\">Retornar ao inventário</a> | <a href=\"shop.php\">Retornar a loja</a>";
			include(__DIR__ . "/templates/private_footer.php");
			break;
		}

		if ($item['voc'] == '1' && $player->voc != 'archer') {
			include(__DIR__ . "/templates/private_header.php");
			echo "<b>Ferreiro:</b><br />\n";
			echo "<i>Desculpe, mas você não pode comprar esse tipo de item!</i><br /><br />\n";
			echo "<a href=\"inventory.php\">Retornar ao inventário</a> | <a href=\"shop.php\">Retornar a loja</a>";
			include(__DIR__ . "/templates/private_footer.php");
			break;
		}


		if ($item['voc'] == '2' && $player->voc != 'knight') {
			include(__DIR__ . "/templates/private_header.php");
			echo "<b>Ferreiro:</b><br />\n";
			echo "<i>Desculpe, mas você não pode comprar esse tipo de item!</i><br /><br />\n";
			echo "<a href=\"inventory.php\">Retornar ao inventário</a> | <a href=\"shop.php\">Retornar a loja</a>";
			include(__DIR__ . "/templates/private_footer.php");
			break;
		}


		if ($item['voc'] == '3' && $player->voc != 'mage') {
			include(__DIR__ . "/templates/private_header.php");
			echo "<b>Ferreiro:</b><br />\n";
			echo "<i>Desculpe, mas você não pode comprar esse tipo de item!</i><br /><br />\n";
			echo "<a href=\"inventory.php\">Retornar ao inventário</a> | <a href=\"shop.php\">Retornar a loja</a>";
			include(__DIR__ . "/templates/private_footer.php");
			break;
		}

		if ($item['type'] == 'addon') {
			include(__DIR__ . "/templates/private_header.php");
			echo "<b>Ferreiro:</b><br />\n";
			echo "<i>Desculpe, mas eu não vendo este tipo de item!</i><br /><br />\n";
			echo "<a href=\"inventory.php\">Retornar ao inventário</a> | <a href=\"shop.php\">Retornar a loja</a>";
			include(__DIR__ . "/templates/private_footer.php");
			break;
		}

		if ($item['canbuy'] == 'f') {
			include(__DIR__ . "/templates/private_header.php");
			echo "<b>Ferreiro:</b><br />\n";
			echo "<i>Desculpe, mas eu não vendo este tipo de item!</i><br /><br />\n";
			echo "<a href=\"inventory.php\">Retornar ao inventário</a> | <a href=\"shop.php\">Retornar a loja</a>";
			include(__DIR__ . "/templates/private_footer.php");
			break;
		}


		if ($item['canbuy'] == 's') {

			$checaquest = $db->execute("select `id` from `quests` where `player_id`=? and `quest_status`=90 and `quest_id`=11", [$player->id]);
			if ($checaquest->recordcount() == 0) {
				include(__DIR__ . "/templates/private_header.php");
				echo "<b>Ferreiro:</b><br />\n";
				echo "<i>Desculpe, mas eu não vendo este tipo de item!</i><br /><br />\n";
				echo "<a href=\"inventory.php\">Retornar ao inventário</a> | <a href=\"shop.php\">Retornar a loja</a>";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}
		}

		$db->execute("update `players` set `gold`=? where `id`=?", [$player->gold - $itemprice, $player->id]);
		$insert['player_id'] = $player->id;
		$insert['item_id'] = $item['id'];
		$query = $db->autoexecute('items', $insert, 'INSERT');

		if ($item['id'] == 176) {
			$ringid = $db->Insert_ID();
			$db->execute("update `items` set `for`=`for`+?, `vit`=`vit`+?, `agi`=`agi`+?, `res`=`res`+? where `id`=?", [30, 40, 30, 40, $ringid]);
		}

		$player = check_user($db); //Get new user stats
		include(__DIR__ . "/templates/private_header.php");
		echo "<b>Ferreiro:</b><br />\n";
		echo "<i>Obrigado, aproveite sua nova <b>" . $item['name'] . "</b>!</i><br /><br />\n";
		echo "<a href=\"inventory.php\">Retornar ao inventário</a> | <a href=\"shop.php\">Retornar a loja</a>";
		include(__DIR__ . "/templates/private_footer.php");
		break;

	case "sell":
		if ($_POST['comfirm'] && ($_POST['actione']) == 'vendeer') {
			include(__DIR__ . "/templates/private_header.php");
			if (!$_POST['id']) {
				echo "Você precisa selecionar algum item para vender.<br/><a href=\"inventory.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			$totalprico = 0;
			$totalsell = 0;
			echo "<form method=\"POST\" action=\"shop.php?act=sell\">\n";
			echo "<b>Deseja vender:</b><br/>";
			foreach ($_POST['id'] as $msg) {
				$multipleitem = $db->execute("select items.id, items.item_id, items.item_bonus, items.status, items.mark, blueprint_items.name, blueprint_items.price, blueprint_items.type from `blueprint_items`, `items` where items.item_id=blueprint_items.id and items.player_id=? and items.id=?", [$player->id, $msg]);
				if ($multipleitem->recordcount() == 0) {
					echo "Este item não te pertence.<br />";
				} else {
					$multisell = $multipleitem->fetchrow();
					if ($multisell['status'] == 'equipped') {
						echo "Você não pode vender um item que está em uso.<br />";
					} elseif ($multisell['type'] == 'stone') {
						echo "Você não pode vender pedras.<br />";
					} elseif ($multisell['item_id'] == 111 || $multisell['item_id'] == 116) {
						echo "Você não pode vender este item, caso contrário não poderá terminar sua missão.<br />";
					} else {
						if ($multisell['item_bonus'] > 10) {
							$precodavenda = floor(($multisell['price'] / 2) + (($multisell['item_bonus'] * $multisell['price']) / 5) + 3000000);
						} else {
							$precodavenda = floor(($multisell['price'] / 2) + (($multisell['item_bonus'] * $multisell['price']) / 5));
						}

						$multisellfor = $multisell['for'] == 0 ? "" : ' +<font color="gray">' . $multisell['for'] . "F</font>";
						$multisellvit = $multisell['vit'] == 0 ? "" : ' +<font color="green">' . $multisell['vit'] . "V</font>";
						$multisellagi = $multisell['agi'] == 0 ? "" : ' +<font color="blue">' . $multisell['agi'] . "A</font>";
						$multisellres = $multisell['res'] == 0 ? "" : ' +<font color="red">' . $multisell['res'] . "R</font>";

						echo "<b>1x</b> " . $multisell['name'] . " +" . $multisell['item_bonus'] . "" . $multisellfor . "" . $multisellvit . "" . $multisellagi . "" . $multisellres . " por " . $precodavenda . " de ouro.<br/>";
						echo '<input type="hidden" name="id[]" value="' . $multisell['id'] . "\" />\n";
						$totalprico += $precodavenda;
						$totalsell += 1;
					}
				}
			}

			if ($totalsell > 0) {
				echo "<b>Vendendo:</b> " . $totalsell . " item(s) por " . $totalprico . " de ouro.<br/><br/><input type=\"submit\" name=\"multiconfirm\" value=\"Desejo vender todos estes itens\" />  <a href=\"inventory.php\">Voltar</a>.\n";
				echo "</form>\n";
			}

			include(__DIR__ . "/templates/private_footer.php");
			break;
		} elseif ($_POST['comfirm'] && ($_POST['actione']) != 'vendeer') {
			include(__DIR__ . "/templates/private_header.php");
			echo "Selecione uma ação.<br/><a href=\"inventory.php\">Voltar</a>.";
			include(__DIR__ . "/templates/private_footer.php");
			break;
		} elseif ($_POST['multiconfirm']) {
			include(__DIR__ . "/templates/private_header.php");
			$totalprico2 = 0;
			foreach ($_POST['id'] as $msg) {
				$multipleitem = $db->execute("select items.id, items.item_id, items.item_bonus, items.status, items.mark, blueprint_items.name, blueprint_items.price, blueprint_items.type from `blueprint_items`, `items` where items.item_id=blueprint_items.id and items.player_id=? and items.id=?", [$player->id, $msg]);
				if ($multipleitem->recordcount() == 0) {
					echo "Este item não te pertence.<br />";
				} else {
					$multisell = $multipleitem->fetchrow();

					if ($multisell['status'] == 'equipped') {
						echo "Você não pode vender um item que está em uso.<br />";
					} elseif ($multisell['type'] == 'stone') {
						echo "Você não pode vender pedras.<br />";
					} elseif ($multisell['item_id'] == 111 || $multisell['item_id'] == 116) {
						echo "Você não pode vender este item, caso contrário não poderá terminar sua missão.<br />";
					} else {
						if ($multisell['item_bonus'] > 10) {
							$precodavenda = floor(($multisell['price'] / 2) + (($multisell['item_bonus'] * $multisell['price']) / 5) + 3000000);
						} else {
							$precodavenda = floor(($multisell['price'] / 2) + (($multisell['item_bonus'] * $multisell['price']) / 5));
						}

						$totalprico2 += $precodavenda;

						if ($multisell['mark'] == 't') {
							$query = $db->execute("delete from `market` where `market_id`=?", [$msg]);
						}

						$query = $db->execute("delete from `items` where `id`=?", [$msg]);
						$multisellfor = $multisell['for'] == 0 ? "" : ' +<font color="gray">' . $multisell['for'] . "F</font>";
						$multisellvit = $multisell['vit'] == 0 ? "" : ' +<font color="green">' . $multisell['vit'] . "V</font>";
						$multisellagi = $multisell['agi'] == 0 ? "" : ' +<font color="blue">' . $multisell['agi'] . "A</font>";
						$multisellres = $multisell['res'] == 0 ? "" : ' +<font color="red">' . $multisell['res'] . "R</font>";

						echo "Você vendeu seu/sua <b>" . $multisell['name'] . " +" . $multisell['item_bonus'] . "</b>" . $multisellfor . "" . $multisellvit . "" . $multisellagi . "" . $multisellres . " por <b>" . $precodavenda . "</b> de ouro.<br/>";
					}
				}
			}

			$query = $db->execute("update `players` set `gold`=? where `id`=?", [$player->gold + $totalprico2, $player->id]);
			echo '<br/><a href="inventory.php">Voltar</a>.';
			include(__DIR__ . "/templates/private_footer.php");
			break;
		} else {

			if (!$_GET['id']) //No item ID
			{
				header("Location: shop.php");
				break;
			}

			//Select the item from the database
			$query = $db->execute("select items.id, items.item_id, items.item_bonus, items.status, items.mark, blueprint_items.name, blueprint_items.price, blueprint_items.type from `blueprint_items`, `items` where items.item_id=blueprint_items.id and items.player_id=? and items.id=?", [$player->id, $_GET['id']]);

			//Either item doesn't exist, or item doesn't belong to user
			if ($query->recordcount() == 0) {
				include(__DIR__ . "/templates/private_header.php");
				echo "Este item não existe!";
				echo '<a href="inventory.php">Voltar</a>.';
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			$sell = $query->fetchrow(); //Get item info
			if ($sell['item_bonus'] > 10) {
				$valordavenda = floor(($sell['price'] / 2) + (($sell['item_bonus'] * $sell['price']) / 5) + 3000000);
			} else {
				$valordavenda = floor(($sell['price'] / 2) + (($sell['item_bonus'] * $sell['price']) / 5));
			}

			if ($sell['item_id'] == 111 || $sell['item_id'] == 116) {
				include(__DIR__ . "/templates/private_header.php");
				echo "Você não pode vender este item, caso contrário não poderá terminar sua missão.<br />\n";
				echo '<a href="inventory.php">Voltar</a>.';
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			if ($sell['type'] == 'stone') {
				include(__DIR__ . "/templates/private_header.php");
				echo "Você não pode vender pedras.<br />\n";
				echo '<a href="inventory.php">Voltar</a>.';
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			if ($sell['status'] == 'equipped') {
				include(__DIR__ . "/templates/private_header.php");
				echo "Você não pode vender um item que está em uso.<br />\n";
				echo '<a href="inventory.php">Voltar</a>.';
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			//Check to make sure clicking Sell wasn't an accident
			if (!$_POST['sure']) {
				include(__DIR__ . "/templates/private_header.php");
				echo "Você tem certeza que quer vender o/a <b>" . $sell['name'] . "</b> por <b>" . $valordavenda . "</b> de ouro?<br /><br />\n";
				echo '<form method="post" action="shop.php?act=sell&id=' . $sell['id'] . "\">\n";
				echo "<input type=\"submit\" name=\"sure\" value=\"Sim, tenho certeza!\" />\n";
				echo "</form>\n";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			//Delete item from database, add gold to player's account
			if ($sell['mark'] == 't') {
				$query = $db->execute("delete from `market` where `market_id`=?", [$sell['id']]);
			}

			$query = $db->execute("delete from `items` where `id`=?", [$sell['id']]);
			$query = $db->execute("update `players` set `gold`=? where `id`=?", [$player->gold + $valordavenda, $player->id]);

			$player = check_user($db); //Get updated user info

			include(__DIR__ . "/templates/private_header.php");
			echo "Você vendeu seu/sua <b>" . $sell['name'] . "</b> por <b>" . $valordavenda . "</b> de ouro.<br /><br />\n";
			echo "<a href=\"inventory.php\">Retornar ao inventário</a> | <a href=\"shop.php\">Retornar a loja</a>";
			include(__DIR__ . "/templates/private_footer.php");
		}

		break;

	default:
		//Show search form
		include(__DIR__ . "/templates/private_header.php");

		echo "<form method=\"GET\" action=\"shop.php\">\n";
		echo "<table width=\"100%\" class=\"brown\" style='border:1px solid #b6804e;height:28px;'><tr>";
		echo "<th width=\"35%\"><b>Procurar por:</b> <select name=\"type\">\n";

		$type = $_GET['type'] ?? '';

		$options = [
			'none' => 'Selecione',
			'amulet' => 'Amuletos',
			'weapon' => 'Armas',
			'armor' => 'Armaduras',
			'boots' => 'Botas',
			'legs' => 'Calças',
			'helmet' => 'Elmos',
			'shield' => 'Escudos'
		];

		foreach ($options as $value => $label) {
			$selected = ($type == $value) ? ' selected="selected"' : '';
			echo "<option value=\"{$value}\"{$selected}>{$label}</option>\n";
		}

		echo "</select></th>";

		$fromprice = isset($_GET['fromprice']) ? htmlspecialchars((string) $_GET['fromprice']) : '';
		$toprice = isset($_GET['toprice']) ? htmlspecialchars((string) $_GET['toprice']) : '';

		echo sprintf('<th width="35%%">Preço de: <input type="text" name="fromprice" size="4" value="%s" /> á  <input type="text" name="toprice" size="5" value="%s" /></th>', $fromprice, $toprice);

		echo '<th width="30%" align="right"><input id="link" class="neg" type="submit" value="Procurar" /></th>';
		echo "</tr></table>";
		echo "</form>";

		if ($_GET['type'] == 'armor' || $_GET['type'] == 'boots' || $_GET['type'] == 'helmet' || $_GET['type'] == 'legs' || $_GET['type'] == 'shield' || $_GET['type'] == 'weapon' || $_GET['type'] == 'amulet') {
			$query = "SELECT `id`, `name`, `description`, `type`, `price`, `effectiveness`, `img`, `needpromo`, `needlvl` FROM `blueprint_items` WHERE ";
			$conditions = [];
			$values = [];
		
			// Price conditions
			if (!empty($_GET['fromprice'])) {
				$fromprice = intval($_GET['fromprice']);
				$conditions[] = "`price` >= ?";
				$values[] = $fromprice;
			}
		
			if (!empty($_GET['toprice'])) {
				$toprice = intval($_GET['toprice']);
				$conditions[] = "`price` <= ?";
				$values[] = $toprice;
			}
		
			// Type condition
			$type = htmlspecialchars($_GET['type']);
			$conditions[] = "`type` = ?";
			$values[] = $type;
		
			// Purchase condition
			$conditions[] = "`canbuy` = 't'";
		
			// Class condition
			switch ($player->voc) {
    		case 'archer':
        	$voc = 1;
        	break;
    		case 'knight':
        	$voc = 2;
        	break;
    		default:
        	$voc = 3;
        	break;
	}
			$conditions[] = "(`voc` = ? OR `voc` = 0)"; // Items that can be used by vocation or by any class
			$values[] = $voc;
		
			// Level condition
			$conditions[] = "`needlvl` < ?";
			$values[] = $player->level + 10;
		
			// Build the final query
			$query .= implode(" AND ", $conditions);
			$query .= " ORDER BY `needlvl` ASC";
		
			// Now execute the query with all parameters
			$result = $db->execute($query, $values);

			echo showAlert("<i>Você pode comprar items de nível " . ($player->level + 10) . " ou menos.</i>");

			while ($item = $result->fetchrow()) {
				echo "<fieldset>\n";
				echo "<legend><b>" . $item['name'] . "</b></legend>\n";
				echo "<table width=\"100%\">\n";
				echo '<tr><td width="5%">';
				echo '<img src="static/images/itens/' . $item['img'] . '"/>';
				echo '</td><td width="75%">';
				echo $item['description'] . "\n<br />";

				if ($item['type'] == 'amulet') {
					$type = "Vitalidade";
				} elseif ($item['type'] == 'weapon') {
					$type = "Ataque";
				} elseif ($item['type'] == 'boots') {
					$type = "Agilidade";
				} else {
					$type = "Defesa";
				}

				echo "<b>" . $type . ":</b> " . $item['effectiveness'] . "\n";
				echo '</td><td width="20%">';

				if ($player->reino == '1' || $player->vip > time()) {
					echo "<b>Preço:</b> " . ceil($item['price'] * 0.9) . "<br />";
				} else {
					echo "<b>Preço:</b> " . $item['price'] . "<br />";
				}

				echo '<a href="shop.php?act=buy&id=' . $item['id'] . '">Comprar</a><br />';
				echo "</td></tr>\n";

				if ($item['needlvl'] > 1) {
					if ($player->level < $item['needlvl']) {
						echo "<table style=\"width:100%; background-color:#EEA2A2;\"><tr><td><center><b>Você precisa ter nivel " . $item['needlvl'] . " ou mais para usar este item.</b></center></td></tr>\n";
					} else {
						echo "<table style=\"width:100%; background-color:#BDF0A6;\"><tr><td><center><b>Você precisa ter nivel " . $item['needlvl'] . " ou mais para usar este item.</b></center></td></tr>\n";
					}
				}

				if ($item['needpromo'] == "t") {
					if ($player->promoted != "f") {
						echo "<table style=\"width:100%; background-color:#BDF0A6;\"><tr><td><center><b>Você precisa ter uma vocação superior para usar este item.</b></center></td></tr>\n";
					} else {
						echo "<table style=\"width:100%; background-color:#EEA2A2;\"><tr><td><center><b>Você precisa ter uma vocação superior para usar este item.</b></center></td></tr>\n";
					}
				}

				echo "</table>";
				echo "</fieldset>\n<br />";
			}

			if ($player->reino == '1') {
				echo showAlert("<i>Você tem 10% de desconto nos items, pelo fato de ser um membro do reino Cathal.</i>");
			} elseif ($player->vip > time()) {
				echo showAlert("<i>Você tem 10% de desconto nos items, pelo fato de ser um membro vip.</i>");
			}
		} elseif ($_GET['type'] == 'shield' && $player->voc == 'archer') {
			echo "<br/><p><i><center>Arqueiros não podem usar/comprar escudos.</center></i></p>";
		} else {
			echo "<br/><p><i><center>Selecione o tipo de item que você deseja procurar.</center></i></p>";
		}

		include(__DIR__ . "/templates/private_footer.php");
		break;
}


