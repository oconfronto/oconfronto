<?php

declare(strict_types=1);

	// Define the function to calculate effectiveness based on item type, base effectiveness, and item bonus
	function calculateEffectiveness(string $type, float $effectiveness, float $itemBonus): float {
		$multiplier = BONUS_MULTIPLIERS[$type] ?? BONUS_MULTIPLIERS['default'];
		return $effectiveness + ($itemBonus * $multiplier);
	}

// BONUS_MULTIPLIERS: Defines the multipliers for specific items or attributes
// The "quiver" item has a multiplier of 1.5, and the default multiplier for other items is 2.0
const BONUS_MULTIPLIERS = [
	'quiver' => 1.5, // Multiplier for quiver item
	'default' => 2.0 // Default multiplier for other items
];
// ATTRIBUTE_LABELS: Maps the attributes to their respective labels
// "shield" is labeled as "Defense" and "quiver" is labeled as "Agility"
$ATTRIBUTE_LABELS = [
	    'shield' => _('Defesa'), // Label for shield attribute (Defense)
	    'quiver' => _('Agilidade') // Label for quiver attribute (Agility)
	];

echo '<table id="table1" align="center">';
echo "<tbody><tr>";

$showitenx = $db->execute("select items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.effectiveness, blueprint_items.img, blueprint_items.type from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='amulet' and items.status='equipped'", [$player->id]);
if ($showitenx->recordcount() == 0) {
	echo '<td class="mark amulet itembg1"><img src="static/images/colar.png" border="0"></td>';
} else {
	$showeditexs = $showitenx->fetchrow();

	if ($showeditexs['item_bonus'] > 2 && $showeditexs['item_bonus'] < 6) {
		$colorbg = "itembg2";
	} elseif ($showeditexs['item_bonus'] > 5 && $showeditexs['item_bonus'] < 9) {
		$colorbg = "itembg3";
	} elseif ($showeditexs['item_bonus'] == 9) {
		$colorbg = "itembg4";
	} elseif ($showeditexs['item_bonus'] > 9) {
		$colorbg = "itembg5";
	} else {
		$colorbg = "itembg1";
	}

	echo '<td class="mark amulet ' . $colorbg . '">';

	if ($showeditexs['for'] == 0) {
		$showitfor = "";
		$showitfor2 = "";
	} else {
		$showitfor2 = "+<font color=gray>" . $showeditexs['for'] . " For</font><br/>";
	}

	if ($showeditexs['vit'] == 0) {
		$showitvit = "";
		$showitvit2 = "";
	} else {
		$showitvit2 = "+<font color=green>" . $showeditexs['vit'] . " Vit</font><br/>";
	}

	if ($showeditexs['agi'] == 0) {
		$showitagi = "";
		$showitagi2 = "";
	} else {
		$showitagi2 = "+<font color=blue>" . $showeditexs['agi'] . " Agi</font><br/>";
	}

	if ($showeditexs['res'] == 0) {
		$showitres = "";
		$showitres2 = "";
	} else {
		$showitres2 = "+<font color=red>" . $showeditexs['res'] . " Res</font>";
	}

	$newefec = ($showeditexs['effectiveness']) + ($showeditexs['item_bonus'] * 2);
	$showitname = "" . $showeditexs['name'] . " + " . $showeditexs['item_bonus'] . "";
	$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>Vitalidade: " . $newefec . "</font></td><td width=35%><font size=1>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></tr></table>";
	echo '<div title="header=[' . $showitname . "] body=[" . $showitinfo . ']">';
	echo '<div id="' . $showeditexs['type'] . '" class="drag ' . $showeditexs['id'] . '"><img src="static/images/itens/' . $showeditexs['img'] . '" border="0"></div>';
	echo "</div>";

	echo "</td>";
}


$showitenx = $db->execute("select items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.effectiveness, blueprint_items.img, blueprint_items.type from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='helmet' and items.status='equipped'", [$player->id]);
if ($showitenx->recordcount() == 0) {
	echo '<td class="mark helmet itembg1"><img src="static/images/elmo.png" border="0"></td>';
} else {
	$showeditexs = $showitenx->fetchrow();

	if ($showeditexs['item_bonus'] > 2 && $showeditexs['item_bonus'] < 6) {
		$colorbg = "itembg2";
	} elseif ($showeditexs['item_bonus'] > 5 && $showeditexs['item_bonus'] < 9) {
		$colorbg = "itembg3";
	} elseif ($showeditexs['item_bonus'] == 9) {
		$colorbg = "itembg4";
	} elseif ($showeditexs['item_bonus'] > 9) {
		$colorbg = "itembg5";
	} else {
		$colorbg = "itembg1";
	}

	echo '<td class="mark helmet ' . $colorbg . '">';

	if ($showeditexs['for'] == 0) {
		$showitfor = "";
		$showitfor2 = "";
	} else {
		$showitfor2 = "+<font color=gray>" . $showeditexs['for'] . " For</font><br/>";
	}

	if ($showeditexs['vit'] == 0) {
		$showitvit = "";
		$showitvit2 = "";
	} else {
		$showitvit2 = "+<font color=green>" . $showeditexs['vit'] . " Vit</font><br/>";
	}

	if ($showeditexs['agi'] == 0) {
		$showitagi = "";
		$showitagi2 = "";
	} else {
		$showitagi2 = "+<font color=blue>" . $showeditexs['agi'] . " Agi</font><br/>";
	}

	if ($showeditexs['res'] == 0) {
		$showitres = "";
		$showitres2 = "";
	} else {
		$showitres2 = "+<font color=red>" . $showeditexs['res'] . " Res</font>";
	}

	$newefec = ($showeditexs['effectiveness']) + ($showeditexs['item_bonus'] * 2);
	$showitname = "" . $showeditexs['name'] . " + " . $showeditexs['item_bonus'] . "";
	$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>Defesa: " . $newefec . "</font></td><td width=35%><font size=1>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></tr></table>";
	echo '<div title="header=[' . $showitname . "] body=[" . $showitinfo . ']">';
	echo '<div id="' . $showeditexs['type'] . '" class="drag ' . $showeditexs['id'] . '"><img src="static/images/itens/' . $showeditexs['img'] . '" border="0"></div>';
	echo "</div>";

	echo "</td>";
}


echo '<td class="mark none">&nbsp;</td>';
echo "</tr><tr>";


$showitenx = $db->execute("select items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.effectiveness, blueprint_items.img, blueprint_items.type from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='weapon' and items.status='equipped'", [$player->id]);
if ($showitenx->recordcount() == 0) {
	$tutorial = $db->execute("select * from `pending` where `pending_id`=2 and `pending_status`=4 and `player_id`=?", [$player->id]);
	if ($tutorial->recordcount() > 0) {
		echo '<td class="mark weapon itembg1"><img src="static/images/itens/show.gif" border="0"></td>';
	} else {
		echo '<td class="mark weapon itembg1"><img src="static/images/luva-esq.png" border="0"></td>';
	}
} else {
	$showeditexs = $showitenx->fetchrow();

	if ($showeditexs['item_bonus'] > 2 && $showeditexs['item_bonus'] < 6) {
		$colorbg = "itembg2";
	} elseif ($showeditexs['item_bonus'] > 5 && $showeditexs['item_bonus'] < 9) {
		$colorbg = "itembg3";
	} elseif ($showeditexs['item_bonus'] == 9) {
		$colorbg = "itembg4";
	} elseif ($showeditexs['item_bonus'] > 9) {
		$colorbg = "itembg5";
	} else {
		$colorbg = "itembg1";
	}

	echo '<td class="mark weapon ' . $colorbg . '">';

	if ($showeditexs['for'] == 0) {
		$showitfor = "";
		$showitfor2 = "";
	} else {
		$showitfor2 = "+<font color=gray>" . $showeditexs['for'] . " For</font><br/>";
	}

	if ($showeditexs['vit'] == 0) {
		$showitvit = "";
		$showitvit2 = "";
	} else {
		$showitvit2 = "+<font color=green>" . $showeditexs['vit'] . " Vit</font><br/>";
	}

	if ($showeditexs['agi'] == 0) {
		$showitagi = "";
		$showitagi2 = "";
	} else {
		$showitagi2 = "+<font color=blue>" . $showeditexs['agi'] . " Agi</font><br/>";
	}

	if ($showeditexs['res'] == 0) {
		$showitres = "";
		$showitres2 = "";
	} else {
		$showitres2 = "+<font color=red>" . $showeditexs['res'] . " Res</font>";
	}

	$newefec = ($showeditexs['effectiveness']) + ($showeditexs['item_bonus'] * 2);
	$showitname = "" . $showeditexs['name'] . " + " . $showeditexs['item_bonus'] . "";
	$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>Ataque: " . $newefec . "</font></td><td width=35%><font size=1>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></tr></table>";
	echo '<div title="header=[' . $showitname . "] body=[" . $showitinfo . ']">';
	echo '<div id="' . $showeditexs['type'] . '" class="drag ' . $showeditexs['id'] . '"><img src="static/images/itens/' . $showeditexs['img'] . '" border="0"></div>';
	echo "</div>";

	echo "</td>";
}


$showitenx = $db->execute("select items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.effectiveness, blueprint_items.img, blueprint_items.type from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='armor' and items.status='equipped'", [$player->id]);
if ($showitenx->recordcount() == 0) {
	echo '<td class="mark armor itembg1"><img src="static/images/armor.png" border="0"></td>';
} else {
	$showeditexs = $showitenx->fetchrow();

	if ($showeditexs['item_bonus'] > 2 && $showeditexs['item_bonus'] < 6) {
		$colorbg = "itembg2";
	} elseif ($showeditexs['item_bonus'] > 5 && $showeditexs['item_bonus'] < 9) {
		$colorbg = "itembg3";
	} elseif ($showeditexs['item_bonus'] == 9) {
		$colorbg = "itembg4";
	} elseif ($showeditexs['item_bonus'] > 9) {
		$colorbg = "itembg5";
	} else {
		$colorbg = "itembg1";
	}

	echo '<td class="mark armor ' . $colorbg . '">';

	if ($showeditexs['for'] == 0) {
		$showitfor = "";
		$showitfor2 = "";
	} else {
		$showitfor2 = "+<font color=gray>" . $showeditexs['for'] . " For</font><br/>";
	}

	if ($showeditexs['vit'] == 0) {
		$showitvit = "";
		$showitvit2 = "";
	} else {
		$showitvit2 = "+<font color=green>" . $showeditexs['vit'] . " Vit</font><br/>";
	}

	if ($showeditexs['agi'] == 0) {
		$showitagi = "";
		$showitagi2 = "";
	} else {
		$showitagi2 = "+<font color=blue>" . $showeditexs['agi'] . " Agi</font><br/>";
	}

	if ($showeditexs['res'] == 0) {
		$showitres = "";
		$showitres2 = "";
	} else {
		$showitres2 = "+<font color=red>" . $showeditexs['res'] . " Res</font>";
	}

	$newefec = ($showeditexs['effectiveness']) + ($showeditexs['item_bonus'] * 2);
	$showitname = "" . $showeditexs['name'] . " + " . $showeditexs['item_bonus'] . "";
	$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>Defesa: " . $newefec . "</font></td><td width=35%><font size=1>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></tr></table>";
	echo '<div title="header=[' . $showitname . "] body=[" . $showitinfo . ']">';
	echo '<div id="' . $showeditexs['type'] . '" class="drag ' . $showeditexs['id'] . '"><img src="static/images/itens/' . $showeditexs['img'] . '" border="0"></div>';
	echo "</div>";

	echo "</td>";
}


// Objective: This code is designed to display an item (either a shield or a quiver) in the player's inventory
// with a background color based on the item's bonus. If no shield or quiver is equipped, a default image is shown.

// Execute 	a query to fetch the items of the player that are either a shield or a quiver and are equipped
$showitenx = $db->execute("SELECT items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.effectiveness, blueprint_items.img, blueprint_items.type FROM `items`, `blueprint_items` WHERE blueprint_items.id = items.item_id AND items.player_id = ? AND (blueprint_items.type = 'shield' OR blueprint_items.type = 'quiver') AND items.status = 'equipped'", [$player->id]);
if ($showitenx->recordcount() == 0) {
	echo '<td class="mark shield quiver itembg1"><img src="static/images/luva-dir.png" border="0"></td>';
} else {
	$showeditexs = $showitenx->fetchrow();

	if ($showeditexs['item_bonus'] > 2 && $showeditexs['item_bonus'] < 6) {
		$colorbg = "itembg2";
	} elseif ($showeditexs['item_bonus'] > 5 && $showeditexs['item_bonus'] < 9) {
		$colorbg = "itembg3";
	} elseif ($showeditexs['item_bonus'] == 9) {
		$colorbg = "itembg4";
	} elseif ($showeditexs['item_bonus'] > 9) {
		$colorbg = "itembg5";
	} else {
		$colorbg = "itembg1";
	}

	// If no items are equipped, display a default image for the item slot (e.g., a glove icon)
	echo '<td class="mark shield quiver ' . $colorbg . '">';

	if ($showeditexs['for'] == 0) {
		$showitfor = "";
		$showitfor2 = "";
	} else {
		$showitfor2 = "+<font color=gray>" . $showeditexs['for'] . " For</font><br/>";
	}

	if ($showeditexs['vit'] == 0) {
		$showitvit = "";
		$showitvit2 = "";
	} else {
		$showitvit2 = "+<font color=green>" . $showeditexs['vit'] . " Vit</font><br/>";
	}

	if ($showeditexs['agi'] == 0) {
		$showitagi = "";
		$showitagi2 = "";
	} else {
		$showitagi2 = "+<font color=blue>" . $showeditexs['agi'] . " Agi</font><br/>";
	}

	if ($showeditexs['res'] == 0) {
		$showitres = "";
		$showitres2 = "";
	} else {
		$showitres2 = "+<font color=red>" . $showeditexs['res'] . " Res</font>";
	}

	// Objective: This code calculates the effectiveness of an item based on its bonus,
	// generates an item name with its bonus, retrieves the appropriate attribute label
	// (e.g., "Agility" for a quiver or "Defense" for a shield), and displays the item
	// with the calculated effectiveness and other attributes (e.g., vit, agi, res).
	$newefec = ($showeditexs['effectiveness']) + ($showeditexs['item_bonus'] * 2);
	$showitname = "" . $showeditexs['name'] . " + " . $showeditexs['item_bonus'] . "";
	$attributeLabel = $ATTRIBUTE_LABELS[$showeditexs['type']] ?? $showeditexs['type'];
	$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>" . $attributeLabel . ": " . $newefec . "</font></td><td width=35%><font size=1>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></tr></table>";
	echo '<div title="header=[' . $showitname . "] body=[" . $showitinfo . ']">';
	echo '<div id="' . $showeditexs['type'] . '" class="drag ' . $showeditexs['id'] . '"><img src="static/images/itens/' . $showeditexs['img'] . '" border="0"></div>';
	echo "</div>";

	echo "</td>";
}


echo "</tr><tr>";


$showitenx = $db->execute("select items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.description, blueprint_items.effectiveness, blueprint_items.img, blueprint_items.type from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='ring' and items.status='equipped'", [$player->id]);
if ($showitenx->recordcount() == 0) {
	echo '<td class="mark ring itembg1"><img src="static/images/anel.png" border="0"></td>';
} else {
	$showeditexs = $showitenx->fetchrow();

	echo '<td class="mark ring itembg1">';

	if ($showeditexs['for'] == 0) {
		$showitfor = "";
		$showitfor2 = "";
	} else {
		$showitfor2 = "+<font color=gray>" . $showeditexs['for'] . " For</font><br/>";
	}

	if ($showeditexs['vit'] == 0) {
		$showitvit = "";
		$showitvit2 = "";
	} else {
		$showitvit2 = "+<font color=green>" . $showeditexs['vit'] . " Vit</font><br/>";
	}

	if ($showeditexs['agi'] == 0) {
		$showitagi = "";
		$showitagi2 = "";
	} else {
		$showitagi2 = "+<font color=blue>" . $showeditexs['agi'] . " Agi</font><br/>";
	}

	if ($showeditexs['res'] == 0) {
		$showitres = "";
		$showitres2 = "";
	} else {
		$showitres2 = "+<font color=red>" . $showeditexs['res'] . " Res</font>";
	}

	$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>" . $showeditexs['description'] . "</font></td><td width=35%><font size=1>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></tr></table>";
	echo '<div title="header=[' . $showeditexs['name'] . "] body=[" . $showitinfo . ']">';
	echo '<div id="' . $showeditexs['type'] . '" class="drag ' . $showeditexs['id'] . '"><img src="static/images/itens/' . $showeditexs['img'] . '" border="0"></div>';
	echo "</div>";

	echo "</td>";
}


$showitenx = $db->execute("select items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.effectiveness, blueprint_items.img, blueprint_items.type from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='legs' and items.status='equipped'", [$player->id]);
if ($showitenx->recordcount() == 0) {
	echo '<td class="mark legs itembg1"><img src="static/images/calca.png" border="0"></td>';
} else {
	$showeditexs = $showitenx->fetchrow();

	if ($showeditexs['item_bonus'] > 2 && $showeditexs['item_bonus'] < 6) {
		$colorbg = "itembg2";
	} elseif ($showeditexs['item_bonus'] > 5 && $showeditexs['item_bonus'] < 9) {
		$colorbg = "itembg3";
	} elseif ($showeditexs['item_bonus'] == 9) {
		$colorbg = "itembg4";
	} elseif ($showeditexs['item_bonus'] > 9) {
		$colorbg = "itembg5";
	} else {
		$colorbg = "itembg1";
	}

	echo '<td class="mark legs ' . $colorbg . '">';

	if ($showeditexs['for'] == 0) {
		$showitfor = "";
		$showitfor2 = "";
	} else {
		$showitfor2 = "+<font color=gray>" . $showeditexs['for'] . " For</font><br/>";
	}

	if ($showeditexs['vit'] == 0) {
		$showitvit = "";
		$showitvit2 = "";
	} else {
		$showitvit2 = "+<font color=green>" . $showeditexs['vit'] . " Vit</font><br/>";
	}

	if ($showeditexs['agi'] == 0) {
		$showitagi = "";
		$showitagi2 = "";
	} else {
		$showitagi2 = "+<font color=blue>" . $showeditexs['agi'] . " Agi</font><br/>";
	}

	if ($showeditexs['res'] == 0) {
		$showitres = "";
		$showitres2 = "";
	} else {
		$showitres2 = "+<font color=red>" . $showeditexs['res'] . " Res</font>";
	}

	// Objective: This code defines a function `calculateEffectiveness` to compute the effectiveness
	// of an item based on its type, effectiveness, and bonus. The function uses a multiplier
	// specific to the item type (e.g., shield, quiver) to adjust the total effectiveness.

	$newefec = calculateEffectiveness($showeditexs['type'], $showeditexs['effectiveness'], $showeditexs['item_bonus']);
	$showitname = "" . $showeditexs['name'] . " + " . $showeditexs['item_bonus'] . "";
	$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>Defesa: " . $newefec . "</font></td><td width=35%><font size=1>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></tr></table>";
	echo '<div title="header=[' . $showitname . "] body=[" . $showitinfo . ']">';
	echo '<div id="' . $showeditexs['type'] . '" class="drag ' . $showeditexs['id'] . '"><img src="static/images/itens/' . $showeditexs['img'] . '" border="0"></div>';
	echo "</div>";

	echo "</td>";
}


$showitenx = $db->execute("select items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, blueprint_items.name, blueprint_items.effectiveness, blueprint_items.img, blueprint_items.type from `items`, `blueprint_items` where blueprint_items.id=items.item_id and items.player_id=? and blueprint_items.type='boots' and items.status='equipped'", [$player->id]);
if ($showitenx->recordcount() == 0) {
	echo '<td class="mark boots itembg1"><img src="static/images/botas.png" border="0"></td>';
} else {
	$showeditexs = $showitenx->fetchrow();

	if ($showeditexs['item_bonus'] > 2 && $showeditexs['item_bonus'] < 6) {
		$colorbg = "itembg2";
	} elseif ($showeditexs['item_bonus'] > 5 && $showeditexs['item_bonus'] < 9) {
		$colorbg = "itembg3";
	} elseif ($showeditexs['item_bonus'] == 9) {
		$colorbg = "itembg4";
	} elseif ($showeditexs['item_bonus'] > 9) {
		$colorbg = "itembg5";
	} else {
		$colorbg = "itembg1";
	}

	echo '<td class="mark boots ' . $colorbg . '">';

	if ($showeditexs['for'] == 0) {
		$showitfor = "";
		$showitfor2 = "";
	} else {
		$showitfor2 = "+<font color=gray>" . $showeditexs['for'] . " For</font><br/>";
	}

	if ($showeditexs['vit'] == 0) {
		$showitvit = "";
		$showitvit2 = "";
	} else {
		$showitvit2 = "+<font color=green>" . $showeditexs['vit'] . " Vit</font><br/>";
	}

	if ($showeditexs['agi'] == 0) {
		$showitagi = "";
		$showitagi2 = "";
	} else {
		$showitagi2 = "+<font color=blue>" . $showeditexs['agi'] . " Agi</font><br/>";
	}

	if ($showeditexs['res'] == 0) {
		$showitres = "";
		$showitres2 = "";
	} else {
		$showitres2 = "+<font color=red>" . $showeditexs['res'] . " Res</font>";
	}

	$newefec = ($showeditexs['effectiveness']) + ($showeditexs['item_bonus'] * 2);
	$showitname = "" . $showeditexs['name'] . " + " . $showeditexs['item_bonus'] . "";
	$showitinfo = "<table width=100%><tr><td width=65%><font size=1px>Agilidade: " . $newefec . "</font></td><td width=35%><font size=1>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></tr></table>";
	echo '<div title="header=[' . $showitname . "] body=[" . $showitinfo . ']">';
	echo '<div id="' . $showeditexs['type'] . '" class="drag ' . $showeditexs['id'] . '"><img src="static/images/itens/' . $showeditexs['img'] . '" border="0"></div>';
	echo "</div>";

	echo "</td>";
}

echo "</tr>";
echo "</tbody></table>";
