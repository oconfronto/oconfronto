<?php
declare(strict_types=1);

$numgoldbars = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=? and `mark`='f'", array($player->id, 177));
if ($numgoldbars->recordcount() > 2){

	$removelmagicgoldbars = $db->execute("delete from `items` where `item_id`=? and `player_id`=? limit ?", array(177, $player->id, 3));

		$insert['player_id'] = $player->id;
		$insert['item_id'] = 178;
		$db->autoexecute('items', $insert, 'INSERT');
			$ringid = $db->Insert_ID();
			$db->execute("update `items` set `for`=`for`+?, `vit`=`vit`+?, `agi`=`agi`+?, `res`=`res`+? where `id`=?", array(40, 40, 40, 40, $ringid));

	include(__DIR__ . "/templates/private_header.php");
	echo "<fieldset><legend><b>Atenção</b></legend>\n";
        echo "Os três cristais mágicos que você possuia em seu inventário parecem ter se misturado, e formado um novo anél.<br />";
        echo '<a href="inventory.php">Voltar</a>.';
	echo "</fieldset>";
        include(__DIR__ . "/templates/private_footer.php");
        exit;
}
?>
