<?php
	$checabattalha = $db->execute("select `hp` from `bixos` where `player_id`=? and `type`!=98 and `type`!=99", array($player->id));
    $verificaLuta = $db->execute("select `id` from `duels` where `status`='s' and (`p_id`=? or `e_id`=?)", array($player->id, $player->id));
    
	if ($checabattalha->recordcount() > 0) {
		header("Location: monster.php?act=attack");
		exit;
	}
    elseif ($verificaLuta->recordcount() > 0) {
		header("Location: duel.php?luta=true");
		exit;
	}
    
?>