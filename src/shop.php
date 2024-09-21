<?php
include("lib.php");
define("PAGENAME", "Ferreiro");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkhp.php");
include("checkwork.php");

switch($_GET['act'])
{
	case "buy":
		if (!$_GET['id']) //No item ID
		{
			header("Location: shop.php");
			break;
		}
		
		//Select the item from the database
		$query = $db->execute("select `id`, `name`, `price`, `type`, `voc`, `canbuy` from `blueprint_items` where `id`=?", array($_GET['id']));
		
		//Invalid item (it doesn't exist)
		if ($query->recordcount() == 0)
		{
			header("Location: shop.php");
			break;
		}

		$item = $query->fetchrow();
			if (($player->reino == '1') or ($player->vip > time())) {
				$itemprice = ceil($item['price'] * 0.9);
			} else {
				$itemprice = $item['price'];
			}

		if ($itemprice > $player->gold)
		{
		include("templates/private_header.php");
		echo "<b>Ferreiro:</b><br />\n";
		echo "<i>Desculpe, mas você não pode pagar por isto!</i><br /><br />\n";
		echo "<a href=\"inventory.php\">Retornar ao inventário</a> | <a href=\"shop.php\">Retornar a loja</a>";
		include("templates/private_footer.php");
		break;
		}

		if (($item['type'] == 'shield') and ($player->voc == 'archer'))
		{
			include("templates/private_header.php");
			echo "<b>Ferreiro:</b><br />\n";
			echo "<i>Desculpe, mas arqueiros não podem usar escudos!</i><br /><br />\n";
			echo "<a href=\"inventory.php\">Retornar ao inventário</a> | <a href=\"shop.php\">Retornar a loja</a>";
			include("templates/private_footer.php");
			break;
		}

		if (($item['voc'] == '1') and ($player->voc != 'archer'))
		{
			include("templates/private_header.php");
			echo "<b>Ferreiro:</b><br />\n";
			echo "<i>Desculpe, mas você não pode comprar esse tipo de item!</i><br /><br />\n";
			echo "<a href=\"inventory.php\">Retornar ao inventário</a> | <a href=\"shop.php\">Retornar a loja</a>";
			include("templates/private_footer.php");
			break;
		}


		if (($item['voc'] == '2') and ($player->voc != 'knight'))
		{
			include("templates/private_header.php");
			echo "<b>Ferreiro:</b><br />\n";
			echo "<i>Desculpe, mas você não pode comprar esse tipo de item!</i><br /><br />\n";
			echo "<a href=\"inventory.php\">Retornar ao inventário</a> | <a href=\"shop.php\">Retornar a loja</a>";
			include("templates/private_footer.php");
			break;
		}


		if (($item['voc'] == '3') and ($player->voc != 'mage'))
		{
			include("templates/private_header.php");
			echo "<b>Ferreiro:</b><br />\n";
			echo "<i>Desculpe, mas você não pode comprar esse tipo de item!</i><br /><br />\n";
			echo "<a href=\"inventory.php\">Retornar ao inventário</a> | <a href=\"shop.php\">Retornar a loja</a>";
			include("templates/private_footer.php");
			break;
		}

		if ($item['type'] == 'addon')
		{
			include("templates/private_header.php");
			echo "<b>Ferreiro:</b><br />\n";
			echo "<i>Desculpe, mas eu não vendo este tipo de item!</i><br /><br />\n";
			echo "<a href=\"inventory.php\">Retornar ao inventário</a> | <a href=\"shop.php\">Retornar a loja</a>";
			include("templates/private_footer.php");
			break;
		}

		if ($item['canbuy'] == 'f')
		{
			include("templates/private_header.php");
			echo "<b>Ferreiro:</b><br />\n";
			echo "<i>Desculpe, mas eu não vendo este tipo de item!</i><br /><br />\n";
			echo "<a href=\"inventory.php\">Retornar ao inventário</a> | <a href=\"shop.php\">Retornar a loja</a>";
			include("templates/private_footer.php");
			break;
		}


		if ($item['canbuy'] == 's')
		{
			
			$checaquest = $db->execute("select `id` from `quests` where `player_id`=? and `quest_status`=90 and `quest_id`=11", array($player->id));
			if ($checaquest->recordcount() == 0)
			{
				include("templates/private_header.php");
				echo "<b>Ferreiro:</b><br />\n";
				echo "<i>Desculpe, mas eu não vendo este tipo de item!</i><br /><br />\n";
				echo "<a href=\"inventory.php\">Retornar ao inventário</a> | <a href=\"shop.php\">Retornar a loja</a>";
				include("templates/private_footer.php");
				break;
			}
		}

		$db->execute("update `players` set `gold`=? where `id`=?", array($player->gold - $itemprice, $player->id));
		$insert['player_id'] = $player->id;
		$insert['item_id'] = $item['id'];
		$query = $db->autoexecute('items', $insert, 'INSERT');

		if ($item['id'] == 176){
			$ringid = $db->Insert_ID();
			$db->execute("update `items` set `for`=`for`+?, `vit`=`vit`+?, `agi`=`agi`+?, `res`=`res`+? where `id`=?", array(30, 40, 30, 40, $ringid));
		}

			$player = check_user($secret_key, $db); //Get new user stats
			include("templates/private_header.php");
			echo "<b>Ferreiro:</b><br />\n";
			echo "<i>Obrigado, aproveite sua nova <b>" . $item['name'] . "</b>!</i><br /><br />\n";
			echo "<a href=\"inventory.php\">Retornar ao inventário</a> | <a href=\"shop.php\">Retornar a loja</a>";
			include("templates/private_footer.php");
			break;

	case "sell":
			if (($_POST['comfirm']) and ($_POST['actione']) == 'vendeer'){
				include("templates/private_header.php");
					if (!$_POST['id'])
					{
					echo "Você precisa selecionar algum item para vender.<br/><a href=\"inventory.php\">Voltar</a>.";
					include("templates/private_footer.php");
					break;
					}
				$totalprico = 0;
				$totalsell = 0;
				echo "<form method=\"POST\" action=\"shop.php?act=sell\">\n";
				echo "<b>Deseja vender:</b><br/>";
				foreach($_POST['id'] as $msg)
				{
				$multipleitem = $db->execute("select items.id, items.item_id, items.item_bonus, items.status, items.mark, blueprint_items.name, blueprint_items.price, blueprint_items.type from `blueprint_items`, `items` where items.item_id=blueprint_items.id and items.player_id=? and items.id=?", array($player->id, $msg));
					if ($multipleitem->recordcount() == 0)
					{
					echo "Este item não te pertence.<br />";
					}else{
					$multisell = $multipleitem->fetchrow();
						if ($multisell['status'] == 'equipped'){
						echo "Você não pode vender um item que está em uso.<br />";
						}elseif ($multisell['type'] == 'stone'){
						echo "Você não pode vender pedras.<br />";
						}elseif (($multisell['item_id'] == 111) or ($multisell['item_id'] == 116)){
						echo "Você não pode vender este item, caso contrário não poderá terminar sua missão.<br />";
						}else{
						if ($multisell['item_bonus'] > 10) {
						$precodavenda = floor(($multisell['price']/2) + (($multisell['item_bonus']*$multisell['price'])/5) + 3000000);
						}else{
						$precodavenda = floor(($multisell['price']/2) + (($multisell['item_bonus']*$multisell['price'])/5));
						}

						if ($multisell['for'] == 0){
						$multisellfor = "";
						}else{
						$multisellfor = " +<font color=\"gray\">" . $multisell['for'] . "F</font>";
						}

						if ($multisell['vit'] == 0){
						$multisellvit = "";
						}else{
						$multisellvit = " +<font color=\"green\">" . $multisell['vit'] . "V</font>";
						}

						if ($multisell['agi'] == 0){
						$multisellagi = "";
						}else{
						$multisellagi = " +<font color=\"blue\">" . $multisell['agi'] . "A</font>";
						}

						if ($multisell['res'] == 0){
						$multisellres = "";
						}else{
						$multisellres = " +<font color=\"red\">" . $multisell['res'] . "R</font>";
						}
					echo "<b>1x</b> " . $multisell['name'] . " +" . $multisell['item_bonus'] . "" . $multisellfor . "" . $multisellvit . "" . $multisellagi . "" . $multisellres . " por " . $precodavenda . " de ouro.<br/>";
					echo "<input type=\"hidden\" name=\"id[]\" value=\"" . $multisell['id'] . "\" />\n";
					$totalprico += $precodavenda;
					$totalsell += 1;
					}
					}
				}
				if ($totalsell > 0){
				echo "<b>Vendendo:</b> " . $totalsell . " item(s) por " . $totalprico . " de ouro.<br/><br/><input type=\"submit\" name=\"multiconfirm\" value=\"Desejo vender todos estes itens\" />  <a href=\"inventory.php\">Voltar</a>.\n";
				echo "</form>\n";
				}
				include("templates/private_footer.php");
				break;
			}elseif (($_POST['comfirm']) and ($_POST['actione']) != 'vendeer'){
				include("templates/private_header.php");
				echo "Selecione uma ação.<br/><a href=\"inventory.php\">Voltar</a>.";
				include("templates/private_footer.php");
				break;
			}elseif ($_POST['multiconfirm']){
				include("templates/private_header.php");
				$totalprico2 = 0;
				foreach($_POST['id'] as $msg)
				{
					$multipleitem = $db->execute("select items.id, items.item_id, items.item_bonus, items.status, items.mark, blueprint_items.name, blueprint_items.price, blueprint_items.type from `blueprint_items`, `items` where items.item_id=blueprint_items.id and items.player_id=? and items.id=?", array($player->id, $msg));
					if ($multipleitem->recordcount() == 0)
					{
					echo "Este item não te pertence.<br />";
					}else{
					$multisell = $multipleitem->fetchrow();

						if ($multisell['status'] == 'equipped'){
						echo "Você não pode vender um item que está em uso.<br />";
						}elseif ($multisell['type'] == 'stone'){
						echo "Você não pode vender pedras.<br />";
						}elseif (($multisell['item_id'] == 111) or ($multisell['item_id'] == 116)){
						echo "Você não pode vender este item, caso contrário não poderá terminar sua missão.<br />";
						}else{
				if ($multisell['item_bonus'] > 10) {
				$precodavenda = floor(($multisell['price']/2) + (($multisell['item_bonus']*$multisell['price'])/5) + 3000000);
				}else{
				$precodavenda = floor(($multisell['price']/2) + (($multisell['item_bonus']*$multisell['price'])/5));
				}

				$totalprico2 += $precodavenda;

					if ($multisell['mark'] == 't'){
					$query = $db->execute("delete from `market` where `market_id`=?", array($msg));
					}
					$query = $db->execute("delete from `items` where `id`=?", array($msg));
						if ($multisell['for'] == 0){
						$multisellfor = "";
						}else{
						$multisellfor = " +<font color=\"gray\">" . $multisell['for'] . "F</font>";
						}

						if ($multisell['vit'] == 0){
						$multisellvit = "";
						}else{
						$multisellvit = " +<font color=\"green\">" . $multisell['vit'] . "V</font>";
						}

						if ($multisell['agi'] == 0){
						$multisellagi = "";
						}else{
						$multisellagi = " +<font color=\"blue\">" . $multisell['agi'] . "A</font>";
						}

						if ($multisell['res'] == 0){
						$multisellres = "";
						}else{
						$multisellres = " +<font color=\"red\">" . $multisell['res'] . "R</font>";
						}
					echo "Você vendeu seu/sua <b>" . $multisell['name'] . " +" . $multisell['item_bonus'] . "</b>" . $multisellfor . "" . $multisellvit . "" . $multisellagi . "" . $multisellres . " por <b>" . $precodavenda . "</b> de ouro.<br/>";
					}
					}
					}
					$query = $db->execute("update `players` set `gold`=? where `id`=?", array($player->gold + $totalprico2, $player->id));
					echo "<br/><a href=\"inventory.php\">Voltar</a>.";
				include("templates/private_footer.php");
				break;
		}else{
		
		if (!$_GET['id']) //No item ID
		{
			header("Location: shop.php");
			break;
		}

		//Select the item from the database
		$query = $db->execute("select items.id, items.item_id, items.item_bonus, items.status, items.mark, blueprint_items.name, blueprint_items.price, blueprint_items.type from `blueprint_items`, `items` where items.item_id=blueprint_items.id and items.player_id=? and items.id=?", array($player->id, $_GET['id']));
		
		//Either item doesn't exist, or item doesn't belong to user
		if ($query->recordcount() == 0)
		{
			include("templates/private_header.php");
			echo "Este item não existe!";
			echo "<a href=\"inventory.php\">Voltar</a>.";
			include("templates/private_footer.php");
			break;
		}
		
		$sell = $query->fetchrow(); //Get item info
		if ($sell['item_bonus'] > 10) {
		$valordavenda = floor(($sell['price']/2) + (($sell['item_bonus']*$sell['price'])/5) + 3000000);
		}else{
		$valordavenda = floor(($sell['price']/2) + (($sell['item_bonus']*$sell['price'])/5));
		}

		if (($sell['item_id'] == 111) or ($sell['item_id'] == 116)){
			include("templates/private_header.php");
			echo "Você não pode vender este item, caso contrário não poderá terminar sua missão.<br />\n";
			echo "<a href=\"inventory.php\">Voltar</a>.";
			include("templates/private_footer.php");
			break;
		}

		if ($sell['type'] == 'stone'){
			include("templates/private_header.php");
			echo "Você não pode vender pedras.<br />\n";
			echo "<a href=\"inventory.php\">Voltar</a>.";
			include("templates/private_footer.php");
			break;
		}

		if ($sell['status'] == 'equipped'){
			include("templates/private_header.php");
			echo "Você não pode vender um item que está em uso.<br />\n";
			echo "<a href=\"inventory.php\">Voltar</a>.";
			include("templates/private_footer.php");
			break;
		}
		
		//Check to make sure clicking Sell wasn't an accident
		if (!$_POST['sure'])
		{
			include("templates/private_header.php");
			echo "Você tem certeza que quer vender o/a <b>" . $sell['name'] . "</b> por <b>" . $valordavenda . "</b> de ouro?<br /><br />\n";
			echo "<form method=\"post\" action=\"shop.php?act=sell&id=" . $sell['id'] . "\">\n";
			echo "<input type=\"submit\" name=\"sure\" value=\"Sim, tenho certeza!\" />\n";
			echo "</form>\n";
			include("templates/private_footer.php");
			break;
		}
		
		//Delete item from database, add gold to player's account
			if ($sell['mark'] == 't'){
			$query = $db->execute("delete from `market` where `market_id`=?", array($sell['id']));
			}
		$query = $db->execute("delete from `items` where `id`=?", array($sell['id']));
		$query = $db->execute("update `players` set `gold`=? where `id`=?", array($player->gold + $valordavenda, $player->id));
		
		$player = check_user($secret_key, $db); //Get updated user info
		
		include("templates/private_header.php");
		echo "Você vendeu seu/sua <b>" . $sell['name'] . "</b> por <b>" . $valordavenda . "</b> de ouro.<br /><br />\n";
		echo "<a href=\"inventory.php\">Retornar ao inventário</a> | <a href=\"shop.php\">Retornar a loja</a>";
		include("templates/private_footer.php");
		}
		break;

	default:
		//Show search form
		include("templates/private_header.php");

		echo "<form method=\"GET\" action=\"shop.php\">\n";
		echo "<table width=\"100%\" class=\"brown\" style='border:1px solid #b6804e;height:28px;background:url(images/bg-barra-form.png) center;'><tr>";
			echo "<th width=\"35%\"><b>Procurar por:</b> <select name=\"type\">\n";

			if ((!$_GET['type']) or ($_GET['type'] == 'none')) {
				echo "<option value=\"none\" selected=\"selected\">Selecione</option>\n";
			} else {
				echo "<option value=\"none\">Selecione</option>\n";
			}

			if ($_GET['type'] == 'amulet') {
				echo "<option value=\"amulet\" selected=\"selected\">Amuletos</option>\n";
			} else {
				echo "<option value=\"amulet\">Amuletos</option>\n";
			}

			if ($_GET['type'] == 'weapon') {
				echo "<option value=\"weapon\" selected=\"selected\">Armas</option>\n";
			} else {
				echo "<option value=\"weapon\">Armas</option>\n";
			}

			if ($_GET['type'] == 'armor') {
				echo "<option value=\"armor\" selected=\"selected\">Armaduras</option>\n";
			} else {
				echo "<option value=\"armor\">Armaduras</option>\n";
			}

			if ($_GET['type'] == 'boots') {
				echo "<option value=\"boots\" selected=\"selected\">Botas</option>\n";
			} else {
				echo "<option value=\"boots\">Botas</option>\n";
			}

			if ($_GET['type'] == 'legs') {
				echo "<option value=\"legs\" selected=\"selected\">Calças</option>\n";
			} else {
				echo "<option value=\"legs\">Calças</option>\n";
			}

			if ($_GET['type'] == 'helmet') {
				echo "<option value=\"helmet\" selected=\"selected\">Elmos</option>\n";
			} else {
				echo "<option value=\"helmet\">Elmos</option>\n";
			}

			if ($_GET['type'] == 'shield') {
				echo "<option value=\"shield\" selected=\"selected\">Escudos</option>\n";
			} else {
				echo "<option value=\"shield\">Escudos</option>\n";
			}

			echo "</select></th>";
			echo "<th width=\"35%\">Preço de: <input type=\"text\" name=\"fromprice\" size=\"4\" value=\"" . stripslashes($_GET['fromprice']) . "\" /> Ã  <input type=\"text\" name=\"toprice\" size=\"5\" value=\"" . stripslashes($_GET['toprice']) . "\" /></th>";

			echo "<th width=\"30%\" align=\"right\"><input  id=\"link\" class=\"neg\" type=\"submit\" value=\"Procurar\" /></th>";
		echo "</tr></table>";
		echo "</form>";

		if (($_GET['type'] == 'armor') or ($_GET['type'] == 'boots') or ($_GET['type'] == 'helmet') or ($_GET['type'] == 'legs') or (($_GET['type'] == 'shield') and ($player->voc != 'archer')) or ($_GET['type'] == 'weapon') or ($_GET['type'] == 'amulet')) {
		$query = "select `id`, `name`, `description`, `type`, `price`, `effectiveness`, `img`, `needpromo`, `needlvl` from `blueprint_items` where ";
		$query .= ($_GET['name'] != "")?"`name` LIKE  ? and ":"";
		$query .= ($_GET['fromprice'] != "")?"`price` >= ? and ":"";
		$query .= ($_GET['toprice'] != "")?"`price` <= ? and ":"";
		$query .= ($_GET['fromeffect'] != "")?"`effectiveness` >= ? and ":"";
		$query .= ($_GET['toeffect'] != "")?"`effectiveness` <= ? and ":"";

		if ($player->voc == 'archer') {
			$voc = 1;
		} elseif ($player->voc == 'knight') {
			$voc = 2;
		} else {
			$voc = 3;
		}
		
		$query .= "`type`='" . $_GET['type'] . "' and `canbuy`='t' and (`voc`=" . $voc . " or `voc`=0) and `needlvl`<" . ($player->level + 10) . " order by `needlvl` asc";
		
		//Construct values array for adoDB
		$values = array();
		if ($_GET['name'] != "")
		{
			array_push($values, "%".trim($_GET['name'])."%");
		}
		if ($_GET['fromprice'])
		{
			array_push($values, intval($_GET['fromprice']));
		}
		if ($_GET['toprice'])
		{
			array_push($values, intval($_GET['toprice']));
		}
		if ($_GET['fromeffect'])
		{
			array_push($values, intval($_GET['fromeffect']));
		}
		if ($_GET['toeffect'])
		{
			array_push($values, intval($_GET['toeffect']));
		}
		
		$query = $db->execute($query, $values);

		echo showAlert("<i>Você pode comprar items de nível " . ($player->level + 10) . " ou menos.</i>");

			while($item = $query->fetchrow()) {
				echo "<fieldset>\n";
				echo "<legend><b>" . $item['name'] . "</b></legend>\n";
				echo "<table width=\"100%\">\n";
				echo "<tr><td width=\"5%\">";
				echo "<img src=\"images/itens/" . $item['img'] . "\"/>";
				echo "</td><td width=\"75%\">";
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
				echo "</td><td width=\"20%\">";

				if (($player->reino == '1') or ($player->vip > time())) {
					echo "<b>Preço:</b> " . ceil($item['price'] * 0.9) . "<br />";
				} else {
					echo "<b>Preço:</b> " . $item['price'] . "<br />";
				}
				echo "<a href=\"shop.php?act=buy&id=" . $item['id'] . "\">Comprar</a><br />";
				echo "</td></tr>\n";

				if ($item['needlvl'] > 1){
					if ($player->level < $item['needlvl']) {
						echo "<table style=\"width:100%; background-color:#EEA2A2;\"><tr><td><center><b>Você precisa ter nivel " . $item['needlvl'] . " ou mais para usar este item.</b></center></td></tr>\n";
					}else{
					echo "<table style=\"width:100%; background-color:#BDF0A6;\"><tr><td><center><b>Você precisa ter nivel " . $item['needlvl'] . " ou mais para usar este item.</b></center></td></tr>\n";
					}
				}
				if ($item['needpromo'] == "t"){
					if ($player->promoted != "f") {
						echo "<table style=\"width:100%; background-color:#BDF0A6;\"><tr><td><center><b>Você precisa ter uma vocação superior para usar este item.</b></center></td></tr>\n";
					}else{
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

		} elseif (($_GET['type'] == 'shield') and ($player->voc == 'archer')) {
			echo "<br/><p><i><center>Arqueiros não podem usar/comprar escudos.</center></i></p>";
		} else {
			echo "<br/><p><i><center>Selecione o tipo de item que você deseja procurar.</center></i></p>";
		}

		include("templates/private_footer.php");
		break;
}
?>
