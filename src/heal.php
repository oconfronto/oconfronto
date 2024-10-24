<?php
declare(strict_types=1);

include(__DIR__ . "/lib.php");
$player = check_user($secret_key, $db);
header("Content-Type: text/html; charset=utf-8",true);

	$checabattalha = $db->execute("select * from `bixos` where `hp`<? and `player_id`=?", array(1, $player->id));
	if (($checabattalha->recordcount() == 1 || $player->hp < 1) && $player->hp != $player->maxhp) {

	include(__DIR__ . "/healcost.php");
	if ($player->gold < $cost && $player->gold < 1) {
			echo showAlert("Você não possui ouro suficiente!", "red");
			exit;

		}
 if ($cost > 0 && $cost2 > 0) {
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
?>
