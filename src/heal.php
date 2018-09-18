<?php
include("lib.php");
$player = check_user($secret_key, $db);
header("Content-Type: text/html; charset=ISO-8859-1",true);

	$checabattalha = $db->execute("select * from `bixos` where `hp`<? and `player_id`=?", array(1, $player->id));
	if ((($checabattalha->recordcount() == 1) or ($player->hp < 1)) and ($player->hp != $player->maxhp)) {

	include("healcost.php");
	if (($player->gold < $cost) and ($player->gold < 1)) {
			echo showAlert("Você não possui ouro suficiente!", "red");
			exit;

		}else{
            if (($cost > 0) and ($cost2 > 0)) {
                if ($player->gold < $cost){
                    $db->execute("update `players` set `gold`=0, `hp`=`hp`+? where `id`=?", array($cost2, $player->id));
                    $player = check_user($secret_key, $db); //Get new stats
                }else{
                    $db->execute("update `players` set `gold`=`gold`-?, `hp`=`maxhp` where `id`=?", array($cost, $player->id));
                    $player = check_user($secret_key, $db); //Get new stats
                }
            }

			echo showAlert("Você acaba de ser curado!");
			exit;
		}
	}
?>