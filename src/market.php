<?php
include("lib.php");
define("PAGENAME", "Mercado");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkhp.php");
include("checkwork.php");

switch($_GET['act'])
{
	case "remove":
		if (!$_GET['item']){
		include("templates/private_header.php");
		echo "Um erro desconhecido ocorreu.<br/><a href=\"market.php\">Voltar</a>.";
		include("templates/private_footer.php");
		break;
		}

		$verifik = $db->execute("select market.seller, blueprint_items.name, items.item_bonus, items.mark from `market`, `blueprint_items`, `items` where market.ite_id=blueprint_items.id and market.market_id=items.id and market.market_id=?", array($_GET['item']));
		if ($verifik->recordcount() == 0)
		{
		include("templates/private_header.php");
		echo "Um erro desconhecido ocorreu.<br/><a href=\"market.php\">Voltar</a>.";
		include("templates/private_footer.php");
		break;
		}

		$item = $verifik->fetchrow();

		if ($item['seller'] != $player->username){
		include("templates/private_header.php");
		echo "Você não pode remover este item do mercado.<br/><a href=\"market.php\">Voltar</a>.";
		include("templates/private_footer.php");
		break;
		}

		if (!$_GET['confirm']){
		include("templates/private_header.php");
		echo "Tem certeza que seseja remover seu item do mercado? (" . $item['name'] . ")<br/><a href=\"market.php?act=remove&item=" . $_GET['item'] . "&confirm=yes\">Sim</a> | <a href=\"market.php\">Voltar</a>.";
		include("templates/private_footer.php");
		}else{
		$mark_sold=$db->execute("update `items` set `mark`='f' where `id`=?", array($_GET['item']));
		$query_delete=$db->execute("delete from `market` where `market_id`=?", array($_GET['item']));
		include("templates/private_header.php");
		echo "Você removeu seu item do mercado<br/><a href=\"market.php\">Voltar</a>.";
		include("templates/private_footer.php");
		}
	break;


	default:
		//Show search form
		include("templates/private_header.php");
		echo "<form method=\"GET\" action=\"market.php\">\n";
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
        
            if ($_GET['type'] == 'ring') {
                echo "<option value=\"ring\" selected=\"selected\">AnŽis</option>\n";
            } else {
                echo "<option value=\"ring\">AnŽis</option>\n";
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

			if ((!$_GET['orderby']) or ($_GET['orderby'] == 'none')) {
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


			echo "<th width=\"30%\" align=\"right\"><input  id=\"link\" class=\"neg\" type=\"submit\" value=\"Procurar\" /></th>";
			echo "</tr></table>";
			echo "</form>";
		echo showAlert("<i>Deseja vender algum item? <a href=\"market_sell.php\"><b>Clique aqui</b></a>.</i>", "white", "left");
		echo "<br/>";

		if (($_GET['type'] == 'armor') or ($_GET['type'] == 'boots') or ($_GET['type'] == 'helmet') or ($_GET['type'] == 'legs') or (($_GET['type'] == 'shield') and ($player->voc != 'archer')) or ($_GET['type'] == 'weapon') or ($_GET['type'] == 'amulet') or ($_GET['type'] == 'potion') or ($_GET['type'] == 'ring') or ($_GET['type'] == 'addon')) {

			if ($_GET['orderby'] == 'nome') {
				$orderby = "blueprint_items.name";
			} elseif ($_GET['orderby'] == 'preco') {
				$orderby = "market.price";
			} elseif ($_GET['orderby'] == 'efetividade') {
				$orderby = "blueprint_items.effectiveness";
			} elseif ($_GET['orderby'] == 'vocacao') {
				$orderby = "blueprint_items.voc";
			}else{
				$orderby = "market.price";
			}
			
			if ($_GET['sort'] == 'desc') {
				$sort = "desc";
			} else {
				$sort = "asc";
			}
			
			$filtrobusca = "" . $orderby . " " . $sort . "";

		$query = $db->execute("select market.market_id, market.price, market.seller, blueprint_items.name, blueprint_items.effectiveness, blueprint_items.needpromo, blueprint_items.needring, blueprint_items.voc, items.item_bonus, items.for, items.vit, items.agi, items.res from `market`, `blueprint_items`, `items` where market.ite_id=blueprint_items.id and market.market_id=items.id and blueprint_items.type=? and market.serv=? order by " . $filtrobusca . "", array($_GET['type'], $player->serv, $orderby));
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
				
			if ($sort == "asc") {
				$linksort = "desc";
			} else {
				$linksort = "asc";
			}

			$btnsort = 0;
			echo "<table width=\"100%\" border=\"0\">";
			echo "<tr>";
			echo "<th width=\"40%\"><a href=\"market.php?type=" . $_GET['type'] . "&orderby=nome&sort=" . $linksort . "\">Item</a> "; if ($_GET['orderby'] == 'nome') { $btnsort = 1; echo $sort; } echo "</td>";
			echo "<th width=\"15%\"><a href=\"market.php?type=" . $_GET['type'] . "&orderby=efetividade&sort=" . $linksort . "\">" . $type . "</a> "; if ($_GET['orderby'] == 'efetividade') { $btnsort = 1; echo $sort; } echo "</td>";
			echo "<th width=\"15%\"><a href=\"market.php?type=" . $_GET['type'] . "&orderby=preco&sort=" . $linksort . "\">Preço</a> "; if (($_GET['orderby'] == 'preco') or (($btnsort == 0) and ($_GET['orderby'] != 'vocacao'))) { $btnsort = 1; echo $sort; } echo "</td>";
			echo "<th width=\"20%\"><a href=\"market.php?type=" . $_GET['type'] . "&orderby=vocacao&sort=" . $linksort . "\">Vocação</a> "; if ($_GET['orderby'] == 'vocacao') { $btnsort = 1; echo $sort; } echo "</td>";
			echo "<th width=\"10%\">Ação</td>";
			echo "</tr>";

			$bool = 1;
			while ($item = $query->fetchrow())
			{
				echo "<tr class=\"row" . $bool . "\">\n";
				if ($item['item_bonus'] > 0){
					$bonus1 = " +" . $item['item_bonus'] . "";
				}else{
					$bonus1 = "";
				}
				if ($item['for'] > 0){
					$bonus2 = " <font color=\"gray\">+" . $item['for'] . "F</font>";
				}else{
					$bonus2 = "";
				}
				if ($item['vit'] > 0){
					$bonus3 = " <font color=\"green\">+" . $item['vit'] . "V</font>";
				}else{
					$bonus3 = "";
				}
				if ($item['agi'] > 0){
					$bonus4 = " <font color=\"blue\">+" . $item['agi'] . "A</font>";
				}else{
					$bonus4 = "";
				}
				if ($item['res'] > 0){
					$bonus5 = " <font color=\"red\">+" . $item['res'] . "R</font>";
				}else{
					$bonus5 = "";
				}
				echo "<td>" . $item['name'] . " <font size=\"1\">" . $bonus1 . "" . $bonus2 . "" . $bonus3 . "" . $bonus4 . "" . $bonus5 . "</font></td>";
				if (($_GET['type'] == 'potion') or ($_GET['type'] == 'ring') or ($_GET['type'] == 'addon')) {
					echo "<td>" . showName($item['seller'], &$db) . "</td>";
				} else {
					echo "<td>" . ($item['effectiveness'] + ($item['item_bonus'] * 2)) . "</td>";
				}
				echo "<td>" . $item['price'] . "</td>";
				echo "<td>";

				if ($item['voc'] == 1 and $item['needpromo'] == 'f') {
					echo "Caçador";
				} elseif ($item['voc'] == 2 and $item['needpromo'] == 'f') {
					echo "Espadachim";
				} elseif ($item['voc'] == 3 and $item['needpromo'] == 'f') {
					echo "Bruxo";
				} elseif ($item['voc'] == 1 and $item['needpromo'] == 't') {
					echo "Arqueiro";
				} elseif ($item['voc'] == 2 and $item['needpromo'] == 't') {
					echo "Guerreiro";
				} elseif ($item['voc'] == 3 and $item['needpromo'] == 't') {
					echo "Mago";
				} elseif ($item['voc'] == 0 and $item['needpromo'] == 't') {
					echo "Vocações superiores";
				} elseif ($item['voc'] == 1 and $item['needpromo'] == 'p') {
					echo "Arqueiro Royal";
				} elseif ($item['voc'] == 2 and $item['needpromo'] == 'p') {
					echo "Cavaleiro";
				} elseif ($item['voc'] == 3 and $item['needpromo'] == 'p') {
					echo "Arquimago";
				} elseif ($item['voc'] == 0 and $item['needpromo'] == 'p') {
					echo "Vocações supremas";
				} else {
					echo "Todas";
				}

				echo "</td>";

				if ($item['seller'] == $player->username) {
					echo "<td><a href=\"market.php?act=remove&item=" . $item['market_id'] . "\">Remover</a></td>\n";
				}else{
					echo "<td><a href=\"market_buy.php?act=buy&item=" . $item['market_id'] . "\">Comprar</a></td>\n";
				}
				
				$bool = ($bool==1)?2:1;
			}
			echo "</tr>";
			echo "</table>";
		}

		} else {
			echo "<p><i><center>Selecione o tipo de item que você deseja procurar.</center></i></p>";
		}

		include("templates/private_footer.php");
		break;
}
