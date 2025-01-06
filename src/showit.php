<?php

declare(strict_types=1);

function displayItem($db, $player, $itemType): void
{
    try {
        $query = "SELECT items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, 
                         blueprint_items.name, blueprint_items.effectiveness, blueprint_items.img 
                  FROM `items`, `blueprint_items` 
                  WHERE blueprint_items.id = items.item_id 
                    AND items.player_id = ? 
                    AND blueprint_items.type = ? 
                    AND items.status = 'equipped'";

        $showitenx = $db->execute($query, [$player->id, $itemType]);

        if ($showitenx->recordcount() == 0) {
            echo '&nbsp;';
        } else {
            while ($showeditexs = $showitenx->fetchrow()) {
                // Convert integer values to strings before passing to htmlspecialchars
                $showitfor2 = ($showeditexs['for'] ?? null) > 0 ? "+<font color=gray>" . htmlspecialchars((string) ($showeditexs['for'] ?? null)) . " For</font><br/>" : "";
                $showitvit2 = ($showeditexs['vit'] ?? null) > 0 ? "+<font color=green>" . htmlspecialchars((string) ($showeditexs['vit'] ?? null)) . " Vit</font><br/>" : "";
                $showitagi2 = ($showeditexs['agi'] ?? null) > 0 ? "+<font color=blue>" . htmlspecialchars((string) ($showeditexs['agi'] ?? null)) . " Agi</font><br/>" : "";
                $showitres2 = ($showeditexs['res'] ?? null) > 0 ? "+<font color=red>" . htmlspecialchars((string) ($showeditexs['res'] ?? null)) . " Res</font>" : "";

                $newefec = ($showeditexs['effectiveness']) + ($showeditexs['item_bonus'] * 2);
                $showitname = htmlspecialchars($showeditexs['name'] . " + " . $showeditexs['item_bonus']);
                $showitinfo = "<table width=100%><tr><td width=65%><font size=1px>Effectiveness: " . htmlspecialchars((string) $newefec) . "</font></td><td width=35%><font size=1px>" . $showitfor2 . $showitvit2 . $showitagi2 . $showitres2 . "</font></td></tr></table>";

                echo sprintf('<div title="header=[%s] body=[%s]">', $showitname, $showitinfo);
                echo '<img src="static/images/itens/' . htmlspecialchars((string) ($showeditexs['img'] ?? null)) . '"/>';
                echo "</div>";
            }
        }
    } catch (Exception $exception) {
        echo "Error: " . htmlspecialchars($exception->getMessage());
    }
}
?>

<inventory-container>
    <equipment-container>
        <?php displayItem($db, $player, 'amulet'); ?>
    </equipment-container>
    <equipment-container>
        <?php displayItem($db, $player, 'helmet'); ?>
    </equipment-container>
    <a href="inventory.php" class="inventory-button"><img src="static/images/bag.gif" alt="inventory"></a>
    <equipment-container>
        <?php displayItem($db, $player, 'weapon'); ?>
    </equipment-container>
    <equipment-container>
        <?php displayItem($db, $player, 'armor'); ?>
    </equipment-container>
    <equipment-container>
        <?php displayItem($db, $player, 'shield'); ?>
    </equipment-container>
    <equipment-container>
        <?php displayItem($db, $player, 'ring'); ?>
    </equipment-container>
    <equipment-container>
        <?php displayItem($db, $player, 'legs'); ?>
    </equipment-container>
    <equipment-container>
        <?php displayItem($db, $player, 'boots'); ?>
    </equipment-container>
</inventory-container>
