<?php
echo "<table id=\"table1\" align=\"center\">";
echo "<tbody><tr>";

$showitenx = $db->execute("select items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.effectiveness, blueprint_items.img, blueprint_items.type from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='amulet' and items.status='equipped'", array($player->id));
if ($showitenx->recordcount() == 0)
{
	echo "<td class=\"mark amulet itembg1\"><img src=\"images/colar.png\" border=\"0\"></td>";
}else{
	$showeditexs = $showitenx->fetchrow();

	if (($showeditexs['item_bonus'] > 2) and ($showeditexs['item_bonus'] < 6)){
		$colorbg = "itembg2";
	}elseif (($showeditexs['item_bonus'] > 5) and ($showeditexs['item_bonus'] < 9)){
		$colorbg = "itembg3";
	}elseif ($showeditexs['item_bonus'] == 9){
		$colorbg = "itembg4";
	}elseif ($showeditexs['item_bonus'] > 9){
		$colorbg = "itembg5";
	}else{
		$colorbg = "itembg1";
	}

	echo "<td class=\"mark amulet " . $colorbg . "\">";

		if ($showeditexs['for'] == 0){
		$showitfor = "";
		$showitfor2 = "";
		}else{
		$showitfor2 = "+<font color=gray>" . $showeditexs['for'] . " For</font><br/>";
		}

		if ($showeditexs['vit'] == 0){
		$showitvit = "";
		$showitvit2 = "";
		}else{
		$showitvit2 = "+<font color=green>" . $showeditexs['vit'] . " Vit</font><br/>";
		}

		if ($showeditexs['agi'] == 0){
		$showitagi = "";
		$showitagi2 = "";
		}else{
		$showitagi2 = "+<font color=blue>" . $showeditexs['agi'] . " Agi</font><br/>";
		}

		if ($showeditexs['res'] == 0){
		$showitres = "";
		$showitres2 = "";
		}else{
		$showitres2 = "+<font color=red>" . $showeditexs['res'] . " Res</font>";
		}

		$newefec = ($showeditexs['effectiveness']) + ($showeditexs['item_bonus'] * 2);
		$showitname = "" . $showeditexs['name'] . " + " . $showeditexs['item_bonus'] . "";
		$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>Vitalidade: " . $newefec . "</font></td><td width=35%><font size=1>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></tr></table>";
		echo "<div title=\"header=[" . $showitname . "] body=[" . $showitinfo . "]\">";
		echo "<div id=\"" . $showeditexs['type'] . "\" class=\"drag " . $showeditexs['id'] . "\"><img src=\"images/itens/" . $showeditexs['img'] . "\" border=\"0\"></div>";
		echo "</div>";

	echo "</td>";
}


$showitenx = $db->execute("select items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.effectiveness, blueprint_items.img, blueprint_items.type from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='helmet' and items.status='equipped'", array($player->id));
if ($showitenx->recordcount() == 0)
{
	echo "<td class=\"mark helmet itembg1\"><img src=\"images/elmo.png\" border=\"0\"></td>";
}else{
	$showeditexs = $showitenx->fetchrow();

	if (($showeditexs['item_bonus'] > 2) and ($showeditexs['item_bonus'] < 6)){
		$colorbg = "itembg2";
	}elseif (($showeditexs['item_bonus'] > 5) and ($showeditexs['item_bonus'] < 9)){
		$colorbg = "itembg3";
	}elseif ($showeditexs['item_bonus'] == 9){
		$colorbg = "itembg4";
	}elseif ($showeditexs['item_bonus'] > 9){
		$colorbg = "itembg5";
	}else{
		$colorbg = "itembg1";
	}

	echo "<td class=\"mark helmet " . $colorbg . "\">";

		if ($showeditexs['for'] == 0){
		$showitfor = "";
		$showitfor2 = "";
		}else{
		$showitfor2 = "+<font color=gray>" . $showeditexs['for'] . " For</font><br/>";
		}

		if ($showeditexs['vit'] == 0){
		$showitvit = "";
		$showitvit2 = "";
		}else{
		$showitvit2 = "+<font color=green>" . $showeditexs['vit'] . " Vit</font><br/>";
		}

		if ($showeditexs['agi'] == 0){
		$showitagi = "";
		$showitagi2 = "";
		}else{
		$showitagi2 = "+<font color=blue>" . $showeditexs['agi'] . " Agi</font><br/>";
		}

		if ($showeditexs['res'] == 0){
		$showitres = "";
		$showitres2 = "";
		}else{
		$showitres2 = "+<font color=red>" . $showeditexs['res'] . " Res</font>";
		}

		$newefec = ($showeditexs['effectiveness']) + ($showeditexs['item_bonus'] * 2);
		$showitname = "" . $showeditexs['name'] . " + " . $showeditexs['item_bonus'] . "";
		$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>Defesa: " . $newefec . "</font></td><td width=35%><font size=1>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></tr></table>";
		echo "<div title=\"header=[" . $showitname . "] body=[" . $showitinfo . "]\">";
		echo "<div id=\"" . $showeditexs['type'] . "\" class=\"drag " . $showeditexs['id'] . "\"><img src=\"images/itens/" . $showeditexs['img'] . "\" border=\"0\"></div>";
		echo "</div>";

	echo "</td>";
}


	echo "<td class=\"mark none\">&nbsp;</td>";
echo "</tr><tr>";


$showitenx = $db->execute("select items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.effectiveness, blueprint_items.img, blueprint_items.type from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='weapon' and items.status='equipped'", array($player->id));
if ($showitenx->recordcount() == 0)
{
		$tutorial = $db->execute("select * from `pending` where `pending_id`=2 and `pending_status`=4 and `player_id`=?", array($player->id));
		if ($tutorial->recordcount() > 0){
			echo "<td class=\"mark weapon itembg1\"><img src=\"images/itens/show.gif\" border=\"0\"></td>";
		} else {
			echo "<td class=\"mark weapon itembg1\"><img src=\"images/luva-esq.png\" border=\"0\"></td>";
		}
}else{
	$showeditexs = $showitenx->fetchrow();

	if (($showeditexs['item_bonus'] > 2) and ($showeditexs['item_bonus'] < 6)){
		$colorbg = "itembg2";
	}elseif (($showeditexs['item_bonus'] > 5) and ($showeditexs['item_bonus'] < 9)){
		$colorbg = "itembg3";
	}elseif ($showeditexs['item_bonus'] == 9){
		$colorbg = "itembg4";
	}elseif ($showeditexs['item_bonus'] > 9){
		$colorbg = "itembg5";
	}else{
		$colorbg = "itembg1";
	}

	echo "<td class=\"mark weapon " . $colorbg . "\">";

		if ($showeditexs['for'] == 0){
		$showitfor = "";
		$showitfor2 = "";
		}else{
		$showitfor2 = "+<font color=gray>" . $showeditexs['for'] . " For</font><br/>";
		}

		if ($showeditexs['vit'] == 0){
		$showitvit = "";
		$showitvit2 = "";
		}else{
		$showitvit2 = "+<font color=green>" . $showeditexs['vit'] . " Vit</font><br/>";
		}

		if ($showeditexs['agi'] == 0){
		$showitagi = "";
		$showitagi2 = "";
		}else{
		$showitagi2 = "+<font color=blue>" . $showeditexs['agi'] . " Agi</font><br/>";
		}

		if ($showeditexs['res'] == 0){
		$showitres = "";
		$showitres2 = "";
		}else{
		$showitres2 = "+<font color=red>" . $showeditexs['res'] . " Res</font>";
		}

		$newefec = ($showeditexs['effectiveness']) + ($showeditexs['item_bonus'] * 2);
		$showitname = "" . $showeditexs['name'] . " + " . $showeditexs['item_bonus'] . "";
		$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>Ataque: " . $newefec . "</font></td><td width=35%><font size=1>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></tr></table>";
		echo "<div title=\"header=[" . $showitname . "] body=[" . $showitinfo . "]\">";
		echo "<div id=\"" . $showeditexs['type'] . "\" class=\"drag " . $showeditexs['id'] . "\"><img src=\"images/itens/" . $showeditexs['img'] . "\" border=\"0\"></div>";
		echo "</div>";

	echo "</td>";
}


$showitenx = $db->execute("select items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.effectiveness, blueprint_items.img, blueprint_items.type from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='armor' and items.status='equipped'", array($player->id));
if ($showitenx->recordcount() == 0)
{
	echo "<td class=\"mark armor itembg1\"><img src=\"images/armor.png\" border=\"0\"></td>";
}else{
	$showeditexs = $showitenx->fetchrow();

	if (($showeditexs['item_bonus'] > 2) and ($showeditexs['item_bonus'] < 6)){
		$colorbg = "itembg2";
	}elseif (($showeditexs['item_bonus'] > 5) and ($showeditexs['item_bonus'] < 9)){
		$colorbg = "itembg3";
	}elseif ($showeditexs['item_bonus'] == 9){
		$colorbg = "itembg4";
	}elseif ($showeditexs['item_bonus'] > 9){
		$colorbg = "itembg5";
	}else{
		$colorbg = "itembg1";
	}

	echo "<td class=\"mark armor " . $colorbg . "\">";

		if ($showeditexs['for'] == 0){
		$showitfor = "";
		$showitfor2 = "";
		}else{
		$showitfor2 = "+<font color=gray>" . $showeditexs['for'] . " For</font><br/>";
		}

		if ($showeditexs['vit'] == 0){
		$showitvit = "";
		$showitvit2 = "";
		}else{
		$showitvit2 = "+<font color=green>" . $showeditexs['vit'] . " Vit</font><br/>";
		}

		if ($showeditexs['agi'] == 0){
		$showitagi = "";
		$showitagi2 = "";
		}else{
		$showitagi2 = "+<font color=blue>" . $showeditexs['agi'] . " Agi</font><br/>";
		}

		if ($showeditexs['res'] == 0){
		$showitres = "";
		$showitres2 = "";
		}else{
		$showitres2 = "+<font color=red>" . $showeditexs['res'] . " Res</font>";
		}

		$newefec = ($showeditexs['effectiveness']) + ($showeditexs['item_bonus'] * 2);
		$showitname = "" . $showeditexs['name'] . " + " . $showeditexs['item_bonus'] . "";
		$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>Defesa: " . $newefec . "</font></td><td width=35%><font size=1>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></tr></table>";
		echo "<div title=\"header=[" . $showitname . "] body=[" . $showitinfo . "]\">";
		echo "<div id=\"" . $showeditexs['type'] . "\" class=\"drag " . $showeditexs['id'] . "\"><img src=\"images/itens/" . $showeditexs['img'] . "\" border=\"0\"></div>";
		echo "</div>";

	echo "</td>";
}


$showitenx = $db->execute("select items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.effectiveness, blueprint_items.img, blueprint_items.type from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='shield' and items.status='equipped'", array($player->id));
if ($showitenx->recordcount() == 0)
{
	echo "<td class=\"mark shield itembg1\"><img src=\"images/luva-dir.png\" border=\"0\"></td>";
}else{
	$showeditexs = $showitenx->fetchrow();

	if (($showeditexs['item_bonus'] > 2) and ($showeditexs['item_bonus'] < 6)){
		$colorbg = "itembg2";
	}elseif (($showeditexs['item_bonus'] > 5) and ($showeditexs['item_bonus'] < 9)){
		$colorbg = "itembg3";
	}elseif ($showeditexs['item_bonus'] == 9){
		$colorbg = "itembg4";
	}elseif ($showeditexs['item_bonus'] > 9){
		$colorbg = "itembg5";
	}else{
		$colorbg = "itembg1";
	}

	echo "<td class=\"mark shield " . $colorbg . "\">";

		if ($showeditexs['for'] == 0){
		$showitfor = "";
		$showitfor2 = "";
		}else{
		$showitfor2 = "+<font color=gray>" . $showeditexs['for'] . " For</font><br/>";
		}

		if ($showeditexs['vit'] == 0){
		$showitvit = "";
		$showitvit2 = "";
		}else{
		$showitvit2 = "+<font color=green>" . $showeditexs['vit'] . " Vit</font><br/>";
		}

		if ($showeditexs['agi'] == 0){
		$showitagi = "";
		$showitagi2 = "";
		}else{
		$showitagi2 = "+<font color=blue>" . $showeditexs['agi'] . " Agi</font><br/>";
		}

		if ($showeditexs['res'] == 0){
		$showitres = "";
		$showitres2 = "";
		}else{
		$showitres2 = "+<font color=red>" . $showeditexs['res'] . " Res</font>";
		}

		$newefec = ($showeditexs['effectiveness']) + ($showeditexs['item_bonus'] * 2);
		$showitname = "" . $showeditexs['name'] . " + " . $showeditexs['item_bonus'] . "";
		$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>Defesa: " . $newefec . "</font></td><td width=35%><font size=1>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></tr></table>";
		echo "<div title=\"header=[" . $showitname . "] body=[" . $showitinfo . "]\">";
		echo "<div id=\"" . $showeditexs['type'] . "\" class=\"drag " . $showeditexs['id'] . "\"><img src=\"images/itens/" . $showeditexs['img'] . "\" border=\"0\"></div>";
		echo "</div>";

	echo "</td>";
}


echo "</tr><tr>";


$showitenx = $db->execute("select items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.description, blueprint_items.effectiveness, blueprint_items.img, blueprint_items.type from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='ring' and items.status='equipped'", array($player->id));
if ($showitenx->recordcount() == 0)
{
	echo "<td class=\"mark ring itembg1\"><img src=\"images/anel.png\" border=\"0\"></td>";
}else{
	$showeditexs = $showitenx->fetchrow();

	echo "<td class=\"mark ring itembg1\">";

		if ($showeditexs['for'] == 0){
		$showitfor = "";
		$showitfor2 = "";
		}else{
		$showitfor2 = "+<font color=gray>" . $showeditexs['for'] . " For</font><br/>";
		}

		if ($showeditexs['vit'] == 0){
		$showitvit = "";
		$showitvit2 = "";
		}else{
		$showitvit2 = "+<font color=green>" . $showeditexs['vit'] . " Vit</font><br/>";
		}

		if ($showeditexs['agi'] == 0){
		$showitagi = "";
		$showitagi2 = "";
		}else{
		$showitagi2 = "+<font color=blue>" . $showeditexs['agi'] . " Agi</font><br/>";
		}

		if ($showeditexs['res'] == 0){
		$showitres = "";
		$showitres2 = "";
		}else{
		$showitres2 = "+<font color=red>" . $showeditexs['res'] . " Res</font>";
		}

		$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>" . $showeditexs['description'] . "</font></td><td width=35%><font size=1>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></tr></table>";
		echo "<div title=\"header=[" . $showeditexs['name'] . "] body=[" . $showitinfo . "]\">";
		echo "<div id=\"" . $showeditexs['type'] . "\" class=\"drag " . $showeditexs['id'] . "\"><img src=\"images/itens/" . $showeditexs['img'] . "\" border=\"0\"></div>";
		echo "</div>";

	echo "</td>";
}


$showitenx = $db->execute("select items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.effectiveness, blueprint_items.img, blueprint_items.type from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='legs' and items.status='equipped'", array($player->id));
if ($showitenx->recordcount() == 0)
{
	echo "<td class=\"mark legs itembg1\"><img src=\"images/calca.png\" border=\"0\"></td>";
}else{
	$showeditexs = $showitenx->fetchrow();

	if (($showeditexs['item_bonus'] > 2) and ($showeditexs['item_bonus'] < 6)){
		$colorbg = "itembg2";
	}elseif (($showeditexs['item_bonus'] > 5) and ($showeditexs['item_bonus'] < 9)){
		$colorbg = "itembg3";
	}elseif ($showeditexs['item_bonus'] == 9){
		$colorbg = "itembg4";
	}elseif ($showeditexs['item_bonus'] > 9){
		$colorbg = "itembg5";
	}else{
		$colorbg = "itembg1";
	}

	echo "<td class=\"mark legs " . $colorbg . "\">";

		if ($showeditexs['for'] == 0){
		$showitfor = "";
		$showitfor2 = "";
		}else{
		$showitfor2 = "+<font color=gray>" . $showeditexs['for'] . " For</font><br/>";
		}

		if ($showeditexs['vit'] == 0){
		$showitvit = "";
		$showitvit2 = "";
		}else{
		$showitvit2 = "+<font color=green>" . $showeditexs['vit'] . " Vit</font><br/>";
		}

		if ($showeditexs['agi'] == 0){
		$showitagi = "";
		$showitagi2 = "";
		}else{
		$showitagi2 = "+<font color=blue>" . $showeditexs['agi'] . " Agi</font><br/>";
		}

		if ($showeditexs['res'] == 0){
		$showitres = "";
		$showitres2 = "";
		}else{
		$showitres2 = "+<font color=red>" . $showeditexs['res'] . " Res</font>";
		}

		$newefec = ($showeditexs['effectiveness']) + ($showeditexs['item_bonus'] * 2);
		$showitname = "" . $showeditexs['name'] . " + " . $showeditexs['item_bonus'] . "";
		$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>Defesa: " . $newefec . "</font></td><td width=35%><font size=1>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></tr></table>";
		echo "<div title=\"header=[" . $showitname . "] body=[" . $showitinfo . "]\">";
		echo "<div id=\"" . $showeditexs['type'] . "\" class=\"drag " . $showeditexs['id'] . "\"><img src=\"images/itens/" . $showeditexs['img'] . "\" border=\"0\"></div>";
		echo "</div>";

	echo "</td>";
}


$showitenx = $db->execute("select items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.effectiveness, blueprint_items.img, blueprint_items.type from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='boots' and items.status='equipped'", array($player->id));
if ($showitenx->recordcount() == 0)
{
	echo "<td class=\"mark boots itembg1\"><img src=\"images/botas.png\" border=\"0\"></td>";
}else{
	$showeditexs = $showitenx->fetchrow();

	if (($showeditexs['item_bonus'] > 2) and ($showeditexs['item_bonus'] < 6)){
		$colorbg = "itembg2";
	}elseif (($showeditexs['item_bonus'] > 5) and ($showeditexs['item_bonus'] < 9)){
		$colorbg = "itembg3";
	}elseif ($showeditexs['item_bonus'] == 9){
		$colorbg = "itembg4";
	}elseif ($showeditexs['item_bonus'] > 9){
		$colorbg = "itembg5";
	}else{
		$colorbg = "itembg1";
	}

	echo "<td class=\"mark boots " . $colorbg . "\">";

		if ($showeditexs['for'] == 0){
		$showitfor = "";
		$showitfor2 = "";
		}else{
		$showitfor2 = "+<font color=gray>" . $showeditexs['for'] . " For</font><br/>";
		}

		if ($showeditexs['vit'] == 0){
		$showitvit = "";
		$showitvit2 = "";
		}else{
		$showitvit2 = "+<font color=green>" . $showeditexs['vit'] . " Vit</font><br/>";
		}

		if ($showeditexs['agi'] == 0){
		$showitagi = "";
		$showitagi2 = "";
		}else{
		$showitagi2 = "+<font color=blue>" . $showeditexs['agi'] . " Agi</font><br/>";
		}

		if ($showeditexs['res'] == 0){
		$showitres = "";
		$showitres2 = "";
		}else{
		$showitres2 = "+<font color=red>" . $showeditexs['res'] . " Res</font>";
		}

		$newefec = ($showeditexs['effectiveness']) + ($showeditexs['item_bonus'] * 2);
		$showitname = "" . $showeditexs['name'] . " + " . $showeditexs['item_bonus'] . "";
		$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>Agilidade: " . $newefec . "</font></td><td width=35%><font size=1>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></tr></table>";
		echo "<div title=\"header=[" . $showitname . "] body=[" . $showitinfo . "]\">";
		echo "<div id=\"" . $showeditexs['type'] . "\" class=\"drag " . $showeditexs['id'] . "\"><img src=\"images/itens/" . $showeditexs['img'] . "\" border=\"0\"></div>";
		echo "</div>";

	echo "</td>";
}

	echo "</tr>";
echo "</tbody></table>";
?>