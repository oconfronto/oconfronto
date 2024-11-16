<?php

declare(strict_types=1);

function displayItem($db, $player, $itemTypes): void
{
    echo '<td><div class="bg_item1">';

    // Permite que $itemTypes seja um array ou uma string única
    $typeQuery = is_array($itemTypes) ? 'IN (' . implode(',', array_fill(0, count($itemTypes), '?')) . ')' : '= ?';
    $params = is_array($itemTypes) ? array_merge([$player->id], $itemTypes) : [$player->id, $itemTypes];

    try {
        $query = "SELECT items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, 
                         blueprint_items.name, blueprint_items.description, blueprint_items.effectiveness, blueprint_items.img, blueprint_items.type
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
                // Gerencia os atributos
                $showitfor2 = $showeditexs['for'] > 0 ? "+<font color=gray>" . htmlspecialchars((string) $showeditexs['for']) . " For</font><br/>" : "";
                $showitvit2 = $showeditexs['vit'] > 0 ? "+<font color=green>" . htmlspecialchars((string) $showeditexs['vit']) . " Vit</font><br/>" : "";
                $showitagi2 = $showeditexs['agi'] > 0 ? "+<font color=blue>" . htmlspecialchars((string) $showeditexs['agi']) . " Agi</font><br/>" : "";
                $showitres2 = $showeditexs['res'] > 0 ? "+<font color=red>" . htmlspecialchars((string) $showeditexs['res']) . " Res</font>" : "";

                // Define a cor de fundo com base no bônus
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

                // Define o atributo com base no tipo de item
                $attributeLabel = match ($showeditexs['type']) {
                    'shield', 'armor', 'legs', 'helmet' => 'Defesa',
                    'quiver', 'boots' => 'Agilidade',
                    'amulet' => 'Vitalidade',
                    'weapon' => 'Ataque',
                    'ring' => htmlspecialchars($showeditexs['description'] ?? 'Sem descrição'), // Anel usa apenas descrição textual
                    default => 'Atributo desconhecido',
                };

                // Calcula effectiveness apenas para itens que não sejam do tipo ring
                $newefec = $showeditexs['type'] === 'ring'
                    ? ''
                    : ($showeditexs['effectiveness'] + $showeditexs['item_bonus'] * 2);

                // Nome do item
                $showitname = htmlspecialchars($showeditexs['name'] . " + " . $showeditexs['item_bonus']);

                // Informações do item
                $showitinfo = $showeditexs['type'] === 'ring'
                    ? "<table width=100%><tr><td><font size=1px>" . htmlspecialchars($showeditexs['description']) . "</font></td><td width=35%><font size=1px>" . $showitfor2 . $showitvit2 . $showitagi2 . $showitres2 . "</font></td></tr></table>"
                    : "<table width=100%><tr><td width=65%><font size=1px>$attributeLabel: " . htmlspecialchars((string) $newefec) . "</font></td><td width=35%><font size=1px>" . $showitfor2 . $showitvit2 . $showitagi2 . $showitres2 . "</font></td></tr></table>";

                // Exibição do item
                echo sprintf("<div class='%s'>", $itemClass);
                echo sprintf('<div title="header=[%s] body=[%s]">', $showitname, $showitinfo);
                echo '<img src="static/images/itens/' . htmlspecialchars((string) $showeditexs['img']) . '"/>';
                echo "</div></div>";
            }
        }
    } catch (Exception $exception) {
        echo "Erro: " . htmlspecialchars($exception->getMessage());
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
