<div class="mochilaa">
<table class="mochila" border="0px" width="170px" align="center"><tr>

<td><?php
$showitenx = $db->execute("select items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.effectiveness, blueprint_items.img from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='amulet' and items.status='equipped'", array($player->id));

if ($showitenx->recordcount() == 0)
{
	echo "<div class=\"bg_item1\">";
	echo "&nbsp;";
}
else
{
	while($showeditexs = $showitenx->fetchrow())
	{

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


		if (($showeditexs['item_bonus'] > 2) and ($showeditexs['item_bonus'] < 6)){
		echo "<div class=\"bg_item2\">";
		}elseif (($showeditexs['item_bonus'] > 5) and ($showeditexs['item_bonus'] < 9)){
		echo "<div class=\"bg_item3\">";
		}elseif ($showeditexs['item_bonus'] == 9){
		echo "<div class=\"bg_item4\">";
		}elseif ($showeditexs['item_bonus'] > 9){
		echo "<div class=\"bg_item5\">";
		}else{
		echo "<div class=\"bg_item1\">";
		}

		$newefec = ($showeditexs['effectiveness']) + ($showeditexs['item_bonus'] * 2);
		$showitname = "" . $showeditexs['name'] . " + " . $showeditexs['item_bonus'] . "";
		$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>Vitalidade: " . $newefec . "</font></td><td width=35%><font size=1px>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></tr></table>";
		echo "<div title=\"header=[" . $showitname . "] body=[" . $showitinfo . "]\">";
		echo "<img src=\"static/images/itens/" . $showeditexs['img'] . "\"/>";
		echo "</div>";
	}
}
echo "</div>";
?></td>

<td><?php
$showitenx = $db->execute("select items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.effectiveness, blueprint_items.img from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='helmet' and items.status='equipped'", array($player->id));
if ($showitenx->recordcount() == 0)
{
	echo "<div class=\"bg_item1\">";
	echo "&nbsp;";
}
else
{
	while($showeditexs = $showitenx->fetchrow())
	{

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


		if (($showeditexs['item_bonus'] > 2) and ($showeditexs['item_bonus'] < 6)){
		echo "<div class=\"bg_item2\">";
		}elseif (($showeditexs['item_bonus'] > 5) and ($showeditexs['item_bonus'] < 9)){
		echo "<div class=\"bg_item3\">";
		}elseif ($showeditexs['item_bonus'] == 9){
		echo "<div class=\"bg_item4\">";
		}elseif ($showeditexs['item_bonus'] > 9){
		echo "<div class=\"bg_item5\">";
		}else{
		echo "<div class=\"bg_item1\">";
		}

		$newefec = ($showeditexs['effectiveness']) + ($showeditexs['item_bonus'] * 2);
		$showitname = "" . $showeditexs['name'] . " + " . $showeditexs['item_bonus'] . "";
		$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>Defesa: " . $newefec . "</font></td><td width=35%><font size=1px>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></tr></table>";
		echo "<div title=\"header=[" . $showitname . "] body=[" . $showitinfo . "]\">";
		echo "<img src=\"static/images/itens/" . $showeditexs['img'] . "\"/>";
		echo "</div>";
	}
}
echo "</div>";
?></td>

<td style="padding: 5px;text-align: center;"><a href="inventory.php"><img src="static/images/bag.gif" ></a></td>
<!-- <td>&nbsp;</td> -->

</tr><tr>
<td><?php
$showitenx = $db->execute("select items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.effectiveness, blueprint_items.img from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='weapon' and items.status='equipped'", array($player->id));
if ($showitenx->recordcount() == 0)
{
	echo "<div class=\"bg_item1\">";
	echo "&nbsp;";
}
else
{
	while($showeditexs = $showitenx->fetchrow())
	{

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


		if (($showeditexs['item_bonus'] > 2) and ($showeditexs['item_bonus'] < 6)){
		echo "<div class=\"bg_item2\">";
		}elseif (($showeditexs['item_bonus'] > 5) and ($showeditexs['item_bonus'] < 9)){
		echo "<div class=\"bg_item3\">";
		}elseif ($showeditexs['item_bonus'] == 9){
		echo "<div class=\"bg_item4\">";
		}elseif ($showeditexs['item_bonus'] > 9){
		echo "<div class=\"bg_item5\">";
		}else{
		echo "<div class=\"bg_item1\">";
		}

		$newefec = ($showeditexs['effectiveness']) + ($showeditexs['item_bonus'] * 2);
		$showitname = "" . $showeditexs['name'] . " + " . $showeditexs['item_bonus'] . "";
		$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>Ataque: " . $newefec . "</font></td><td width=35%><font size=1px>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></tr></table>";
		echo "<div title=\"header=[" . $showitname . "] body=[" . $showitinfo . "]\">";
		echo "<img src=\"static/images/itens/" . $showeditexs['img'] . "\"/>";
		echo "</div>";
	}
}
echo "</div>";
?></td>

<td><?php
$showitenx = $db->execute("select items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.effectiveness, blueprint_items.img from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='armor' and items.status='equipped'", array($player->id));
if ($showitenx->recordcount() == 0)
{
	echo "<div class=\"bg_item1\">";
	echo "&nbsp;";
}
else
{
	while($showeditexs = $showitenx->fetchrow())
	{

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


		if (($showeditexs['item_bonus'] > 2) and ($showeditexs['item_bonus'] < 6)){
		echo "<div class=\"bg_item2\">";
		}elseif (($showeditexs['item_bonus'] > 5) and ($showeditexs['item_bonus'] < 9)){
		echo "<div class=\"bg_item3\">";
		}elseif ($showeditexs['item_bonus'] == 9){
		echo "<div class=\"bg_item4\">";
		}elseif ($showeditexs['item_bonus'] > 9){
		echo "<div class=\"bg_item5\">";
		}else{
		echo "<div class=\"bg_item1\">";
		}

		$newefec = ($showeditexs['effectiveness']) + ($showeditexs['item_bonus'] * 2);
		$showitname = "" . $showeditexs['name'] . " + " . $showeditexs['item_bonus'] . "";
		$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>Defesa: " . $newefec . "</font></td><td width=35%><font size=1px>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></tr></table>";
		echo "<div title=\"header=[" . $showitname . "] body=[" . $showitinfo . "]\">";
		echo "<img src=\"static/images/itens/" . $showeditexs['img'] . "\"/>";
		echo "</div>";
	}
}
echo "</div>";
?></td>

<td><?php
$showitenx = $db->execute("select items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.effectiveness, blueprint_items.img from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='shield' and items.status='equipped'", array($player->id));
if ($showitenx->recordcount() == 0)
{
	echo "<div class=\"bg_item1\">";
	echo "&nbsp;";
}
else
{
	while($showeditexs = $showitenx->fetchrow())
	{

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


		if (($showeditexs['item_bonus'] > 2) and ($showeditexs['item_bonus'] < 6)){
		echo "<div class=\"bg_item2\">";
		}elseif (($showeditexs['item_bonus'] > 5) and ($showeditexs['item_bonus'] < 9)){
		echo "<div class=\"bg_item3\">";
		}elseif ($showeditexs['item_bonus'] == 9){
		echo "<div class=\"bg_item4\">";
		}elseif ($showeditexs['item_bonus'] > 9){
		echo "<div class=\"bg_item5\">";
		}else{
		echo "<div class=\"bg_item1\">";
		}

		$newefec = ($showeditexs['effectiveness']) + ($showeditexs['item_bonus'] * 2);
		$showitname = "" . $showeditexs['name'] . " + " . $showeditexs['item_bonus'] . "";
		$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>Defesa: " . $newefec . "</font></td><td width=35%><font size=1px>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></tr></table>";
		echo "<div title=\"header=[" . $showitname . "] body=[" . $showitinfo . "]\">";
		echo "<img src=\"static/images/itens/" . $showeditexs['img'] . "\"/>";
		echo "</div>";
	}
}
echo "</div>";
?></td></tr>
<tr>
<td><?php
$showitenx = $db->execute("select items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.description, blueprint_items.effectiveness, blueprint_items.img from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='ring' and items.status='equipped'", array($player->id));
if ($showitenx->recordcount() == 0)
{
	echo "<div class=\"bg_item1\">";
	echo "&nbsp;";
}
else
{
	while($showeditexs = $showitenx->fetchrow())
	{

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

		echo "<div class=\"bg_item1\">";
		$newefec = ($showeditexs['effectiveness']) + ($showeditexs['item_bonus'] * 2);
		$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>" . $showeditexs['description'] . "</font></td><td width=35%><font size=1px>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></tr></table>";
		echo "<div title=\"header=[" . $showeditexs['name'] . "] body=[" . $showitinfo . "]\">";
		echo "<img src=\"static/images/itens/" . $showeditexs['img'] . "\"/>";
		echo "</div>";
	}
}
echo "</div>";
?></td>

<td><?php
$showitenx = $db->execute("select items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.effectiveness, blueprint_items.img from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='legs' and items.status='equipped'", array($player->id));
if ($showitenx->recordcount() == 0)
{
	echo "<div class=\"bg_item1\">";
	echo "&nbsp;";
}
else
{
	while($showeditexs = $showitenx->fetchrow())
	{

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


		if (($showeditexs['item_bonus'] > 2) and ($showeditexs['item_bonus'] < 6)){
		echo "<div class=\"bg_item2\">";
		}elseif (($showeditexs['item_bonus'] > 5) and ($showeditexs['item_bonus'] < 9)){
		echo "<div class=\"bg_item3\">";
		}elseif ($showeditexs['item_bonus'] == 9){
		echo "<div class=\"bg_item4\">";
		}elseif ($showeditexs['item_bonus'] > 9){
		echo "<div class=\"bg_item5\">";
		}else{
		echo "<div class=\"bg_item1\">";
		}

		$newefec = ($showeditexs['effectiveness']) + ($showeditexs['item_bonus'] * 2);
		$showitname = "" . $showeditexs['name'] . " + " . $showeditexs['item_bonus'] . "";
		$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>Defesa: " . $newefec . "</font></td><td width=35%><font size=1px>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></tr></table>";
		echo "<div title=\"header=[" . $showitname . "] body=[" . $showitinfo . "]\">";
		echo "<img src=\"static/images/itens/" . $showeditexs['img'] . "\"/>";
		echo "</div>";
	}
}
echo "</div>";
?></td>
<td><?php
$showitenx = $db->execute("select items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.effectiveness, blueprint_items.img from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='boots' and items.status='equipped'", array($player->id));
if ($showitenx->recordcount() == 0)
{
	echo "<div class=\"bg_item1\">";
	echo "&nbsp;";
}
else
{
	while($showeditexs = $showitenx->fetchrow())
	{

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


		if (($showeditexs['item_bonus'] > 2) and ($showeditexs['item_bonus'] < 6)){
		echo "<div class=\"bg_item2\">";
		}elseif (($showeditexs['item_bonus'] > 5) and ($showeditexs['item_bonus'] < 9)){
		echo "<div class=\"bg_item3\">";
		}elseif ($showeditexs['item_bonus'] == 9){
		echo "<div class=\"bg_item4\">";
		}elseif ($showeditexs['item_bonus'] > 9){
		echo "<div class=\"bg_item5\">";
		}else{
		echo "<div class=\"bg_item1\">";
		}

		$newefec = ($showeditexs['effectiveness']) + ($showeditexs['item_bonus'] * 2);
		$showitname = "" . $showeditexs['name'] . " + " . $showeditexs['item_bonus'] . "";
		$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>Agilidade: " . $newefec . "</font></td><td width=35%><font size=1px>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></tr></table>";
		echo "<div title=\"header=[" . $showitname . "] body=[" . $showitinfo . "]\">";
		echo "<img src=\"static/images/itens/" . $showeditexs['img'] . "\"/>";
		echo "</div>";
	}
}
echo "</div>";
    ?></td>
</tr>
</table>
</div>