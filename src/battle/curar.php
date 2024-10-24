<?php
declare(strict_types=1);

$selectmana = $db->GetOne("select `mana` from `blueprint_magias` where `id`=4");
$mana = $player->reino == '1' || $player->vip > time() ? $selectmana - 5 : $selectmana;

$log = explode(", ", $_SESSION['battlelog'][0]);

if ($player->mana < $mana){
      	if ($log[1] !== "Você tentou lançar um feitiço mas está sem mana sufuciente.") {
		array_unshift($_SESSION['battlelog'], "5, Você tentou lançar um feitiço mas está sem mana sufuciente.");
	}
       
	$otroatak = 5;
}else {
    $curar = $player->level < 50 ? random_int(30, 100) : random_int($player->level, ($player->level * 2));

    if (($player->hp + $curar) > $player->maxhp){
   		$db->execute("update `players` set `hp`=`maxhp` where `id`=?", [$player->id]);
   		array_unshift($_SESSION['battlelog'], "3, Você fez um feitiço e recuperou toda sua vida.");
   	}else{
   		$db->execute("update `players` set `hp`=`hp`+? where `id`=?", [$curar, $player->id]);
   		array_unshift($_SESSION['battlelog'], "3, Você fez um feitiço e recuperou " . $curar . " pontos de vida.");
   	}

    $db->execute("update `players` set `mana`=`mana`-? where `id`=?", [$mana, $player->id]);
    $db->execute("update `bixos` set `type`=?, `vez`='e' where `player_id`=?", [97, $player->id]);
}
?>
