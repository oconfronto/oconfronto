<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Mercado");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkhp.php");
include(__DIR__ . "/checkwork.php");

switch ($_GET['act'] ?? null) {
	case "confirm": {

			if (!($_POST['market_id'] ?? null)) {
				include(__DIR__ . "/templates/private_header.php");
				echo 'Um erro desconhecido ocorreu. <a href="market.php">Voltar</a>.';
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			$market_id = $_POST['market_id'];
			$seleciona_market = $db->execute("select market.price, market.seller, market.serv, blueprint_items.id, blueprint_items.name, items.item_bonus, items.for, items.vit, items.agi, items.res from `market`, `blueprint_items`, `items` where market.ite_id=blueprint_items.id and market.market_id=items.id and market.market_id=?", [$_POST['market_id'] ?? null]);

			if ($seleciona_market->recordcount() == 0) {
				include(__DIR__ . "/templates/private_header.php");
				echo 'Um erro desconhecido ocorreu. <a href="market.php">Voltar</a>.';
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			$seleciona_market = $seleciona_market->fetchrow();

			if (($seleciona_market['serv'] ?? null) != $player->serv) {
				include(__DIR__ . "/templates/private_header.php");
				echo "Você não pode comprar itens de outro servidor. <a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				exit;
			}

			if (($seleciona_market['seller'] ?? null) == $player->username) {
				include(__DIR__ . "/templates/private_header.php");
				echo "Você não pode comprar seus própios itens. <a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				exit;
			}

			if (($seleciona_market['price'] ?? null) > $player->gold) {
				include(__DIR__ . "/templates/private_header.php");
				echo "Você não possui dinheiro suficiente. <a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				exit;
			}


			$seleciona_seller = $db->execute("select `id` from `players` where `username`=?", [$seleciona_market['seller'] ?? null]);
			if ($seleciona_seller->recordcount() == 0) {
				include(__DIR__ . "/templates/private_header.php");
				echo 'Erro no mercado. Contate o administrador sobre esse erro. <a href="market.php">Voltar</a>.';
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}


			$query_switch = $db->execute("update `items` set `player_id`=?, `status`='unequipped' where `id`=?", [$player->id, $market_id]);
			$query_buyer_gold = $db->execute("update `players` set `gold`=? where `id`=?", [$player->gold - $seleciona_market['price'], $player->id]);

			$seleciona_seller = $seleciona_seller->fetchrow();
			$query_seller_gold = $db->execute("update `players` set `bank`=`bank`+? where `id`=?", [$seleciona_market['price'] ?? null, $seleciona_seller['id'] ?? null]);

			$logmsg = "Você vendeu um iten no mercado. <a href=\"profile.php?id=" . $player->username . '">' . $player->username . "</a> comprou seu/sua " . $seleciona_market['name'] . " e você ganhou " . $seleciona_market['price'] . " de ouro.";
			addlog($seleciona_seller['id'], $logmsg, $db);

			$query_delete = $db->execute("delete from `market` where `market_id`=?", [$market_id]);
			$mark_sold = $db->execute("update `items` set `mark`='f' where `id`=?", [$market_id]);

			$insert['player_id'] = $player->id;
			$insert['name1'] = $player->username;
			$insert['name2'] = $seleciona_market['seller'];
			$insert['action'] = "comprou";
			$insert['value'] = "um(a) <b>" . $seleciona_market['name'] . " +" . $seleciona_market['item_bonus'] . "</b>";
			$insert['itemid'] = $market_id;
			$insert['blue_id'] = $seleciona_market['id'];
			$insert['aditional'] = " por " . $seleciona_market['price'] . " de ouro";
			$insert['time'] = time();
			$query = $db->autoexecute('log_item', $insert, 'INSERT');

			$insert['player_id'] = $seleciona_seller['id'];
			$insert['name1'] = $seleciona_market['seller'];
			$insert['name2'] = $player->username;
			$insert['action'] = "vendeu";
			$insert['value'] = "um(a) <b>" . $seleciona_market['name'] . " +" . $seleciona_market['item_bonus'] . "</b>";
			$insert['itemid'] = $market_id;
			$insert['blue_id'] = $seleciona_market['id'];
			$insert['aditional'] = " por " . $seleciona_market['price'] . " de ouro";
			$insert['time'] = time();
			$query = $db->autoexecute('log_item', $insert, 'INSERT');


			$player = check_user($db);
			include(__DIR__ . "/templates/private_header.php");
			echo 'Obrigado por comprar. <a href="market.php">Voltar</a>.';
			include(__DIR__ . "/templates/private_footer.php");
		}
		break;



	case "buy": {

			if (!($_GET['item'] ?? null)) {
				include(__DIR__ . "/templates/private_header.php");
				echo 'Um erro desconhecido ocorreu. <a href="market.php">Voltar</a>.';
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			$market_id = $_GET['item'];

			$query_market = $db->execute("select market.market_id, market.price, market.seller, blueprint_items.name, blueprint_items.type, blueprint_items.effectiveness, blueprint_items.img, blueprint_items.description, blueprint_items.needpromo, blueprint_items.needlvl, blueprint_items.voc, items.item_bonus, items.for, items.vit, items.agi, items.res from `market`, `blueprint_items`, `items` where market.ite_id=blueprint_items.id and market.market_id=items.id and market.market_id=?", [$market_id]);
			while ($market = $query_market->fetchrow()) {

				include(__DIR__ . "/templates/private_header.php");
				echo '<form method="POST" action="market_buy.php?act=confirm">';
				echo "<b>Você gostaria de comprar:</b><br />";
				$marketataque = $market['effectiveness'] + ($market['item_bonus'] * 2);
				echo "<fieldset>\n<legend>";

				if (($market['for'] ?? null) == 0) {
					$marketfor = "";
					$marketfor2 = "";
				} else {
					$marketfor = ' +<font color="gray">' . $market['for'] . "F</font>";
					$marketfor2 = '+<font color="gray">' . $market['for'] . " <b>For</b></font><br/>";
				}

				if (($market['vit'] ?? null) == 0) {
					$marketvit = "";
					$marketvit2 = "";
				} else {
					$marketvit = ' +<font color="green">' . $market['vit'] . "V</font>";
					$marketvit2 = '+<font color="green">' . $market['vit'] . " <b>Vit</b></font><br/>";
				}

				if (($market['agi'] ?? null) == 0) {
					$marketagi = "";
					$marketagi2 = "";
				} else {
					$marketagi = ' +<font color="blue">' . $market['agi'] . "A</font>";
					$marketagi2 = '+<font color="blue">' . $market['agi'] . " <b>Agi</b></font><br/>";
				}

				if (($market['res'] ?? null) == 0) {
					$marketres = "";
					$marketres2 = "";
				} else {
					$marketres = ' +<font color="red">' . $market['res'] . "R</font>";
					$marketres2 = '+<font color="red">' . $market['res'] . " <b>Res</b></font>";
				}


				echo "<b>" . $market['name'] . " +" . $market['item_bonus'] . "" . $marketfor . "" . $marketvit . "" . $marketagi . "" . $marketres . "</b></legend>\n";

				if (($market['item_bonus'] ?? null) > 2 && ($market['item_bonus'] ?? null) < 6) {
					echo "<table width=\"100%\" bgcolor=\"#ECD1BC\">\n";
				} elseif (($market['item_bonus'] ?? null) > 5 && ($market['item_bonus'] ?? null) < 9) {
					echo "<table width=\"100%\" bgcolor=\"#F2D0C7\">\n";
				} elseif (($market['item_bonus'] ?? null) == 9) {
					echo "<table width=\"100%\" bgcolor=\"#EEB0B1\">\n";
				} elseif (($market['item_bonus'] ?? null) > 9) {
					echo "<table width=\"100%\" bgcolor=\"#D9C6D9\">\n";
				} else {
					echo "<table width=\"100%\">\n";
				}

				echo '<tr><td width="5%">';
				echo '<img src="static/images/itens/' . $market['img'] . '"/>';
				echo '</td><td width="80%">';
				echo $market['description'] . "\n<br />";
				if (($market['type'] ?? null) == 'ring' || ($market['type'] ?? null) == 'potion' || ($market['type'] ?? null) == 'addon') {
					echo "<b>Vocação:</b> ";
				} elseif (($market['type'] ?? null) == 'amulet') {
					echo "<b>Vitalidade:</b> " . $marketataque . " <b>Vocação:</b> ";
				} elseif (($market['type'] ?? null) == 'weapon') {
					echo "<b>Ataque:</b> " . $marketataque . " <b>Vocação:</b> ";
				} elseif (($market['type'] ?? null) == 'boots') {
					echo "<b>Agilidade:</b> " . $marketataque . " <b>Vocação:</b> ";
				} else {
					echo "<b>Defesa:</b> " . $marketataque . " <b>Vocação:</b> ";
				}

				if (($market['voc'] ?? null) == 1 && ($market['needpromo'] ?? null) == 'f') {
					echo "Caçador";
				} elseif (($market['voc'] ?? null) == 2 && ($market['needpromo'] ?? null) == 'f') {
					echo "Espadachim";
				} elseif (($market['voc'] ?? null) == 3 && ($market['needpromo'] ?? null) == 'f') {
					echo "Bruxo";
				} elseif (($market['voc'] ?? null) == 1 && ($market['needpromo'] ?? null) == 't') {
					echo "Arqueiro";
				} elseif (($market['voc'] ?? null) == 2 && ($market['needpromo'] ?? null) == 't') {
					echo "Guerreiro";
				} elseif (($market['voc'] ?? null) == 3 && ($market['needpromo'] ?? null) == 't') {
					echo "Mago";
				} elseif (($market['voc'] ?? null) == 0 && ($market['needpromo'] ?? null) == 't') {
					echo "Vocações superiores";
				} elseif (($market['voc'] ?? null) == 1 && ($market['needpromo'] ?? null) == 'p') {
					echo "Arqueiro Royal";
				} elseif (($market['voc'] ?? null) == 2 && ($market['needpromo'] ?? null) == 'p') {
					echo "Cavaleiro";
				} elseif (($market['voc'] ?? null) == 3 && ($market['needpromo'] ?? null) == 'p') {
					echo "Arquimago";
				} elseif (($market['voc'] ?? null) == 0 && ($market['needpromo'] ?? null) == 'p') {
					echo "Vocações supremas";
				} else {
					echo "Todas";
					if (($market['type'] ?? null) == 'shield') {
						echo ' <font size="1">(exceto arqueiros)</font>';
					}
				}

				echo ' <b>Vendedor:</b> <a href="profile.php?id=' . $market['seller'] . '">' . $market['seller'] . "</a>";

				echo '</td><td width="15%">';
				if (($market['type'] ?? null) != 'ring' && ($market['type'] ?? null) != 'potion' && ($market['type'] ?? null) != 'addon') {
					echo "" . $marketfor2 . "" . $marketvit2 . "" . $marketagi2 . "" . $marketres2 . "";
				}

				echo "</td>\n";



				echo "</td></tr>\n";
				if (($market['needlvl'] ?? null) > 1) {
					if ($player->level < ($market['needlvl'] ?? null)) {
						echo "<table style=\"width:100%; background-color:#EEA2A2;\"><tr><td><center><b>Você precisa ter nível " . $market['needlvl'] . " ou mais para usar este item.</b></center></td></tr>\n";
					} else {
						echo "<table style=\"width:100%; background-color:#BDF0A6;\"><tr><td><center><b>Você precisa ter nível " . $market['needlvl'] . " ou mais para usar este item.</b></center></td></tr>\n";
					}
				}

				if (($market['needpromo'] ?? null) == "t") {
					if ($player->promoted != "f") {
						echo "<table style=\"width:100%; background-color:#BDF0A6;\"><tr><td><center><b>Você precisa ter uma vocação superior para usar este item.</b></center></td></tr>\n";
					} else {
						echo "<table style=\"width:100%; background-color:#EEA2A2;\"><tr><td><center><b>Você precisa ter uma vocação superior para usar este item.</b></center></td></tr>\n";
					}
				}

				if (($market['needpromo'] ?? null) == "p") {
					if ($player->promoted == "p") {
						echo "<table style=\"width:100%; background-color:#BDF0A6;\"><tr><td><center><b>Você precisa ter uma vocação suprema para usar este item.</b></center></td></tr>\n";
					} else {
						echo "<table style=\"width:100%; background-color:#EEA2A2;\"><tr><td><center><b>Você precisa ter uma vocação suprema para usar este item.</b></center></td></tr>\n";
					}
				}

				echo "</table>";
				echo "</fieldset>\n";
				echo "<b>Por apenas:</b> " . $market['price'] . " de ouro?<br /><br />";
				echo '<input type="hidden" name="act" value="confirm">';
				echo '<input type="hidden" name="market_id" value="' . $market['market_id'] . '">';
				echo '<center><input type="submit" value="Sim, eu quero comprar este item."></center></form>';

				include(__DIR__ . "/templates/private_footer.php");
			}
		}
		break;
}
