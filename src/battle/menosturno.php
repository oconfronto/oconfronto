<?php

 		$magiaaff = $db->execute("select `magia`, `turnos` from `bixos` where `player_id`=?", array($player->id));
		$tornosrestantes = $magiaaff->fetchrow();

		if ($tornosrestantes['turnos'] != 0){
		if (($tornosrestantes['turnos'] - 1) < 1){
		$db->execute("update `bixos` set `magia`=0, `turnos`=0 where `player_id`=?", array($player->id));
		}else{
		$db->execute("update `bixos` set `turnos`=`turnos`-1 where `player_id`=?", array($player->id));
		}
		}
?>