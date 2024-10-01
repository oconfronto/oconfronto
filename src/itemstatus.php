<?php

$types = array('amulet', 'armor', 'boots', 'helmet', 'legs', 'shield', 'weapon', 'ring');
$attributes = array('for', 'vit', 'agi', 'res');

// Initialize arrays to store bonuses
$bonuses = array(
    'for' => 0,
    'vit' => 0,
    'agi' => 0,
    'res' => 0
);

foreach ($attributes as $attribute) {
    foreach ($types as $type) {
        $query = "SELECT items.$attribute, blueprint_items.id 
                  FROM `items`, `blueprint_items` 
                  WHERE items.player_id = ? 
                  AND blueprint_items.id = items.item_id 
                  AND blueprint_items.type = ? 
                  AND items.status = 'equipped'";

        $result = $db->execute($query, array($player->id, $type));
        $row = $result->fetchrow();

        // Sum the respective attribute bonus
        if ($row) {
            $bonuses[$attribute] += $row[$attribute];
        }
    }
}

// Assign bonuses to variables
$forcaadebonus = $bonuses['for'];
$vitalidadeeeeebonus = $bonuses['vit'];
$agilidadeeedebonus = $bonuses['agi'];
$resistenciaaaadebonus = $bonuses['res'];

?>
