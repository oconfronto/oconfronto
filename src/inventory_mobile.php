<?php

declare(strict_types=1);

ob_start(); // Inicia o buffer de saída
include(__DIR__ . "/lib.php");
define("PAGENAME", "Inventário");
$player = check_user($db);
include(__DIR__ . "/checkbattle.php");
include(__DIR__ . "/checkhp.php");
include(__DIR__ . "/checkwork.php");

include(__DIR__ . "/includes/items/gift.php");
include(__DIR__ . "/includes/items/goldbar.php");
include(__DIR__ . "/includes/items/magiccrystal.php");
include(__DIR__ . "/includes/actions/transfer-potions.php");
include(__DIR__ . "/includes/actions/transfer-items.php");

include(__DIR__ . "/templates/private_header.php");

$tuto = false;

$tutorial = $db->execute("select * from `pending` where `pending_id`=2 and `pending_status`=4 and `player_id`=?", [$player->id]);
if ($tutorial->recordcount() > 0) {
    $tutorial = $db->execute("select * from `items` where `player_id`=? and `status`='equipped'", [$player->id]);
    if ($tutorial->recordcount() == 0) {
        global $tuto;
        $tuto = true;
        echo showAlert("<table width=\"100%\"><tr><td width=\"90%\">Itens ajudam na sua força e resistência.<br/><font size=\"1px\">Você pode obter itens <u>lutando contra monstros</u> ou <u>comprando-os no ferreiro</u>.</font><br/><br/>Para equipar seu item, clique em <b style='color:green'>EQUIPAR</b> na arma abaixo.</td><th><font size=\"1px\"><a href=\"start.php?act=5\">Próximo</a></font></th></tr></table>", "white", "left");
    } else {
        global $tuto;
        $tuto = false;
        echo showAlert("ótimo, <a href=\"start.php?act=5\">clique aqui</a> para continuar seu tutorial.", "green");
    }
}

function displayItemOptions(array $item, $action, $label): ?string
{
    if ($item['item_bonus'] == 0) {
        $precol = ceil($item['price'] / 3.5);
    } elseif ($item['item_bonus'] == 1) {
        $precol = ceil(($item['price'] / 3.5) * 1.3);
    } elseif ($item['item_bonus'] == 2) {
        $precol = ceil(($item['price'] / 3.5) * 1.7);
    } elseif ($item['item_bonus'] == 3) {
        $precol = ceil(($item['price'] / 3.5) * 2);
    } else {
        $precol = ceil(($item['price'] / 3.5) * ($item['item_bonus'] / 1.85));
    }

    if ($item['item_bonus'] > 10) {
        $valordavenda = floor(($item['price'] / 2) + (($item['item_bonus'] * $item['price']) / 5) + 3000000);
    } else {
        $valordavenda = floor(($item['price'] / 2) + (($item['item_bonus'] * $item['price']) / 5));
    }

    if ($action == 'sell') {
        return sprintf("<a onclick=\"return confirm('Tem certeza que deseja VENDER o item %s +%s no valor de: %s ?');\" href=\"inventory_mobile.php?%s=%s\">%s</a>", $item['name'], $item['item_bonus'], $valordavenda, $action, $item['id'], $label);
    }

    if ($action == 'mature') {
        return sprintf("<a onclick=\"return confirm('Tem certeza que deseja MATURAR o item %s +%s no valor de: %s ?');\" href=\"inventory_mobile.php?%s=%s\">%s</a>", $item['name'], $item['item_bonus'], $precol, $action, $item['id'], $label);
    }

    global $tuto;
    if ($tuto) {
        echo "<style>
            .txt-tutorial-equip{
            font-size:14px;
            animation: piscar 2.5s infinite;
            }
            @keyframes piscar{
            0%, 100%{color:#745927}
            100%{color:green}            
            }
            </style>";

        return sprintf("<a href=\"inventory_mobile.php?%s=%s\"><b class='txt-tutorial-equip'>->%s<-</b></a>", $action, $item['id'], $label);
    }

    return sprintf('<a href="inventory_mobile.php?%s=%s">%s</a>', $action, $item['id'], $label);
}

function displayItemMobile(array $item, $type, $player, int $bool): string
{
    $options = []; // Use array() instead of []
    if ($type === 'equipped') {
        $options[] = displayItemOptions($item, 'unequip', 'Desequipar');
    } else {
        $options[] = displayItemOptions($item, 'equip', 'Equipar');
    }

    $options[] = displayItemOptions($item, 'sell', 'Vender');
    $options[] = displayItemOptions($item, 'mature', 'Maturar');

    $type = "";
    if ($item['type'] == 'amulet') {
        $type = "Vitalidade";
    }

    if ($item['type'] == 'weapon') {
        $type = "Ataque";
    }

    if ($item['type'] == 'armor') {
        $type = "Defesa";
    }

    if ($item['type'] == 'boots') {
        $type = "Agilidade";
    }

    if ($item['type'] == 'legs') {
        $type = "Defesa";
    }

    if ($item['type'] == 'helmet') {
        $type = "Defesa";
    }

    if ($item['type'] == 'shield') {
        $type = "Defesa";
    }

    $atributo = "";
    if ($item['type'] != 'ring') {
        $atributo =  $type . (': ' . $item['effectiveness']);
    } else {
        switch ($item['item_id']) {
            case 163:
                $item['for'] = 10;
                $item['vit'] = 10;
                $item['agi'] = 10;
                $item['res'] = 10;
                break;
            case 164:
                $item['for'] = 10;
                break;
            case 165:
                $item['vit'] = 10;
                break;
            case 166:
                $item['agi'] = 10;
                break;
            case 167:
                $item['res'] = 10;
                break;
            case 168:
                $item['for'] = 20;
                $item['vit'] = 20;
                $item['agi'] = 20;
                $item['res'] = 20;
                break;
            case 169:
                $item['for'] = 10;
                $item['res'] = 15;
                break;
            case 170:
                $item['vit'] = 15;
                $item['agi'] = 15;
                $item['res'] = 5;
                break;
            case 172:
                $item['for'] = 40;
                $item['vit'] = 30;
                $item['agi'] = 40;
                $item['res'] = 30;
                break;
            case 176:
                $item['for'] = 30;
                $item['vit'] = 40;
                $item['agi'] = 30;
                $item['res'] = 40;
                break;
            case 178:
                //Não seu qual atributo dá ainda.
                break;
            default:
        }
    }

    $bonus1 = "";
    $bonus2 = "";
    $bonus3 = "";
    $bonus4 = "";
    $bonus5 = "";

    if ($item['item_bonus'] > 0) {
        $bonus1 = " (+" . $item['item_bonus'] . ")";
    }

    if ($item['for'] > 0) {
        $bonus2 = ' <font color="gray">+' . $item['for'] . "F</font>";
    }

    if ($item['vit'] > 0) {
        $bonus3 = ' <font color="green">+' . $item['vit'] . "V</font>";
    }

    if ($item['agi'] > 0) {
        $bonus4 = ' <font color="blue">+' . $item['agi'] . "A</font>";
    }

    if ($item['res'] > 0) {
        $bonus5 = ' <font color="red">+' . $item['res'] . "R</font>";
    }

    return '<tr class="row' . $bool . "\">
                <td style='text-align: center;padding:10px;border:1px solid #B9892F;vertical-align: middle;'><img src=\"static/images/itens/{$item['img']}\" alt=\"{$item['name']}\"></td>
                <td style='text-align: center;padding:10px;border:1px solid #B9892F;vertical-align: middle;'>" . $atributo . "</td>
                <td style='text-align: center;padding:10px;border:1px solid #B9892F;vertical-align: middle;'>" . $item['name'] . " " . $bonus1 . "" . $bonus2 . "" . $bonus3 . "" . $bonus4 . "" . $bonus5 . "</td>
                <td style='text-align: center;padding:10px;border:1px solid #B9892F;vertical-align: middle;'>" . $options[0] . "</td>
                <td style='text-align: center;padding:10px;border:1px solid #B9892F;vertical-align: middle;'>" . $options[1] . "</td>
                <td style='text-align: center;padding:10px;border:1px solid #B9892F;vertical-align: middle;'>" . $options[2] . "</td>
                </tr>";
}

function fetchItems($playerId, $status)
{
    global $db;
    return $db->execute("SELECT items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, 
                        blueprint_items.name, blueprint_items.img, blueprint_items.effectiveness, blueprint_items.type, blueprint_items.description, blueprint_items.price
                        FROM `items` 
                        JOIN `blueprint_items` ON items.item_id=blueprint_items.id 
                        WHERE items.player_id=? AND items.status=? AND blueprint_items.type !='potion' AND blueprint_items.type!='stone' AND items.mark='f' ORDER BY items.tile", [$playerId, $status]);
}

function fetchPlayers($playerId)
{
    global $db;
    return $db->execute("select * FROM players where id=?", [$playerId]);
}

function displayItems($playerId, $status, $title): void
{
    $items = fetchItems($playerId, $status);
    $player = fetchPlayers($playerId);
    echo sprintf("<div style='text-align:center'><h3>%s</h3></div>", $title);
    if ($items->recordcount() > 0) {
        $bool = 1;
        echo "<fieldset>";
        echo "<table style='width:100%;border-collapse: collapse;'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th style='width:5%;text-align: center;'><b>Item</b></th>";
        echo "<th style='width:15%;text-align: center;'><b>Atributo</b></th>";
        echo "<th style='width:55%;text-align: center;'><b>Descrição</b></th>";
        echo "<th colspan='3' style='width:25%;text-align: center;'><b>Ações</b></th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        while ($item = $items->fetchrow()) {
            echo displayItemMobile($item, $status, $player, $bool);
            $bool = ($bool == 1) ? 2 : 1;
        }

        echo "</tbody></table></fieldset>";
    } else {
        echo "<div style='text-align:center'><p>Nenhum item encontrado.</p></div>";
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

echo '<div id="main_container">';
echo '<div id="inventory">';
displayItems($player->id, 'equipped', 'Itens Equipados');
displayItems($player->id, 'unequipped', 'Itens na Mochila');
echo "</div>";
echo "</div>";

include(__DIR__ . "/templates/private_footer.php");
ob_end_flush(); // Envia o conteúdo do buffer e limpa
