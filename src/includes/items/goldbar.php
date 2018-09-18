<?php
$numgoldbars = $db->execute("select `id` from `items` where `player_id`=? and `item_id`=? and `mark`='f'", array($player->id, 157));
if ($numgoldbars->recordcount() > 2){

	$removelmagicgoldbars = $db->execute("delete from `items` where `item_id`=? and `player_id`=? limit ?", array(157, $player->id, 3));

		$insert['player_id'] = $player->id;
		$insert['item_id'] = 158;
		$addgoldhelm = $db->autoexecute('items', $insert, 'INSERT');

	include("templates/private_header.php");
	echo "<fieldset><legend><b>Atenção</b></legend>\n";
        echo "As três barras de ouro que você possuia em seu inventário parecem ter se misturado, e formado um novo elmo.<br />";
        echo "<a href=\"inventory.php\">Voltar</a>.";
	echo "</fieldset>";
        include("templates/private_footer.php");
        exit;
}
?>
