<?php

declare(strict_types=1);

include(__DIR__ . "/lib.php");
define("PAGENAME", "Mercado");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkhp.php");
include(__DIR__ . "/checkwork.php");

if ($player->level < 15) {
	include(__DIR__ . "/templates/private_header.php");
	echo "<i>Você precisa ter nível 15 ou mais para vender items.</i><br/>\n";
	echo '<a href="market.php">Voltar</a>.';
	echo "</fieldset>";
	include(__DIR__ . "/templates/private_footer.php");
	exit;
}

switch ($_GET['act'] ?? null) {
	case "sell": {
			include(__DIR__ . "/templates/private_header.php");

			$gsadasdiiii = $db->execute("select items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.type from `items`, `blueprint_items` where items.id=? and items.player_id=? and items.item_id=blueprint_items.id", [$_GET['item'] ?? null, $player->id]);
			$goooosdsfds = $gsadasdiiii->fetchrow();

			if ($gsadasdiiii->recordcount() == 0) {
				echo "Você não possui este item.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			if (($goooosdsfds['mark'] ?? null) == 't') {
				echo "Este item já está á venda!<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			if (($goooosdsfds['status'] ?? null) == 'equipped') {
				echo "O item que você deseja vender está em uso. Desequipe-o e tente novamente.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			if (($goooosdsfds['item_id'] ?? null) == 111 || ($goooosdsfds['item_id'] ?? null) == 116 || ($goooosdsfds['item_id'] ?? null) == 163 || ($goooosdsfds['item_id'] ?? null) == 168) {
				echo "Você não pode vender este item.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			if (($goooosdsfds['type'] ?? null) == 'stone') {
				echo "Você não pode vender pedras no mercado.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			if (($goooosdsfds['item_bonus'] ?? null) > 0) {
				$bonus1 = " +" . $goooosdsfds['item_bonus'] . "";
			}

			if (($goooosdsfds['for'] ?? null) > 0) {
				$bonus2 = ' <font color="gray">+' . $goooosdsfds['for'] . "F</font>";
			}

			if (($goooosdsfds['vit'] ?? null) > 0) {
				$bonus3 = ' <font color="green">+' . $goooosdsfds['vit'] . "V</font>";
			}

			if (($goooosdsfds['agi'] ?? null) > 0) {
				$bonus4 = ' <font color="blue">+' . $goooosdsfds['agi'] . "A</font>";
			}

			if (($goooosdsfds['res'] ?? null) > 0) {
				$bonus5 = ' <font color="red">+' . $goooosdsfds['res'] . "R</font>";
			}

?>
			Então você quer vender seu iten? ótimo! Me diga quando você quer por ele. Mas lembre-se, este mercado não funciona de graça, você tem que nos pagar 5% de comissão.<br /><br />
			<form method="POST" action="market_sell.php?act=confirm">
				<table>
					<tr>
						<td><b>Vender:</b></td>
						<td><?php echo "" . $goooosdsfds['name'] . "" . $bonus1 . "" . $bonus2 . "" . $bonus3 . "" . $bonus4 . "" . $bonus5 . ""; ?></td>
					</tr>
					<input type="hidden" name="act" value="confirm">
					<input type="hidden" name="item" value="<?php echo $_GET['item'] ?? null; ?>">
					<tr>
						<td><b>Preço:</b></td>
						<td><input autocomplete='off' type="text" name="price" size="15"></td>
					</tr>
					<?php
					if ($player->transpass != 'f') {
						echo "<tr><td><b>Senha de<br/>transferência:</b></td><td><input autocomplete='off' type=\"password\" name=\"passcode\" size=\"20\"/></td></tr>";
					}
					?>
				</table>
				<input type="submit" value="Adicionar ao mercado">
			</form>
		<?php
			include(__DIR__ . "/templates/private_footer.php");
			//end of sell action
			break;
		}
	case "confirm": {

			if ($player->transpass != 'f') {
				if (!($_POST['passcode'] ?? null)) {
					include(__DIR__ . "/templates/private_header.php");
					echo "<fieldset><legend><b>Erro</b></legend>\n";
					echo "Preencha todos os campos.<br />";
					echo '<a href="market.php">Voltar</a>.';
					echo "</fieldset>";
					include(__DIR__ . "/templates/private_footer.php");
					break;
				}

				if (strtolower((string) ($_POST['passcode'] ?? null)) !== strtolower($player->transpass)) {
					include(__DIR__ . "/templates/private_header.php");
					echo "<fieldset><legend><b>Erro</b></legend>\n";
					echo "Sua senha de transferência está incorreta.<br />";
					echo '<a href="market.php">Voltar</a>.';
					echo "</fieldset>";
					include(__DIR__ . "/templates/private_footer.php");
					break;
				}
			}

			if (!($_POST['item'] ?? null)) {
				include(__DIR__ . "/templates/private_header.php");
				echo 'Um erro desconhecido ocorreu.<br/><a href="market.php">Voltar</a>.';
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			$verificaall = $db->execute("select items.item_id, items.status, items.mark, items.item_bonus, items.for, items.vit, items.agi, items.res, blueprint_items.name, blueprint_items.type from `items`, `blueprint_items` where items.id=? and items.player_id=? and items.item_id=blueprint_items.id", [$_POST['item'] ?? null, $player->id]);
			if ($verificaall->recordcount() == 0) {
				include(__DIR__ . "/templates/private_header.php");
				echo "Você não possui este item.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			$ver = $verificaall->fetchrow();

			if (($ver['mark'] ?? null) == 't') {
				include(__DIR__ . "/templates/private_header.php");
				echo "Este item já está no mercado.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			if (($ver['status'] ?? null) == 'equipped') {
				include(__DIR__ . "/templates/private_header.php");
				echo "O item que você deseja vender está em uso. Desequipe-o e tente novamente.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}



			$item = stripslashes((string) ($_POST['item'] ?? null));

			if (!($_POST['price'] ?? null)) {
				include(__DIR__ . "/templates/private_header.php");
				echo "Você precisa preencher todos os campos.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			if (!is_numeric($_POST['price'])) {
				include(__DIR__ . "/templates/private_header.php");
				echo "O valor que você inseriu não é válido.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			if (($_POST['price'] ?? null) < 100) {
				include(__DIR__ . "/templates/private_header.php");
				echo "O preço não pode ser menor que 100.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}


			if (($_POST['price'] ?? null) > 50000000) {
				include(__DIR__ . "/templates/private_header.php");
				echo "O preço não pode ser maior que 50 milhões.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			$price = stripslashes($_POST['price']);
			$price = ceil((float)$price);

			$fee = ceil($price / 100);
			$fee = ceil($fee * 5);
			if ($price <= 0) {
				include(__DIR__ . "/templates/private_header.php");
				echo "Desculpe, mas nós não permitimos que os usuários dêem itens. <a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			$gsassaaa = $db->execute("select blueprint_items.name, items.item_id from `blueprint_items`, `items` where items.id=? and blueprint_items.id=items.item_id", [$item]);
			$gooooa = $gsassaaa->fetchrow();
			include(__DIR__ . "/templates/private_header.php");
		?>
			Você tem certeza que quer vender seu/sua <b><?php echo $gooooa['name'] ?? null; ?> por <?php echo $price; ?> de ouro</b>? Você precisará nos pagar <b><?php echo $fee; ?> de ouro</b>, que é nossa comissão.<br /><br />
			<form method="post" action="market_sell.php?act=list">
				<input type="hidden" name="item" value="<?php echo $item; ?>">
				<input type="hidden" name="price" value="<?php echo $price; ?>">
				<input type="submit" name="list" value="Sim, tenho certeza!">
			</form>
		<?php
		}
		include(__DIR__ . "/templates/private_footer.php");
		break;


	case "list": {

			if (!($_POST['item'] ?? null)) {
				include(__DIR__ . "/templates/private_header.php");
				echo 'Um erro desconhecido ocorreu.<br/><a href="market.php">Voltar</a>.';
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			$verificaall = $db->execute("select items.item_id, items.status, items.mark, items.item_bonus, items.for, items.vit, items.agi, items.res, blueprint_items.name, blueprint_items.type from `items`, `blueprint_items` where items.id=? and items.player_id=? and items.item_id=blueprint_items.id", [$_POST['item'] ?? null, $player->id]);
			if ($verificaall->recordcount() == 0) {
				include(__DIR__ . "/templates/private_header.php");
				echo "Você não possui este item.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			$ver = $verificaall->fetchrow();

			if (($ver['mark'] ?? null) == 't') {
				include(__DIR__ . "/templates/private_header.php");
				echo "Este item já está no mercado.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			if (($ver['status'] ?? null) == 'equipped') {
				include(__DIR__ . "/templates/private_header.php");
				echo "O item que você deseja vender está em uso. Desequipe-o e tente novamente.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			$item = stripslashes((string) ($_POST['item'] ?? null));


			if (!($_POST['price'] ?? null)) {
				include(__DIR__ . "/templates/private_header.php");
				echo "Você precisa preencher todos os campos.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			if (!is_numeric($_POST['price'])) {
				include(__DIR__ . "/templates/private_header.php");
				echo "O valor que você inseriu não é válido.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			if (($_POST['price'] ?? null) < 100) {
				include(__DIR__ . "/templates/private_header.php");
				echo "O preço não pode ser menor que 100.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}


			if (($_POST['price'] ?? null) > 50000000) {
				include(__DIR__ . "/templates/private_header.php");
				echo "O preço não pode ser maior que 50 milhões.<br/><a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
				break;
			}

			$price = stripslashes($_POST['price']);
			$price = ceil((float)$price);

			$fee = ceil($price / 100);
			$fee = ceil($fee * 5);

			if ($player->gold < $fee) {
				include(__DIR__ . "/templates/private_header.php");
				echo "Você não tem ouro para pagar nossa comissão.";
				include(__DIR__ . "/templates/private_footer.php");
			} else {
				$insert['market_id'] = $item;
				$insert['ite_id'] = $ver['item_id'];
				$insert['price'] = $price;
				$insert['seller'] = $player->username;
				$insert['expira'] = ceil(time() + 1209600);
				$insert['serv'] = $player->serv;
				$query2 = $db->autoexecute('market', $insert, 'INSERT');
				//remove fee from player
				$query02 = $db->execute("update `players` set `gold`=? where `id`=?", [$player->gold - $fee, $player->id]);
				$query03 = $db->execute("update `items` set `mark`='t', `status`='unequipped' where `id`=?", [$item]);

				include(__DIR__ . "/templates/private_header.php");
				echo "Agora seu item está disponível no mercado! <a href=\"market.php\">Voltar</a>.";
				include(__DIR__ . "/templates/private_footer.php");
			}

			break;
		}
	default:

		//Default Page
		include(__DIR__ . "/templates/private_header.php");
		echo showAlert("<i style='text-align:center'><b>Aqui você pode vender seus itens listados abaixo. Eles ficarão disponíveis no mercado por até 14 dias, com um custo de comissão de 5% do valor ofertado a venda.</b></i><br/>", "white", "left");
		?>
		<?php
		//Pull info from blueprint items and then compare them to items list to get count.
		$query = $db->execute("select * from blueprint_items order by blueprint_items.name asc");
		if ($query->recordcount() == 0) {
			echo "<br /><b>Você não tem itens para vender.</b>";
		}

		$abaioepa = $db->execute("select `id` from `items` where `player_id`=?", [$player->id]);
		if ($abaioepa->recordcount() == 0) {
			echo "<br /><b>Você não tem itens para vender.</b>";
			include(__DIR__ . "/templates/private_footer.php");
			exit;
		}

		echo "<fieldset><legend><b>Quais itens você gostaria de vender?</b></legend>";
		echo "<table style='width:100%;border-collapse: collapse;'>";
		echo "<thead>";
		echo "<tr>";
		echo "<th style='width:10%;text-align: center;'><b>imagem</b></th>";
		echo "<th style='width:76%;text-align: center;'><b>Item</b></th>";
		echo "<th style='width:20%;text-align: center;'><b>Ação</b></th>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";
		$gettheitemuniqid = $db->execute("select items.id, items.item_bonus, items.for, items.vit, items.agi, items.res, blueprint_items.name, blueprint_items.img from `items`, `blueprint_items` where `player_id`=? and mark!='t' and status != 'equipped'  and items.item_id=blueprint_items.id order by blueprint_items.name asc", [$player->id]);
		$bool = 1;
		while ($gettheitemuniqiditem = $gettheitemuniqid->fetchrow()) {
			$bonus01 = ($gettheitemuniqiditem['item_bonus'] ?? null) > 0 ? " (+" . $gettheitemuniqiditem['item_bonus'] . ")" : "";

			$bonus02 = ($gettheitemuniqiditem['for'] ?? null) > 0 ? ' <font color="gray">+' . $gettheitemuniqiditem['for'] . "F</font>" : "";

			$bonus03 = ($gettheitemuniqiditem['vit'] ?? null) > 0 ? ' <font color="green">+' . $gettheitemuniqiditem['vit'] . "V</font>" : "";

			$bonus04 = ($gettheitemuniqiditem['agi'] ?? null) > 0 ? ' <font color="blue">+' . $gettheitemuniqiditem['agi'] . "A</font>" : "";

			$bonus05 = ($gettheitemuniqiditem['res'] ?? null) > 0 ? ' <font color="red">+' . $gettheitemuniqiditem['res'] . "R</font>" : "";

			echo '<tr class="row' . $bool . "\">
				<td style='text-align: center;padding:10px;border:1px solid #B9892F;vertical-align: middle;'><img src=\"static/images/itens/{$gettheitemuniqiditem['img']}\" alt=\"{$gettheitemuniqiditem['name']}\"></td>
				<td style='text-align: center;padding:10px;border:1px solid #B9892F;vertical-align: middle;'>" . $gettheitemuniqiditem['name'] . "" . $bonus01 . "" . $bonus02 . "" . $bonus03 . "" . $bonus04 . "" . $bonus05 . "</td>
				<td style='text-align:center;padding:10px;border:1px solid #B9892F;vertical-align: middle;'><a href=\"market_sell.php?act=sell&item=" . $gettheitemuniqiditem['id'] . '">Vender</a></td></tr>';

			$bool = ($bool == 1) ? 2 : 1;
		}

		echo "</tbody></table></fieldset>";
		?>
<?php
		include(__DIR__ . "/templates/private_footer.php");
}
?>