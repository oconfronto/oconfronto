<?php
ob_start(); // Inicia o buffer de saída
include("lib.php");
define("PAGENAME", "Inventário");
$player = check_user($secret_key, $db);
include("checkbattle.php");
include("checkhp.php");
include("checkwork.php");

include("includes/items/gift.php");
include("includes/items/goldbar.php");
include("includes/items/magiccrystal.php");
include("includes/actions/transfer-potions.php");
include("includes/actions/transfer-items.php");

include("templates/private_header.php");

function displayItemOptions($item, $action, $label)
{
    return "<a href=\"inventory_mobile.php?{$action}={$item['id']}\">{$label}</a>";
}

function displayItem($item, $type)
{
    $options = array(); // Use array() instead of []
    if ($type === 'equipped') {
        $options[] = displayItemOptions($item, 'unequip', 'Desequipar');
    } else {
        $options[] = displayItemOptions($item, 'equip', 'Equipar');
    }
    $options[] = displayItemOptions($item, 'sell', 'Vender');
    $options[] = displayItemOptions($item, 'mature', 'Maturar');

    if ($item["item_id"] != "136" && $item["item_id"] != "137" && $item["item_id"] != "150" && $item["item_id"] != "148") {
        return "
        <div class=\"item\">
            <img src=\"images/itens/{$item['img']}\" alt=\"{$item['name']}\">
            <div class=\"item-info\">
                <span>{$item['name']} +{$item['item_bonus']}</span>
                <div class=\"item-options\">" . implode(' | ', $options) . "</div>
            </div>
        </div>
    ";
    } else {
        return "";
    }
}

function fetchItems($playerId, $status)
{
    global $db;
    return $db->execute("SELECT items.id, items.item_id, items.item_bonus, items.status, blueprint_items.name, blueprint_items.img FROM `items` 
                         JOIN `blueprint_items` ON items.item_id=blueprint_items.id 
                         WHERE items.player_id=? AND items.status=?", array($playerId, $status));
}

function displayItems($playerId, $status, $title)
{
    $items = fetchItems($playerId, $status);
    echo "<h3>{$title}</h3>";
    if ($items->recordcount() > 0) {
        while ($item = $items->fetchrow()) {
            echo displayItem($item, $status);
        }
    } else {
        echo "<p>Nenhum item encontrado.</p>";
    }
}

if (isset($_GET['sell'])) {
    // lógica de venda de itens
    $itemId = $_GET['sell'];
    // lógica de venda de itens
    header("Location: inventory.php?sellit=" . $itemId . "&comfirm=true");
    exit; // Importante para parar a execução do script após o redirecionamento
}

if (isset($_GET['mature'])) {
    // lógica de maturação de itens
    $itemId = $_GET['mature'];
    // lógica de maturação de itens
    header("Location: inventory.php?mature=" . $itemId . "&comfirm=true");
    exit;
}

if (isset($_GET['equip'])) {
    // lógica de equipar itens    
    $itemId = $_GET['equip'];
    // lógica de equipar itens
    header("Location: equipit.php?itid=" . $itemId);
    exit;
}

if (isset($_GET['unequip'])) {
    // lógica de desequipar itens
    $itemId = $_GET['unequip'];
    // lógica de desequipar itens
    header("Location: moveit.php?itid=" . $itemId . "&tile=1");
    exit;
}

echo "<div id=\"main_container\">";
echo "<div id=\"inventory\">";
displayItems($player->id, 'equipped', 'Itens Equipados');
displayItems($player->id, 'unequipped', 'Itens na Mochila');
echo "</div>";
echo "</div>";

include("templates/private_footer.php");
ob_end_flush(); // Envia o conteúdo do buffer e limpa