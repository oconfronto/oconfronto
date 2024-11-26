<?php

declare(strict_types=1);

if (basename($_SERVER['PHP_SELF']) == 'gift.php') {
// Check if the 'gift' parameter is present and not empty
if (!isset($_GET['gift']) || empty($_GET['gift'])) { 
    // If the 'gift' parameter is not present, display the error message and stop execution
    include(__DIR__ . "/templates/private_header.php");
    echo "<fieldset><legend><b>Erro</b></legend>\n";
    echo "Parâmetro 'gift' não fornecido.<br />";
    echo '<a href="inventory.php">Voltar</a>.';
    echo "</fieldset>";
    include(__DIR__ . "/templates/private_footer.php");
    exit;  // Stop execution after displaying the error
}

// Here begins the logic to process the gift, if the 'gift' is present
$numgifts = $db->execute("SELECT `id` FROM `items` WHERE `player_id`=? AND `id`=? AND `item_id`=? AND `mark`='f'", [$player->id, $_GET['gift'], 155]);

// Check if the item was found
if ($numgifts->recordcount() != 1) {
    include(__DIR__ . "/templates/private_header.php");
    echo "<fieldset><legend><b>Erro</b></legend>\n";
    echo "Item não encontrado.<br />";
    echo '<a href="inventory.php">Voltar</a>.';
    echo "</fieldset>";
    include(__DIR__ . "/templates/private_footer.php");
    exit;  // Stop execution after displaying the error
}

// Logic to open the gift
if ($player->level < 50) {
    include(__DIR__ . "/templates/private_header.php");
    echo "<fieldset><legend><b>Erro</b></legend>\n";
    echo "Você não possui nível suficiente para abrir o presente.<br />";
    echo '<a href="inventory.php">Voltar</a>.';
    echo "</fieldset>";
    include(__DIR__ . "/templates/private_footer.php");
    exit; // Stop execution after displaying the error
}

// Logic to process the gift draw
$gifte = $numgifts->fetchrow();
$numgifts = $db->execute("DELETE FROM `items` WHERE `id`=?", [$_GET['gift']]);
$itemchance = random_int(1, 30);

// Draw the item
if ($itemchance < 20) {
    $sotona = random_int(1, 30);
    if ($sotona < 20) {
        $sorteiaitem = $db->execute("SELECT `id`, `name` FROM `blueprint_items` WHERE `type` !=? AND `type` !=? AND `type` !=? AND `type` !=? AND `canbuy` !=? ORDER BY rand() LIMIT 1", ["addon", "quest", "stone", "potion", "f"]);
    } else {
        $sorteiaitem = $db->execute("SELECT `id`, `name` FROM `blueprint_items` WHERE `type` !=? AND `type` !=? AND `type` !=? AND `type` !=? ORDER BY rand() LIMIT 1", ["addon", "quest", "stone", "potion"]);
    }

    $giftitem = $sorteiaitem->fetchrow();
    $insert['player_id'] = $player->id;
    $insert['item_id'] = $giftitem['id'];
    $addlootitemwin = $db->autoexecute('items', $insert, 'INSERT');

    include(__DIR__ . "/templates/private_header.php");
    echo "<fieldset><legend><b>Presente</b></legend>\n";
    echo "Você abriu seu presente e encontrou um(a) " . $giftitem['name'] . ".<br />";
    echo '<a href="inventory.php">Voltar</a>.';
    echo "</fieldset>";
    include(__DIR__ . "/templates/private_footer.php");
    exit;  // Stop execution after item draw
}

// Logic for drawing gold
$goldchance = random_int(1, 30);
if ($goldchance < 5) {
    $ganhagold = random_int(1, 3000);
} elseif ($goldchance < 10) {
    $ganhagold = random_int(1, 30000);
} elseif ($goldchance < 15) {
    $ganhagold = random_int(1, 90000);
} elseif ($goldchance < 25) {
    $ganhagold = random_int(1, 140000);
} elseif ($goldchance < 31) {
    $ganhagold = random_int(1, 200000);
}

$ganhagold = ceil($itemchance * $ganhagold);
$query = $db->execute("UPDATE `players` SET `gold`=`gold`+? WHERE `id`=?", [$ganhagold, $player->id]);

include(__DIR__ . "/templates/private_header.php");
echo "<fieldset><legend><b>Presente</b></legend>\n";
echo "Você abriu seu presente e encontrou " . $ganhagold . " de ouro.<br />";
echo '<a href="inventory.php">Voltar</a>.';
echo "</fieldset>";
include(__DIR__ . "/templates/private_footer.php");
exit;  // Stop execution after gold draw
}