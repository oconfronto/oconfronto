<?php
$heal = $player->maxhp - $player->hp;

if($player->level < 36){
	$cost = ceil($heal * 1);
	$cost2 = floor($player->gold / 1);
}else if (($player->level > 35) and ($player->level < 90)){
	$cost = ceil($heal * 1.45);
	$cost2 = floor($player->gold / 1.45);
}else{
	$cost = ceil($heal * 1.8);
	$cost2 = floor($player->gold / 1.8);
}

?>