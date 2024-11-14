<?php

declare(strict_types=1);

function displayItem($db, $player, $itemTypes): void
{
    echo '<td><div class="bg_item1">';

    // Allows $itemTypes to be an array or a single string
    $typeQuery = is_array($itemTypes) ? 'IN (' . implode(',', array_fill(0, count($itemTypes), '?')) . ')' : '= ?';
    $params = is_array($itemTypes) ? array_merge([$player->id], $itemTypes) : [$player->id, $itemTypes];

    try {
        $query = "SELECT items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, 
                         blueprint_items.name, blueprint_items.effectiveness, blueprint_items.img, blueprint_items.type
                  FROM `items`, `blueprint_items` 
                  WHERE blueprint_items.id = items.item_id 
                    AND items.player_id = ? 
                    AND blueprint_items.type $typeQuery 
                    AND items.status = 'equipped'";

        $showitenx = $db->execute($query, $params);

        if ($showitenx->recordcount() == 0) {
            echo '&nbsp;';
        } else {
            while ($showeditexs = $showitenx->fetchrow()) {
                // Manages attributes
                $showitfor2 = $showeditexs['for'] > 0 ? "+<font color=gray>" . htmlspecialchars((string) $showeditexs['for']) . " For</font><br/>" : "";
                $showitvit2 = $showeditexs['vit'] > 0 ? "+<font color=green>" . htmlspecialchars((string) $showeditexs['vit']) . " Vit</font><br/>" : "";
                $showitagi2 = $showeditexs['agi'] > 0 ? "+<font color=blue>" . htmlspecialchars((string) $showeditexs['agi']) . " Agi</font><br/>" : "";
                $showitres2 = $showeditexs['res'] > 0 ? "+<font color=red>" . htmlspecialchars((string) $showeditexs['res']) . " Res</font>" : "";

                // Sets background color based on bonus
                $itemClass = 'bg_item1';
                if ($showeditexs['item_bonus'] > 9) {
                    $itemClass = 'bg_item5';
                } elseif ($showeditexs['item_bonus'] == 9) {
                    $itemClass = 'bg_item4';
                } elseif ($showeditexs['item_bonus'] > 5) {
                    $itemClass = 'bg_item3';
                } elseif ($showeditexs['item_bonus'] > 2) {
                    $itemClass = 'bg_item2';
                }

                // Choose the correct description based on the item type
                // Set the attribute based on the item type
               
    switch ($showeditexs['type']) {
    case 'shield':
    case 'armor':
    case 'legs':
        $attributeLabel = 'Defesa';  // For shield, armor and pants
        break;
    case 'quiver':
    case 'boots':
        $attributeLabel = 'Agilidade';  // For quiver and boots
        break;
    case 'amulet':
        $attributeLabel = 'Vitalidade';  // For the necklace
        break;
    case 'weapon':
        $attributeLabel = 'Ataque';  // For the weapon
        break;
    case 'helmet':
        $attributeLabel = 'Defesa';  // For the helmet
        break;
    default:
        $attributeLabel = 'Atributo desconhecido';  // If the item type is not recognized (Example: adding possible runes)
        break;
}
                $newefec = ($showeditexs['effectiveness']) + ($showeditexs['item_bonus'] * 2);
                $showitname = htmlspecialchars($showeditexs['name'] . " + " . $showeditexs['item_bonus']);
                $showitinfo = "<table width=100%><tr><td width=65%><font size=1px>$attributeLabel: " . htmlspecialchars((string) $newefec) . "</font></td><td width=35%><font size=1px>" . $showitfor2 . $showitvit2 . $showitagi2 . $showitres2 . "</font></td></tr></table>";

                echo sprintf("<div class='%s'>", $itemClass);
                echo sprintf('<div title="header=[%s] body=[%s]">', $showitname, $showitinfo);
                echo '<img src="static/images/itens/' . htmlspecialchars((string) $showeditexs['img']) . '"/>';
                echo "</div></div>";
            }
        }
    } catch (Exception $exception) {
        echo "Error: " . htmlspecialchars($exception->getMessage());
    }

    echo '</div></td>';
}
?>

<div class="mochilaa">
    <table class="mochila" border="0" width="170" align="center">
        <tr>
            <?php displayItem($db, $player, 'amulet'); ?>
            <?php displayItem($db, $player, 'helmet'); ?>
            <td style="padding: 5px;text-align: center;"><a href="inventory.php"><img src="static/images/bag.gif" alt="Inventory"></a></td>
        </tr>
        <tr>
            <?php displayItem($db, $player, 'weapon'); ?>
            <?php displayItem($db, $player, 'armor'); ?>
            <?php displayItem($db, $player, ['shield', 'quiver']); ?>
        </tr>
        <tr>
            <?php displayItem($db, $player, 'ring'); ?>
            <?php displayItem($db, $player, 'legs'); ?>
            <?php displayItem($db, $player, 'boots'); ?>
        </tr>
    </table>
</div>
