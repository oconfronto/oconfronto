<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Mercado");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkhp.php");
include(__DIR__ . "/checkwork.php");

switch ($_GET['act']) {
	case "remove":
		if (!$_GET['item']) {
			include(__DIR__ . "/templates/private_header.php");
			echo 'Um erro desconhecido ocorreu.<br/><a href="market.php">Voltar</a>.';
			include(__DIR__ . "/templates/private_footer.php");
			break;
		}

		$verifik = $db->execute("select market.seller, blueprint_items.name, items.item_bonus, items.mark from `market`, `blueprint_items`, `items` where market.ite_id=blueprint_items.id and market.market_id=items.id and market.market_id=?", [$_GET['item']]);
		if ($verifik->recordcount() == 0) {
			include(__DIR__ . "/templates/private_header.php");
			echo 'Um erro desconhecido ocorreu.<br/><a href="market.php">Voltar</a>.';
			include(__DIR__ . "/templates/private_footer.php");
			break;
		}

		$item = $verifik->fetchrow();

		if ($item['seller'] != $player->username) {
			include(__DIR__ . "/templates/private_header.php");
			echo "Você não pode remover este item do mercado.<br/><a href=\"market.php\">Voltar</a>.";
			include(__DIR__ . "/templates/private_footer.php");
			break;
		}

		if (!$_GET['confirm']) {
			include(__DIR__ . "/templates/private_header.php");
			echo "Tem certeza que seseja remover seu item do mercado? (" . $item['name'] . ')<br/><a href="market.php?act=remove&item=' . $_GET['item'] . '&confirm=yes">Sim</a> | <a href="market.php">Voltar</a>.';
			include(__DIR__ . "/templates/private_footer.php");
		} else {
			$mark_sold = $db->execute("update `items` set `mark`='f' where `id`=?", [$_GET['item']]);
			$query_delete = $db->execute("delete from `market` where `market_id`=?", [$_GET['item']]);
			include(__DIR__ . "/templates/private_header.php");
			echo "Você removeu seu item do mercado<br/><a href=\"market.php\">Voltar</a>.";
			include(__DIR__ . "/templates/private_footer.php");
		}

		break;


	default:
		//Show search form		
		include(__DIR__ . "/templates/private_header.php");
		echo "<form method=\"GET\" action=\"market.php\">\n";
		echo "<table width=\"100%\" class=\"brown\" style='border:1px solid #b6804e;height:28px;'><tr>";
		echo "<th width=\"35%\"><b>Procurar por:</b> <select name=\"type\">\n";

		if (!$_GET['type'] || $_GET['type'] == 'none') {
			echo "<option value=\"none\" selected=\"selected\">Selecione</option>\n";
		} else {
			echo "<option value=\"none\">Selecione</option>\n";
		}

		if ($_GET['type'] == 'amulet') {
			echo "<option value=\"amulet\" selected=\"selected\">Amuletos</option>\n";
		} else {
			echo "<option value=\"amulet\">Amuletos</option>\n";
		}

		if ($_GET['type'] == 'ring') {
			echo "<option value=\"ring\" selected=\"selected\">Anéis</option>\n";
		} else {
			echo "<option value=\"ring\">Anéis</option>\n";
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

		if ($_GET['type'] == 'potion') {
			echo "<option value=\"potion\" selected=\"selected\">Poções</option>\n";
		} else {
			echo "<option value=\"potion\">Poções</option>\n";
		}

		if ($_GET['type'] == 'addon') {
			echo "<option value=\"addon\" selected=\"selected\">Extras</option>\n";
		} else {
			echo "<option value=\"addon\">Extras</option>\n";
		}

		echo "</select></th>";

		echo "<th width=\"35%\">Ordenar por: <select name=\"orderby\">\n";

		if (!$_GET['orderby'] || $_GET['orderby'] == 'none') {
			echo "<option value=\"none\" selected=\"selected\">Selecione</option>\n";
		} else {
			echo "<option value=\"none\">Selecione</option>\n";
		}

		if ($_GET['orderby'] == 'nome') {
			echo "<option value=\"nome\" selected=\"selected\">Nome</option>\n";
		} else {
			echo "<option value=\"nome\">Nome</option>\n";
		}

		if ($_GET['orderby'] == 'preco') {
			echo "<option value=\"preco\" selected=\"selected\">Preço</option>\n";
		} else {
			echo "<option value=\"preco\">Preço</option>\n";
		}

		if ($_GET['orderby'] == 'efetividade') {
			echo "<option value=\"efetividade\" selected=\"selected\">Atributos</option>\n";
		} else {
			echo "<option value=\"efetividade\">Atributos</option>\n";
		}

		if ($_GET['orderby'] == 'vocacao') {
			echo "<option value=\"vocacao\" selected=\"selected\">Vocação</option>\n";
		} else {
			echo "<option value=\"vocacao\">Vocação</option>\n";
		}

		echo "</select></th>";


		echo '<th width="30%" align="right"><input  id="link" class="neg" type="submit" value="Procurar" /></th>';
		echo "</tr></table>";
		echo "</form>";
		echo showAlert('<i>Deseja vender algum item? <a href="market_sell.php"><b>Clique aqui</b></a>.</i>', "white", "left");
		echo "<br/>";

		if ($_GET['type'] == 'armor' || $_GET['type'] == 'boots' || $_GET['type'] == 'helmet' || $_GET['type'] == 'legs' || $_GET['type'] == 'shield' && $player->voc != 'archer' || $_GET['type'] == 'weapon' || $_GET['type'] == 'amulet' || $_GET['type'] == 'potion' || $_GET['type'] == 'ring' || $_GET['type'] == 'addon') {

			if ($_GET['orderby'] == 'nome') {
				$orderby = "blueprint_items.name";
			} elseif ($_GET['orderby'] == 'preco') {
				$orderby = "market.price";
			} elseif ($_GET['orderby'] == 'efetividade') {
				$orderby = "blueprint_items.effectiveness";
			} elseif ($_GET['orderby'] == 'vocacao') {
				$orderby = "blueprint_items.voc";
			} else {
				$orderby = "market.price";
			}

			$sort = $_GET['sort'] == 'desc' ? "desc" : "asc";

			$filtrobusca = "" . $orderby . " " . $sort . "";

			$query = $db->execute("select market.market_id, market.price, market.seller, blueprint_items.name, blueprint_items.effectiveness, blueprint_items.needpromo, blueprint_items.needring, blueprint_items.voc, blueprint_items.img, items.item_bonus, items.for, items.vit, items.agi, items.res from `market`, `blueprint_items`, `items` where market.ite_id=blueprint_items.id and market.market_id=items.id and blueprint_items.type=? and market.serv=? order by " . $filtrobusca . "", [$_GET['type'], $player->serv, $orderby]);
			if ($query->recordcount() == 0) {
				echo "<p><i><center>Nenhum item encontrado! Tente procurar por outra coisa.</center></i></p>";
			} else {

				if ($_GET['type'] == 'amulet') {
					$type = "Vitalidade";
				} elseif ($_GET['type'] == 'weapon') {
					$type = "Ataque";
				} elseif ($_GET['type'] == 'boots') {
					$type = "Agilidade";
				} elseif ($_GET['type'] == 'potion') {
					$type = "Vendedor";
				} elseif ($_GET['type'] == 'ring') {
					$type = "Vendedor";
				} elseif ($_GET['type'] == 'addon') {
					$type = "Vendedor";
				} else {
					$type = "Defesa";
				}

				$linksort = $sort === "asc" ? "desc" : "asc";

				$btnsort = 0;

				echo "<fieldset>";
				echo "<table style='width:100%;border-collapse: collapse;'>";
				echo "<thead>";
				echo "<tr>";
				echo "<th style='width:5%;text-align: center;'>Imagem</td>";
				echo "<th style='width:50%;text-align: center;'><a href=\"market.php?type=" . $_GET['type'] . "&orderby=nome&sort=" . $linksort . '">Item</a> ';
				if ($_GET['orderby'] == 'nome') {
					$btnsort = 1;
					echo $sort;
				}
				echo "</th>";
				echo "<th style='width:10%;text-align: center;'><a href=\"market.php?type=" . $_GET['type'] . "&orderby=efetividade&sort=" . $linksort . '">' . $type . "</a> ";
				if ($_GET['orderby'] == 'efetividade') {
					$btnsort = 1;
					echo $sort;
				}
				echo "</th>";
				echo "<th style='width:15%;text-align: center;'><a href=\"market.php?type=" . $_GET['type'] . "&orderby=preco&sort=" . $linksort . "\">Preço</a> ";
				if ($_GET['orderby'] == 'preco' || $btnsort == 0 && $_GET['orderby'] != 'vocacao') {
					$btnsort = 1;
					echo $sort;
				}
				echo "</th>";
				echo "<th style='width:15%;text-align: center;'><a href=\"market.php?type=" . $_GET['type'] . "&orderby=vocacao&sort=" . $linksort . "\">Vocação</a> ";
				if ($_GET['orderby'] == 'vocacao') {
					$btnsort = 1;
					echo $sort;
				}
				echo "</th>";
				echo "<th style='width:5%;text-align: center;'>Ação</td>";
				echo "</tr>";
				echo "<tbody>";

				$bool = 1;
				while ($item = $query->fetchrow()) {
					$bonus1 = $item['item_bonus'] > 0 ? " (+" . $item['item_bonus'] . ")" : "";

					$bonus2 = $item['for'] > 0 ? ' <font color="gray">+' . $item['for'] . "F</font>" : "";

					$bonus3 = $item['vit'] > 0 ? ' <font color="green">+' . $item['vit'] . "V</font>" : "";

					$bonus4 = $item['agi'] > 0 ? ' <font color="blue">+' . $item['agi'] . "A</font>" : "";

					$bonus5 = $item['res'] > 0 ? ' <font color="red">+' . $item['res'] . "R</font>" : "";
					echo '<tr class="row' . $bool . '">';

					echo sprintf("<td style='text-align: center;padding:10px;border:1px solid #B9892F;vertical-align: middle;'><img src=\"static/images/itens/%s\" alt=\"%s\"></td>", $item['img'], $item['name']);
					echo "<td style='text-align: center;padding:10px;border:1px solid #B9892F;vertical-align: middle;'>" . $item['name'] . " " . $bonus1 . "" . $bonus2 . "" . $bonus3 . "" . $bonus4 . "" . $bonus5 . "</td>";

					if ($_GET['type'] == 'potion' || $_GET['type'] == 'ring' || $_GET['type'] == 'addon') {
						echo "<td style='text-align: center;padding:10px;border:1px solid #B9892F;vertical-align: middle;'>" . showName($item['seller'], $db) . "</td>";
					} else {
						echo "<td style='text-align: center;padding:10px;border:1px solid #B9892F;vertical-align: middle;'>" . ($item['effectiveness'] + ($item['item_bonus'] * 2)) . "</td>";
					}

					echo "<td style='text-align: center;padding:10px;border:1px solid #B9892F;vertical-align: middle;'>" . $item['price'] . "</td>";
					echo "<td style='text-align: center;padding:10px;border:1px solid #B9892F;vertical-align: middle;'>";

					if ($item['voc'] == 1 && $item['needpromo'] == 'f') {
						echo "Caçador";
					} elseif ($item['voc'] == 2 && $item['needpromo'] == 'f') {
						echo "Espadachim";
					} elseif ($item['voc'] == 3 && $item['needpromo'] == 'f') {
						echo "Bruxo";
					} elseif ($item['voc'] == 1 && $item['needpromo'] == 't') {
						echo "Arqueiro";
					} elseif ($item['voc'] == 2 && $item['needpromo'] == 't') {
						echo "Guerreiro";
					} elseif ($item['voc'] == 3 && $item['needpromo'] == 't') {
						echo "Mago";
					} elseif ($item['voc'] == 0 && $item['needpromo'] == 't') {
						echo "Vocações superiores";
					} elseif ($item['voc'] == 1 && $item['needpromo'] == 'p') {
						echo "Arqueiro Royal";
					} elseif ($item['voc'] == 2 && $item['needpromo'] == 'p') {
						echo "Cavaleiro";
					} elseif ($item['voc'] == 3 && $item['needpromo'] == 'p') {
						echo "Arquimago";
					} elseif ($item['voc'] == 0 && $item['needpromo'] == 'p') {
						echo "Vocações supremas";
					} else {
						echo "Todas";
					}

					echo "</td>";

					if ($item['seller'] == $player->username) {
						echo "<td style='text-align: center;padding:10px;border:1px solid #B9892F;vertical-align: middle;'><a href=\"market.php?act=remove&item=" . $item['market_id'] . "\">Remover</a></td>\n";
					} else {
						echo "<td style='text-align: center;padding:10px;border:1px solid #B9892F;vertical-align: middle;'><a href=\"market_buy.php?act=buy&item=" . $item['market_id'] . "\">Comprar</a></td>\n";
					}

					$bool = ($bool == 1) ? 2 : 1;
					echo "</tr>";
				}

				echo "</tbody></table></fieldset>";
			}
		} else {
			echo "<p><i><center>Selecione o tipo de item que você deseja procurar.</center></i></p>";
		}

		include(__DIR__ . "/templates/private_footer.php");
		break;
}
