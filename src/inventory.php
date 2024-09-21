<?php
	include("lib.php");
	define("PAGENAME", "Inventário");
	$player = check_user($secret_key, $db);
	include("checkbattle.php");
	include("checkhp.php");
	include("checkwork.php");

	$fieldnumber = 1;
	$newline = 0;

	include("includes/items/gift.php");
	include("includes/items/goldbar.php");
	include("includes/items/magiccrystal.php");
	include("includes/actions/transfer-potions.php");
	include("includes/actions/transfer-items.php");

	include("templates/private_header.php");

	if ($_GET['sellit']){
		$query = $db->execute("select items.id, items.item_id, items.item_bonus, items.status, items.mark, blueprint_items.name, blueprint_items.price, blueprint_items.img, blueprint_items.type from `blueprint_items`, `items` where items.item_id=blueprint_items.id and items.player_id=? and items.id=?", array($player->id, $_GET['sellit']));
		
		if ($query->recordcount() > 0)
		{
			$sell = $query->fetchrow(); //Get item info
			if ($sell['item_bonus'] > 10) {
				$valordavenda = floor(($sell['price']/2) + (($sell['item_bonus']*$sell['price'])/5) + 3000000);
			}else{
				$valordavenda = floor(($sell['price']/2) + (($sell['item_bonus']*$sell['price'])/5));
			}
		}

		if ($query->recordcount() == 0)
		{
			echo "<div style=\"background-color:#EEA2A2; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">";
			echo "<table width=\"100%\" align=\"center\"><tr>";
			echo "<td width=\"100%\" align=\"center\">Este item não existe!</td>";
			echo "</tr></table>";
			echo "</div>";
		}

		elseif (($sell['item_id'] == 111) or ($sell['item_id'] == 116) or ($sell['item_id'] == 163) or ($sell['item_id'] == 168)){
			echo "<div style=\"background-color:#EEA2A2; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">";
			echo "<table width=\"100%\" align=\"center\"><tr>";
			echo "<td width=\"100%\" align=\"center\">Voc&ecirc; não pode vender este item!</td>";
			echo "</tr></table>";
			echo "</div>";
		}

		elseif ($sell['type'] == 'stone'){
			echo "<div style=\"background-color:#EEA2A2; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">";
			echo "<table width=\"100%\" align=\"center\"><tr>";
			echo "<td width=\"100%\" align=\"center\">Voc&ecirc; não pode vender pedras.</td>";
			echo "</tr></table>";
			echo "</div>";
		}

		elseif ($sell['status'] == 'equipped'){
			echo "<div style=\"background-color:#EEA2A2; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">";
			echo "<table width=\"100%\" align=\"center\"><tr>";
			echo "<td width=\"100%\" align=\"center\">Voc&ecirc; não pode vender um item que está em uso.</td>";
			echo "</tr></table>";
			echo "</div>";
		}

		elseif (($_GET['sellit'] > 0) and ($_GET['comfirm'] != true))
		{
			echo "<div style=\"background-color:#EEA2A2; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">";
			echo "<table width=\"100%\" align=\"center\"><tr>";
			echo "<td width=\"10%\" align=\"center\"><img src=\"images/itens/" . $sell['img'] . "\" border=\"0\"></td>";
			echo "<td width=\"55%\">Deseja vender seu(a) " . $sell['name'] . " + " . $sell['item_bonus'] . "<br/>por " . $valordavenda . " moedas de ouro?</td>";
			echo "<td width=\"35%\" align=\"right\"><a href=\"inventory.php\">Não, obrigado.</a><br/><b><a href=\"inventory.php?sellit=" . $_GET['sellit'] . "&comfirm=true\">Desejo vender o item.</a></b></td>";
			echo "</tr></table>";
			echo "</div>";
		}

		elseif (($_GET['sellit'] > 0) and ($_GET['comfirm'] == true))
		{
			if ($sell['mark'] == 't'){
				$db->execute("delete from `market` where `market_id`=?", array($_GET['sellit']));
			}

			$db->execute("delete from `items` where `id`=?", array($_GET['sellit']));
			$db->execute("update `players` set `gold`=`gold`+? where `id`=?", array($valordavenda, $player->id));

			echo "<div style=\"background-color:#EEA2A2; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">";
			echo "<table width=\"100%\" align=\"center\"><tr>";
			echo "<td width=\"100%\" align=\"center\">Voc&ecirc; vendeu seu(a) " . $sell['name'] . " + " . $sell['item_bonus'] . " por " . $valordavenda . " moedas de ouro.</td>";
			echo "</tr></table>";
			echo "</div>";
		}
	}

	if ($_GET['mature']){
		$querymature = $db->execute("select items.id, items.item_bonus, items.status, items.mark, blueprint_items.name, blueprint_items.price, blueprint_items.img, blueprint_items.type from `blueprint_items`, `items` where items.item_id=blueprint_items.id and items.player_id=? and items.id=?", array($player->id, $_GET['mature']));

		if ($querymature->recordcount() > 0)
		{
		$mature = $querymature->fetchrow();

			if ($mature['item_bonus'] == 0){
				$precol = ceil($mature['price']/3.5);
			} elseif ($mature['item_bonus'] == 1){
				$precol = ceil(($mature['price']/3.5) * 1.3);
			} elseif ($mature['item_bonus'] == 2){
				$precol = ceil(($mature['price']/3.5) * 1.7);
			} elseif ($mature['item_bonus'] == 3){
				$precol = ceil(($mature['price']/3.5) * 2);
			}else{
				$precol = ceil(($mature['price']/3.5) * ($mature['item_bonus'] / 1.85));
			}
		}

		if ($querymature->recordcount() == 0)
		{
			echo "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">";
			echo "<table width=\"100%\" align=\"center\"><tr>";
			echo "<td width=\"100%\" align=\"center\">Este item não existe!</td>";
			echo "</tr></table>";
			echo "</div>";
		}

		elseif ($mature['item_bonus'] > 8)
		{
			echo "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">";
			echo "<table width=\"100%\" align=\"center\"><tr>";
			echo "<td width=\"100%\" align=\"center\">Seu item já está maturado ao máximo! (+9)</td>";
			echo "</tr></table>";
			echo "</div>";
		}

		elseif ($mature['mark'] == 't')
		{
			echo "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">";
			echo "<table width=\"100%\" align=\"center\"><tr>";
			echo "<td width=\"100%\" align=\"center\">Voc&ecirc; não pode maturar itens a venda no mercado.</td>";
			echo "</tr></table>";
			echo "</div>";
		}

		elseif (($mature['type'] == 'addon') or ($mature['type'] == 'potion') or ($mature['type'] == 'stone') or ($mature['type'] == 'ring'))
		{
			echo "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">";
			echo "<table width=\"100%\" align=\"center\"><tr>";
			echo "<td width=\"100%\" align=\"center\">Voc&ecirc; não pode maturar este tipo de item.</td>";
			echo "</tr></table>";
			echo "</div>";
		}

		elseif ($mature['price'] < 1000)
		{
			echo "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">";
			echo "<table width=\"100%\" align=\"center\"><tr>";
			echo "<td width=\"100%\" align=\"center\">Voc&ecirc; não pode maturar este item.";
			echo "<br/>Itens com preços mais baixos que mil moedas de ouro não podem ser maturados.";
			echo "</td></tr></table>";
			echo "</div>";
		}

		elseif (($_GET['mature'] > 0) and ($_GET['comfirm'] != true))
		{
			echo "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">";
			echo "<table width=\"100%\" align=\"center\"><tr>";
			echo "<td width=\"10%\" align=\"center\"><img src=\"images/itens/" . $mature['img'] . "\" border=\"0\"></td>";
			echo "<td width=\"55%\">Deseja maturar seu(a) " . $mature['name'] . " + " . $mature['item_bonus'] . "<br/>por " . $precol . " moedas de ouro?</td>";
			echo "<td width=\"35%\" align=\"right\"><a href=\"inventory.php\">Não, obrigado.</a><br/><b><a href=\"inventory.php?mature=" . $_GET['mature'] . "&comfirm=true\">Desejo maturar o item.</a></b></td>";
			echo "</tr></table>";
			echo "</div>";
		}

		elseif (($_GET['mature'] > 0) and ($_GET['comfirm'] == true))
		{

			if ($precol > $player->gold)
			{
				echo "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">";
				echo "<table width=\"100%\" align=\"center\"><tr>";
				echo "<td width=\"100%\" align=\"center\">Voc&ecirc; não pode pagar pela maturação. (" . $precol . " moedas de ouro)</td>";
				echo "</tr></table>";
				echo "</div>";
			}else{


				if (($mature['type'] == 'amulet') and ($mature['status'] == 'equipped')) {
					$addhp = 40;
                    $extramana = 10;
				}else{
					$addhp = 0;
                    $extramana = 0;
				}

				$db->execute("update `items` set `item_bonus`=? where `id`=?", array($mature['item_bonus'] + 1, $mature['id']));
				$db->execute("update `players` set `hp`=`hp`+?, `maxhp`=`maxhp`+?, `mana`=`mana`+?, `maxmana`=`maxmana`+?, `extramana`=`extramana`+?, `gold`=`gold`-? where `id`=?", array($addhp, $addhp, $extramana, $extramana, $extramana, $precol, $player->id));

				echo "<div style=\"background-color:#45E61D; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">";
				echo "<table width=\"100%\" align=\"center\"><tr>";
				echo "<td width=\"100%\" align=\"center\">Voc&ecirc; maturou seu(a) " . $mature['name'] . " por " . $precol . " moedas de ouro.<br />Os atributos de seu item subiram em 2 pontos.</td>";
				echo "</tr></table>";
				echo "</div>";
			}
		}
	}

		$tutorial = $db->execute("select * from `pending` where `pending_id`=2 and `pending_status`=4 and `player_id`=?", array($player->id));
		if ($tutorial->recordcount() > 0){
			$tutorial = $db->execute("select * from `items` where `player_id`=? and `status`='equipped'", array($player->id));
			if ($tutorial->recordcount() == 0){
				echo showAlert("<table width=\"100%\"><tr><td width=\"90%\">Itens ajudam na sua força e resist&ecirc;ncia.<br/><font size=\"1px\">Voc&ecirc; pode obter itens <u>lutando contra monstros</u> ou <u>comprando-os no ferreiro</u>.</font><br/><br/>Para equipar seu item, arraste sua arma para sua mão esquerda.</td><th><font size=\"1px\"><a href=\"start.php?act=5\">Próximo</a></font></th></tr></table>", "white", "left");
			}else{
				echo showAlert("ótimo, <a href=\"start.php?act=5\">clique aqui</a> para continuar seu tutorial.", "green");
			}
		}


		echo "<div id=\"main_container\">";
			echo "<div id=\"drag\">";
				echo "<div id=\"left\" style='width:200px;min-height:230px;'>";
                 echo"<fieldset style='border:0px;text-align:center;'><b>Inventário</b></fieldset>";
				include("showit2.php");

			echo "<table align=\"center\">";
				echo "<tr><td class=\"sell\">Vender</td></tr>";
				echo "<tr><td class=\"mature\">Maturar</td></tr>";
			echo "</table>";

$backpackquery = $db->execute("select items.id, items.tile, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.effectiveness, blueprint_items.img, blueprint_items.type, blueprint_items.description, blueprint_items.needlvl, blueprint_items.needpromo from `items`, `blueprint_items` where items.player_id=? and items.status='unequipped' and items.item_id=blueprint_items.id and blueprint_items.type!='potion' and blueprint_items.type!='stone' and items.mark='f' order by items.tile asc limit 55", array($player->id));

	echo "<center><font size=\"1px\"><b>Capacidade:</b> 60</font><br/>";
	echo "<font size=\"1px\"><b>Espaço Restante:</b> ";
	if ((60 - $backpackquery->recordcount()) >= 0){ echo (60 - $backpackquery->recordcount()); }else{ echo "0"; }
	echo "</font></center>";

echo "</div><div id=\"right\" style='padding:0px;height:230px;'>";
echo"<fieldset style='border:0px;text-align:center;'><b>Mochila</b></fieldset>";

echo "<table id=\"table2\" style=\"margin:5px;\" align=\"center\">";
echo "<tr>";
while($bag = $backpackquery->fetchrow())
{



	if (($bag['item_bonus'] > 2) and ($bag['item_bonus'] < 6)){
		$colorbg = "itembg2";
	}elseif (($bag['item_bonus'] > 5) and ($bag['item_bonus'] < 9)){
		$colorbg = "itembg3";
	}elseif ($bag['item_bonus'] == 9){
		$colorbg = "itembg4";
	}elseif ($bag['item_bonus'] > 9){
		$colorbg = "itembg5";
	}else{
		$colorbg = "itembg1";
	}

		if ($bag['for'] == 0){
		$showitfor = "";
		$showitfor2 = "";
		}else{
		$showitfor2 = "+<font color=gray>" . $bag['for'] . " For</font><br/>";
		}

		if ($bag['vit'] == 0){
		$showitvit = "";
		$showitvit2 = "";
		}else{
		$showitvit2 = "+<font color=green>" . $bag['vit'] . " Vit</font><br/>";
		}

		if ($bag['agi'] == 0){
		$showitagi = "";
		$showitagi2 = "";
		}else{
		$showitagi2 = "+<font color=blue>" . $bag['agi'] . " Agi</font><br/>";
		}

		if ($bag['res'] == 0){
		$showitres = "";
		$showitres2 = "";
		}else{
		$showitres2 = "+<font color=red>" . $bag['res'] . " Res</font>";
		}

		if ($bag['type'] == 'amulet'){
			$nametype = "Vitalidade";
		} elseif ($bag['type'] == 'weapon'){
			$nametype = "Ataque";
		}else{
			$nametype = "Defesa";
		}

		if (($bag['type'] != 'addon') and ($bag['type'] != 'ring')){
			$newefec = ($bag['effectiveness']) + ($bag['item_bonus'] * 2);
			$showitname = "" . $bag['name'] . " + " . $bag['item_bonus'] . "";
		}else{
			$showitname = $bag['name'];
		}

		$need = NULL;
		if ($bag['needlvl'] > 1){
            if ($player->vip > time()) {
                $lvlbonus = 10;
            } else {
                $lvlbonus = 0;
            }
            if ($bag['needlvl'] > ($player->level + $lvlbonus))
			{
			$need .= "<br/><font color=red><b>Requer nível " . $bag['needlvl'] . ".</b></font>";
			}else{
			$need .= "<br/><b>Requer nível " . $bag['needlvl'] . ".</b>";
			}
		}
		if ($bag['needpromo'] == "t"){
			if ($player->promoted != "f")
			{
			$need .= "<br/><b>Voc superior.</b>";
			}
			else
			{
			$need .= "<br/><font color=red><b>Voc superior.</b></font>";
			}
		}
		if ($bag['needpromo'] == "p"){
			if ($player->promoted == "p")
			{
			$need .= "<br/><b>Voc suprema.</b>";
			}
			else
			{
			$need .= "<br/><font color=red><b>Voc suprema.</b></font>";
			}
		}

		if ($bag['type'] == addon){
			$showitinfo = "<table width=100%><tr><td width=100%><font size=1px>" . $bag['description'] . "</font></td></tr></table>";
		}elseif ($bag['type'] == ring){
			$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>" . $bag['description'] . "" . $need . "</font></td><td width=35%><font size=1px>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></table>";
		}else{
			$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>" . $nametype . ": " . $newefec . "" . $need . "</font></td><td width=35%><font size=1px>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></table>";
		}
echo "<td class=\"" . $colorbg . " " . $fieldnumber . "\">";
echo "<div id=\"" . $bag['type'] . "\" class=\"drag " . $bag['id'] . "\" title=\"header=[" . $showitname . "] body=[" . $showitinfo . "]\">";
echo "<img src=\"images/itens/" . $bag['img'] . "\" border=\"0\">";
echo "</div>";
echo "</td>";

				$fieldnumber = $fieldnumber + 1;
					$newline = $newline + 1;
					if ($newline == 12){
						echo "</tr><tr>";
						$newline = 0;
					}
}

$total = $backpackquery->recordcount();
	while($total < 60){
	echo "<td class=\"itembg1 " . $fieldnumber . "\">&nbsp;</td>";

		$fieldnumber = $fieldnumber + 1;
		$total = $total + 1;

		$newline = $newline + 1;
		if ($newline == 12){
			echo "</tr><tr>";
			$newline = 0;
		}
	}


echo "</tr></table>";

		echo "</div>";
	echo "</div>";

if ($backpackquery->recordcount() > 60){
echo "<div style=\"background-color:#EEA2A2; padding:5px; border: 1px solid #DEDEDE; margin-bottom:10px\">";
echo "<center><font size=\"1\"><b>Espaço insuficiente na mochila.<br/>Venda alguns de seus itens para ver os outros.</b></font></center>";
echo "</div>";
}


echo "<br />";


$query = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=136 and `mark`='f' order by rand()", array($player->id));
$numerodepocoes = $query->recordcount();

$query2 = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=137 and `mark`='f' order by rand()", array($player->id));
$numerodepocoes2 = $query2->recordcount();

$query3 = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=148 and `mark`='f' order by rand()", array($player->id));
$numerodepocoes3 = $query3->recordcount();

$query4 = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=150 and `mark`='f' order by rand()", array($player->id));
$numerodepocoes4 = $query4->recordcount();

echo "<fieldset style='padding:0px;border:1px solid #b9892f;'>";
echo"<fieldset style='margin-bottom:5px;border:0px;text-align:center;'><b>Poções</b></fieldset>";
echo "<table width=\"100%\"><tr><td><table width=\"80px\"><tr><td><div title=\"header=[Health Potion] body=[Recupera até 5 mil de vida.]\"><img src=\"images/itens/healthpotion.gif\"></div></td><td><b>x" . $numerodepocoes . "</b>";
if ($numerodepocoes > 0){
$item = $query->fetchrow();
echo "<br/><a href=\"hospt.php?act=potion&pid=" . $item['id'] . "\">Usar</a>";
}
echo "</td></tr></table></td>";
echo "<td><table width=\"80px\"><tr><td><div title=\"header=[Big Health Potion] body=[Recupera até 10 mil de vida.]\"><img src=\"images/itens/bighealthpotion.gif\"></div></td><td><b>x" . $numerodepocoes3 . "</b>";
if ($numerodepocoes3 > 0){
$item3 = $query3->fetchrow();
echo "<br/><a href=\"hospt.php?act=potion&pid=" . $item3['id'] . "\">Usar</a>";
}
echo "</td></tr></table></td>";
echo "<td><table width=\"80px\"><tr><td><div title=\"header=[Mana Potion] body=[Recupera até 500 de mana.]\"><img src=\"images/itens/manapotion.gif\"></div></td><td><b>x" . $numerodepocoes4 . "</b>";
if ($numerodepocoes4 > 0){
$item4 = $query4->fetchrow();
echo "<br/><a href=\"hospt.php?act=potion&pid=" . $item4['id'] . "\">Usar</a>";
}
echo "</td></tr></table></td>";
echo "<td><table width=\"80px\"><tr><td><div title=\"header=[Energy Potion] body=[Recupera até 50 de energia.]\"><img src=\"images/itens/energypotion.gif\"></div></td><td><b>x" . $numerodepocoes2 . "</b>";
if ($numerodepocoes2 > 0){
$item2 = $query2->fetchrow();
echo "<br/><a href=\"hospt.php?act=potion&pid=" . $item2['id'] . "\">Usar</a>";
}
echo "</td></tr></table></td><td><font size=\"1\">
<a id=\"link\" class=\"neg\" style='float:right;color:#fff;margin-top:-5px;' href=\"hospt.php?act=sell\">Vender Poções</a>
<br/>
<a id=\"link\" class=\"neg\" style='float:right;color:#fff;' href=\"inventory.php?transpotion=true\">Transferir Poções</a></font></td></tr></table>";
echo "</fieldset>";

echo "<br>";
echo "<fieldset style='padding:0px;border:1px solid #b9892f;'>";
echo"<fieldset style='margin-bottom:5px;border:0px;text-align:center;'><b>Enviar itens</b></fieldset>";

$verifikeuser = $db->execute("select `id` from `quests` where `quest_id`=4 and `quest_status`=90 and `player_id`=?", array($player->id));

if ($player->level < $setting->activate_level)
{
	echo "<center><p><font size=\"1\">Para poder transferir itens sua conta precisa estar ativa.<br/>Ela será ativada automaticamente quando voc&ecirc; alcançar o nível " . $setting->activate_level . ".</font></p></center>";
}elseif ($verifikeuser->recordcount() == 0) {
	echo"<center><font size=\"1\">Voc&ecirc; precisa chegar ao nivel 40 e completar uma missão para utilizar esta função.</font></center>";
	if ($player->level > 39) {
		echo"<center><font size=\"1\"><a href=\"quest2.php\"><b>Clique aqui para fazer a missão.</b></a></font></center>";
	}
	}elseif ($player->transpass == f){
	echo "<form method=\"POST\" action=\"transferpass.php\">";
		echo "<center><i>Escolha uma senha de transfer&ecirc;ncia para enviar ouro e itens</i><p><font size=\"1px\"><b>Senha:</b></font> <input type=\"password\" name=\"pass\" size=\"15\"/> <font size=\"1px\"><b>Confirme:</b></font> <input type=\"password\" name=\"pass2\" size=\"15\"/> <input type=\"submit\" name=\"submit\" value=\"Definir Senha\"></p><br/><font size=\"1px\">Lembre-se desta senha, ela sempre será usada para fazer transfer&ecirc;ncias bancárias.</font></center>";
	echo "</form>";
	}else{

echo "<table width=\"100%\">";
echo "<form method=\"POST\" action=\"inventory.php\">";
echo "<tr><td width=\"40%\">Usuário:</td><td><input type=\"text\" name=\"username\" size=\"20\"/></td></tr>";
echo "<tr><td width=\"40%\">Item:</td><td>";

$queoppa = $db->execute("select items.id, items.item_bonus, items.item_id, items.mark, blueprint_items.name from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type!='stone' and blueprint_items.type!='potion' and items.mark='f' order by blueprint_items.type, blueprint_items.name asc", array($player->id));
    if ($queoppa->recordcount() == 0) {
echo "<b>Voc&ecirc; não possui itens.</b>";
}else{
	echo "<select name=\"itselected\">";
	while($item = $queoppa->fetchrow())
	{
	echo "<option value=\"" . $item['id'] . "\">" . $item['name'] . " +" . $item['item_bonus'] . "</option>";
	}
	echo "</select>";
	}

echo "</td></tr>";
echo "<tr><td width=\"40%\">Senha de transfer&ecirc;ncia:</td><td><input type=\"password\" name=\"passcode\" size=\"20\"/></td></tr>";
echo "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"transferitems\" value=\"Enviar\"></td></tr>";
echo "</table></form>";
echo "<font size=\"1\"><a href=\"forgottrans.php\">Esqueceu sua senha de transfer&ecirc;ncia?</a></font>";

$morelogs = 1;
}
echo "</fieldset>";
if ($morelogs == 1){
echo "<center><font size=\"1\"><a href=\"#\" onclick=\"javascript:window.open('logitem.php', '_blank','top=100, left=100, height=350, width=450, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');\">Transfer&ecirc;ncias realizadas nos últimos 14 dias.</a></font></center>";
}


echo "</div>";

include("templates/private_footer.php");
?>
