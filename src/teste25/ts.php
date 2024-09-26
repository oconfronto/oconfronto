<?php

$timestamp = microtime(); // Retorna o timestamp atual com microsegundos
$timestamp = explode(" ", $timestamp); // tiro todos os espaços da variavel que contem os microsegundos
$timestamp = $timestamp[1] + $timestamp[0];
$start = $timestamp; // Starto o tempo atual

$timestamp = microtime();
$timestamp = explode(" ", $timestamp);
$timestamp = $timestamp[1] + $timestamp[0];
$finish = $timestamp;
$totaltime = ($finish - $start); // calculo a diferença entre o tempo inicial e até carregar a pagina por completo
printf ("Esta pagina demorou %f segundos para ser carregada!", $totaltime);
?>








<?php
// ob_start(); // Inicia o buffer de saída
// include("lib.php");
// define("PAGENAME", "Inventário");
// $player = check_user($secret_key, $db);
// include("checkbattle.php");
// include("checkhp.php");
// include("checkwork.php");

// include("includes/items/gift.php");
// include("includes/items/goldbar.php");
// include("includes/items/magiccrystal.php");
// include("includes/actions/transfer-potions.php");
// include("includes/actions/transfer-items.php");

// include("templates/private_header.php");

// function displayItemOptions($item, $action, $label)
// {
//     return "<a href=\"inventory_mobile.php?{$action}={$item['id']}\">{$label}</a>";
// }

// function displayItem($item, $type, $player)
// {
//     $options = array(); // Use array() instead of []
//     if ($type === 'equipped') {
//         $options[] = displayItemOptions($item, 'unequip', 'Desequipar');
//     } else {
//         $options[] = displayItemOptions($item, 'equip', 'Equipar');
//     }
//     $options[] = displayItemOptions($item, 'sell', 'Vender');
//     $options[] = displayItemOptions($item, 'mature', 'Maturar');

//     if ($item["item_id"] != "136" && $item["item_id"] != "137" && $item["item_id"] != "150" && $item["item_id"] != "148") {

//         if (($item['item_bonus'] > 2) and ($item['item_bonus'] < 6)) {
//             $colorbg = "itembg2";
//         } elseif (($item['item_bonus'] > 5) and ($item['item_bonus'] < 9)) {
//             $colorbg = "itembg3";
//         } elseif ($item['item_bonus'] == 9) {
//             $colorbg = "itembg4";
//         } elseif ($item['item_bonus'] > 9) {
//             $colorbg = "itembg5";
//         } else {
//             $colorbg = "itembg1";
//         }

//         if ($item['for'] == 0) {
//             $showitfor = "";
//             $showitfor2 = "";
//         } else {
//             $showitfor2 = "+<font color=gray>" . $item['for'] . " For</font><br/>";
//         }

//         if ($item['vit'] == 0) {
//             $showitvit = "";
//             $showitvit2 = "";
//         } else {
//             $showitvit2 = "+<font color=green>" . $item['vit'] . " Vit</font><br/>";
//         }

//         if ($item['agi'] == 0) {
//             $showitagi = "";
//             $showitagi2 = "";
//         } else {
//             $showitagi2 = "+<font color=blue>" . $item['agi'] . " Agi</font><br/>";
//         }

//         if ($item['res'] == 0) {
//             $showitres = "";
//             $showitres2 = "";
//         } else {
//             $showitres2 = "+<font color=red>" . $item['res'] . " Res</font>";
//         }

//         if ($item['type'] == 'amulet') {
//             $nametype = "Vitalidade";
//         } elseif ($item['type'] == 'weapon') {
//             $nametype = "Ataque";
//         } else {
//             $nametype = "Defesa";
//         }

//         if (($item['type'] != 'addon') and ($item['type'] != 'ring')) {
//             $newefec = ($item['effectiveness']) + ($item['item_bonus'] * 2);
//             $showitname = "" . $item['name'] . " + " . $item['item_bonus'] . "";
//         } else {
//             $showitname = $item['name'];
//         }

//         $need = NULL;
//         if ($item['needlvl'] > 1) {
//             if ($player->vip > time()) {
//                 $lvlbonus = 10;
//             } else {
//                 $lvlbonus = 0;
//             }
//             if ($item['needlvl'] > ($player->level + $lvlbonus)) {
//                 $need .= "<br/><font color=red><b>Requer nível " . $item['needlvl'] . ".</b></font>";
//             } else {
//                 $need .= "<br/><b>Requer nível " . $item['needlvl'] . ".</b>";
//             }
//         }
//         if ($item['needpromo'] == "t") {
//             if ($player->promoted != "f") {
//                 $need .= "<br/><b>Voc superior.</b>";
//             } else {
//                 $need .= "<br/><font color=red><b>Voc superior.</b></font>";
//             }
//         }
//         if ($item['needpromo'] == "p") {
//             if ($player->promoted == "p") {
//                 $need .= "<br/><b>Voc suprema.</b>";
//             } else {
//                 $need .= "<br/><font color=red><b>Voc suprema.</b></font>";
//             }
//         }

//         if ($item['type'] == 'addon') {
//             $showitinfo = "<table width=100%><tr><td width=100%><font size=1px>" . $item['description'] . "</font></td></tr></table>";
//         } elseif ($item['type'] == 'ring') {
//             $showitinfo = "<table width=100%><tr><td width=65%><font size=1px>" . $item['description'] . "" . $need . "</font></td><td width=35%><font size=1px>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></table>";
//         } else {
//             $showitinfo = "<table width=100%><tr><td width=65%><font size=1px>" . $nametype . ": " . $newefec . "" . $need . "</font></td><td width=35%><font size=1px>" . $showitfor2 . "" . $showitvit2 . "" . $showitagi2 . "" . $showitres2 . "</font></td></table>";
//         }

//         $for = (int) $item["for"];
//         $vit = (int) $item["vit"];
//         $agi = (int) $item["agi"];
//         $res = (int) $item["res"];

//         // <span style='padding-right:8px'>{$item['name']} +{$item['item_bonus']}</span>

//         $string = " <div>                        
//                         <div class=\"item\" style='padding-top:20px'>
//                             <img src=\"images/itens/{$item['img']}\" alt=\"{$item['name']}\" title=\"header=[" . $showitname . "] body=[" . $showitinfo . "]\">
//                         </div>
//                         <div class=\"item-info\" style='padding-top:20px'>
//                             <div class=\"item-options\">" . implode(' | ', $options) . "</div>
//                         </div>
//                     </div>";

//         return $string;
//     } else {
//         return "";
//     }
// }

// function fetchItems($playerId, $status)
// {
//     global $db;
//     return $db->execute("SELECT items.id, items.item_id, items.item_bonus, items.for, items.vit, items.agi, items.res, items.status, 
//                         blueprint_items.name, blueprint_items.img, blueprint_items.type, blueprint_items.description, blueprint_items.needlvl,
//                         blueprint_items.effectiveness
//                         FROM `items` 
//                         JOIN `blueprint_items` ON items.item_id=blueprint_items.id 
//                         WHERE items.player_id=? AND items.status=?", array($playerId, $status));
// }

// function fetchPlayers($playerId)
// {
//     global $db;
//     return $db->execute("select * FROM players where id=?", array($playerId));
// }

// function displayItems($playerId, $status, $title)
// {
//     $items = fetchItems($playerId, $status);
//     $player = fetchPlayers($playerId);
//     echo "<h3>{$title}</h3>";
//     if ($items->recordcount() > 0) {
//         while ($item = $items->fetchrow()) {
//             echo displayItem($item, $status, $player);
//         }
//     } else {
//         echo "<p>Nenhum item encontrado.</p>";
//     }
// }

// if (isset($_GET['sell'])) {
//     // lógica de venda de itens
//     $itemId = $_GET['sell'];
//     // lógica de venda de itens
//     header("Location: inventory.php?sellit=" . $itemId . "&comfirm=true");
//     exit; // Importante para parar a execução do script após o redirecionamento
// }

// if (isset($_GET['mature'])) {
//     // lógica de maturação de itens
//     $itemId = $_GET['mature'];
//     // lógica de maturação de itens
//     header("Location: inventory.php?mature=" . $itemId . "&comfirm=true");
//     exit;
// }

// if (isset($_GET['equip'])) {
//     // lógica de equipar itens    
//     $itemId = $_GET['equip'];
//     // lógica de equipar itens
//     header("Location: equipit.php?itid=" . $itemId);
//     exit;
// }

// if (isset($_GET['unequip'])) {
//     // lógica de desequipar itens
//     $itemId = $_GET['unequip'];
//     // lógica de desequipar itens
//     header("Location: moveit.php?itid=" . $itemId . "&tile=1");
//     exit;
// }

// echo "<div id=\"main_container\">";
// echo "<div id=\"inventory\">";
// displayItems($player->id, 'equipped', 'Itens Equipados');
// displayItems($player->id, 'unequipped', 'Itens na Mochila');
// echo "</div>";
// echo "</div>";

// include("templates/private_footer.php");
// ob_end_flush(); // Envia o conteúdo do buffer e limpa